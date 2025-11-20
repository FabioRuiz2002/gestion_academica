<?php
class Tarea {
    private $conn;
    private $table_name = "tareas";
    public $id_tarea; public $id_curso; public $titulo; public $descripcion; public $fecha_limite;

    public function __construct($db) { $this->conn = $db; }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " (id_curso, titulo, descripcion, fecha_limite) VALUES (:c, :t, :d, :f)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':c', $this->id_curso);
        $stmt->bindParam(':t', $this->titulo);
        $stmt->bindParam(':d', $this->descripcion);
        $stmt->bindParam(':f', $this->fecha_limite);
        return $stmt->execute();
    }

    public function readPorCurso($id_curso) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_curso = :id ORDER BY fecha_limite ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_curso);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_tarea = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_tarea = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id_tarea);
        return $stmt->execute();
    }
    
    public function readTareasProximasPorEstudiante($id_estudiante) {
        $query = "SELECT t.titulo, t.fecha_limite, c.nombre_curso
                  FROM " . $this->table_name . " t
                  JOIN matriculas m ON t.id_curso = m.id_curso
                  JOIN cursos c ON t.id_curso = c.id_curso
                  WHERE m.id_estudiante = :id
                  AND t.fecha_limite >= CURDATE()
                  ORDER BY t.fecha_limite ASC LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_estudiante);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>