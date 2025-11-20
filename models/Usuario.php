<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id_usuario;
    public $id_rol;
    public $id_plan_estudio;
    public $nombre;
    public $apellido;
    public $dni;
    public $codigo_estudiante;
    public $email;
    public $password;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login() {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $this->email = htmlspecialchars(strip_tags($this->email));
            $stmt->bindParam(1, $this->email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return false; }
    }
    
    public function readByRol($id_rol) {
        try {
            $query = "";
            if ($id_rol == 3) {
                $query = "SELECT u.*, r.nombre_rol, p.nombre_plan, e.nombre_escuela, f.nombre_facultad
                          FROM " . $this->table_name . " u
                          JOIN roles r ON u.id_rol = r.id_rol
                          LEFT JOIN planes_estudio p ON u.id_plan_estudio = p.id_plan_estudio
                          LEFT JOIN escuelas e ON p.id_escuela = e.id_escuela
                          LEFT JOIN facultades f ON e.id_facultad = f.id_facultad
                          WHERE u.id_rol = 3
                          ORDER BY f.nombre_facultad, e.nombre_escuela, u.apellido, u.nombre";
            } else {
                $query = "SELECT u.*, r.nombre_rol
                          FROM " . $this->table_name . " u
                          JOIN roles r ON u.id_rol = r.id_rol
                          WHERE u.id_rol = :id_rol
                          ORDER BY u.apellido, u.nombre";
            }
            $stmt = $this->conn->prepare($query);
            if ($id_rol != 3) {
                $stmt->bindParam(':id_rol', $id_rol);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) { return []; }
    }
    
    public function readOne() {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_usuario = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id_usuario);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return false; }
    }
    
    public function readRoles() {
        try {
            $query = "SELECT * FROM roles ORDER BY id_rol";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }
    
    public function readProfesores() {
        try {
            $query = "SELECT id_usuario, nombre, apellido FROM " . $this->table_name . "
                      WHERE id_rol = 2 AND estado = 1
                      ORDER BY apellido, nombre";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    public function crear() {
        try {
            $checkQuery = "SELECT id_usuario FROM " . $this->table_name . " WHERE email = :email OR dni = :dni";
            $checkStmt = $this->conn->prepare($checkQuery);
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->dni = htmlspecialchars(strip_tags($this->dni));
            $checkStmt->bindParam(':email', $this->email);
            $checkStmt->bindParam(':dni', $this->dni);
            $checkStmt->execute();
            if ($checkStmt->rowCount() > 0) { return false; }

            $query = "INSERT INTO " . $this->table_name . "
                      (id_rol, id_plan_estudio, nombre, apellido, dni, email, password)
                      VALUES
                      (:id_rol, :id_plan_estudio, :nombre, :apellido, :dni, :email, :password)";
            $stmt = $this->conn->prepare($query);
            
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->apellido = htmlspecialchars(strip_tags($this->apellido));
            $this->password = password_hash(htmlspecialchars(strip_tags($this->password)), PASSWORD_DEFAULT);
            
            $stmt->bindParam(':id_rol', $this->id_rol);
            $stmt->bindParam(':id_plan_estudio', $this->id_plan_estudio);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':apellido', $this->apellido);
            $stmt->bindParam(':dni', $this->dni);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);
            
            if (!$stmt->execute()) { return false; }

            if ($this->id_rol == 3) {
                $nuevoId = $this->conn->lastInsertId();
                $codigo = "E" . date('Y') . "-" . str_pad($nuevoId, 4, '0', STR_PAD_LEFT);
                $updateQuery = "UPDATE " . $this->table_name . " SET codigo_estudiante = :codigo WHERE id_usuario = :id";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':codigo', $codigo);
                $updateStmt->bindParam(':id', $nuevoId);
                $updateStmt->execute();
            }
            return true;
        } catch (PDOException $e) { return false; }
    }

    public function update() {
        try {
            $checkQuery = "SELECT id_usuario FROM " . $this->table_name . " WHERE dni = :dni AND id_usuario != :id_usuario";
            $checkStmt = $this->conn->prepare($checkQuery);
            $this->dni = htmlspecialchars(strip_tags($this->dni));
            $checkStmt->bindParam(':dni', $this->dni);
            $checkStmt->bindParam(':id_usuario', $this->id_usuario);
            $checkStmt->execute();
            if ($checkStmt->rowCount() > 0) { return false; }

            $password_set = !empty($this->password) ? "password = :password," : "";
            $query = "UPDATE " . $this->table_name . " SET
                        id_rol = :id_rol,
                        id_plan_estudio = :id_plan_estudio,
                        nombre = :nombre,
                        apellido = :apellido,
                        dni = :dni,
                        email = :email,
                        " . $password_set . "
                        estado = :estado
                      WHERE id_usuario = :id_usuario";
            
            $stmt = $this->conn->prepare($query);
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->apellido = htmlspecialchars(strip_tags($this->apellido));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->estado = $this->estado ?? 1;
            
            $stmt->bindParam(':id_rol', $this->id_rol);
            $stmt->bindParam(':id_plan_estudio', $this->id_plan_estudio);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':apellido', $this->apellido);
            $stmt->bindParam(':dni', $this->dni);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':estado', $this->estado);
            $stmt->bindParam(':id_usuario', $this->id_usuario);
            
            if (!empty($this->password)) {
                $this->password = password_hash(htmlspecialchars(strip_tags($this->password)), PASSWORD_DEFAULT);
                $stmt->bindParam(':password', $this->password);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }
    
    public function cambiarPassword($id_usuario, $nuevo_password) {
        try {
            $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($query);
            $password_hash = password_hash($nuevo_password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':id_usuario', $id_usuario);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_usuario = ?";
            $stmt = $this->conn->prepare($query);
            $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
            $stmt->bindParam(1, $this->id_usuario);
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
    
    public function countProfesores() {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE id_rol = 2";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        } catch (PDOException $e) { return 0; }
    }
}
?>