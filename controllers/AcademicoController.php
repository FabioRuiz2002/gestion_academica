<?php
require_once MODEL_PATH . 'Facultad.php';
require_once MODEL_PATH . 'Escuela.php'; 
require_once MODEL_PATH . 'PlanEstudio.php';
require_once MODEL_PATH . 'CursoPlan.php';
require_once MODEL_PATH . 'Curso.php';
require_once MODEL_PATH . 'Usuario.php';
require_once MODEL_PATH . 'Prerequisito.php';
require_once CONFIG_PATH . 'Database.php';
require_once 'utils/HorarioHelper.php';

class AcademicoController {
    private $db; private $facultad; private $escuela; private $planEstudio; private $cursoPlan; private $curso; private $usuario; private $prerequisito; private $horarioHelper;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) { header('Location: index.php'); exit(); }
        $this->db = (new Database())->getConnection();
        $this->facultad = new Facultad($this->db); 
        $this->escuela = new Escuela($this->db); 
        $this->planEstudio = new PlanEstudio($this->db); 
        $this->cursoPlan = new CursoPlan($this->db);
        $this->curso = new Curso($this->db); 
        $this->usuario = new Usuario($this->db);
        $this->prerequisito = new Prerequisito($this->db); 
        $this->horarioHelper = new HorarioHelper();
    }

    public function index() {
        $listaFacultades = $this->facultad->readAll();
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'admin/academico_facultades.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }
    
    public function crearFacultad() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['nombre_facultad'])) {
            $this->facultad->nombre_facultad = $_POST['nombre_facultad'];
            $this->facultad->crear();
        }
        header('Location: index.php?controller=Academico&action=index'); exit();
    }

    public function verFacultad() {
        $id = $_GET['id_facultad'] ?? 0;
        $this->facultad->id_facultad = $id;
        $infoFacultad = $this->facultad->readOne();
        $listaEscuelas = $this->escuela->readByFacultad($id);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'admin/academico_escuelas.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function crearEscuela() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->escuela->nombre_escuela = $_POST['nombre_escuela'];
            $this->escuela->id_facultad = $_POST['id_facultad'];
            $this->escuela->crear();
        }
        header('Location: index.php?controller=Academico&action=verFacultad&id_facultad=' . $_POST['id_facultad']); exit();
    }

    public function verEscuela() {
        $id = $_GET['id_escuela'] ?? 0;
        $this->escuela->id_escuela = $id;
        $infoEscuela = $this->escuela->readOne();
        $this->facultad->id_facultad = $infoEscuela['id_facultad'];
        $infoFacultad = $this->facultad->readOne();
        $listaPlanes = $this->planEstudio->readByEscuela($id);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'admin/academico_planes.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function crearPlan() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->planEstudio->id_escuela = $_POST['id_escuela'];
            $this->planEstudio->nombre_plan = $_POST['nombre_plan'];
            $this->planEstudio->anio = $_POST['anio'];
            $this->planEstudio->crear();
        }
        header('Location: index.php?controller=Academico&action=verEscuela&id_escuela=' . $_POST['id_escuela']); exit();
    }

    public function gestionarMalla() {
        $id_plan = $_GET['id_plan'] ?? 0;
        $infoPlan = $this->planEstudio->readOne($id_plan);
        $cursosEnPlan = $this->cursoPlan->readCursosEnPlan($id_plan);
        $cursosDisponibles = $this->cursoPlan->readCursosFueraDePlan($id_plan);
        $listaProfesores = $this->usuario->readProfesores();
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'admin/gestionar_malla.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function asignarCursoPlan() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->cursoPlan->id_plan_estudio = $_POST['id_plan_estudio'];
            $this->cursoPlan->id_curso = $_POST['id_curso'];
            $this->cursoPlan->ciclo = $_POST['ciclo'];
            $this->cursoPlan->crear();
        }
        header('Location: index.php?controller=Academico&action=gestionarMalla&id_plan=' . $_POST['id_plan_estudio']); exit();
    }

    public function quitarCursoPlan() {
        $this->cursoPlan->id_cursos_plan = $_GET['id_cursos_plan'];
        $this->cursoPlan->delete();
        header('Location: index.php?controller=Academico&action=gestionarMalla&id_plan=' . $_GET['id_plan']); exit();
    }

    public function editarProfesorCurso() {
        $id_plan = $_POST['id_plan']; 
        $id_curso = $_POST['id_curso']; 
        $id_profesor = $_POST['id_profesor'];
        
        $infoCurso = $this->curso->readOne($id_curso);
        $horariosProfesor = $this->curso->getHorariosPorProfesor($id_profesor, $id_curso);

        if ($this->horarioHelper->verificarConflictoConLista($infoCurso['horario'], $horariosProfesor)) {
            $_SESSION['error_message_academico'] = "Error: El profesor tiene cruce de horarios.";
        } else {
            $this->curso->updateProfesor($id_curso, $id_profesor);
        }
        header('Location: index.php?controller=Academico&action=gestionarMalla&id_plan=' . $id_plan); exit();
    }
    
    public function gestionarPrerequisitos() {
        $id_curso = $_GET['id_curso']; $id_plan = $_GET['id_plan'];
        $infoCurso = $this->curso->readOne($id_curso);
        $infoPlan = $this->planEstudio->readOne($id_plan);
        $cursosDelPlan = $this->cursoPlan->readCursosEnPlan($id_plan);
        $ciclo_actual = 0;
        foreach($cursosDelPlan as $c) { if ($c['id_curso'] == $id_curso) { $ciclo_actual = $c['ciclo']; break; } }
        $listaRequisitos = $this->prerequisito->readByCurso($id_curso);
        $listaCursosDisponibles = $this->prerequisito->readCursosDisponiblesParaReq($id_plan, $ciclo_actual, $id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'admin/gestionar_prerequisitos.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function addPrerequisito() {
        $this->prerequisito->id_curso_principal = $_POST['id_curso_principal'];
        $this->prerequisito->id_curso_requisito = $_POST['id_curso_requisito'];
        $this->prerequisito->crear();
        header('Location: index.php?controller=Academico&action=gestionarPrerequisitos&id_curso=' . $_POST['id_curso_principal'] . '&id_plan=' . $_POST['id_plan']); exit();
    }

    public function deletePrerequisito() {
        $this->prerequisito->id_prerequisito = $_GET['id_prerequisito'];
        $this->prerequisito->delete();
        header('Location: index.php?controller=Academico&action=gestionarPrerequisitos&id_curso=' . $_GET['id_curso'] . '&id_plan=' . $_GET['id_plan']); exit();
    }
    
    public function getFacultad() { if (isset($_GET['id'])) { $this->facultad->id_facultad = $_GET['id']; echo json_encode($this->facultad->readOne()); } exit(); }
    public function editarFacultad() { if ($_SERVER['REQUEST_METHOD'] == 'POST') { $this->facultad->id_facultad = $_POST['edit_id_facultad']; $this->facultad->nombre_facultad = $_POST['edit_nombre_facultad']; $this->facultad->update(); } header('Location: index.php?controller=Academico&action=index'); exit(); }
    public function eliminarFacultad() { if ($_SERVER['REQUEST_METHOD'] == 'POST') { $this->facultad->id_facultad = $_POST['id']; echo json_encode(['success' => $this->facultad->delete()]); } exit(); }
    public function getEscuela() { if (isset($_GET['id'])) { $this->escuela->id_escuela = $_GET['id']; echo json_encode($this->escuela->readOne()); } exit(); }
    public function editarEscuela() { if ($_SERVER['REQUEST_METHOD'] == 'POST') { $this->escuela->id_escuela = $_POST['edit_id_escuela']; $this->escuela->nombre_escuela = $_POST['edit_nombre_escuela']; $this->escuela->id_facultad = $_POST['edit_id_facultad']; $this->escuela->update(); } header('Location: index.php?controller=Academico&action=verFacultad&id_facultad=' . $_POST['id_facultad_redirect']); exit(); }
    public function eliminarEscuela() { if ($_SERVER['REQUEST_METHOD'] == 'POST') { $this->escuela->id_escuela = $_POST['id']; echo json_encode(['success' => $this->escuela->delete()]); } exit(); }
    public function getPlan() { if (isset($_GET['id'])) { $this->planEstudio->id_plan_estudio = $_GET['id']; echo json_encode($this->planEstudio->readOne($_GET['id'])); } exit(); }
    public function editarPlan() { if ($_SERVER['REQUEST_METHOD'] == 'POST') { $this->planEstudio->id_plan_estudio = $_POST['edit_id_plan_estudio']; $this->planEstudio->nombre_plan = $_POST['edit_nombre_plan']; $this->planEstudio->anio = $_POST['edit_anio']; $this->planEstudio->id_escuela = $_POST['edit_id_escuela']; $this->planEstudio->update(); } header('Location: index.php?controller=Academico&action=verEscuela&id_escuela=' . $_POST['id_escuela_redirect']); exit(); }
    public function eliminarPlan() { if ($_SERVER['REQUEST_METHOD'] == 'POST') { $this->planEstudio->id_plan_estudio = $_POST['id']; echo json_encode(['success' => $this->planEstudio->delete()]); } exit(); }
}
?>