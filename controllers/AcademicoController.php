<?php
/*
 * Archivo: controllers/AcademicoController.php
 * (CORREGIDOS todos los errores de sintaxis 'this.')
 */

require_once MODEL_PATH . 'Facultad.php';
require_once MODEL_PATH . 'Escuela.php'; 
require_once MODEL_PATH . 'PlanEstudio.php';
require_once MODEL_PATH . 'CursoPlan.php';
require_once MODEL_PATH . 'Curso.php';
require_once MODEL_PATH . 'Usuario.php';
require_once MODEL_PATH . 'Prerequisito.php'; // <-- Añadido
require_once CONFIG_PATH . 'Database.php';

class AcademicoController {

    private $db;
    private $facultad;
    private $escuela; 
    private $planEstudio;
    private $cursoPlan;
    private $curso;
    private $usuario;
    private $prerequisito; // <-- Añadido

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
            header('Location: index.php?controller=Usuario&action=index');
            exit();
        }
        
        $database = new Database();
        $this->db = $database->getConnection();
        
        $this->facultad = new Facultad($this->db);
        $this->escuela = new Escuela($this->db); 
        $this->planEstudio = new PlanEstudio($this->db);
        $this->cursoPlan = new CursoPlan($this->db);
        $this->curso = new Curso($this->db);
        $this->usuario = new Usuario($this->db);
        $this->prerequisito = new Prerequisito($this->db); // <-- Añadido
    }

    /**
     * PÁGINA 1: Muestra la lista de Facultades
     */
    public function index() {
        $listaFacultades = $this->facultad->readAll();
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'admin/academico_facultades.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }
    
    // -------------------------------------------------------------------
    // --- ACCIONES CRUD FACULTAD ---
    // -------------------------------------------------------------------

    public function crearFacultad() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['nombre_facultad'])) {
            $this->facultad->nombre_facultad = $_POST['nombre_facultad'];
            if (!$this->facultad->crear()) {
                $_SESSION['error_message_academico'] = "Error al crear la facultad.";
            }
        }
        header('Location: index.php?controller=Academico&action=index');
        exit();
    }
    
    public function getFacultad() {
        if (isset($_GET['id'])) { 
            $this->facultad->id_facultad = $_GET['id']; 
            $datos = $this->facultad->readOne(); 
            if ($datos) { 
                header('Content-Type: application/json'); 
                echo json_encode($datos); 
            }
        }
        exit(); 
    }
    
    public function editarFacultad() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['edit_id_facultad']) && !empty($_POST['edit_nombre_facultad'])) {
            $this->facultad->id_facultad = $_POST['edit_id_facultad'];
            $this->facultad->nombre_facultad = $_POST['edit_nombre_facultad'];
            if (!$this->facultad->update()) {
                $_SESSION['error_message_academico'] = "Error al actualizar la facultad.";
            }
        }
        header('Location: index.php?controller=Academico&action=index');
        exit();
    }

    public function eliminarFacultad() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['id'])) {
            $this->facultad->id_facultad = $_POST['id'];
            if ($this->facultad->delete()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: No se puede eliminar. Asegúrese de que no tenga Escuelas asociadas.']);
            }
        }
        exit();
    }

    // -------------------------------------------------------------------
    // --- ACCIONES DE ESCUELAS ---
    // -------------------------------------------------------------------
    
    public function verFacultad() {
        $id_facultad = $_GET['id_facultad'] ?? 0;
        if ($id_facultad <= 0) {
            header('Location: index.php?controller=Academico&action=index');
            exit();
        }
        
        $this->facultad->id_facultad = $id_facultad;
        $infoFacultad = $this->facultad->readOne();
        
        if (!$infoFacultad) {
            header('Location: index.php?controller=Academico&action=index');
            exit();
        }
        
        $listaEscuelas = $this->escuela->readByFacultad($id_facultad);
        $listaFacultades = $this->facultad->readAll();
        
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'admin/academico_escuelas.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }
    
    public function crearEscuela() {
        $id_facultad = $_POST['id_facultad'] ?? 0;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['nombre_escuela']) && $id_facultad > 0) {
            $this->escuela->nombre_escuela = $_POST['nombre_escuela'];
            $this->escuela->id_facultad = $id_facultad;
            if (!$this->escuela->crear()) {
                $_SESSION['error_message_academico'] = "Error al crear la escuela.";
            }
        } else {
             $_SESSION['error_message_academico'] = "Error: Faltan datos.";
        }
        header('Location: index.php?controller=Academico&action=verFacultad&id_facultad=' . $id_facultad);
        exit();
    }
    
    public function getEscuela() {
        if (isset($_GET['id'])) { 
            $this->escuela->id_escuela = $_GET['id']; 
            $datos = $this->escuela->readOne(); 
            if ($datos) { 
                header('Content-Type: application/json'); 
                echo json_encode($datos); 
            }
        }
        exit(); 
    }
    
    public function editarEscuela() {
        $id_facultad_redirect = $_POST['id_facultad_redirect'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['edit_id_escuela']) && !empty($_POST['edit_nombre_escuela']) && !empty($_POST['edit_id_facultad'])) {
            $this->escuela->id_escuela = $_POST['edit_id_escuela'];
            $this->escuela->nombre_escuela = $_POST['edit_nombre_escuela'];
            $this->escuela->id_facultad = $_POST['edit_id_facultad'];
            
            if (!$this->escuela->update()) {
                $_SESSION['error_message_academico'] = "Error al actualizar la escuela.";
            }
            if ($id_facultad_redirect != $this->escuela->id_facultad) {
                $id_facultad_redirect = $this->escuela->id_facultad;
            }
        } else {
            $_SESSION['error_message_academico'] = "Error: Faltan datos.";
        }
        header('Location: index.php?controller=Academico&action=verFacultad&id_facultad=' . $id_facultad_redirect);
        exit();
    }

    public function eliminarEscuela() {
        header('Content-Type: application/json');
        $id_facultad_redirect = $_POST['id_facultad_redirect'] ?? 0;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['id']) && $id_facultad_redirect > 0) {
            $this->escuela->id_escuela = $_POST['id'];
            if ($this->escuela->delete()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: No se puede eliminar. Asegúrese de que no tenga Planes de Estudio asociados.']);
            }
        } else {
             echo json_encode(['success' => false, 'message' => 'Error: Petición inválida.']);
        }
        exit();
    }
    
    // -------------------------------------------------------------------
    // --- ACCIONES DE PLANES DE ESTUDIO (MALLAS) ---
    // -------------------------------------------------------------------

    public function verEscuela() {
        $id_escuela = $_GET['id_escuela'] ?? 0;
        if ($id_escuela <= 0) {
            header('Location: index.php?controller=Academico&action=index');
            exit();
        }
        $this->escuela->id_escuela = $id_escuela;
        $infoEscuela = $this->escuela->readOne();
        if (!$infoEscuela) {
            header('Location: index.php?controller=Academico&action=index');
            exit();
        }
        $this->facultad->id_facultad = $infoEscuela['id_facultad'];
        $infoFacultad = $this->facultad->readOne();
        $listaPlanes = $this->planEstudio->readByEscuela($id_escuela);
        $listaEscuelas = $this->escuela->readAll();
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'admin/academico_planes.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function crearPlan() {
        $id_escuela = $_POST['id_escuela'] ?? 0;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['nombre_plan']) && !empty($_POST['anio']) && $id_escuela > 0) {
            $this->planEstudio->id_escuela = $id_escuela;
            $this->planEstudio->nombre_plan = $_POST['nombre_plan'];
            $this->planEstudio->anio = $_POST['anio'];
            if (!$this->planEstudio->crear()) {
                $_SESSION['error_message_academico'] = "Error al crear el Plan de Estudio.";
            }
        } else {
             $_SESSION['error_message_academico'] = "Error: Faltan datos.";
        }
        header('Location: index.php?controller=Academico&action=verEscuela&id_escuela=' . $id_escuela);
        exit();
    }
    
    public function getPlan() {
        if (isset($_GET['id'])) { 
            $this->planEstudio->id_plan_estudio = $_GET['id']; 
            $datos = $this->planEstudio->readOne($_GET['id']);
            if ($datos) { 
                header('Content-Type: application/json'); 
                echo json_encode($datos); 
            }
        }
        exit(); 
    }
    
    public function editarPlan() {
        $id_escuela_redirect = $_POST['id_escuela_redirect'] ?? 0;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['edit_id_plan_estudio']) && !empty($_POST['edit_nombre_plan']) && !empty($_POST['edit_anio'])) {
            $this->planEstudio->id_plan_estudio = $_POST['edit_id_plan_estudio'];
            $this->planEstudio->nombre_plan = $_POST['edit_nombre_plan'];
            $this->planEstudio->anio = $_POST['edit_anio'];
            $this->planEstudio->id_escuela = $_POST['edit_id_escuela'];
            if (!$this->planEstudio->update()) {
                $_SESSION['error_message_academico'] = "Error al actualizar el Plan.";
            }
            if ($id_escuela_redirect != $this->planEstudio->id_escuela) {
                $id_escuela_redirect = $this->planEstudio->id_escuela;
            }
        } else {
            $_SESSION['error_message_academico'] = "Error: Faltan datos.";
        }
        header('Location: index.php?controller=Academico&action=verEscuela&id_escuela=' . $id_escuela_redirect);
        exit();
    }

    public function eliminarPlan() {
        header('Content-Type: application/json');
        $id_escuela_redirect = $_POST['id_escuela_redirect'] ?? 0;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['id']) && $id_escuela_redirect > 0) {
            $this->planEstudio->id_plan_estudio = $_POST['id'];
            if ($this->planEstudio->delete()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: No se puede eliminar. Asegúrese de que no tenga Cursos o Estudiantes asociados.']);
            }
        } else {
             echo json_encode(['success' => false, 'message' => 'Error: Petición inválida.']);
        }
        exit();
    }

    // -------------------------------------------------------------------
    // --- ACCIONES DE GESTIÓN DE MALLA (PÁGINA 4) ---
    // -------------------------------------------------------------------
    
    public function gestionarMalla() {
        $id_plan = $_GET['id_plan'] ?? 0;
        if ($id_plan <= 0) {
            header('Location: index.php?controller=Academico&action=index');
            exit();
        }
        $infoPlan = $this->planEstudio->readOne($id_plan);
        if (!$infoPlan) {
            header('Location: index.php?controller=Academico&action=index');
            exit();
        }
        $cursosEnPlan = $this->cursoPlan->readCursosEnPlan($id_plan);
        $cursosDisponibles = $this->cursoPlan->readCursosFueraDePlan($id_plan);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'admin/gestionar_malla.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function asignarCursoPlan() {
        $id_plan = $_POST['id_plan_estudio'] ?? 0;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id_plan > 0 && !empty($_POST['id_curso']) && !empty($_POST['ciclo'])) {
            $this->cursoPlan->id_plan_estudio = $id_plan;
            $this->cursoPlan->id_curso = $_POST['id_curso'];
            $this->cursoPlan->ciclo = $_POST['ciclo'];
            if (!$this->cursoPlan->crear()) {
                $_SESSION['error_message_academico'] = "Error al asignar el curso. Es posible que el curso ya esté en esta malla.";
            }
        } else {
            $_SESSION['error_message_academico'] = "Error: Faltan datos (Curso o Ciclo).";
        }
        header('Location: index.php?controller=Academico&action=gestionarMalla&id_plan=' . $id_plan);
        exit();
    }

    public function quitarCursoPlan() {
        $id_cursos_plan = $_GET['id_cursos_plan'] ?? 0;
        $id_plan = $_GET['id_plan'] ?? 0;
        if ($id_cursos_plan > 0 && $id_plan > 0) {
            $this->cursoPlan->id_cursos_plan = $id_cursos_plan;
            if (!$this->cursoPlan->delete()) {
                $_SESSION['error_message_academico'] = "Error al quitar el curso de la malla.";
            }
        } else {
            $_SESSION['error_message_academico'] = "Error: Petición inválida.";
        }
        header('Location: index.php?controller=Academico&action=gestionarMalla&id_plan=' . $id_plan);
        exit();
    }
    
    // -------------------------------------------------------------------
    // --- ACCIONES DE PRERREQUISITOS (NUEVAS) ---
    // -------------------------------------------------------------------
    
    public function gestionarPrerequisitos() {
        $id_curso = $_GET['id_curso'] ?? 0;
        $id_plan = $_GET['id_plan'] ?? 0;
        if ($id_curso <= 0 || $id_plan <= 0) {
            header('Location: index.php?controller=Academico&action=index');
            exit();
        }
        $infoCurso = $this->curso->readOne($id_curso);
        $infoPlan = $this->planEstudio->readOne($id_plan);
        if (!$infoCurso || !$infoPlan) {
            header('Location: index.php?controller=Academico&action=index');
            exit();
        }
        $cursosDelPlan = $this->cursoPlan->readCursosEnPlan($id_plan);
        $ciclo_actual = 0;
        foreach($cursosDelPlan as $c) {
            if ($c['id_curso'] == $id_curso) {
                $ciclo_actual = $c['ciclo'];
                break;
            }
        }
        $listaRequisitos = $this->prerequisito->readByCurso($id_curso);
        $listaCursosDisponibles = $this->prerequisito->readCursosDisponiblesParaReq($id_plan, $ciclo_actual, $id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'admin/gestionar_prerequisitos.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function addPrerequisito() {
        $id_curso_principal = $_POST['id_curso_principal'] ?? 0;
        $id_curso_requisito = $_POST['id_curso_requisito'] ?? 0;
        $id_plan = $_POST['id_plan'] ?? 0;
        if ($id_curso_principal > 0 && $id_curso_requisito > 0 && $id_plan > 0) {
            $this->prerequisito->id_curso_principal = $id_curso_principal;
            $this->prerequisito->id_curso_requisito = $id_curso_requisito;
            if (!$this->prerequisito->crear()) {
                 $_SESSION['error_message_academico'] = "Error al añadir el requisito. Es posible que ya exista.";
            }
        } else {
            $_SESSION['error_message_academico'] = "Error: Faltan datos.";
        }
        header('Location: index.php?controller=Academico&action=gestionarPrerequisitos&id_curso=' . $id_curso_principal . '&id_plan=' . $id_plan);
        exit();
    }

    public function deletePrerequisito() {
        $id_prerequisito = $_GET['id_prerequisito'] ?? 0;
        $id_curso = $_GET['id_curso'] ?? 0;
        $id_plan = $_GET['id_plan'] ?? 0;
        if ($id_prerequisito > 0) {
            $this->prerequisito->id_prerequisito = $id_prerequisito;
            if (!$this->prerequisito->delete()) {
                 $_SESSION['error_message_academico'] = "Error al eliminar el requisito.";
            }
        }
        header('Location: index.php?controller=Academico&action=gestionarPrerequisitos&id_curso=' . $id_curso . '&id_plan=' . $id_plan);
        exit();
    }
}
?>