<?php
/*
 * Archivo: models/Escuela.php
 * Propósito: Modelo para la tabla 'escuelas'.
 * (Añadida la función 'readByFacultad')
 */
class Escuela {
    
    private $conn;
    private $table_name = "escuelas";

    public $id_escuela;
    public $id_facultad;
    public $nombre_escuela;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        try {
            $query = "SELECT 
                        e.id_escuela, 
                        e.nombre_escuela,
                        e.id_facultad, 
                        f.nombre_facultad
                      FROM 
                        " . $this->table_name . " e
                      JOIN 
                        facultades f ON e.id_facultad = f.id_facultad
                      ORDER BY 
                        f.nombre_facultad, e.nombre_escuela ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }
    
    /**
     * NUEVA FUNCIÓN
     * Lee todas las escuelas de UNA facultad específica.
     */
    public function readByFacultad($id_facultad) {
         try {
            $query = "SELECT * FROM " . $this->table_name . "
                      WHERE id_facultad = :id_facultad
                      ORDER BY nombre_escuela ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_facultad', $id_facultad);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function readOne() {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_escuela = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id_escuela);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return false; }
    }

    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . " (nombre_escuela, id_facultad) VALUES (:nombre, :id_facultad)";
            $stmt = $this->conn->prepare($query);
            $this->nombre_escuela = htmlspecialchars(strip_tags($this->nombre_escuela));
            $this->id_facultad = htmlspecialchars(strip_tags($this->id_facultad));
            $stmt->bindParam(':nombre', $this->nombre_escuela);
            $stmt->bindParam(':id_facultad', $this->id_facultad);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . " SET nombre_escuela = :nombre, id_facultad = :id_facultad WHERE id_escuela = :id";
            $stmt = $this->conn->prepare($query);
            $this->nombre_escuela = htmlspecialchars(strip_tags($this->nombre_escuela));
            $this->id_facultad = htmlspecialchars(strip_tags($this->id_facultad));
            $this->id_escuela = htmlspecialchars(strip_tags($this->id_escuela));
            $stmt->bindParam(':nombre', $this->nombre_escuela);
            $stmt->bindParam(':id_facultad', $this->id_facultad);
            $stmt->bindParam(':id', $this->id_escuela);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_escuela = :id";
            $stmt = $this->conn->prepare($query);
            $this->id_escuela = htmlspecialchars(strip_tags($this->id_escuela));
            $stmt->bindParam(':id', $this->id_escuela);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }
}
?>