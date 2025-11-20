<?php
class Material {
    private $conn;
    private $table_name = "materiales";
    public $id_material; public $id_curso; public $nombre_archivo; public $ruta_archivo;

    public function __construct($db) { $this->conn = $db; }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " (id_curso, nombre_archivo, ruta_archivo) VALUES (:c, :n, :r)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':c', $this->id_curso);
        $stmt->bindParam(':n', $this->nombre_archivo);
        $stmt->bindParam(':r', $this->ruta_archivo);
        return $stmt->execute();
    }

    public function readPorCurso($id_curso) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_curso = :id ORDER BY fecha_subida DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_curso);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_material = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_material = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id_material);
        return $stmt->execute();
    }
}
?>