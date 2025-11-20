<?php
class Matricula {
    private $conn;
    private $table_name = "matriculas";

    public $id_matricula;
    public $id_curso;
    public $id_estudiante;

    public function __construct($db) { $this->conn = $db; }

    // --- NUEVA FUNCIÓN: Obtener detalles completos de cursos matriculados ---
    public function readCursosMatriculados($id_estudiante) {
        try {
            $query = "SELECT 
                        c.id_curso, c.nombre_curso, c.horario,
                        u.nombre as nombre_profesor, u.apellido as apellido_profesor,
                        cp.ciclo
                      FROM " . $this->table_name . " m
                      JOIN cursos c ON m.id_curso = c.id_curso
                      JOIN usuarios u ON c.id_profesor = u.id_usuario
                      LEFT JOIN cursos_plan cp ON c.id_curso = cp.id_curso
                      WHERE m.id_estudiante = :id_estudiante
                      ORDER BY cp.ciclo ASC, c.nombre_curso ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_estudiante', $id_estudiante);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    // Obtiene solo los horarios (strings) para validar cruces
    public function getHorariosEstudiante($id_estudiante) {
        try {
            $query = "SELECT c.horario FROM " . $this->table_name . " m
                      JOIN cursos c ON m.id_curso = c.id_curso
                      WHERE m.id_estudiante = :id_estudiante";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_estudiante', $id_estudiante);
            $stmt->execute();
            $horarios = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (!empty($row['horario'])) $horarios[] = $row['horario'];
            }
            return $horarios;
        } catch (PDOException $e) { return []; }
    }

    public function matricular() {
        try {
            $query = "INSERT INTO " . $this->table_name . " (id_curso, id_estudiante) VALUES (:id_curso, :id_estudiante)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $this->id_curso);
            $stmt->bindParam(':id_estudiante', $this->id_estudiante);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function readEstudiantesPorCurso($id_curso) {
        try {
            $query = "SELECT u.id_usuario, u.nombre, u.apellido, u.email 
                      FROM " . $this->table_name . " m 
                      JOIN usuarios u ON m.id_estudiante = u.id_usuario 
                      WHERE m.id_curso = :id_curso ORDER BY u.apellido";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }
    
    // Esta función YA EXCLUYE los cursos matriculados (NOT IN), por eso no te salen repetidos.
    public function readCursosDisponiblesPorPlan($id_plan, $id_estudiante) {
        try {
            $query = "SELECT cp.ciclo, c.id_curso, c.nombre_curso, c.horario, u.nombre as nombre_profesor, u.apellido as apellido_profesor
                      FROM cursos_plan cp
                      JOIN cursos c ON cp.id_curso = c.id_curso
                      JOIN usuarios u ON c.id_profesor = u.id_usuario
                      WHERE cp.id_plan_estudio = :id_plan
                      AND c.id_curso NOT IN (SELECT id_curso FROM " . $this->table_name . " WHERE id_estudiante = :id_estudiante)
                      ORDER BY cp.ciclo, c.nombre_curso";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_plan', $id_plan);
            $stmt->bindParam(':id_estudiante', $id_estudiante);
            $stmt->execute();
            $cursosPorCiclo = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $cursosPorCiclo[$row['ciclo']][] = $row;
            }
            return $cursosPorCiclo;
        } catch (PDOException $e) { return []; }
    }
    
    public function countEstudiantesUnicos() {
        $query = "SELECT COUNT(DISTINCT id_estudiante) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // --- NUEVA FUNCIÓN: El Admin elimina un curso específico de un alumno ---
    public function eliminarCursoDeAlumno($id_estudiante, $id_curso) {
        try {
            $query = "DELETE FROM " . $this->table_name . " 
                      WHERE id_estudiante = :id_estudiante AND id_curso = :id_curso";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_estudiante', $id_estudiante);
            $stmt->bindParam(':id_curso', $id_curso);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

}
?>