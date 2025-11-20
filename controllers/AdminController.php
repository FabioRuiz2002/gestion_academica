<?php
require_once MODEL_PATH . 'Usuario.php';
require_once MODEL_PATH . 'Curso.php'; 
require_once MODEL_PATH . 'Matricula.php';
require_once MODEL_PATH . 'PlanEstudio.php';
require_once CONFIG_PATH . 'Database.php';
require_once 'utils/HorarioHelper.php'; 

class AdminController {
    private $db; private $usuario; private $curso; private $matricula; private $planEstudio; private $horarioHelper;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) { header('Location: index.php'); exit(); }
        $this->db = (new Database())->getConnection();
        $this->usuario = new Usuario($this->db);
        $this->curso = new Curso($this->db); 
        $this->matricula = new Matricula($this->db);
        $this->planEstudio = new PlanEstudio($this->db);
        $this->horarioHelper = new HorarioHelper();
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

    // --- AQUÍ ESTABA EL ERROR (Corregido para usar readByRol) ---
    public function gestionarUsuarios() { 
        $listaRoles = $this->usuario->readRoles();
        $listaPlanes = $this->planEstudio->readForDropdown();
        
        // Cargamos las listas por separado para las pestañas
        $listaAdmins = $this->usuario->readByRol(1);
        $listaProfesores = $this->usuario->readByRol(2);
        $estudiantesRaw = $this->usuario->readByRol(3);
        
        // Agrupar estudiantes
        $listaEstudiantesAgrupados = [];
        foreach ($estudiantesRaw as $est) {
            $facultad = $est['nombre_facultad'] ?? 'Sin Facultad';
            $escuela = $est['nombre_escuela'] ?? 'Sin Escuela';
            $listaEstudiantesAgrupados[$facultad][$escuela][] = $est;
        }
        
        require_once VIEW_PATH . 'layouts/header.php'; 
        require_once VIEW_PATH . 'admin/gestionar_usuarios.php'; 
        require_once VIEW_PATH . 'layouts/footer.php'; 
    }
    
    public function crearUsuario() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            $this->usuario->nombre = $_POST['nombre']; 
            $this->usuario->apellido = $_POST['apellido']; 
            $this->usuario->dni = $_POST['dni']; 
            $this->usuario->email = $_POST['email']; 
            $this->usuario->password = $_POST['password']; 
            $this->usuario->id_rol = $_POST['id_rol'];
            $this->usuario->id_plan_estudio = ($_POST['id_rol'] == 3) ? $_POST['id_plan_estudio'] : null;
            if (!$this->usuario->crear()) $_SESSION['error_message'] = "Error al crear. DNI o Email duplicado."; 
        } 
        header('Location: index.php?controller=Admin&action=gestionarUsuarios'); exit(); 
    }
    
    public function editarUsuario() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            $this->usuario->id_usuario = $_POST['edit_id_usuario']; 
            $this->usuario->nombre = $_POST['edit_nombre']; 
            $this->usuario->apellido = $_POST['edit_apellido']; 
            $this->usuario->dni = $_POST['edit_dni']; 
            $this->usuario->email = $_POST['edit_email'];
            $this->usuario->id_rol = $_POST['edit_id_rol'];
            $this->usuario->id_plan_estudio = ($_POST['edit_id_rol'] == 3) ? $_POST['edit_id_plan_estudio'] : null;
            $this->usuario->password = $_POST['edit_password']; 
            if (!$this->usuario->update()) $_SESSION['error_message'] = "Error al actualizar."; 
        } 
        header('Location: index.php?controller=Admin&action=gestionarUsuarios'); exit(); 
    }
    
    public function eliminarUsuario() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_usuario'])) { 
            $this->usuario->id_usuario = $_POST['id_usuario']; 
            echo json_encode(['success' => $this->usuario->delete()]); 
        } 
        exit(); 
    }
    
    public function getUsuario() { 
        if (isset($_GET['id'])) { 
            $this->usuario->id_usuario = $_GET['id']; 
            echo json_encode($this->usuario->readOne()); 
        }
        exit(); 
    }

    public function gestionarCursos() {
        $listaCursos = $this->curso->readAll(); 
        $listaProfesores = $this->usuario->readProfesores(); 
        require_once VIEW_PATH . 'layouts/header.php'; 
        require_once VIEW_PATH . 'admin/gestionar_cursos.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function crearCurso() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            $horariosProfesor = $this->curso->getHorariosPorProfesor($_POST['id_profesor']);
            if ($this->horarioHelper->verificarConflictoConLista($_POST['horario'], $horariosProfesor)) {
                $_SESSION['error_message_curso'] = "Cruce de horario.";
            } else {
                $this->curso->nombre_curso = $_POST['nombre_curso']; 
                $this->curso->descripcion = $_POST['descripcion']; 
                $this->curso->horario = $_POST['horario']; 
                $this->curso->id_profesor = $_POST['id_profesor']; 
                $this->curso->anio_academico = $_POST['anio_academico']; 
                $this->curso->crear();
            }
        } 
        header('Location: index.php?controller=Admin&action=gestionarCursos'); exit(); 
    }
    
    public function editarCurso() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            $horariosProfesor = $this->curso->getHorariosPorProfesor($_POST['edit_id_profesor'], $_POST['edit_id_curso']);
            if ($this->horarioHelper->verificarConflictoConLista($_POST['edit_horario'], $horariosProfesor)) {
                $_SESSION['error_message_curso'] = "Cruce de horario.";
            } else {
                $this->curso->id_curso = $_POST['edit_id_curso']; 
                $this->curso->nombre_curso = $_POST['edit_nombre_curso']; 
                $this->curso->descripcion = $_POST['edit_descripcion']; 
                $this->curso->horario = $_POST['edit_horario']; 
                $this->curso->id_profesor = $_POST['edit_id_profesor']; 
                $this->curso->anio_academico = $_POST['edit_anio_academico']; 
                $this->curso->update();
            }
        } 
        header('Location: index.php?controller=Admin&action=gestionarCursos'); exit(); 
    }
    
    public function eliminarCurso() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_curso'])) { 
            $this->curso->id_curso = $_POST['id_curso']; 
            echo json_encode(['success' => $this->curso->delete()]); 
        } 
        exit(); 
    }
    
    public function getCurso() { 
        if (isset($_GET['id'])) { 
            $this->curso->id_curso = $_GET['id']; 
            echo json_encode($this->curso->readOne($_GET['id'])); 
        }
        exit(); 
    }
}
?>