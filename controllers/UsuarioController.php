<?php
/*
 * Archivo: controllers/UsuarioController.php
 * Propósito: Controlador para el login y logout de usuarios.
 * (Añadido 'id_plan_estudio' a la sesión)
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

    public function index() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        if (isset($_SESSION['id_usuario'])) {
            $this->redirectPorRol($_SESSION['id_rol']);
        }
        
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'usuario/login.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function login() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->usuario->email = $_POST['email'];
            $this->usuario->password = $_POST['password'];

            $usuarioData = $this->usuario->login();

            if ($usuarioData && password_verify($this->usuario->password, $usuarioData['password'])) {
                if ($usuarioData['estado'] == 1) {
                    $_SESSION['id_usuario'] = $usuarioData['id_usuario'];
                    $_SESSION['id_rol'] = $usuarioData['id_rol'];
                    $_SESSION['nombre'] = $usuarioData['nombre'];
                    $_SESSION['apellido'] = $usuarioData['apellido'];
                    
                    // --- ¡AÑADIDO! ---
                    if ($usuarioData['id_rol'] == 3) {
                        $_SESSION['id_plan_estudio'] = $usuarioData['id_plan_estudio'];
                    }
                    // --- FIN AÑADIDO ---
                    
                    $this->redirectPorRol($usuarioData['id_rol']);
                } else {
                    $_SESSION['error_message'] = 'Tu cuenta está inactiva. Contacta al administrador.';
                    header('Location: index.php');
                    exit();
                }
            } else {
                $_SESSION['error_message'] = 'Email o contraseña incorrectos.';
                header('Location: index.php');
                exit();
            }
        }
    }

    private function redirectPorRol($id_rol) {
        switch ($id_rol) {
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
                header('Location: index.php');
                break;
        }
        exit();
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
?>