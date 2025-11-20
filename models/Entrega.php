<?php
class Entrega {
    private $conn;
    private $table_name = "entregas";
    public $id_entrega; public $id_tarea; public $id_estudiante; public $archivo; public $calificacion; public $comentario; public $nombre_archivo; public $ruta_archivo; public $comentario_profesor;

    public function __construct($db) { $this->conn = $db; }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " (id_tarea, id_estudiante, nombre_archivo, ruta_archivo) VALUES (:t, :e, :n, :r)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':t', $this->id_tarea);
        $stmt->bindParam(':e', $this->id_estudiante);
        $stmt->bindParam(':n', $this->nombre_archivo);
        $stmt->bindParam(':r', $this->ruta_archivo);
        return $stmt->execute();
    }

    public function readPorTarea($id_tarea) {
        $query = "SELECT e.*, u.nombre, u.apellido FROM " . $this->table_name . " e JOIN usuarios u ON e.id_estudiante = u.id_usuario WHERE e.id_tarea = :id ORDER BY u.apellido";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_tarea);
        $stmt->execute();
        return $stmt;
    }

    public function readPorEstudianteYCurso($id_estudiante, $id_curso) {
        $query = "SELECT e.*, t.titulo FROM " . $this->table_name . " e JOIN tareas t ON e.id_tarea = t.id_tarea WHERE e.id_estudiante = :e AND t.id_curso = :c";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':e', $id_estudiante);
        $stmt->bindParam(':c', $id_curso);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calificar() {
        $query = "UPDATE " . $this->table_name . " SET calificacion = :c, comentario_profesor = :cp WHERE id_entrega = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':c', $this->calificacion);
        $stmt->bindParam(':cp', $this->comentario_profesor);
        $stmt->bindParam(':id', $this->id_entrega);
        return $stmt->execute();
    }
    
    public function readEntregasRecientesPorProfesor($id_profesor) {
        $query = "SELECT e.fecha_entrega, t.titulo as nombre_tarea, c.nombre_curso, u.nombre, u.apellido
                  FROM " . $this->table_name . " e
                  JOIN tareas t ON e.id_tarea = t.id_tarea
                  JOIN cursos c ON t.id_curso = c.id_curso
                  JOIN usuarios u ON e.id_estudiante = u.id_usuario
                  WHERE c.id_profesor = :id AND e.calificacion IS NULL
                  ORDER BY e.fecha_entrega DESC LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_profesor);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>