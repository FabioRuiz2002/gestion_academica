<?php
class PlanEstudio {
    private $conn;
    private $table_name = "planes_estudio";
    public $id_plan_estudio; public $id_escuela; public $nombre_plan; public $anio;

    public function __construct($db) { $this->conn = $db; }

    public function readByEscuela($id_escuela) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_escuela = ? ORDER BY anio DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_escuela);
        $stmt->execute();
        return $stmt;
    }
    
    public function readOne($id) {
        $query = "SELECT p.*, e.nombre_escuela, f.nombre_facultad, f.id_facultad 
                  FROM " . $this->table_name . " p
                  JOIN escuelas e ON p.id_escuela = e.id_escuela
                  JOIN facultades f ON e.id_facultad = f.id_facultad
                  WHERE p.id_plan_estudio = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function readForDropdown() {
        $query = "SELECT p.id_plan_estudio, p.nombre_plan, e.nombre_escuela 
                  FROM " . $this->table_name . " p
                  JOIN escuelas e ON p.id_escuela = e.id_escuela
                  ORDER BY e.nombre_escuela, p.nombre_plan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " (id_escuela, nombre_plan, anio) VALUES (:ide, :nom, :anio)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ide', $this->id_escuela);
        $stmt->bindParam(':nom', $this->nombre_plan);
        $stmt->bindParam(':anio', $this->anio);
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nombre_plan=:n, anio=:a, id_escuela=:ide WHERE id_plan_estudio=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':n', $this->nombre_plan);
        $stmt->bindParam(':a', $this->anio);
        $stmt->bindParam(':ide', $this->id_escuela);
        $stmt->bindParam(':id', $this->id_plan_estudio);
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_plan_estudio = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id_plan_estudio);
        return $stmt->execute();
    }
}
?>