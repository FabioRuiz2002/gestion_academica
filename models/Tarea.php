<?php
/*
 * Archivo: models/Tarea.php
 * Propósito: Modelo para la gestión de Tareas.
 * (Corregidos errores de sintaxis 'this.')
 */
class Tarea {
    
    private $conn;
    private $table_name = "tareas";

    public $id_tarea;
    public $id_curso;
    public $titulo;
    public $descripcion;
    public $fecha_limite;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readPorCurso($id_curso) {
        try {
            $query = "SELECT * FROM " . $this->table_name . "
                      WHERE id_curso = :id_curso
                      ORDER BY fecha_limite DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                      (id_curso, titulo, descripcion, fecha_limite)
                      VALUES
                      (:id_curso, :titulo, :descripcion, :fecha_limite)";
            $stmt = $this->conn->prepare($query);
            $this->id_curso = htmlspecialchars(strip_tags($this->id_curso));
            $this->titulo = htmlspecialchars(strip_tags($this->titulo));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            $stmt->bindParam(':id_curso', $this->id_curso);
            $stmt->bindParam(':titulo', $this->titulo);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':fecha_limite', $this->fecha_limite);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_tarea = :id_tarea";
            $stmt = $this->conn->prepare($query);
            $this->id_tarea = htmlspecialchars(strip_tags($this->id_tarea));
            $stmt->bindParam(':id_tarea', $this->id_tarea);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function readOne($id_tarea) {
        try {
            $query = "SELECT t.*, c.id_curso 
                      FROM " . $this->table_name . " t
                      JOIN cursos c ON t.id_curso = c.id_curso
                      WHERE t.id_tarea = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id_tarea);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return false; }
    }
    
    public function readTareasProximasPorEstudiante($id_estudiante) {
        try {
            $query = "SELECT 
                        t.id_tarea, t.titulo, t.fecha_limite,
                        c.id_curso, c.nombre_curso
                      FROM 
                        tareas t
                      JOIN 
                        cursos c ON t.id_curso = c.id_curso
                      JOIN 
                        matriculas m ON t.id_curso = m.id_curso
                      LEFT JOIN 
                        entregas e ON t.id_tarea = e.id_tarea AND e.id_estudiante = m.id_estudiante
                      WHERE 
                        m.id_estudiante = :id_estudiante
                        AND t.fecha_limite IS NOT NULL
                        AND t.fecha_limite >= NOW() -- Tareas que no han vencido
                        AND e.id_entrega IS NULL      -- Tareas que no ha entregado
                      ORDER BY 
                        t.fecha_limite ASC
                      LIMIT 5";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_estudiante', $id_estudiante);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>