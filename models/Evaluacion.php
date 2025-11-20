<?php
class Evaluacion {
    
    private $conn;
    private $table_name = "evaluaciones";

    public $id_evaluacion;
    public $id_curso;
    public $nombre;
    public $descripcion;
    public $porcentaje;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readPorCurso($id_curso) {
        try {
            $query = "SELECT * FROM " . $this->table_name . "
                      WHERE id_curso = :id_curso
                      ORDER BY nombre ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    public function checkTotalPorcentaje($id_curso, $nuevo_porcentaje, $excluir_id = 0) {
        try {
            $query = "SELECT SUM(porcentaje) as total 
                      FROM " . $this->table_name . "
                      WHERE id_curso = :id_curso 
                      AND id_evaluacion != :excluir_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->bindParam(':excluir_id', $excluir_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $total_actual = $row['total'] ?? 0;
            
            return ($total_actual + $nuevo_porcentaje) > 100;
        } catch (PDOException $e) { return true; } 
    }

    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                      (id_curso, nombre, descripcion, porcentaje)
                      VALUES
                      (:id_curso, :nombre, :descripcion, :porcentaje)";
            $stmt = $this->conn->prepare($query);

            $this->id_curso = htmlspecialchars(strip_tags($this->id_curso));
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            $this->porcentaje = htmlspecialchars(strip_tags($this->porcentaje));

            $stmt->bindParam(':id_curso', $this->id_curso);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':porcentaje', $this->porcentaje);
            
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_evaluacion = :id";
            $stmt = $this->conn->prepare($query);
            $this->id_evaluacion = htmlspecialchars(strip_tags($this->id_evaluacion));
            $stmt->bindParam(':id', $this->id_evaluacion);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }
}
?>