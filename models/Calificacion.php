<?php
/*
 * Archivo: models/Calificacion.php
 * Propósito: Modelo para la entidad Calificacion.
 * (Añadido 'horario' a readPorEstudiante)
 */
class Calificacion {
    
    private $conn;
    private $table_name = "calificaciones";

    public $id_calificacion;
    public $id_curso;
    public $id_estudiante;
    public $nota1;
    public $nota2;
    public $nota3;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readPorCurso($id_curso) {
        $listaCalificaciones = [];
        try {
            $query = "SELECT id_estudiante, nota1, nota2, nota3
                      FROM " . $this->table_name . "
                      WHERE id_curso = :id_curso";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $listaCalificaciones[$row['id_estudiante']] = $row;
            }
            return $listaCalificaciones;
        } catch (PDOException $e) {
            echo "Error al leer calificaciones: " . $e->getMessage();
            return $listaCalificaciones;
        }
    }

    public function guardar() {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                      (id_curso, id_estudiante, nota1, nota2, nota3)
                      VALUES
                      (:id_curso, :id_estudiante, :nota1, :nota2, :nota3)
                      ON DUPLICATE KEY UPDATE
                        nota1 = :up_nota1,
                        nota2 = :up_nota2,
                        nota3 = :up_nota3";
            $stmt = $this->conn->prepare($query);

            $this->id_curso = htmlspecialchars(strip_tags($this->id_curso));
            $this->id_estudiante = htmlspecialchars(strip_tags($this->id_estudiante));
            $this->nota1 = htmlspecialchars(strip_tags($this->nota1));
            $this->nota2 = htmlspecialchars(strip_tags($this->nota2));
            $this->nota3 = htmlspecialchars(strip_tags($this->nota3));

            $stmt->bindParam(':id_curso', $this->id_curso);
            $stmt->bindParam(':id_estudiante', $this->id_estudiante);
            $stmt->bindParam(':nota1', $this->nota1);
            $stmt->bindParam(':nota2', $this->nota2);
            $stmt->bindParam(':nota3', $this->nota3);
            
            $stmt->bindParam(':up_nota1', $this->nota1);
            $stmt->bindParam(':up_nota2', $this->nota2);
            $stmt->bindParam(':up_nota3', $this->nota3);
            
            if ($stmt->execute()) { return true; }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function readPorEstudiante($id_estudiante) {
        try {
            $query = "SELECT
                        c.nombre_curso,
                        c.horario, 
                        cal.nota1,
                        cal.nota2,
                        cal.nota3
                      FROM 
                        matriculas m
                      JOIN 
                        cursos c ON m.id_curso = c.id_curso
                      LEFT JOIN 
                        " . $this->table_name . " cal ON m.id_curso = cal.id_curso AND m.id_estudiante = cal.id_estudiante
                      WHERE 
                        m.id_estudiante = :id_estudiante
                      ORDER BY 
                        c.nombre_curso ASC";
            $stmt = $this->conn->prepare($query);
            $id_estudiante = htmlspecialchars(strip_tags($id_estudiante));
            $stmt->bindParam(':id_estudiante', $id_estudiante);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al leer calificaciones del estudiante: " . $e->getMessage();
            return [];
        }
    }
}
?>