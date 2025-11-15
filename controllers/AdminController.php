<?php
/*
 * Archivo: controllers/AdminController.php
 * (CORREGIDOS todos los errores de sintaxis 'this.')
 */

require_once MODEL_PATH . 'Usuario.php';
require_once MODEL_PATH . 'Curso.php'; 
require_once MODEL_PATH . 'Matricula.php';
require_once MODEL_PATH . 'PlanEstudio.php';
require_once CONFIG_PATH . 'Database.php';

class AdminController {

    private $db;
    private $usuario;
    private $curso; 
    private $matricula;
    private $planEstudio;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
            header('Location: index.php?controller=Usuario&action=index');
            exit();
        }
        $database = new Database();
        $this->db = $database->getConnection();
        
        $this->usuario = new Usuario($this->db);
        $this->curso = new Curso($this->db); 
        $this->matricula = new Matricula($this->db);
        $this->planEstudio = new PlanEstudio($this->db);
    }

    public function index() {
        $estadisticas = [
            'total_usuarios' => $this->usuario->countTotal(),
            'total_cursos' => $this->curso->countTotal(),
            'total_profesores' => $this->usuario->countProfesores(),
            'total_estudiantes' => $this->matricula->countEstudiantesUnicos()
        ];
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'admin/dashboard.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    // --- ACCIONES DE USUARIOS ---
    
    public function gestionarUsuarios() { 
        $listaUsuarios = $this->usuario->readAll(); 
        $listaRoles = $this->usuario->readRoles();
        $listaPlanes = $this->planEstudio->readForDropdown();
        
        require_once VIEW_PATH . 'layouts/header.php'; 
        require_once VIEW_PATH . 'admin/gestionar_usuarios.php'; 
        require_once VIEW_PATH . 'layouts/footer.php'; 
    }
    
    public function crearUsuario() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if ( !empty($_POST['nombre']) && !empty($_POST['apellido']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['id_rol']) ) { 
                $this->usuario->nombre = $_POST['nombre']; 
                $this->usuario->apellido = $_POST['apellido']; 
                $this->usuario->email = $_POST['email']; 
                $this->usuario->password = $_POST['password']; 
                $this->usuario->id_rol = $_POST['id_rol'];
                $this->usuario->id_plan_estudio = ($_POST['id_rol'] == 3) ? $_POST['id_plan_estudio'] : null;

                if (!$this->usuario->crear()) { 
                    $_SESSION['error_message'] = "Error al crear el usuario. Es posible que el email ya exista."; 
                } 
            } else { 
                $_SESSION['error_message'] = "Error: Todos los campos son obligatorios."; 
            } 
        } 
        header('Location: index.php?controller=Admin&action=gestionarUsuarios'); 
        exit(); 
    }
    
    public function getUsuario() { 
        if (isset($_GET['id'])) { 
            $this->usuario->id_usuario = $_GET['id']; 
            $datosUsuario = $this->usuario->readOne(); 
            if ($datosUsuario) { 
                header('Content-Type: application/json'); 
                echo json_encode($datosUsuario); 
            } else { 
                header('HTTP/1.0 404 Not Found'); 
                echo json_encode(['error' => 'Usuario no encontrado']); 
            } 
        } else { 
            header('HTTP/1.0 400 Bad Request'); 
            echo json_encode(['error' => 'ID no proporcionado']); 
        } 
        exit(); 
    }
    
    public function editarUsuario() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if ( !empty($_POST['edit_id_usuario']) && !empty($_POST['edit_nombre']) && !empty($_POST['edit_apellido']) && !empty($_POST['edit_email']) && !empty($_POST['edit_id_rol']) ) { 
                $this->usuario->id_usuario = $_POST['edit_id_usuario']; 
                $this->usuario->nombre = $_POST['edit_nombre']; 
                $this->usuario->apellido = $_POST['edit_apellido']; 
                $this->usuario->email = $_POST['edit_email'];
                $this->usuario->id_rol = $_POST['edit_id_rol'];
                $this->usuario->id_plan_estudio = ($_POST['edit_id_rol'] == 3) ? $_POST['edit_id_plan_estudio'] : null;

                if (!empty($_POST['edit_password'])) { 
                    $this->usuario->password = $_POST['edit_password']; 
                } else { 
                    $this->usuario->password = null; 
                } 
                if (!$this->usuario->update()) { 
                    $_SESSION['error_message'] = "Error al actualizar el usuario. Es posible que el email ya exista."; 
                } 
            } else { 
                $_SESSION['error_message'] = "Error: Todos los campos (excepto contraseña) son obligatorios para editar."; 
            } 
        } 
        header('Location: index.php?controller=Admin&action=gestionarUsuarios'); 
        exit(); 
    }
    
    public function eliminarUsuario() { 
        header('Content-Type: application/json'); 
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_usuario'])) { 
            $this->usuario->id_usuario = $_POST['id_usuario']; 
            if ($this->usuario->delete()) { 
                echo json_encode(['success' => true]); 
            } else { 
                echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario desde el controlador.']); 
            } 
        } else { 
            echo json_encode(['success' => false, 'message' => 'Petición inválida.']); 
        } 
        exit(); 
    }

    // --- ACCIONES DE CURSOS (Llamadas desde AcademicoController) ---
    
    public function crearCurso() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if ( !empty($_POST['nombre_curso']) && !empty($_POST['id_profesor']) && !empty($_POST['anio_academico']) ) { 
                $this->curso->nombre_curso = $_POST['nombre_curso']; 
                $this->curso->descripcion = $_POST['descripcion']; 
                $this->curso->horario = $_POST['horario']; 
                $this->curso->id_profesor = $_POST['id_profesor']; 
                $this->curso->anio_academico = $_POST['anio_academico']; 
                if (!$this->curso->crear()) { 
                    $_SESSION['error_message_academico'] = "Error al crear el curso.";
                } 
            } else { 
                $_SESSION['error_message_academico'] = "Error: Nombre, Profesor y Año son obligatorios.";
            } 
        } 
        header('Location: index.php?controller=Academico&action=index'); 
        exit(); 
    }
    
    public function getCurso() { 
        if (isset($_GET['id'])) { 
            $this->curso->id_curso = $_GET['id']; 
            $datosCurso = $this->curso->readOne($_GET['id']); // <-- CORREGIDO
            if ($datosCurso) { 
                header('Content-Type: application/json'); 
                echo json_encode($datosCurso); 
            } else { 
                header('HTTP/1.0 404 Not Found'); 
                echo json_encode(['error' => 'Curso no encontrado']); 
            } 
        } else { 
            header('HTTP/1.0 400 Bad Request'); 
            echo json_encode(['error' => 'ID de curso no proporcionado']); 
        } 
        exit(); 
    }
    
    public function editarCurso() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if ( !empty($_POST['edit_id_curso']) && !empty($_POST['edit_nombre_curso']) && !empty($_POST['edit_id_profesor']) && !empty($_POST['edit_anio_academico']) ) { 
                $this->curso->id_curso = $_POST['edit_id_curso']; 
                $this->curso->nombre_curso = $_POST['edit_nombre_curso']; 
                $this->curso->descripcion = $_POST['edit_descripcion']; 
                $this->curso->horario = $_POST['edit_horario']; 
                $this->curso->id_profesor = $_POST['edit_id_profesor']; // <-- CORREGIDO
                $this->curso->anio_academico = $_POST['edit_anio_academico']; 
                if (!$this->curso->update()) { 
                    $_SESSION['error_message_academico'] = "Error al actualizar el curso.";
                } 
            } else { 
                $_SESSION['error_message_academico'] = "Error: Nombre, Profesor y Año son obligatorios para editar.";
            } 
        } 
        header('Location: index.php?controller=Academico&action=index'); 
        exit(); 
    }
    
    public function eliminarCurso() { 
        header('Content-Type: application/json'); 
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_curso'])) { 
            $this->curso->id_curso = $_POST['id_curso']; 
            if ($this->curso->delete()) { 
                echo json_encode(['success' => true]); 
            } else { 
                echo json_encode(['success' => false, 'message' => 'Error al eliminar el curso.']); 
            } 
        } else { 
            echo json_encode(['success' => false, 'message' => 'Petición inválida.']); 
        } 
        exit(); 
    }
}
?>