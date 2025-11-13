<?php
/*
 * Archivo: controllers/PerfilController.php
 * Propósito: Controlador para que el usuario gestione su perfil.
 */

require_once MODEL_PATH . 'Usuario.php';
require_once CONFIG_PATH . 'Database.php';

class PerfilController {

    private $db;
    private $usuario;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        // Seguridad: Si no está logueado, no puede ver el perfil
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?controller=Usuario&action=index');
            exit();
        }
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuario = new Usuario($this->db);
    }

    /**
     * Muestra el formulario de cambio de contraseña.
     */
    public function index() {
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'perfil/index.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    /**
     * Procesa el formulario de cambio de contraseña.
     */
    public function cambiarPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_usuario = $_SESSION['id_usuario'];
            $pass_actual = $_POST['pass_actual'] ?? '';
            $pass_nuevo = $_POST['pass_nuevo'] ?? '';
            $pass_confirmar = $_POST['pass_confirmar'] ?? '';

            // 1. Validar que las contraseñas nuevas coincidan
            if ($pass_nuevo !== $pass_confirmar) {
                $_SESSION['error_message'] = "La nueva contraseña y su confirmación no coinciden.";
                header('Location: index.php?controller=Perfil&action=index');
                exit();
            }

            // 2. Validar que la nueva contraseña no esté vacía
            if (empty($pass_nuevo)) {
                $_SESSION['error_message'] = "La nueva contraseña no puede estar vacía.";
                header('Location: index.php?controller=Perfil&action=index');
                exit();
            }

            // 3. Intentar cambiar la contraseña en el modelo
            $resultado = $this->usuario->cambiarPassword($id_usuario, $pass_actual, $pass_nuevo);

            if ($resultado === true) {
                // ¡Éxito!
                $_SESSION['success_message'] = "¡Contraseña actualizada exitosamente!";
            } else {
                // Error (devuelve el mensaje de error del modelo)
                $_SESSION['error_message'] = $resultado;
            }
            
            header('Location: index.php?controller=Perfil&action=index');
            exit();
        }
    }
}
?>