<?php
/*
 * Archivo: models/Matricula.php
 * (Reescrita la función 'readCursosDisponibles' para usar la Malla Curricular)
 */
class Matricula {
    
    private $conn;
    private $table_name = "matriculas";

    public $id_matricula;
    public $id_curso;
    public $id_estudiante;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readEstudiantesPorCurso($id_curso) {
        try {
            $query = "SELECT u.id_usuario, u.nombre, u.apellido, u.email, m.id_matricula
                      FROM " . $this->table_name . " m
                      JOIN usuarios u ON m.id_estudiante = u.id_usuario
                      WHERE m.id_curso = :id_curso
                      ORDER BY u.apellido ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    public function desmatricular() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_matricula = :id_matricula";
            $stmt = $this->conn->prepare($query);
            $this->id_matricula = htmlspecialchars(strip_tags($this->id_matricula));
            $stmt->bindParam(':id_matricula', $this->id_matricula);
            if ($stmt->execute()) { return true; }
            return false;
        } catch (PDOException $e) { return false; }
    }
    
    /**
     * FUNCIÓN REESCRITA
     * Lee los cursos del Plan del Estudiante, excluyendo los que ya matriculó.
     * Devuelve los cursos agrupados por ciclo.
     */
    public function readCursosDisponiblesPorPlan($id_plan_estudio, $id_estudiante) {
        try {
            // (La función 'readCursosDisponibles' antigua se borra o reemplaza por esta)
            $query = "SELECT 
                        cp.id_curso,
                        cp.ciclo,
                        c.nombre_curso,
                        c.horario,
                        u.nombre as nombre_profesor,
                        u.apellido as apellido_profesor
                      FROM 
                        cursos_plan cp
                      JOIN 
                        cursos c ON cp.id_curso = c.id_curso
                      JOIN
                        usuarios u ON c.id_profesor = u.id_usuario
                      WHERE 
                        cp.id_plan_estudio = :id_plan_estudio
                        AND cp.id_curso NOT IN (
                            -- Subconsulta para excluir cursos ya matriculados
                            SELECT id_curso 
                            FROM " . $this->table_name . "
                            WHERE id_estudiante = :id_estudiante
                        )
                      ORDER BY 
                        cp.ciclo ASC, c.nombre_curso ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_plan_estudio', $id_plan_estudio);
            $stmt->bindParam(':id_estudiante', $id_estudiante);
            $stmt->execute();
            
            $cursos_por_ciclo = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $cursos_por_ciclo[$row['ciclo']][] = $row;
            }
            return $cursos_por_ciclo;

        } catch (PDOException $e) {
            echo "Error al leer cursos disponibles: " . $e->getMessage();
            return [];
        }
    }
    
    public function matricular() {
        try {
            $checkQuery = "SELECT id_matricula FROM " . $this->table_name . " WHERE id_curso = :id_curso AND id_estudiante = :id_estudiante";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':id_curso', $this->id_curso);
            $checkStmt->bindParam(':id_estudiante', $this->id_estudiante);
            $checkStmt->execute();
            if ($checkStmt->rowCount() > 0) { return false; }

            $query = "INSERT INTO " . $this->table_name . " (id_curso, id_estudiante) VALUES (:id_curso, :id_estudiante)";
            $stmt = $this->conn->prepare($query);
            $this->id_curso = htmlspecialchars(strip_tags($this->id_curso));
            $this->id_estudiante = htmlspecialchars(strip_tags($this->id_estudiante));
            $stmt->bindParam(':id_curso', $this->id_curso);
            $stmt->bindParam(':id_estudiante', $this->id_estudiante);
            if ($stmt->execute()) { return true; }
            return false;
        } catch (PDOException $e) { return false; }
    }
    
    public function readEstudiantesNoInscritos($id_curso) {
        try {
            $query = "SELECT id_usuario, nombre, apellido
                      FROM usuarios
                      WHERE id_rol = 3 AND estado = 1
                      AND id_usuario NOT IN (
                          SELECT id_estudiante 
                          FROM " . $this->table_name . "
                          WHERE id_curso = :id_curso
                      )
                      ORDER BY apellido ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }
    
    public function countEstudiantesUnicos() {
        try {
            $query = "SELECT COUNT(DISTINCT id_estudiante) as total FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        } catch (PDOException $e) { return 0; }
    }
}
?>