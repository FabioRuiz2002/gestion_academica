<?php
/*
 * Archivo: controllers/AdminController.php
 * (AÑADIDA: Validación de cruce de horario de Profesor)
 */

require_once MODEL_PATH . 'Usuario.php';
require_once MODEL_PATH . 'Curso.php'; 
require_once MODEL_PATH . 'Matricula.php';
require_once MODEL_PATH . 'PlanEstudio.php';
require_once CONFIG_PATH . 'Database.php';
require_once 'utils/HorarioHelper.php'; // --- NUEVO HELPER ---

class AdminController {

    private $db;
    private $usuario;
    private $curso; 
    private $matricula;
    private $planEstudio;
    private $horarioHelper; // --- NUEVO HELPER ---

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
        $this->horarioHelper = new HorarioHelper(); // --- NUEVO HELPER ---
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
        // ... (código existente, sin cambios en esta función) ...
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if ( !empty($_POST['nombre']) && !empty($_POST['apellido']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['id_rol']) ) { 
                $email = $_POST['email'];
                $password = $_POST['password'];
                $passRegex = '/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/';
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['error_message'] = "Error: El formato del email no es válido.";
                } else if (!preg_match($passRegex, $password)) {
                    $_SESSION['error_message'] = "Error: La contraseña debe tener al menos 8 caracteres, una mayúscula y una minúscula.";
                } else {
                    $this->usuario->nombre = $_POST['nombre']; 
                    $this->usuario->apellido = $_POST['apellido']; 
                    $this->usuario->email = $email; 
                    $this->usuario->password = $password; 
                    $this->usuario->id_rol = $_POST['id_rol'];
                    $this->usuario->id_plan_estudio = ($_POST['id_rol'] == 3) ? $_POST['id_plan_estudio'] : null;
                    if (!$this->usuario->crear()) { 
                        $_SESSION['error_message'] = "Error al crear el usuario. Es posible que el email ya exista."; 
                    }
                }
            } else { 
                $_SESSION['error_message'] = "Error: Todos los campos son obligatorios."; 
            } 
        } 
        header('Location: index.php?controller=Admin&action=gestionarUsuarios'); 
        exit(); 
    }
    
    public function getUsuario() { /* ... (código existente) ... */ }
    public function editarUsuario() { /* ... (código existente) ... */ }
    public function eliminarUsuario() { /* ... (código existente) ... */ }

    // --- ACCIONES DE CURSOS (MODIFICADAS) ---
    
    public function gestionarCursos() {
        $listaCursos = $this->curso->readAll(); 
        $listaProfesores = $this->usuario->readProfesores(); 
        require_once VIEW_PATH . 'layouts/header.php'; 
        require_once VIEW_PATH . 'admin/gestionar_cursos.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    /**
     * MODIFICADO: Añadida validación de cruce de horario
     */
    public function crearCurso() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if ( !empty($_POST['nombre_curso']) && !empty($_POST['id_profesor']) && !empty($_POST['anio_academico']) ) { 
                
                $horarioNuevo = $_POST['horario'];
                $id_profesor = $_POST['id_profesor'];

                // --- INICIO VALIDACIÓN DE CRUCE ---
                $horariosProfesor = $this->curso->getHorariosPorProfesor($id_profesor);
                if ($this->horarioHelper->verificarConflictoConLista($horarioNuevo, $horariosProfesor)) {
                    $_SESSION['error_message_curso'] = "Error: Hay un cruce de horario con otro curso asignado a este profesor.";
                    header('Location: index.php?controller=Admin&action=gestionarCursos'); 
                    exit();
                }
                // --- FIN VALIDACIÓN DE CRUCE ---

                $this->curso->nombre_curso = $_POST['nombre_curso']; 
                $this->curso->descripcion = $_POST['descripcion']; 
                $this->curso->horario = $horarioNuevo; 
                $this->curso->id_profesor = $id_profesor; 
                $this->curso->anio_academico = $_POST['anio_academico']; 
                
                if (!$this->curso->crear()) { 
                    $_SESSION['error_message_curso'] = "Error al crear el curso.";
                } 
            } else { 
                $_SESSION['error_message_curso'] = "Error: Nombre, Profesor y Año son obligatorios.";
            } 
        } 
        header('Location: index.php?controller=Admin&action=gestionarCursos'); 
        exit(); 
    }
    
    public function getCurso() { 
        if (isset($_GET['id'])) { 
            $this->curso->id_curso = $_GET['id']; 
            $datosCurso = $this->curso->readOne($_GET['id']);
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
    
    /**
     * MODIFICADO: Añadida validación de cruce de horario
     */
    public function editarCurso() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if ( !empty($_POST['edit_id_curso']) && !empty($_POST['edit_nombre_curso']) && !empty($_POST['edit_id_profesor']) && !empty($_POST['edit_anio_academico']) ) { 
                
                $id_curso_editar = $_POST['edit_id_curso'];
                $horarioNuevo = $_POST['edit_horario'];
                $id_profesor = $_POST['edit_id_profesor'];

                // --- INICIO VALIDACIÓN DE CRUCE ---
                // Excluimos el propio curso de la validación
                $horariosProfesor = $this->curso->getHorariosPorProfesor($id_profesor, $id_curso_editar);
                if ($this->horarioHelper->verificarConflictoConLista($horarioNuevo, $horariosProfesor)) {
                    $_SESSION['error_message_curso'] = "Error: Hay un cruce de horario con otro curso asignado a este profesor.";
                    header('Location: index.php?controller=Admin&action=gestionarCursos'); 
                    exit();
                }
                // --- FIN VALIDACIÓN DE CRUCE ---
                
                $this->curso->id_curso = $id_curso_editar; 
                $this->curso->nombre_curso = $_POST['edit_nombre_curso']; 
                $this->curso->descripcion = $_POST['edit_descripcion']; 
                $this->curso->horario = $horarioNuevo; 
                $this->curso->id_profesor = $id_profesor; 
                $this->curso->anio_academico = $_POST['edit_anio_academico']; 
                
                if (!$this->curso->update()) { 
                    $_SESSION['error_message_curso'] = "Error al actualizar el curso.";
                } 
            } else { 
                $_SESSION['error_message_curso'] = "Error: Nombre, Profesor y Año son obligatorios para editar.";
            } 
        } 
        header('Location: index.php?controller=Admin&action=gestionarCursos'); 
        exit(); 
    }
    
    public function eliminarCurso() { /* ... (código existente) ... */ }
}
?>