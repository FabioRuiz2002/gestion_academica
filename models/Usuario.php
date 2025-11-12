<?php
/*
 * Archivo: models/Usuario.php
 * Propósito: Modelo para la entidad Usuario.
 * Contiene la lógica de negocio y BD para usuarios (login, registro, etc).
 * Criterios: Criterio 1 (MVC), Criterio 3 (PDO), Criterio 6 (Seguridad - Hashing)
 */

class Usuario {
    
    // Conexión a la BD (inyectada desde el controlador)
    private $conn;
    private $table_name = "usuarios";

    // Propiedades del usuario
    public $id_usuario;
    public $id_rol;
    public $email;
    public $password;
    public $nombre;
    public $apellido;

    /**
     * Constructor que recibe la conexión PDO.
     * @param PDO $db Objeto de conexión PDO.
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Intenta autenticar a un usuario.
     * @return boolean|array Retorna los datos del usuario si es exitoso, false si falla.
     */
    public function login() {
        try {
            // 1. Consulta para buscar el usuario por email
            $query = "SELECT u.id_usuario, u.email, u.password, u.nombre, u.apellido, u.id_rol, r.nombre_rol
                      FROM " . $this->table_name . " u
                      JOIN roles r ON u.id_rol = r.id_rol
                      WHERE u.email = :email AND u.estado = 1
                      LIMIT 0,1";

            // 2. Preparar la consulta (Previene Inyección SQL)
            $stmt = $this->conn->prepare($query);

            // 3. Sanitizar y vincular el email
            $this->email = htmlspecialchars(strip_tags($this->email));
            $stmt->bindParam(':email', $this->email);

            // 4. Ejecutar la consulta
            $stmt->execute();
            
            // 5. Verificar si se encontró el usuario
            if ($stmt->rowCount() == 1) {
                // 6. Obtener el registro
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // 7. Verificar la contraseña
                // Comparamos el password del POST con el HASH de la BD
                if (password_verify($this->password, $row['password'])) {
                    // Password es correcto. Retornamos los datos.
                    return $row;
                }
            }

            // 8. Si el email no existe o la contraseña es incorrecta
            return false;

        } catch (PDOException $e) {
            // Manejo de errores de base de datos
            echo "Error en el modelo de login: " . $e->getMessage();
            return false;
        }
    }
    
    // ... (Aquí irían otras funciones: crear, editar, eliminar usuario, etc.)
}
?>