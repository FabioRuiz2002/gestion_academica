<?php
class Asistencia {
    private $conn;
    private $table_name = "asistencias";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function checkAsistenciaTomada($id_curso, $fecha) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE id_curso = :id AND fecha = :fecha";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_curso);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }

    public function readAsistenciaPorFecha($id_curso, $fecha) {
        $query = "SELECT a.*, u.nombre, u.apellido 
                  FROM " . $this->table_name . " a
                  JOIN usuarios u ON a.id_estudiante = u.id_usuario
                  WHERE a.id_curso = :id AND a.fecha = :f
                  ORDER BY u.apellido";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_curso);
        $stmt->bindParam(':f', $fecha);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarAsistencia($id_curso, $fecha, $asistencias) {
        try {
            $this->conn->beginTransaction();
            $query = "INSERT INTO " . $this->table_name . " (id_curso, id_estudiante, fecha, estado) VALUES (:c, :e, :f, :s)";
            $stmt = $this->conn->prepare($query);
            foreach ($asistencias as $est_id => $estado) {
                $stmt->bindParam(':c', $id_curso);
                $stmt->bindParam(':e', $est_id);
                $stmt->bindParam(':f', $fecha);
                $stmt->bindParam(':s', $estado);
                $stmt->execute();
            }
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function readHistoricoPorCurso($id_curso) {
        $query = "SELECT a.*, u.nombre, u.apellido 
                  FROM " . $this->table_name . " a
                  JOIN usuarios u ON a.id_estudiante = u.id_usuario
                  WHERE a.id_curso = :id
                  ORDER BY a.fecha DESC, u.apellido";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_curso);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readPorEstudiante($id_estudiante, $id_curso) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id_estudiante = :e AND id_curso = :c
                  ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':e', $id_estudiante);
        $stmt->bindParam(':c', $id_curso);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>