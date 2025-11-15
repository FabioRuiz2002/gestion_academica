<?php
/*
 * Archivo: models/PlanEstudio.php
 * Propósito: Modelo para la tabla 'planes_estudio' (Malla).
 * (CORREGIDOS todos los errores de sintaxis 'this.')
 */
class PlanEstudio {
    
    private $conn;
    private $table_name = "planes_estudio";

    public $id_plan_estudio;
    public $id_escuela;
    public $nombre_plan;
    public $anio;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        try {
            $query = "SELECT 
                        p.id_plan_estudio, p.nombre_plan, p.anio,
                        e.id_escuela, e.nombre_escuela,
                        f.id_facultad, f.nombre_facultad
                      FROM " . $this->table_name . " p
                      JOIN escuelas e ON p.id_escuela = e.id_escuela
                      JOIN facultades f ON e.id_facultad = f.id_facultad
                      ORDER BY f.nombre_facultad, e.nombre_escuela, p.anio DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }
    
    public function readByEscuela($id_escuela) {
         try {
            $query = "SELECT * FROM " . $this->table_name . "
                      WHERE id_escuela = :id_escuela
                      ORDER BY anio DESC, nombre_plan ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_escuela', $id_escuela);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }
    
    public function readForDropdown() {
        try {
            $query = "SELECT 
                        p.id_plan_estudio, p.nombre_plan, e.nombre_escuela
                      FROM " . $this->table_name . " p
                      JOIN escuelas e ON p.id_escuela = e.id_escuela
                      ORDER BY e.nombre_escuela, p.nombre_plan";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }
    
    public function readOne($id_plan) {
        try {
            $query = "SELECT 
                        p.*,
                        e.id_escuela, e.nombre_escuela,
                        f.id_facultad, f.nombre_facultad
                      FROM " . $this->table_name . " p
                      JOIN escuelas e ON p.id_escuela = e.id_escuela
                      JOIN facultades f ON e.id_facultad = f.id_facultad
                      WHERE p.id_plan_estudio = ? 
                      LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id_plan);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return false; }
    }

    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . " (id_escuela, nombre_plan, anio) VALUES (:id_escuela, :nombre, :anio)";
            $stmt = $this->conn->prepare($query);
            $this->id_escuela = htmlspecialchars(strip_tags($this->id_escuela));
            $this->nombre_plan = htmlspecialchars(strip_tags($this->nombre_plan));
            $this->anio = htmlspecialchars(strip_tags($this->anio));
            $stmt->bindParam(':id_escuela', $this->id_escuela);
            $stmt->bindParam(':nombre', $this->nombre_plan);
            $stmt->bindParam(':anio', $this->anio);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . " SET nombre_plan = :nombre, anio = :anio, id_escuela = :id_escuela WHERE id_plan_estudio = :id";
            $stmt = $this->conn->prepare($query);
            $this->nombre_plan = htmlspecialchars(strip_tags($this->nombre_plan));
            $this->anio = htmlspecialchars(strip_tags($this->anio));
            $this->id_escuela = htmlspecialchars(strip_tags($this->id_escuela));
            $this->id_plan_estudio = htmlspecialchars(strip_tags($this->id_plan_estudio));
            $stmt->bindParam(':nombre', $this->nombre_plan);
            $stmt->bindParam(':anio', $this->anio);
            $stmt->bindParam(':id_escuela', $this->id_escuela);
            $stmt->bindParam(':id', $this->id_plan_estudio);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_plan_estudio = :id";
            $stmt = $this->conn->prepare($query);
            $this->id_plan_estudio = htmlspecialchars(strip_tags($this->id_plan_estudio));
            $stmt->bindParam(':id', $this->id_plan_estudio);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }
}
?>