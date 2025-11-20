<?php
require_once MODEL_PATH . 'Usuario.php';
require_once CONFIG_PATH . 'Database.php';

class UsuarioController {
    private $db;
    private $usuario;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->usuario = new Usuario($this->db);
    }

    public function index() {
        // CAMBIO: Ahora apunta a la carpeta 'usuario'
        require_once VIEW_PATH . 'usuario/login.php'; 
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->usuario->email = $_POST['email'];
            $datos = $this->usuario->login();
            
            if ($datos && password_verify($_POST['password'], $datos['password'])) {
                if ($datos['estado'] == 0) {
                    // Usamos SESSION como en tu vista
                    $_SESSION['error_message'] = "Usuario inactivo."; 
                    require_once VIEW_PATH . 'usuario/login.php'; 
                    return;
                }
                
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['id_usuario'] = $datos['id_usuario'];
                $_SESSION['id_rol'] = $datos['id_rol'];
                $_SESSION['nombre'] = $datos['nombre'];
                $_SESSION['apellido'] = $datos['apellido'];
                $_SESSION['id_plan_estudio'] = $datos['id_plan_estudio'];

                if ($datos['id_rol'] == 1) header('Location: index.php?controller=Admin&action=index');
                elseif ($datos['id_rol'] == 2) header('Location: index.php?controller=Profesor&action=index');
                elseif ($datos['id_rol'] == 3) header('Location: index.php?controller=Estudiante&action=index');
                else header('Location: index.php');
                exit();
            } else {
                // Usamos SESSION como en tu vista
                $_SESSION['error_message'] = "Credenciales incorrectas."; 
                require_once VIEW_PATH . 'usuario/login.php';
            }
        }
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
?>