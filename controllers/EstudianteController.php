<?php
require_once MODEL_PATH . 'Matricula.php'; 
require_once MODEL_PATH . 'Usuario.php'; 
require_once MODEL_PATH . 'Asistencia.php';
require_once MODEL_PATH . 'Material.php';
require_once MODEL_PATH . 'Curso.php';
require_once MODEL_PATH . 'Tarea.php';
require_once MODEL_PATH . 'Entrega.php';
require_once MODEL_PATH . 'PlanEstudio.php';
require_once MODEL_PATH . 'Prerequisito.php';
require_once MODEL_PATH . 'Evaluacion.php';
require_once MODEL_PATH . 'Nota.php';
require_once CONFIG_PATH . 'Database.php';
require_once 'utils/HorarioHelper.php';

class EstudianteController {

    private $db; private $usuario; private $matricula; private $asistencia; private $material; private $curso; private $tarea; private $entrega; private $planEstudio; private $prerequisito; private $horarioHelper; private $evaluacion; private $nota;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 3) {
            header('Location: index.php?controller=Usuario&action=index');
            exit();
        }
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuario = new Usuario($this->db);
        $this->matricula = new Matricula($this->db); 
        $this->asistencia = new Asistencia($this->db);
        $this->material = new Material($this->db);
        $this->curso = new Curso($this->db);
        $this->tarea = new Tarea($this->db);
        $this->entrega = new Entrega($this->db);
        $this->planEstudio = new PlanEstudio($this->db);
        $this->prerequisito = new Prerequisito($this->db);
        $this->horarioHelper = new HorarioHelper();
        $this->evaluacion = new Evaluacion($this->db);
        $this->nota = new Nota($this->db);
    }

    public function index() {
        $id = $_SESSION['id_usuario'];
        
        // Obtener cursos donde YA está inscrito para mostrarlos en el panel
        $misCursos = $this->matricula->readCursosMatriculados($id);
        $listaTareasProximas = $this->tarea->readTareasProximasPorEstudiante($id);
        
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/bienvenida_estudiante.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function panelCurso() {
        if (!isset($_GET['id_curso'])) { header('Location: index.php?controller=Estudiante&action=index'); exit(); }
        $id_curso = $_GET['id_curso'];
        $infoCurso = $this->curso->readOne($id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/panel_curso.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function verCalificaciones() {
        $id_curso = $_GET['id_curso'];
        $id_estudiante = $_SESSION['id_usuario'];
        $infoCurso = $this->curso->readOne($id_curso);
        $listaNotas = $this->nota->readNotasPorEstudiante($id_estudiante, $id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/mis_calificaciones.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function verAsistencias() {
        $id_curso = $_GET['id_curso'];
        $id_estudiante = $_SESSION['id_usuario'];
        $infoCurso = $this->curso->readOne($id_curso);
        $asistencias = $this->asistencia->readPorEstudiante($id_estudiante, $id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/mis_asistencias.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function verMateriales() {
        $id_curso = $_GET['id_curso'];
        $infoCurso = $this->curso->readOne($id_curso);
        $listaMateriales = $this->material->readPorCurso($id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/mis_materiales.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function verTareas() {
        $id_curso = $_GET['id_curso'];
        $id_estudiante = $_SESSION['id_usuario'];
        $infoCurso = $this->curso->readOne($id_curso);
        $listaTareas = $this->tarea->readPorCurso($id_curso);
        $listaEntregas = $this->entrega->readPorEstudianteYCurso($id_estudiante, $id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/mis_tareas.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function entregarTarea() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo_entrega'])) {
            $archivo = $_FILES['archivo_entrega'];
            $nombre = time() . "_" . basename($archivo['name']);
            if (move_uploaded_file($archivo['tmp_name'], ROOT_PATH . 'uploads/entregas/' . $nombre)) {
                $this->entrega->id_tarea = $_POST['id_tarea'];
                $this->entrega->id_estudiante = $_SESSION['id_usuario'];
                $this->entrega->nombre_archivo = basename($archivo['name']);
                $this->entrega->ruta_archivo = 'uploads/entregas/' . $nombre;
                $this->entrega->crear();
                $_SESSION['mensaje_tarea'] = ['tipo'=>'success', 'texto'=>'Tarea enviada correctamente.'];
            }
        }
        header('Location: index.php?controller=Estudiante&action=verTareas&id_curso=' . $_POST['id_curso']); exit();
    }

    // --- MATRÍCULA (CON LÓGICA DE CANDADO Y LISTAS) ---
    public function verMatriculas() {
        $id_estudiante = $_SESSION['id_usuario'];
        
        // 1. Verificar si la matrícula está bloqueada por el Admin
        $this->usuario->id_usuario = $id_estudiante;
        $usuarioInfo = $this->usuario->readOne();
        $matriculaBloqueada = $usuarioInfo['matricula_bloqueada'] ?? 0;

        $id_plan = $_SESSION['id_plan_estudio'] ?? 0;
        $historialPromedios = $this->nota->getHistorialPromedios($id_estudiante);
        
        $infoPlan = null; 
        $cursosPorCiclo = []; 
        $reglasPrerequisitos = []; 
        $horariosActuales = []; 
        $misCursos = []; // Cursos ya inscritos

        if ($id_plan > 0) {
            $infoPlan = $this->planEstudio->readOne($id_plan);
            // Cursos disponibles (que NO ha inscrito)
            $cursosPorCiclo = $this->matricula->readCursosDisponiblesPorPlan($id_plan, $id_estudiante);
            // Cursos YA inscritos (para mostrarlos arriba)
            $misCursos = $this->matricula->readCursosMatriculados($id_estudiante);
            
            $reglasPrerequisitos = $this->prerequisito->readPrerequisitosPorPlan($id_plan);
            $horariosActuales = $this->matricula->getHorariosEstudiante($id_estudiante);
        }

        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/ver_matriculas.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function matricularCurso() {
        $id_estudiante = $_SESSION['id_usuario'];
        
        // 1. VALIDACIÓN DE SEGURIDAD (CANDADO)
        $this->usuario->id_usuario = $id_estudiante;
        $u = $this->usuario->readOne();
        if ($u['matricula_bloqueada'] == 1) {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Tu matrícula ha sido cerrada por la administración.'];
            header('Location: index.php?controller=Estudiante&action=verMatriculas');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_curso'])) {
            $id_curso = $_POST['id_curso'];
            $infoCurso = $this->curso->readOne($id_curso);
            $horarioNuevo = $infoCurso['horario'];
            $horariosActuales = $this->matricula->getHorariosEstudiante($id_estudiante);
            
            if ($this->horarioHelper->verificarConflictoConLista($horarioNuevo, $horariosActuales)) {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'No se puede matricular: Cruce de horario.'];
            } else {
                $this->matricula->id_estudiante = $id_estudiante;
                $this->matricula->id_curso = $id_curso;
                if ($this->matricula->matricular()) {
                    $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => '¡Matrícula exitosa!'];
                } else {
                    $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error al procesar la matrícula.'];
                }
            }
        }
        header('Location: index.php?controller=Estudiante&action=verMatriculas');
        exit();
    }
}
?>