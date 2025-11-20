<?php
class Prerequisito {
    private $conn;
    private $table_name = "prerequisitos";
    public $id_prerequisito; public $id_curso_principal; public $id_curso_requisito;

    public function __construct($db) { $this->conn = $db; }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " (id_curso_principal, id_curso_requisito) VALUES (:p, :r)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':p', $this->id_curso_principal);
        $stmt->bindParam(':r', $this->id_curso_requisito);
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_prerequisito = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id_prerequisito);
        return $stmt->execute();
    }
    
    public function readByCurso($id_curso) {
        $query = "SELECT p.id_prerequisito, c.nombre_curso as nombre_requisito 
                  FROM " . $this->table_name . " p
                  JOIN cursos c ON p.id_curso_requisito = c.id_curso
                  WHERE p.id_curso_principal = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_curso);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readCursosDisponiblesParaReq($id_plan, $ciclo_limite, $id_curso_actual) {
        $query = "SELECT c.id_curso, c.nombre_curso 
                  FROM cursos_plan cp 
                  JOIN cursos c ON cp.id_curso = c.id_curso
                  WHERE cp.id_plan_estudio = :plan 
                  AND cp.ciclo < :ciclo 
                  AND c.id_curso != :actual
                  AND c.id_curso NOT IN (SELECT id_curso_requisito FROM " . $this->table_name . " WHERE id_curso_principal = :actual)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':plan', $id_plan);
        $stmt->bindParam(':ciclo', $ciclo_limite);
        $stmt->bindParam(':actual', $id_curso_actual);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readPrerequisitosPorPlan($id_plan) {
        $query = "SELECT p.id_curso_principal, p.id_curso_requisito, c_req.nombre_curso as nombre_curso_requisito
                  FROM " . $this->table_name . " p
                  JOIN cursos_plan cp ON p.id_curso_principal = cp.id_curso
                  JOIN cursos c_req ON p.id_curso_requisito = c_req.id_curso
                  WHERE cp.id_plan_estudio = :plan";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':plan', $id_plan);
        $stmt->execute();
        $reglas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reglas[$row['id_curso_principal']][] = $row;
        }
        return $reglas;
    }
}
?>