<?php
/*
 * Archivo: models/Calificacion.php
 * (Añadida la función 'getHistorialPromedios')
 */
class Calificacion {
    
    private $conn;
    private $table_name = "calificaciones";

    public $id_calificacion;
    public $id_curso;
    public $id_estudiante;
    public $nota1;
    public $nota2;
    public $nota3; // Prom. Tareas

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readPorCurso($id_curso) {
        $listaCalificaciones = [];
        try {
            $query = "SELECT id_estudiante, nota1, nota2, nota3
                      FROM " . $this->table_name . "
                      WHERE id_curso = :id_curso";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $listaCalificaciones[$row['id_estudiante']] = $row;
            }
            return $listaCalificaciones;
        } catch (PDOException $e) { return $listaCalificaciones; }
    }

    public function guardar() {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                      (id_curso, id_estudiante, nota1, nota2, nota3)
                      VALUES
                      (:id_curso, :id_estudiante, :nota1, :nota2, :nota3)
                      ON DUPLICATE KEY UPDATE
                        nota1 = :up_nota1,
                        nota2 = :up_nota2";
            $stmt = $this->conn->prepare($query);
            $this->id_curso = htmlspecialchars(strip_tags($this->id_curso));
            $this->id_estudiante = htmlspecialchars(strip_tags($this->id_estudiante));
            $this->nota1 = htmlspecialchars(strip_tags($this->nota1));
            $this->nota2 = htmlspecialchars(strip_tags($this->nota2));
            $this->nota3 = $this->nota3 ?? 0; 
            $stmt->bindParam(':id_curso', $this->id_curso);
            $stmt->bindParam(':id_estudiante', $this->id_estudiante);
            $stmt->bindParam(':nota1', $this->nota1);
            $stmt->bindParam(':nota2', $this->nota2);
            $stmt->bindParam(':nota3', $this->nota3);
            $stmt->bindParam(':up_nota1', $this->nota1);
            $stmt->bindParam(':up_nota2', $this->nota2);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function readPorEstudiante($id_estudiante) {
        try {
            $query = "SELECT
                        c.id_curso,
                        c.nombre_curso,
                        c.horario, 
                        cal.nota1,
                        cal.nota2,
                        cal.nota3
                      FROM 
                        matriculas m
                      JOIN 
                        cursos c ON m.id_curso = c.id_curso
                      LEFT JOIN 
                        " . $this->table_name . " cal ON m.id_curso = cal.id_curso AND m.id_estudiante = cal.id_estudiante
                      WHERE 
                        m.id_estudiante = :id_estudiante
                      ORDER BY 
                        c.nombre_curso ASC";
            $stmt = $this->conn->prepare($query);
            $id_estudiante = htmlspecialchars(strip_tags($id_estudiante));
            $stmt->bindParam(':id_estudiante', $id_estudiante);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    public function readPorEstudianteYCurso($id_estudiante, $id_curso) {
        try {
            $query = "SELECT
                        c.nombre_curso,
                        cal.nota1,
                        cal.nota2,
                        cal.nota3
                      FROM 
                        matriculas m
                      JOIN 
                        cursos c ON m.id_curso = c.id_curso
                      LEFT JOIN 
                        " . $this->table_name . " cal ON m.id_curso = cal.id_curso AND m.id_estudiante = cal.id_estudiante
                      WHERE 
                        m.id_estudiante = :id_estudiante AND m.id_curso = :id_curso
                      LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_estudiante', $id_estudiante);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return false; }
    }

    public function sincronizarPromedioTareas($id_curso, $entregaModel) {
        try {
            $queryEstudiantes = "SELECT id_estudiante FROM matriculas WHERE id_curso = :id_curso";
            $stmtEstudiantes = $this->conn->prepare($queryEstudiantes);
            $stmtEstudiantes->bindParam(':id_curso', $id_curso);
            $stmtEstudiantes->execute();
            $estudiantes = $stmtEstudiantes->fetchAll(PDO::FETCH_ASSOC);
            $queryUpdate = "UPDATE " . $this->table_name . " 
                            SET nota3 = :promedio 
                            WHERE id_estudiante = :id_estudiante AND id_curso = :id_curso";
            $stmtUpdate = $this->conn->prepare($queryUpdate);
            foreach ($estudiantes as $est) {
                $id_estudiante = $est['id_estudiante'];
                $promedio_tareas = $entregaModel->getPromedioDeTareas($id_estudiante, $id_curso);
                $stmtUpdate->bindParam(':promedio', $promedio_tareas);
                $stmtUpdate->bindParam(':id_estudiante', $id_estudiante);
                $stmtUpdate->bindParam(':id_curso', $id_curso);
                $stmtUpdate->execute();
            }
            return true;
        } catch (PDOException $e) { return false; }
    }
    
    /**
     * NUEVA FUNCIÓN
     * Obtiene el historial de promedios de un estudiante.
     * Devuelve un array simple: [id_curso => promedio_final]
     */
    public function getHistorialPromedios($id_estudiante) {
        $historial = $this->readPorEstudiante($id_estudiante);
        $promedios = [];
        
        foreach ($historial as $curso) {
            $n1 = $curso['nota1'] ?? 0;
            $n2 = $curso['nota2'] ?? 0;
            $n3 = $curso['nota3'] ?? 0; // Prom. Tareas
            
            // Solo calculamos el promedio si las notas han sido puestas
            if ($n1 > 0 || $n2 > 0 || $n3 > 0) {
                 $promedio = ($n1 + $n2 + $n3) / 3;
                 $promedios[$curso['id_curso']] = $promedio;
            } else {
                 $promedios[$curso['id_curso']] = 0; // Aún no calificado
            }
        }
        return $promedios;
    }
}
?>