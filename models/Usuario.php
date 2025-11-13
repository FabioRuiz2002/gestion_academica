<?php
/*
 * Archivo: models/Usuario.php
 * Propósito: Modelo para la entidad Usuario.
 * (Añadida la función 'cambiarPassword')
 */
class Usuario {
    
    private $conn;
    private $table_name = "usuarios";

    public $id_usuario;
    public $id_rol;
    public $email;
    public $password;
    public $nombre;
    public $apellido;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login() {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error en login: " . $e->getMessage();
            return false;
        }
    }
    
    public function readAll() {
        $query = "SELECT u.id_usuario, u.email, u.nombre, u.apellido, u.estado, r.nombre_rol 
                  FROM " . $this->table_name . " u
                  JOIN roles r ON u.id_rol = r.id_rol
                  ORDER BY u.apellido ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readRoles() {
        $query = "SELECT * FROM roles";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function readProfesores() {
        $query = "SELECT id_usuario, nombre, apellido FROM " . $this->table_name . " WHERE id_rol = 2 ORDER BY apellido ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_usuario = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . " (id_rol, email, password, nombre, apellido) VALUES (:id_rol, :email, :password, :nombre, :apellido)";
            $stmt = $this->conn->prepare($query);
            
            $this->id_rol = htmlspecialchars(strip_tags($this->id_rol));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = password_hash(htmlspecialchars(strip_tags($this->password)), PASSWORD_DEFAULT);
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->apellido = htmlspecialchars(strip_tags($this->apellido));
            
            $stmt->bindParam(':id_rol', $this->id_rol);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':apellido', $this->apellido);

            if ($stmt->execute()) { return true; }
            return false;
        } catch (PDOException $e) {
            return false; 
        }
    }

    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . " SET
                        id_rol = :id_rol,
                        email = :email,
                        nombre = :nombre,
                        apellido = :apellido";
            
            if ($this->password) {
                $query .= ", password = :password";
            }
            
            $query .= " WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($query);
            
            $this->id_rol = htmlspecialchars(strip_tags($this->id_rol));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->apellido = htmlspecialchars(strip_tags($this->apellido));
            $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
            
            $stmt->bindParam(':id_rol', $this->id_rol);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':apellido', $this->apellido);
            $stmt->bindParam(':id_usuario', $this->id_usuario);
            
            if ($this->password) {
                $this->password = password_hash(htmlspecialchars(strip_tags($this->password)), PASSWORD_DEFAULT);
                $stmt->bindParam(':password', $this->password);
            }
            
            if ($stmt->execute()) { return true; }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($query);
            $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
            $stmt->bindParam(':id_usuario', $this->id_usuario);
            if ($stmt->execute()) { return true; }
            return false;
        } catch (PDOException $e) {
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

    public function countProfesores() {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE id_rol = 2";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * NUEVA FUNCIÓN PARA CAMBIAR CONTRASEÑA
     * Verifica la contraseña actual y actualiza a la nueva.
     */
    public function cambiarPassword($id_usuario, $pass_actual, $pass_nuevo) {
        try {
            // 1. Obtener el hash actual de la BD
            $query = "SELECT password FROM " . $this->table_name . " WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return "Usuario no encontrado.";
            }

            // 2. Verificar si la contraseña actual coincide
            if (password_verify($pass_actual, $row['password'])) {
                // 3. Si coincide, actualizar con el nuevo hash
                $nuevo_hash = password_hash($pass_nuevo, PASSWORD_DEFAULT);
                $update_query = "UPDATE " . $this->table_name . " SET password = :password WHERE id_usuario = :id_usuario";
                $update_stmt = $this->conn->prepare($update_query);
                $update_stmt->bindParam(':password', $nuevo_hash);
                $update_stmt->bindParam(':id_usuario', $id_usuario);
                
                if ($update_stmt->execute()) {
                    return true; // Éxito
                } else {
                    return "Error al actualizar la contraseña.";
                }
            } else {
                return "La contraseña actual es incorrecta.";
            }
        } catch (PDOException $e) {
            return "Error de base de datos: " . $e->getMessage();
        }
    }
}
?>