<?php
/*
 * Archivo: models/Entrega.php
 * Propósito: Modelo para la gestión de Entregas de tareas.
 * (Añadida la función 'getPromedioDeTareas')
 */
class Entrega {
    
    private $conn;
    private $table_name = "entregas";

    // ... (propiedades) ...
    public $id_entrega;
    public $id_tarea;
    public $id_estudiante;
    public $nombre_archivo;
    public $ruta_archivo;
    public $calificacion;
    public $comentario_profesor;


    public function __construct($db) {
        $this->conn = $db;
    }

    public function readPorTarea($id_tarea) {
        // ... (código existente) ...
        try {
            $query = "SELECT 
                        e.*, 
                        u.nombre, 
                        u.apellido
                      FROM 
                        " . $this->table_name . " e
                      JOIN 
                        usuarios u ON e.id_estudiante = u.id_usuario
                      WHERE 
                        e.id_tarea = :id_tarea
                      ORDER BY 
                        u.apellido ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_tarea', $id_tarea);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    public function readPorEstudianteYCurso($id_estudiante, $id_curso) {
        // ... (código existente) ...
        try {
            $query = "SELECT e.*, t.titulo
                      FROM " . $this->table_name . " e
                      JOIN tareas t ON e.id_tarea = t.id_tarea
                      WHERE e.id_estudiante = :id_estudiante AND t.id_curso = :id_curso";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_estudiante', $id_estudiante);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            $entregas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $entregas[$row['id_tarea']] = $row;
            }
            return $entregas;
        } catch (PDOException $e) { return []; }
    }

    public function crear() {
        // ... (código existente) ...
        try {
            $checkQuery = "SELECT id_entrega FROM " . $this->table_name . " WHERE id_tarea = :id_tarea AND id_estudiante = :id_estudiante";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':id_tarea', $this->id_tarea);
            $checkStmt->bindParam(':id_estudiante', $this->id_estudiante);
            $checkStmt->execute();
            if ($checkStmt->rowCount() > 0) { return false; }
            $query = "INSERT INTO " . $this->table_name . "
                      (id_tarea, id_estudiante, nombre_archivo, ruta_archivo)
                      VALUES
                      (:id_tarea, :id_estudiante, :nombre_archivo, :ruta_archivo)";
            $stmt = $this->conn->prepare($query);
            $this->id_tarea = htmlspecialchars(strip_tags($this->id_tarea));
            $this->id_estudiante = htmlspecialchars(strip_tags($this->id_estudiante));
            $this->nombre_archivo = htmlspecialchars(strip_tags($this->nombre_archivo));
            $this->ruta_archivo = htmlspecialchars(strip_tags($this->ruta_archivo));
            $stmt->bindParam(':id_tarea', $this->id_tarea);
            $stmt->bindParam(':id_estudiante', $this->id_estudiante);
            $stmt->bindParam(':nombre_archivo', $this->nombre_archivo);
            $stmt->bindParam(':ruta_archivo', $this->ruta_archivo);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }
    
    public function calificar() {
        // ... (código existente) ...
        try {
            $query = "UPDATE " . $this->table_name . " SET
                        calificacion = :calificacion,
                        comentario_profesor = :comentario
                      WHERE 
                        id_entrega = :id_entrega";
            $stmt = $this->conn->prepare($query);
            $this->id_entrega = htmlspecialchars(strip_tags($this->id_entrega));
            $this->calificacion = htmlspecialchars(strip_tags($this->calificacion));
            $this->comentario_profesor = htmlspecialchars(strip_tags($this->comentario_profesor));
            $stmt->bindParam(':calificacion', $this->calificacion);
            $stmt->bindParam(':comentario', $this->comentario_profesor);
            $stmt->bindParam(':id_entrega', $this->id_entrega);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    /**
     * NUEVA FUNCIÓN
     * Calcula el promedio de las tareas calificadas de un estudiante para un curso.
     */
    public function getPromedioDeTareas($id_estudiante, $id_curso) {
        try {
            $query = "SELECT AVG(e.calificacion) as promedio
                      FROM " . $this->table_name . " e
                      JOIN tareas t ON e.id_tarea = t.id_tarea
                      WHERE e.id_estudiante = :id_estudiante 
                        AND t.id_curso = :id_curso 
                        AND e.calificacion IS NOT NULL"; // Solo tareas calificadas

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_estudiante', $id_estudiante);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Si no hay tareas calificadas, devuelve 0
            return $row['promedio'] ?? 0; 

        } catch (PDOException $e) {
            return 0;
        }
    }
    /**
     * NUEVA FUNCIÓN
     * Busca las 5 entregas más recientes sin calificar para un profesor.
     */
    public function readEntregasRecientesPorProfesor($id_profesor) {
        try {
            $query = "SELECT 
                        e.id_entrega, e.fecha_entrega,
                        t.id_tarea, t.titulo AS nombre_tarea,
                        c.nombre_curso,
                        u.nombre, u.apellido
                      FROM 
                        entregas e
                      JOIN 
                        tareas t ON e.id_tarea = t.id_tarea
                      JOIN 
                        cursos c ON t.id_curso = c.id_curso
                      JOIN 
                        usuarios u ON e.id_estudiante = u.id_usuario
                      WHERE 
                        c.id_profesor = :id_profesor
                        AND e.calificacion IS NULL      -- Entregas SIN calificar
                      ORDER BY 
                        e.fecha_entrega DESC
                      LIMIT 5";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_profesor', $id_profesor);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>