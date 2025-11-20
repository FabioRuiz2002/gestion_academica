<?php
class CursoPlan {
    private $conn;
    private $table_name = "cursos_plan";
    public $id_cursos_plan; public $id_plan_estudio; public $id_curso; public $ciclo;

    public function __construct($db) { $this->conn = $db; }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " (id_plan_estudio, id_curso, ciclo) VALUES (:p, :c, :ci)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':p', $this->id_plan_estudio);
        $stmt->bindParam(':c', $this->id_curso);
        $stmt->bindParam(':ci', $this->ciclo);
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_cursos_plan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id_cursos_plan);
        return $stmt->execute();
    }

    public function readCursosEnPlan($id_plan) {
        $query = "SELECT cp.id_cursos_plan, cp.ciclo, c.id_curso, c.nombre_curso, c.horario, c.id_profesor, u.nombre as nombre_profesor, u.apellido as apellido_profesor 
                  FROM " . $this->table_name . " cp
                  JOIN cursos c ON cp.id_curso = c.id_curso
                  JOIN usuarios u ON c.id_profesor = u.id_usuario
                  WHERE cp.id_plan_estudio = :id_plan
                  ORDER BY cp.ciclo ASC, c.nombre_curso ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_plan', $id_plan);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readCursosFueraDePlan($id_plan) {
        // Muestra cursos que no están en ninguna malla
        $query = "SELECT c.id_curso, c.nombre_curso FROM cursos c WHERE c.id_curso NOT IN (SELECT id_curso FROM " . $this->table_name . ") ORDER BY c.nombre_curso ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>