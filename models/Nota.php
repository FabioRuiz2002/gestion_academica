<?php
class Nota {
    
    private $conn;
    private $table_name = "notas";

    public $id_nota;
    public $id_evaluacion;
    public $id_estudiante;
    public $calificacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Función vital para el Estudiante (calcula si aprobó cursos pasados)
    public function getHistorialPromedios($id_estudiante) {
        $historial = [];
        try {
            // 1. Obtener cursos del estudiante
            $queryCursos = "SELECT DISTINCT c.id_curso 
                            FROM matriculas m
                            JOIN cursos c ON m.id_curso = c.id_curso
                            WHERE m.id_estudiante = :id_estudiante";
            $stmtCursos = $this->conn->prepare($queryCursos);
            $stmtCursos->bindParam(':id_estudiante', $id_estudiante);
            $stmtCursos->execute();
            $cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);

            // 2. Para cada curso, calcular su promedio real
            $queryNotas = "SELECT e.porcentaje, n.calificacion
                           FROM evaluaciones e
                           LEFT JOIN notas n ON e.id_evaluacion = n.id_evaluacion AND n.id_estudiante = :id_estudiante
                           WHERE e.id_curso = :id_curso";
            $stmtNotas = $this->conn->prepare($queryNotas);

            foreach ($cursos as $curso) {
                $id_curso = $curso['id_curso'];
                $stmtNotas->bindParam(':id_estudiante', $id_estudiante);
                $stmtNotas->bindParam(':id_curso', $id_curso);
                $stmtNotas->execute();
                $notas_curso = $stmtNotas->fetchAll(PDO::FETCH_ASSOC);
                
                $promedio = 0;
                $total_pct = 0;
                $completo = true;

                if(empty($notas_curso)) $completo = false;

                foreach ($notas_curso as $n) {
                    $total_pct += $n['porcentaje'];
                    if ($n['calificacion'] === null) {
                        $completo = false; 
                    } else {
                        $promedio += $n['calificacion'] * ($n['porcentaje'] / 100);
                    }
                }

                // Solo guardamos promedio si el curso tiene notas completas (100%)
                if ($total_pct == 100 && $completo) {
                    $historial[$id_curso] = $promedio;
                } else {
                    $historial[$id_curso] = 0;
                }
            }
            return $historial;
        } catch (PDOException $e) { return []; }
    }

    // Función para el Profesor (Sábana de notas)
    public function readNotasParaLibro($id_curso) {
        try {
            $query = "SELECT n.id_evaluacion, n.id_estudiante, n.calificacion 
                      FROM " . $this->table_name . " n
                      JOIN evaluaciones e ON n.id_evaluacion = e.id_evaluacion
                      WHERE e.id_curso = :id_curso";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            
            $notas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $notas[$row['id_estudiante']][$row['id_evaluacion']] = $row['calificacion'];
            }
            return $notas;
        } catch (PDOException $e) { return []; }
    }

    // Función para el Estudiante (Ver mis notas)
    public function readNotasPorEstudiante($id_estudiante, $id_curso) {
        try {
            $query = "SELECT e.nombre, e.porcentaje, n.calificacion 
                      FROM evaluaciones e
                      LEFT JOIN " . $this->table_name . " n 
                        ON e.id_evaluacion = n.id_evaluacion 
                        AND n.id_estudiante = :id_estudiante
                      WHERE e.id_curso = :id_curso
                      ORDER BY e.nombre";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_estudiante', $id_estudiante);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    // Función para Guardar (Profesor)
    public function guardarNotas($id_evaluacion, $notas_estudiantes) {
        try {
            $query = "INSERT INTO " . $this->table_name . " (id_evaluacion, id_estudiante, calificacion)
                      VALUES (:id_evaluacion, :id_estudiante, :calificacion)
                      ON DUPLICATE KEY UPDATE calificacion = :up_calificacion";
            
            $stmt = $this->conn->prepare($query);
            
            $this->conn->beginTransaction();
            foreach ($notas_estudiantes as $id_est => $calif) {
                // Validar que sea número o null
                $val = (!empty($calif) || $calif === '0') ? (float)$calif : null;

                $stmt->bindParam(':id_evaluacion', $id_evaluacion);
                $stmt->bindParam(':id_estudiante', $id_est);
                $stmt->bindParam(':calificacion', $val);
                $stmt->bindParam(':up_calificacion', $val);
                $stmt->execute();
            }
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>