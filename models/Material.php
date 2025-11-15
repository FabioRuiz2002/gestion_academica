<?php
/*
 * Archivo: models/Material.php
 * Propósito: Modelo para la gestión de materiales del curso.
 */
class Material {
    
    private $conn;
    private $table_name = "materiales";

    public $id_material;
    public $id_curso;
    public $nombre_archivo;
    public $ruta_archivo;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lee todos los materiales de un curso específico.
     */
    public function readPorCurso($id_curso) {
        try {
            $query = "SELECT * FROM " . $this->table_name . "
                      WHERE id_curso = :id_curso
                      ORDER BY fecha_subida DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Error al leer materiales: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Crea un nuevo registro de material en la BD.
     */
    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                      (id_curso, nombre_archivo, ruta_archivo)
                      VALUES
                      (:id_curso, :nombre_archivo, :ruta_archivo)";
            
            $stmt = $this->conn->prepare($query);

            // Limpiar datos
            $this->id_curso = htmlspecialchars(strip_tags($this->id_curso));
            $this->nombre_archivo = htmlspecialchars(strip_tags($this->nombre_archivo));
            $this->ruta_archivo = htmlspecialchars(strip_tags($this->ruta_archivo));

            $stmt->bindParam(':id_curso', $this->id_curso);
            $stmt->bindParam(':nombre_archivo', $this->nombre_archivo);
            $stmt->bindParam(':ruta_archivo', $this->ruta_archivo);

            if ($stmt->execute()) {
                return true;
            }
            return false;

        } catch (PDOException $e) {
            echo "Error al crear material: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Elimina un material de la BD (y su archivo).
     */
    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_material = :id_material";
            $stmt = $this->conn->prepare($query);
            
            $this->id_material = htmlspecialchars(strip_tags($this->id_material));
            $stmt->bindParam(':id_material', $this->id_material);

            if ($stmt->execute()) {
                return true;
            }
            return false;
            
        } catch (PDOException $e) {
            echo "Error al eliminar material: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Lee un solo material por ID (para saber qué archivo borrar).
     */
    public function readOne($id_material) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_material = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id_material);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>