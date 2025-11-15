<?php
/*
 * Archivo: models/Prerequisito.php
 * Propósito: Modelo para la tabla 'prerequisitos'.
 * (Añadida la función 'readPrerequisitosPorPlan')
 */
class Prerequisito {
    
    private $conn;
    private $table_name = "prerequisitos";

    public $id_prerequisito;
    public $id_curso_principal;
    public $id_curso_requisito;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lee todos los requisitos para un curso principal.
     */
    public function readByCurso($id_curso) {
        try {
            $query = "SELECT 
                        pr.id_prerequisito,
                        c.nombre_curso AS nombre_curso_requisito
                      FROM 
                        " . $this->table_name . " pr
                      JOIN 
                        cursos c ON pr.id_curso_requisito = c.id_curso
                      WHERE 
                        pr.id_curso_principal = :id_curso";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    /**
     * Lee los cursos que pueden ser un prerrequisito.
     */
    public function readCursosDisponiblesParaReq($id_plan, $ciclo_actual, $id_curso_actual) {
        try {
            $query = "SELECT c.id_curso, c.nombre_curso, cp.ciclo
                      FROM cursos_plan cp
                      JOIN cursos c ON cp.id_curso = c.id_curso
                      WHERE cp.id_plan_estudio = :id_plan
                        AND cp.ciclo < :ciclo_actual
                        AND cp.id_curso != :id_curso_actual
                        AND cp.id_curso NOT IN (
                            SELECT id_curso_requisito 
                            FROM " . $this->table_name . "
                            WHERE id_curso_principal = :id_curso_actual
                        )
                      ORDER BY cp.ciclo, c.nombre_curso";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_plan', $id_plan);
            $stmt->bindParam(':ciclo_actual', $ciclo_actual);
            $stmt->bindParam(':id_curso_actual', $id_curso_actual);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    /**
     * Añade un nuevo prerrequisito.
     */
    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                      (id_curso_principal, id_curso_requisito)
                      VALUES
                      (:id_curso_principal, :id_curso_requisito)";
            
            $stmt = $this->conn->prepare($query);
            $this->id_curso_principal = htmlspecialchars(strip_tags($this->id_curso_principal));
            $this->id_curso_requisito = htmlspecialchars(strip_tags($this->id_curso_requisito));
            $stmt->bindParam(':id_curso_principal', $this->id_curso_principal);
            $stmt->bindParam(':id_curso_requisito', $this->id_curso_requisito);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    /**
     * Elimina un prerrequisito.
     */
    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_prerequisito = :id";
            $stmt = $this->conn->prepare($query);
            $this->id_prerequisito = htmlspecialchars(strip_tags($this->id_prerequisito));
            $stmt->bindParam(':id', $this->id_prerequisito);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }
    
    /**
     * NUEVA FUNCIÓN
     * Lee TODAS las reglas de prerrequisitos para un plan completo.
     * Devuelve un array agrupado por el id_curso_principal.
     */
    public function readPrerequisitosPorPlan($id_plan) {
        try {
            $query = "SELECT 
                        pr.id_curso_principal,
                        pr.id_curso_requisito,
                        c_req.nombre_curso AS nombre_curso_requisito
                    FROM 
                        " . $this->table_name . " pr
                    JOIN 
                        cursos_plan cp ON pr.id_curso_principal = cp.id_curso
                    JOIN 
                        cursos c_req ON pr.id_curso_requisito = c_req.id_curso
                    WHERE 
                        cp.id_plan_estudio = :id_plan";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_plan', $id_plan);
            $stmt->execute();
            
            $reglas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reglas[$row['id_curso_principal']][] = $row;
            }
            return $reglas;

        } catch (PDOException $e) {
            return [];
        }
    }
}
?>