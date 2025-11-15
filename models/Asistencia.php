<?php
/*
 * Archivo: models/Asistencia.php
 * Propósito: Modelo para la gestión de la Asistencia de estudiantes.
 * (Añadida la función 'readPorEstudianteYCurso')
 */
class Asistencia {
    
    private $conn;
    private $table_name = "asistencias";

    // ... (propiedades: id_asistencia, id_curso, etc.) ...
    public $id_asistencia;
    public $id_curso;
    public $id_estudiante;
    public $fecha;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ... (funciones del profesor: checkAsistenciaTomada, guardarAsistencia, etc.) ...
    public function checkAsistenciaTomada($id_curso, $fecha) {
        // ... (código existente) ...
        $query = "SELECT COUNT(id_asistencia) as total 
                  FROM " . $this->table_name . "
                  WHERE id_curso = :id_curso AND fecha = :fecha";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_curso', $id_curso);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] > 0;
    }

    public function guardarAsistencia($id_curso, $fecha, $asistencias) {
        // ... (código existente) ...
        $this->conn->beginTransaction();
        try {
            $query = "INSERT INTO " . $this->table_name . " (id_curso, id_estudiante, fecha, estado) 
                      VALUES (:id_curso, :id_estudiante, :fecha, :estado)";
            $stmt = $this->conn->prepare($query);
            foreach ($asistencias as $id_estudiante => $estado) {
                $id_estudiante_clean = htmlspecialchars(strip_tags($id_estudiante));
                $estado_clean = htmlspecialchars(strip_tags($estado));
                $stmt->bindParam(':id_curso', $id_curso);
                $stmt->bindParam(':id_estudiante', $id_estudiante_clean);
                $stmt->bindParam(':fecha', $fecha);
                $stmt->bindParam(':estado', $estado_clean);
                if (!$stmt->execute()) {
                    throw new Exception("Error al insertar asistencia.");
                }
            }
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    
    public function readAsistenciaPorFecha($id_curso, $fecha) {
        // ... (código existente) ...
        $query = "SELECT 
                    a.estado, 
                    u.nombre, 
                    u.apellido
                  FROM 
                    " . $this->table_name . " a
                  JOIN 
                    usuarios u ON a.id_estudiante = u.id_usuario
                  WHERE 
                    a.id_curso = :id_curso AND a.fecha = :fecha
                  ORDER BY 
                    u.apellido ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_curso', $id_curso);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function readHistoricoPorCurso($id_curso) {
        // ... (código existente) ...
        $query = "SELECT 
                    a.fecha, 
                    u.nombre, 
                    u.apellido, 
                    a.estado
                  FROM 
                    " . $this->table_name . " a
                  JOIN 
                    usuarios u ON a.id_estudiante = u.id_usuario
                  WHERE 
                    a.id_curso = :id_curso
                  ORDER BY 
                    a.fecha DESC, u.apellido ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_curso', $id_curso);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene TODAS las asistencias de un estudiante (para la vista general).
     */
    public function readPorEstudiante($id_estudiante) {
        try {
            $query = "SELECT 
                        c.nombre_curso,
                        a.fecha,
                        a.estado
                      FROM 
                        " . $this->table_name . " a
                      JOIN 
                        cursos c ON a.id_curso = c.id_curso
                      WHERE 
                        a.id_estudiante = :id_estudiante
                      ORDER BY 
                        c.nombre_curso ASC, a.fecha DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_estudiante', $id_estudiante);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al leer asistencias del estudiante: " . $e->getMessage();
            return [];
        }
    }
    
    /**
     * NUEVA FUNCIÓN
     * Obtiene la asistencia de UN estudiante para UN curso.
     */
    public function readPorEstudianteYCurso($id_estudiante, $id_curso) {
        try {
            $query = "SELECT 
                        a.fecha,
                        a.estado
                      FROM 
                        " . $this->table_name . " a
                      WHERE 
                        a.id_estudiante = :id_estudiante AND a.id_curso = :id_curso
                      ORDER BY 
                        a.fecha DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_estudiante', $id_estudiante);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al leer asistencias del curso: " . $e->getMessage();
            return [];
        }
    }
}
?>