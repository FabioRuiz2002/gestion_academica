<?php
/*
 * Archivo: models/Curso.php
 * Propósito: Modelo para la entidad Curso.
 * (Añadido el campo 'horario')
 */

class Curso {

    private $conn;
    private $table_name = "cursos";

    public $id_curso;
    public $id_profesor;
    public $nombre_curso;
    public $descripcion;
    public $horario; // <-- NUEVO
    public $anio_academico;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        try {
            $query = "SELECT 
                        c.id_curso, 
                        c.nombre_curso, 
                        c.anio_academico, 
                        c.horario, 
                        u.nombre as nombre_profesor, 
                        u.apellido as apellido_profesor
                      FROM " . $this->table_name . " c
                      JOIN usuarios u ON c.id_profesor = u.id_usuario
                      ORDER BY c.nombre_curso ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo "Error al leer cursos: " . $e->getMessage();
            return false;
        }
    }

    public function readOne($id_curso) {
        try {
            // Seleccionamos todo, incluyendo el nuevo campo horario
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_curso = :id_curso LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al leer curso: " . $e->getMessage();
            return false;
        }
    }

    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                      (id_profesor, nombre_curso, descripcion, horario, anio_academico) 
                      VALUES 
                      (:id_profesor, :nombre_curso, :descripcion, :horario, :anio_academico)";
            
            $stmt = $this->conn->prepare($query);

            $this->id_profesor = htmlspecialchars(strip_tags($this->id_profesor));
            $this->nombre_curso = htmlspecialchars(strip_tags($this->nombre_curso));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            $this->horario = htmlspecialchars(strip_tags($this->horario)); // <-- NUEVO
            $this->anio_academico = htmlspecialchars(strip_tags($this->anio_academico));

            $stmt->bindParam(':id_profesor', $this->id_profesor);
            $stmt->bindParam(':nombre_curso', $this->nombre_curso);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':horario', $this->horario); // <-- NUEVO
            $stmt->bindParam(':anio_academico', $this->anio_academico);

            if ($stmt->execute()) { return true; }
            return false;
        } catch (PDOException $e) {
            echo "Error al crear curso: " . $e->getMessage();
            return false;
        }
    }

    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . " SET
                        id_profesor = :id_profesor,
                        nombre_curso = :nombre_curso,
                        descripcion = :descripcion,
                        horario = :horario, 
                        anio_academico = :anio_academico
                      WHERE id_curso = :id_curso";
            
            $stmt = $this->conn->prepare($query);

            $this->id_profesor = htmlspecialchars(strip_tags($this->id_profesor));
            $this->nombre_curso = htmlspecialchars(strip_tags($this->nombre_curso));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            $this->horario = htmlspecialchars(strip_tags($this->horario)); // <-- NUEVO
            $this->anio_academico = htmlspecialchars(strip_tags($this->anio_academico));
            $this->id_curso = htmlspecialchars(strip_tags($this->id_curso));

            $stmt->bindParam(':id_profesor', $this->id_profesor);
            $stmt->bindParam(':nombre_curso', $this->nombre_curso);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':horario', $this->horario); // <-- NUEVO
            $stmt->bindParam(':anio_academico', $this->anio_academico);
            $stmt->bindParam(':id_curso', $this->id_curso);

            if ($stmt->execute()) { return true; }
            return false;
        } catch (PDOException $e) {
            echo "Error al actualizar curso: " . $e->getMessage();
            return false;
        }
    }

    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_curso = :id_curso";
            $stmt = $this->conn->prepare($query);
            $this->id_curso = htmlspecialchars(strip_tags($this->id_curso));
            $stmt->bindParam(':id_curso', $this->id_curso);
            if ($stmt->execute()) { return true; }
            return false;
        } catch (PDOException $e) {
            echo "Error al eliminar curso: " . $e->getMessage();
            return false;
        }
    }
    
    public function countTotal() {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function readPorProfesor($id_profesor) {
        try {
            $query = "SELECT 
                        c.id_curso, 
                        c.nombre_curso, 
                        c.descripcion,
                        c.horario, 
                        u.nombre AS nombre_profesor,
                        u.apellido AS apellido_profesor
                      FROM 
                        " . $this->table_name . " c
                      JOIN 
                        usuarios u ON c.id_profesor = u.id_usuario
                      WHERE 
                        c.id_profesor = :id_profesor
                      ORDER BY 
                        c.nombre_curso ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_profesor', $id_profesor);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al leer cursos por profesor: " . $e->getMessage(); 
            return [];
        }
    }
}
?>