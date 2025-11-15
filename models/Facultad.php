<?php
/*
 * Archivo: models/Facultad.php
 * Propósito: Modelo para la tabla 'facultades'.
 * (Añadidas funciones CRUD completas)
 */
class Facultad {
    
    private $conn;
    private $table_name = "facultades";

    public $id_facultad;
    public $nombre_facultad;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        try {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY nombre_facultad ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function readOne() {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_facultad = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id_facultad);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . " (nombre_facultad) VALUES (:nombre)";
            $stmt = $this->conn->prepare($query);
            $this->nombre_facultad = htmlspecialchars(strip_tags($this->nombre_facultad));
            $stmt->bindParam(':nombre', $this->nombre_facultad);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . " SET nombre_facultad = :nombre WHERE id_facultad = :id";
            $stmt = $this->conn->prepare($query);
            $this->nombre_facultad = htmlspecialchars(strip_tags($this->nombre_facultad));
            $this->id_facultad = htmlspecialchars(strip_tags($this->id_facultad));
            $stmt->bindParam(':nombre', $this->nombre_facultad);
            $stmt->bindParam(':id', $this->id_facultad);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_facultad = :id";
            $stmt = $this->conn->prepare($query);
            $this->id_facultad = htmlspecialchars(strip_tags($this->id_facultad));
            $stmt->bindParam(':id', $this->id_facultad);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Error si una escuela depende de esta facultad
            return false;
        }
    }
}
?>