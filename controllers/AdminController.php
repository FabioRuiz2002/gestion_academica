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

    public function gestionarUsuarios() { 
        $listaRoles = $this->usuario->readRoles();
        $listaPlanes = $this->planEstudio->readForDropdown();
        $listaAdmins = $this->usuario->readByRol(1);
        $listaProfesores = $this->usuario->readByRol(2);
        $estudiantesRaw = $this->usuario->readByRol(3);
        $listaEstudiantesAgrupados = [];
        foreach ($estudiantesRaw as $est) {
            $listaEstudiantesAgrupados[$est['nombre_facultad'] ?? 'Sin Facultad'][$est['nombre_escuela'] ?? 'Sin Escuela'][] = $est;
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
            if (!$this->usuario->crear()) $_SESSION['error_message'] = "Error al crear."; 
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

    public function gestionarMatriculaAlumno() {
        if (!isset($_GET['id_estudiante'])) { header('Location: index.php?controller=Admin&action=gestionarUsuarios'); exit(); }
        $id_estudiante = $_GET['id_estudiante'];
        $this->usuario->id_usuario = $id_estudiante;
        $infoEstudiante = $this->usuario->readOne();
        if (!$infoEstudiante || $infoEstudiante['id_rol'] != 3) { header('Location: index.php?controller=Admin&action=gestionarUsuarios'); exit(); }
        $id_plan = $infoEstudiante['id_plan_estudio'];
        $cursosMatriculados = $this->matricula->readCursosMatriculados($id_estudiante);
        $cursosPorCiclo = ($id_plan > 0) ? $this->matricula->readCursosDisponiblesPorPlan($id_plan, $id_estudiante) : [];
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'admin/gestionar_matricula_alumno.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function matricularAlumnoComoAdmin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_estudiante = $_POST['id_estudiante'];
            $id_curso = $_POST['id_curso'];
            $infoCurso = $this->curso->readOne($id_curso);
            $horariosActuales = $this->matricula->getHorariosEstudiante($id_estudiante);
            if ($this->horarioHelper->verificarConflictoConLista($infoCurso['horario'], $horariosActuales)) {
                $_SESSION['error_matricula'] = "Error: Cruce de horario detectado.";
            } else {
                $this->matricula->id_estudiante = $id_estudiante;
                $this->matricula->id_curso = $id_curso;
                if ($this->matricula->matricular()) $_SESSION['success_matricula'] = "Alumno matriculado.";
                else $_SESSION['error_matricula'] = "Error al matricular.";
            }
            header('Location: index.php?controller=Admin&action=gestionarMatriculaAlumno&id_estudiante=' . $id_estudiante); exit();
        }
    }

    public function toggleCandado() {
        if (isset($_POST['id_estudiante']) && isset($_POST['nuevo_estado'])) {
            $this->usuario->toggleBloqueoMatricula($_POST['id_estudiante'], $_POST['nuevo_estado']);
            $_SESSION['success_matricula'] = ($_POST['nuevo_estado'] == 1) ? "Matrícula BLOQUEADA." : "Matrícula DESBLOQUEADA.";
        }
        header('Location: index.php?controller=Admin&action=gestionarMatriculaAlumno&id_estudiante=' . $_POST['id_estudiante']); exit();
    }

    public function eliminarCursoAlumno() {
        if (isset($_GET['id_estudiante']) && isset($_GET['id_curso'])) {
            if ($this->matricula->eliminarCursoDeAlumno($_GET['id_estudiante'], $_GET['id_curso'])) {
                $_SESSION['success_matricula'] = "Curso eliminado.";
            } else {
                $_SESSION['error_matricula'] = "Error al eliminar.";
            }
            header('Location: index.php?controller=Admin&action=gestionarMatriculaAlumno&id_estudiante=' . $_GET['id_estudiante']); exit();
        }
    }
    
    // --- NUEVA FUNCIÓN REPORTES ---
    public function verReportes() {
        $datosReporte = $this->matricula->getReporteGeneral();
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'admin/reportes.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }
}
?>