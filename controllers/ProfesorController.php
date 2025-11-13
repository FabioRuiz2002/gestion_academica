<?php
/*
 * Archivo: controllers/ProfesorController.php
 * Propósito: Controlador para el panel del Profesor.
 * (Corregido 'this->' en guardarCalificaciones y tomarAsistencia)
 */

require_once MODEL_PATH . 'Curso.php';
require_once MODEL_PATH . 'Matricula.php'; 
require_once MODEL_PATH . 'Calificacion.php';
require_once MODEL_PATH . 'Asistencia.php'; 
require_once CONFIG_PATH . 'Database.php';

class ProfesorController {

    private $db;
    private $curso;
    private $matricula;
    private $calificacion;
    private $asistencia;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 2) {
            header('Location: index.php?controller=Usuario&action=index');
            exit();
        }
        $database = new Database();
        $this->db = $database->getConnection();
        $this->curso = new Curso($this->db);
        $this->matricula = new Matricula($this->db);
        $this->calificacion = new Calificacion($this->db);
        $this->asistencia = new Asistencia($this->db);
    }

    public function index() {
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/bienvenida_profesor.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    // --- ACCIONES DE CALIFICACIONES ---
    public function verCursosCalificaciones() {
        $id_profesor = $_SESSION['id_usuario'];
        $cursos = $this->curso->readPorProfesor($id_profesor); 
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/seleccionar_curso_calificaciones.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function verCurso() {
        if (!isset($_GET['id_curso'])) {
            header('Location: index.php?controller=Profesor&action=index');
            exit();
        }
        $id_curso = $_GET['id_curso'];
        $this->curso->id_curso = $id_curso;
        $infoCurso = $this->curso->readOne($id_curso);
        $listaEstudiantes = $this->matricula->readEstudiantesPorCurso($id_curso);
        $calificaciones = $this->calificacion->readPorCurso($id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/ver_curso.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function guardarCalificaciones() {
        $id_curso = $_POST['id_curso'] ?? 0;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id_curso > 0 && isset($_POST['calificaciones'])) {
            $calificaciones_post = $_POST['calificaciones'];
            foreach ($calificaciones_post as $id_estudiante => $notas) {
                // --- ¡CORREGIDO! ---
                $this->calificacion->id_curso = $id_curso;
                $this->calificacion->id_estudiante = $id_estudiante; 
                $this->calificacion->nota1 = $notas['nota1'] ?? 0;
                $this->calificacion->nota2 = $notas['nota2'] ?? 0;
                $this->calificacion->nota3 = $notas['nota3'] ?? 0;
                $this->calificacion->guardar();
            }
            $_SESSION['success_message'] = "¡Calificaciones guardadas exitosamente!";
        } else {
            $_SESSION['error_message'] = "Error: No se recibieron datos válidos.";
        }
        header('Location: index.php?controller=Profesor&action=verCurso&id_curso=' . $id_curso);
        exit();
    }

    // --- ACCIONES DE ASISTENCIA ---
    public function verCursosAsistencia() {
        $id_profesor = $_SESSION['id_usuario'];
        $cursos = $this->curso->readPorProfesor($id_profesor); 
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/seleccionar_curso_asistencia.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function tomarAsistencia() {
        $id_curso = isset($_GET['id_curso']) ? $_GET['id_curso'] : (isset($_POST['id_curso']) ? $_POST['id_curso'] : null);
        $fecha = isset($_POST['fecha_asistencia']) ? $_POST['fecha_asistencia'] : date('Y-m-d');
        if (!$id_curso) {
             header('Location: index.php?controller=Profesor&action=verCursosAsistencia');
             exit();
        }
        $curso_info = $this->curso->readOne($id_curso);
        $estudiantes = $this->matricula->readEstudiantesPorCurso($id_curso);
        // --- ¡CORREGIDO! ---
        $asistencia_tomada = $this->asistencia->checkAsistenciaTomada($id_curso, $fecha);
        $registros_asistencia = [];
        if ($asistencia_tomada) {
            $registros_asistencia = $this->asistencia->readAsistenciaPorFecha($id_curso, $fecha);
            $_SESSION['mensaje'] = ['tipo' => 'warning', 'texto' => "La asistencia para el curso <b>{$curso_info['nombre_curso']}</b> en la fecha <b>{$fecha}</b> ya fue registrada."];
        }
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/tomar_asistencia.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function guardarAsistencia() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_curso']) && isset($_POST['fecha_asistencia']) && isset($_POST['asistencia'])) {
            $id_curso = $_POST['id_curso'];
            $fecha = $_POST['fecha_asistencia'];
            $asistencias = $_POST['asistencia'];
            if ($this->asistencia->checkAsistenciaTomada($id_curso, $fecha)) {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: La asistencia para esta fecha ya fue registrada anteriormente.'];
            } 
            else if ($this->asistencia->guardarAsistencia($id_curso, $fecha, $asistencias)) {
                $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Asistencia guardada exitosamente.'];
            } else {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Hubo un error al guardar la asistencia. Intente nuevamente.'];
            }
            header("Location: index.php?controller=Profesor&action=tomarAsistencia&id_curso={$id_curso}");
            exit();
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Solicitud inválida.'];
            header('Location: index.php?controller=Profesor&action=index');
            exit();
        }
    }
    
    public function verHistoricoAsistencia() {
        $id_curso = isset($_GET['id_curso']) ? $_GET['id_curso'] : null;
        if (!$id_curso) {
            header('Location: index.php?controller=Profesor&action=verCursosAsistencia');
            exit();
        }
        $curso_info = $this->curso->readOne($id_curso);
        $historico = $this->asistencia->readHistoricoPorCurso($id_curso);
        $datos_por_estudiante = [];
        $fechas = [];
        foreach ($historico as $registro) {
            $nombre_completo = $registro['apellido'] . ', ' . $registro['nombre'];
            $fecha = $registro['fecha'];
            if (!isset($datos_por_estudiante[$nombre_completo])) {
                $datos_por_estudiante[$nombre_completo] = [];
            }
            $datos_por_estudiante[$nombre_completo][$fecha] = $registro['estado'];
            if (!in_array($fecha, $fechas)) {
                $fechas[] = $fecha;
            }
        }
        usort($fechas, function($a, $b) {
            return strtotime($a) - strtotime($b);
        });
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/ver_historico_asistencia.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }
}
?>