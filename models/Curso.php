<?php
/*
 * Archivo: models/Curso.php
 * (AÑADIDA: 'getHorariosPorProfesor' y 'updateProfesor')
 * (CORREGIDOS: todos los errores 'this.')
 */
class Curso {
    
    private $conn;
    private $table_name = "cursos";

    public $id_curso;
    public $id_profesor;
    public $nombre_curso;
    public $descripcion;
    public $horario;
    public $anio_academico;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function updateProfesor($id_curso, $id_profesor) {
        try {
            $query = "UPDATE " . $this->table_name . " SET id_profesor = :id_profesor WHERE id_curso = :id_curso";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_profesor', $id_profesor);
            $stmt->bindParam(':id_curso', $id_curso);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    /**
     * NUEVA FUNCIÓN
     * Obtiene todos los horarios de un profesor, EXCLUYENDO un curso (para editar)
     */
    public function getHorariosPorProfesor($id_profesor, $excluir_id_curso = 0) {
        try {
            $query = "SELECT horario FROM " . $this->table_name . "
                      WHERE id_profesor = :id_profesor 
                      AND id_curso != :excluir_id_curso";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_profesor', $id_profesor);
            $stmt->bindParam(':excluir_id_curso', $excluir_id_curso);
            $stmt->execute();
            
            $horarios = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $horarios[] = $row['horario'];
            }
            return $horarios;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function readCursosAgrupadosPorProfesor($id_profesor) {
        try {
            $query = "SELECT 
                        f.nombre_facultad,
                        esc.nombre_escuela,
                        p.nombre_plan,
                        cp.ciclo,
                        c.id_curso, c.nombre_curso, c.horario
                    FROM 
                        cursos c
                    LEFT JOIN 
                        cursos_plan cp ON c.id_curso = cp.id_curso
                    LEFT JOIN 
                        planes_estudio p ON cp.id_plan_estudio = p.id_plan_estudio
                    LEFT JOIN 
                        escuelas esc ON p.id_escuela = esc.id_escuela
                    LEFT JOIN 
                        facultades f ON esc.id_facultad = f.id_facultad
                    WHERE 
                        c.id_profesor = :id_profesor
                    ORDER BY 
                        f.nombre_facultad, esc.nombre_escuela, p.nombre_plan, cp.ciclo, c.nombre_curso";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_profesor', $id_profesor);
            $stmt->execute();
            $cursosAgrupados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $cursosAgrupados
                    [$row['nombre_facultad'] ?? 'Cursos sin Asignar a Facultad']
                    [$row['nombre_escuela'] ?? 'Cursos sin Asignar a Escuela']
                    [$row['nombre_plan'] ?? 'Cursos sin Asignar a Plan']
                    [$row['ciclo'] ?? 'N/A'][] = $row;
            }
            return $cursosAgrupados;
        } catch (PDOException $e) { return []; }
    }
    
    public function readAll() {
        try {
            $query = "SELECT c.*, u.nombre AS nombre_profesor, u.apellido AS apellido_profesor 
                      FROM " . $this->table_name . " c
                      LEFT JOIN usuarios u ON c.id_profesor = u.id_usuario
                      ORDER BY c.nombre_curso ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) { return false; }
    }

    public function readOne($id_curso) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_curso = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id_curso);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return false; }
    }

    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . " (nombre_curso, descripcion, horario, id_profesor, anio_academico) 
                      VALUES (:nombre, :descripcion, :horario, :id_profesor, :anio)";
            $stmt = $this->conn->prepare($query);
            $this->nombre_curso = htmlspecialchars(strip_tags($this->nombre_curso));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            $this->horario = htmlspecialchars(strip_tags($this->horario));
            $this->id_profesor = htmlspecialchars(strip_tags($this->id_profesor));
            $this->anio_academico = htmlspecialchars(strip_tags($this->anio_academico));
            $stmt->bindParam(':nombre', $this->nombre_curso);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':horario', $this->horario);
            $stmt->bindParam(':id_profesor', $this->id_profesor);
            $stmt->bindParam(':anio', $this->anio_academico);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . " SET
                        nombre_curso = :nombre,
                        descripcion = :descripcion,
                        horario = :horario,
                        id_profesor = :id_profesor,
                        anio_academico = :anio
                      WHERE id_curso = :id_curso";
            $stmt = $this->conn->prepare($query);
            $this->nombre_curso = htmlspecialchars(strip_tags($this->nombre_curso));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            $this->horario = htmlspecialchars(strip_tags($this->horario));
            $this->id_profesor = htmlspecialchars(strip_tags($this->id_profesor));
            $this->anio_academico = htmlspecialchars(strip_tags($this->anio_academico));
            $this->id_curso = htmlspecialchars(strip_tags($this->id_curso));
            $stmt->bindParam(':nombre', $this->nombre_curso);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':horario', $this->horario);
            $stmt->bindParam(':id_profesor', $this->id_profesor);
            $stmt->bindParam(':anio', $this->anio_academico);
            $stmt->bindParam(':id_curso', $this->id_curso);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_curso = :id_curso";
            $stmt = $this->conn->prepare($query);
            $this->id_curso = htmlspecialchars(strip_tags($this->id_curso));
            $stmt->bindParam(':id_curso', $this->id_curso);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function countTotal() {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        } catch (PDOException $e) { return 0; }
    }
}
?>