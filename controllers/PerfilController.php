<?php
require_once MODEL_PATH . 'Usuario.php';
require_once CONFIG_PATH . 'Database.php';

class PerfilController {
    private $db; private $usuario;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['id_usuario'])) { header('Location: index.php'); exit(); }
        $this->db = (new Database())->getConnection();
        $this->usuario = new Usuario($this->db);
    }

    public function index() {
        $this->usuario->id_usuario = $_SESSION['id_usuario'];
        $datosUsuario = $this->usuario->readOne();
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'perfil/mi_perfil.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function cambiarPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_SESSION['id_usuario'];
            $new = $_POST['nuevo_password'];
            $conf = $_POST['confirmar_password'];

            if ($new === $conf) {
                if ($this->usuario->cambiarPassword($id, $new)) {
                    $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Contraseña actualizada.'];
                } else {
                    $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error al actualizar.'];
                }
            } else {
                $_SESSION['mensaje'] = ['tipo' => 'warning', 'texto' => 'No coinciden.'];
            }
        }
        header('Location: index.php?controller=Perfil&action=index'); exit();
    }
}
?>