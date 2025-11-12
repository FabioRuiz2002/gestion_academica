<?php
/*
 * Archivo: controllers/UsuarioController.php
 * Propósito: Controlador para las acciones relacionadas con los Usuarios (Login, Logout).
 * Criterios: Criterio 1 (Arquitectura MVC)
 */

// Incluir el modelo (El controlador es el único que incluye modelos y vistas)
require_once MODEL_PATH . 'Usuario.php';
// Incluir la conexión (El controlador la necesita para pasarla al modelo)
require_once 'config/Database.php';

class UsuarioController {

    private $db;
    private $usuario;

    /**
     * Constructor: Inicializa la conexión a la BD y el modelo Usuario.
     */
    public function __construct() {
        // Crear una instancia de la base de datos
        $database = new Database();
        $this->db = $database->getConnection();
        
        // Crear una instancia del modelo Usuario, pasándole la conexión
        $this->usuario = new Usuario($this->db);
    }

    /**
     * Acción 'index': Muestra la página de login.
     * URL: index.php?controller=Usuario&action=index
     */
    public function index() {
        // Si el usuario ya está logueado, lo redirigimos a su panel
        if (isset($_SESSION['id_usuario'])) {
            $this->redirectToDashboard($_SESSION['id_rol']);
            return;
        }

        // Cargar la vista del login (que está dentro del layout)
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'login.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    /**
     * Acción 'autenticar': Procesa el formulario de login.
     * URL: index.php?controller=Usuario&action=autenticar (recibe POST)
     */
    public function autenticar() {
        // 1. Verificar que se envíen datos por POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 2. Asignar datos del POST al modelo
            $this->usuario->email = $_POST['email'];
            $this->usuario->password = $_POST['password'];

            // 3. Intentar el login llamando al método del modelo
            $datosUsuario = $this->usuario->login();

            if ($datosUsuario) {
                // 4. Éxito: Guardar datos en la SESIÓN
                // Criterio 6: Sesiones Seguras
                session_regenerate_id(true); // Previene fijación de sesión
                $_SESSION['id_usuario'] = $datosUsuario['id_usuario'];
                $_SESSION['nombre'] = $datosUsuario['nombre'];
                $_SESSION['apellido'] = $datosUsuario['apellido'];
                $_SESSION['id_rol'] = $datosUsuario['id_rol'];
                $_SESSION['nombre_rol'] = $datosUsuario['nombre_rol'];

                // 5. Redirigir al panel correspondiente
                $this->redirectToDashboard($datosUsuario['id_rol']);
            } else {
                // 6. Falla: Redirigir al login con un mensaje de error
                header('Location: index.php?controller=Usuario&action=index&error=1');
                exit();
            }

        } else {
            // Si no es POST, redirigir al inicio
            header('Location: index.php');
            exit();
        }
    }

    /**
     * Acción 'logout': Cierra la sesión del usuario.
     * URL: index.php?controller=Usuario&action=logout
     */
    public function logout() {
        session_unset();    // Limpia las variables de sesión
        session_destroy();  // Destruye la sesión
        
        // Redirigir a la página de login
        header('Location: index.php?controller=Usuario&action=index');
        exit();
    }

    /**
     * Helper privado para redirigir según el rol.
     */
    private function redirectToDashboard($id_rol) {
        switch ($id_rol) {
            case 1: // Administrador
                header('Location: index.php?controller=Admin&action=index');
                break;
            case 2: // Profesor
                header('Location: index.php?controller=Profesor&action=index');
                break;
            case 3: // Estudiante
                header('Location: index.php?controller=Estudiante&action=index');
                break;
            case 4: // Padre
                header('Location: index.php?controller=Padre&action=index');
                break;
            default:
                header('Location: index.php');
        }
        exit(); // Asegurarse de que el script se detiene después de redirigir
    }
}
?>