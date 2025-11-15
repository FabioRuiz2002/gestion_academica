<?php
/*
 * Archivo: models/CursoPlan.php
 * Propósito: Modelo para la tabla 'cursos_plan' (Malla Curricular).
 * (CORREGIDO: Añadido 'c.id_curso' a readCursosEnPlan)
 */
class CursoPlan {
    
    private $conn;
    private $table_name = "cursos_plan";

    public $id_cursos_plan;
    public $id_plan_estudio;
    public $id_curso;
    public $ciclo;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                      (id_plan_estudio, id_curso, ciclo)
                      VALUES
                      (:id_plan_estudio, :id_curso, :ciclo)";
            
            $stmt = $this->conn->prepare($query);

            $this->id_plan_estudio = htmlspecialchars(strip_tags($this->id_plan_estudio));
            $this->id_curso = htmlspecialchars(strip_tags($this->id_curso));
            $this->ciclo = htmlspecialchars(strip_tags($this->ciclo));

            $stmt->bindParam(':id_plan_estudio', $this->id_plan_estudio);
            $stmt->bindParam(':id_curso', $this->id_curso);
            $stmt->bindParam(':ciclo', $this->ciclo);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_cursos_plan = :id_cursos_plan";
            $stmt = $this->conn->prepare($query);
            $this->id_cursos_plan = htmlspecialchars(strip_tags($this->id_cursos_plan));
            $stmt->bindParam(':id_cursos_plan', $this->id_cursos_plan);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Lee todos los cursos que están en un plan (la malla).
     */
    public function readCursosEnPlan($id_plan) {
        try {
            // --- ¡AQUÍ ESTABA EL ERROR! (c.id_curso FALTABA) ---
            $query = "SELECT 
                        cp.id_cursos_plan, 
                        cp.ciclo, 
                        c.id_curso, -- <-- CORREGIDO
                        c.nombre_curso,
                        u.nombre as nombre_profesor,
                        u.apellido as apellido_profesor
                      FROM " . $this->table_name . " cp
                      JOIN cursos c ON cp.id_curso = c.id_curso
                      JOIN usuarios u ON c.id_profesor = u.id_usuario
                      WHERE cp.id_plan_estudio = :id_plan
                      ORDER BY cp.ciclo ASC, c.nombre_curso ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_plan', $id_plan);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Lee todos los cursos que NO están en un plan (para el dropdown).
     */
    public function readCursosFueraDePlan($id_plan) {
        try {
            $query = "SELECT c.id_curso, c.nombre_curso
                      FROM cursos c
                      WHERE c.id_curso NOT IN (
                          SELECT id_curso 
                          FROM " . $this->table_name . "
                          WHERE id_plan_estudio = :id_plan
                      )
                      ORDER BY c.nombre_curso ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_plan', $id_plan);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>