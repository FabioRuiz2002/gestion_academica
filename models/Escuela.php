<?php
class Escuela {
    private $conn;
    private $table_name = "escuelas";
    public $id_escuela; public $id_facultad; public $nombre_escuela;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readByFacultad($id_facultad) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_facultad = ? ORDER BY nombre_escuela ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_facultad);
        $stmt->execute();
        return $stmt;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nombre_escuela ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_escuela = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_escuela);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " (nombre_escuela, id_facultad) VALUES (:nombre, :id_facultad)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $this->nombre_escuela);
        $stmt->bindParam(':id_facultad', $this->id_facultad);
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nombre_escuela = :nombre, id_facultad = :id_facultad WHERE id_escuela = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $this->nombre_escuela);
        $stmt->bindParam(':id_facultad', $this->id_facultad);
        $stmt->bindParam(':id', $this->id_escuela);
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_escuela = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id_escuela);
        return $stmt->execute();
    }
}
?>