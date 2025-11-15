<?php
/*
 * Archivo: controllers/EstudianteController.php
 * (Actualizada 'verMatriculas' para cargar Prerrequisitos e Historial de Notas)
 */
require_once MODEL_PATH . 'Calificacion.php';
require_once MODEL_PATH . 'Matricula.php'; 
require_once MODEL_PATH . 'Usuario.php'; 
require_once MODEL_PATH . 'Asistencia.php';
require_once MODEL_PATH . 'Material.php';
require_once MODEL_PATH . 'Curso.php';
require_once MODEL_PATH . 'Tarea.php';
require_once MODEL_PATH . 'Entrega.php';
require_once MODEL_PATH . 'PlanEstudio.php';
require_once MODEL_PATH . 'Prerequisito.php'; // <-- NUEVO
require_once CONFIG_PATH . 'Database.php';

class EstudianteController {

    private $db;
    private $calificacion;
    private $usuario;
    private $matricula;
    private $asistencia;
    private $material;
    private $curso;
    private $tarea;
    private $entrega;
    private $planEstudio;
    private $prerequisito; // <-- NUEVO

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 3) {
            header('Location: index.php?controller=Usuario&action=index');
            exit();
        }
        $database = new Database();
        $this->db = $database->getConnection();
        $this->calificacion = new Calificacion($this->db);
        $this->usuario = new Usuario($this->db);
        $this->matricula = new Matricula($this->db); 
        $this->asistencia = new Asistencia($this->db);
        $this->material = new Material($this->db);
        $this->curso = new Curso($this->db);
        $this->tarea = new Tarea($this->db);
        $this->entrega = new Entrega($this->db);
        $this->planEstudio = new PlanEstudio($this->db);
        $this->prerequisito = new Prerequisito($this->db); // <-- NUEVO
    }

    // --- ACCIONES PRINCIPALES ---
    public function index() {
        $id_estudiante = $_SESSION['id_usuario'];
        $cursosMatriculados = $this->calificacion->readPorEstudiante($id_estudiante);
        $listaTareasProximas = $this->tarea->readTareasProximasPorEstudiante($id_estudiante);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/bienvenida_estudiante.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function panelCurso() {
        if (!isset($_GET['id_curso'])) {
            header('Location: index.php?controller=Estudiante&action=index');
            exit();
        }
        $id_curso = $_GET['id_curso'];
        $infoCurso = $this->curso->readOne($id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/panel_curso.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    // --- ACCIONES DEL PANEL DE CURSO ---
    public function verCalificaciones() {
        if (!isset($_GET['id_curso'])) {
            header('Location: index.php?controller=Estudiante&action=index');
            exit();
        }
        $id_curso = $_GET['id_curso'];
        $id_estudiante = $_SESSION['id_usuario'];
        $infoCurso = $this->curso->readOne($id_curso);
        $datosCalificacion = $this->calificacion->readPorEstudianteYCurso($id_estudiante, $id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/mis_calificaciones.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function verAsistencias() {
        if (!isset($_GET['id_curso'])) {
            header('Location: index.php?controller=Estudiante&action=index');
            exit();
        }
        $id_curso = $_GET['id_curso'];
        $id_estudiante = $_SESSION['id_usuario'];
        $infoCurso = $this->curso->readOne($id_curso);
        $datosAsistencias = $this->asistencia->readPorEstudianteYCurso($id_estudiante, $id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/mis_asistencias.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function verMateriales() {
        if (!isset($_GET['id_curso'])) {
            header('Location: index.php?controller=Estudiante&action=index');
            exit();
        }
        $id_curso = $_GET['id_curso'];
        $infoCurso = $this->curso->readOne($id_curso);
        $listaMateriales = $this->material->readPorCurso($id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/ver_materiales.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }
    
    public function verTareas() {
        if (!isset($_GET['id_curso'])) {
            header('Location: index.php?controller=Estudiante&action=index');
            exit();
        }
        $id_curso = $_GET['id_curso'];
        $id_estudiante = $_SESSION['id_usuario'];
        $infoCurso = $this->curso->readOne($id_curso);
        $listaTareas = $this->tarea->readPorCurso($id_curso);
        $entregasHechas = $this->entrega->readPorEstudianteYCurso($id_estudiante, $id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/ver_tareas.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function entregarTarea() {
        $id_curso = $_POST['id_curso'] ?? 0;
        $id_tarea = $_POST['id_tarea'] ?? 0;
        $id_estudiante = $_SESSION['id_usuario'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id_curso > 0 && $id_tarea > 0 && isset($_FILES['archivo_entrega'])) {
            $archivo = $_FILES['archivo_entrega'];
            if ($archivo['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error_message'] = "Error al subir el archivo (Código: " . $archivo['error'] . ").";
                header('Location: index.php?controller=Estudiante&action=verTareas&id_curso=' . $id_curso);
                exit();
            }
            $carpeta_destino = ROOT_PATH . 'uploads/';
            $nombre_archivo_original = basename($archivo['name']);
            $nombre_archivo_seguro = $id_estudiante . "_" . $id_tarea . "_" . preg_replace("/[^a-zA-Z0-9.\-_]/", "", $nombre_archivo_original);
            $ruta_destino_completa = $carpeta_destino . $nombre_archivo_seguro;
            $ruta_bd = 'uploads/' . $nombre_archivo_seguro;
            if (move_uploaded_file($archivo['tmp_name'], $ruta_destino_completa)) {
                $this->entrega->id_tarea = $id_tarea;
                $this->entrega->id_estudiante = $id_estudiante;
                $this->entrega->nombre_archivo = $nombre_archivo_original;
                $this->entrega->ruta_archivo = $ruta_bd;
                if ($this->entrega->crear()) {
                    $_SESSION['success_message'] = "¡Tarea entregada exitosamente!";
                } else {
                    $_SESSION['error_message'] = "Error al guardar la entrega. Es posible que ya hayas entregado esta tarea.";
                    unlink($ruta_destino_completa);
                }
            } else {
                $_SESSION['error_message'] = "Error: No se pudo mover el archivo a la carpeta de destino.";
            }
        } else {
            $_SESSION['error_message'] = "Error: Solicitud inválida.";
        }
        header('Location: index.php?controller=Estudiante&action=verTareas&id_curso=' . $id_curso);
        exit();
    }

    // --- ACCIÓN DE MATRÍCULA (MODIFICADA) ---
    public function verMatriculas() {
        $id_estudiante = $_SESSION['id_usuario'];
        $id_plan_estudio = $_SESSION['id_plan_estudio'] ?? 0;
        
        $infoPlan = null;
        $cursosPorCiclo = [];
        $historialPromedios = [];
        $reglasPrerequisitos = [];
        
        if ($id_plan_estudio > 0) {
            // 1. Info del Plan
            $infoPlan = $this->planEstudio->readOne($id_plan_estudio);
            
            // 2. Cursos disponibles de la malla
            $cursosPorCiclo = $this->matricula->readCursosDisponiblesPorPlan($id_plan_estudio, $id_estudiante);
            
            // 3. Historial de notas del estudiante (NUEVO)
            $historialPromedios = $this->calificacion->getHistorialPromedios($id_estudiante);
            
            // 4. Reglas de prerrequisito para este plan (NUEVO)
            $reglasPrerequisitos = $this->prerequisito->readPrerequisitosPorPlan($id_plan_estudio);

        } else {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'No estás asignado a ningún Plan de Estudio (Malla). Contacta al administrador.'];
        }

        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/ver_matriculas.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function matricularCurso() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_curso'])) {
            $this->matricula->id_estudiante = $_SESSION['id_usuario'];
            $this->matricula->id_curso = $_POST['id_curso'];
            if ($this->matricula->matricular()) {
                $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => '¡Matrícula exitosa!'];
            } else {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error al matricular (quizás ya estabas inscrito).'];
            }
        }
        header('Location: index.php?controller=Estudiante&action=verMatriculas');
        exit();
    }
    
    // --- ACCIÓN DE REPORTE PDF ---
    public function generarReporte() {
        require_once ROOT_PATH . 'lib/fpdf/fpdf.php';
        $id_estudiante = $_SESSION['id_usuario'];
        $nombreCompleto = $_SESSION['nombre'] . ' ' . $_SESSION['apellido'];
        $datosCalificaciones = $this->calificacion->readPorEstudiante($id_estudiante);
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'BOLETA DE CALIFICACIONES', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Estudiante: ' . iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $nombreCompleto), 0, 1, 'L');
        $pdf->Cell(0, 8, 'Fecha: ' . date('d/m/Y'), 0, 1, 'L');
        $pdf->Ln(10); 
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(230, 230, 230); 
        $pdf->Cell(95, 8, 'Curso', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Nota 1', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Nota 2', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Prom. Tareas', 1, 0, 'C', true);
        $pdf->Cell(35, 8, 'Promedio Final', 1, 1, 'C', true); 
        $pdf->SetFont('Arial', '', 9);
        if (empty($datosCalificaciones)) {
            $pdf->Cell(0, 10, 'No hay calificaciones registradas.', 1, 1, 'C');
        } else {
            foreach ($datosCalificaciones as $fila) {
                $n1 = $fila['nota1'] ?? 0;
                $n2 = $fila['nota2'] ?? 0;
                $n3 = $fila['nota3'] ?? 0;
                $prom = ($n1 + $n2 + $n3) / 3;
                $pdf->Cell(95, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $fila['nombre_curso']), 1);
                $pdf->Cell(20, 7, number_format($n1, 2), 1, 0, 'C');
                $pdf->Cell(20, 7, number_format($n2, 2), 1, 0, 'C');
                $pdf->Cell(20, 7, number_format($n3, 2), 1, 0, 'C');
                $pdf->Cell(35, 7, number_format($prom, 2), 1, 1, 'C');
            }
        }
        $pdf->Output('D', 'Reporte_Calificaciones.pdf');
        exit;
    }
}
?>