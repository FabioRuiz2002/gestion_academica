<?php
/*
 * Archivo: controllers/UsuarioController.php
 * Propósito: Controlador para el Login y Logout de usuarios.
 * (Corregido 'this->' en la función login)
 */

require_once MODEL_PATH . 'Usuario.php';
require_once CONFIG_PATH . 'Database.php';

class UsuarioController {

    private $db;
    private $usuario;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuario = new Usuario($this->db);
    }

    // Muestra la página de login
    public function index() {
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'usuario/login.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    // Procesa el formulario de login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // --- ¡CORREGIDO! ---
            $this->usuario->email = $_POST['email'];
            $this->usuario->password = $_POST['password'];

            $user = $this->usuario->login();

            if ($user && password_verify($this->usuario->password, $user['password'])) {
                // Inicio de sesión exitoso
                $_SESSION['id_usuario'] = $user['id_usuario'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['apellido'] = $user['apellido'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['id_rol'] = $user['id_rol'];

                // Redirigir según el rol
                switch ($user['id_rol']) {
                    case 1: // Admin
                        header('Location: index.php?controller=Admin&action=index');
                        break;
                    case 2: // Profesor
                        header('Location: index.php?controller=Profesor&action=index');
                        break;
                    case 3: // Estudiante
                        header('Location: index.php?controller=Estudiante&action=index');
                        break;
                    default:
                        header('Location: index.php?controller=Usuario&action=index');
                        break;
                }
                exit();
            } else {
                $_SESSION['error_message'] = "Email o contraseña incorrectos.";
                header('Location: index.php?controller=Usuario&action=index');
                exit();
            }
        }
    }

    // Cierra la sesión
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php?controller=Usuario&action=index');
        exit();
    }
}
?>