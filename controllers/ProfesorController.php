<?php
require_once MODEL_PATH . 'Curso.php';
require_once MODEL_PATH . 'Matricula.php'; 
require_once MODEL_PATH . 'Asistencia.php'; 
require_once MODEL_PATH . 'Material.php';
require_once MODEL_PATH . 'Tarea.php';
require_once MODEL_PATH . 'Entrega.php';
require_once MODEL_PATH . 'Evaluacion.php';
require_once MODEL_PATH . 'Nota.php';
require_once CONFIG_PATH . 'Database.php';
require_once 'utils/HorarioHelper.php';

class ProfesorController {
    private $db; private $curso; private $matricula; private $asistencia; private $material; private $tarea; private $entrega; private $evaluacion; private $nota; private $horarioHelper;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 2) { header('Location: index.php'); exit(); }
        $this->db = (new Database())->getConnection();
        $this->curso = new Curso($this->db); $this->matricula = new Matricula($this->db);
        $this->asistencia = new Asistencia($this->db); $this->material = new Material($this->db);
        $this->tarea = new Tarea($this->db); $this->entrega = new Entrega($this->db);
        $this->evaluacion = new Evaluacion($this->db); $this->nota = new Nota($this->db);
        $this->horarioHelper = new HorarioHelper();
    }

    private function esHoraDeClase($h) {
        if (empty($h)) return false;
        $d = date('N'); $hr = date('G');
        $m = ['Lu' => 1, 'Ma' => 2, 'Mi' => 3, 'Ju' => 4, 'Vi' => 5, 'Sa' => 6, 'Do' => 7];
        $ok = false;
        if (preg_match_all('/(Lu|Ma|Mi|Ju|Vi|Sa|Do) (\d{1,2})-(\d{1,2})/', $h, $mts, PREG_SET_ORDER)) {
            foreach ($mts as $v) if (($m[$v[1]]??0) == $d && $hr >= $v[2] && $hr < $v[3]) $ok = true;
        }
        return $ok;
    }

    public function index() {
        $id = $_SESSION['id_usuario'];
        $cursosAgrupados = $this->curso->readCursosAgrupadosPorProfesor($id);
        $listaEntregasPendientes = $this->entrega->readEntregasRecientesPorProfesor($id);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/bienvenida_profesor.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function panelCurso() {
        if (!isset($_GET['id_curso'])) { header('Location: index.php?controller=Profesor&action=index'); exit(); }
        $id_curso = $_GET['id_curso'];
        $infoCurso = $this->curso->readOne($id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/panel_curso.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function gestionarEvaluaciones() {
        if (!isset($_GET['id_curso'])) { header('Location: index.php?controller=Profesor&action=index'); exit(); }
        $id_curso = $_GET['id_curso'];
        $infoCurso = $this->curso->readOne($id_curso);
        $listaEvaluaciones = $this->evaluacion->readPorCurso($id_curso);
        $porcentajeUsado = 0;
        foreach ($listaEvaluaciones as $eval) $porcentajeUsado += $eval['porcentaje'];
        $porcentajeRestante = 100 - $porcentajeUsado;
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/gestionar_evaluaciones.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }
    
    public function crearEvaluacion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->evaluacion->checkTotalPorcentaje($_POST['id_curso'], $_POST['porcentaje'])) {
                $_SESSION['error_message'] = "Excede 100%.";
            } else {
                $this->evaluacion->id_curso = $_POST['id_curso'];
                $this->evaluacion->nombre = $_POST['nombre'];
                $this->evaluacion->descripcion = $_POST['descripcion'];
                $this->evaluacion->porcentaje = $_POST['porcentaje'];
                $this->evaluacion->crear();
            }
        }
        header('Location: index.php?controller=Profesor&action=gestionarEvaluaciones&id_curso=' . $_POST['id_curso']); exit();
    }
    
    public function eliminarEvaluacion() {
        if (isset($_GET['id_evaluacion'])) {
            $this->evaluacion->id_evaluacion = $_GET['id_evaluacion'];
            $this->evaluacion->delete();
        }
        header('Location: index.php?controller=Profesor&action=gestionarEvaluaciones&id_curso=' . $_GET['id_curso']); exit();
    }
    
    public function libroCalificaciones() {
        if (!isset($_GET['id_curso'])) { header('Location: index.php?controller=Profesor&action=index'); exit(); }
        $id_curso = $_GET['id_curso'];
        $infoCurso = $this->curso->readOne($id_curso);
        $listaEvaluaciones = $this->evaluacion->readPorCurso($id_curso);
        $listaEstudiantes = $this->matricula->readEstudiantesPorCurso($id_curso);
        $notasGuardadas = $this->nota->readNotasParaLibro($id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/libro_calificaciones.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function guardarNotasColumna() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->nota->guardarNotas($_POST['id_evaluacion'], $_POST['notas'] ?? []);
            $_SESSION['success_message'] = "Guardado.";
        }
        header('Location: index.php?controller=Profesor&action=libroCalificaciones&id_curso=' . $_POST['id_curso']); exit();
    }
    
    public function gestionarMateriales() {
        $id_curso = $_GET['id_curso'];
        $infoCurso = $this->curso->readOne($id_curso);
        $listaMateriales = $this->material->readPorCurso($id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/gestionar_materiales.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }
    
    public function subirMaterial() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo_material'])) {
            $archivo = $_FILES['archivo_material'];
            $nombre = time() . "_" . basename($archivo['name']);
            if (move_uploaded_file($archivo['tmp_name'], ROOT_PATH . 'uploads/' . $nombre)) {
                $this->material->id_curso = $_POST['id_curso'];
                $this->material->nombre_archivo = $archivo['name'];
                $this->material->ruta_archivo = 'uploads/' . $nombre;
                $this->material->crear();
            }
        }
        header('Location: index.php?controller=Profesor&action=gestionarMateriales&id_curso=' . $_POST['id_curso']); exit();
    }

    public function eliminarMaterial() {
        $id = $_GET['id_material'];
        $m = $this->material->readOne($id);
        if($m) {
            $this->material->id_material = $id;
            $this->material->delete();
            if(file_exists(ROOT_PATH . $m['ruta_archivo'])) unlink(ROOT_PATH . $m['ruta_archivo']);
        }
        header('Location: index.php?controller=Profesor&action=gestionarMateriales&id_curso=' . $_GET['id_curso']); exit();
    }

    public function gestionarTareas() {
        $id_curso = $_GET['id_curso'];
        $infoCurso = $this->curso->readOne($id_curso);
        $listaTareas = $this->tarea->readPorCurso($id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/gestionar_tareas.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function crearTarea() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->tarea->id_curso = $_POST['id_curso'];
            $this->tarea->titulo = $_POST['titulo'];
            $this->tarea->descripcion = $_POST['descripcion'];
            $this->tarea->fecha_limite = $_POST['fecha_limite'];
            $this->tarea->crear();
        }
        header('Location: index.php?controller=Profesor&action=gestionarTareas&id_curso=' . $_POST['id_curso']); exit();
    }

    public function eliminarTarea() {
        $this->tarea->id_tarea = $_GET['id_tarea'];
        $this->tarea->delete();
        header('Location: index.php?controller=Profesor&action=gestionarTareas&id_curso=' . $_GET['id_curso']); exit();
    }
    
    public function verEntregas() {
        $id_tarea = $_GET['id_tarea'];
        $infoTarea = $this->tarea->readOne($id_tarea);
        $listaEntregas = $this->entrega->readPorTarea($id_tarea);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/ver_entregas.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function calificarEntrega() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->entrega->id_entrega = $_POST['id_entrega'];
            $this->entrega->calificacion = $_POST['calificacion'];
            $this->entrega->comentario_profesor = $_POST['comentario'];
            $this->entrega->calificar();
        }
        header('Location: index.php?controller=Profesor&action=verEntregas&id_tarea=' . $_POST['id_tarea']); exit();
    }

    // --- AQUÍ ESTABA EL ERROR DE VARIABLE ---
    public function tomarAsistencia() {
        $id_curso = $_GET['id_curso'] ?? $_POST['id_curso'];
        $fecha = $_POST['fecha_asistencia'] ?? date('Y-m-d');
        
        // CORREGIDO: Usamos $infoCurso para coincidir con la vista
        $infoCurso = $this->curso->readOne($id_curso);
        
        $estudiantes = $this->matricula->readEstudiantesPorCurso($id_curso);
        $asistencia_tomada = $this->asistencia->checkAsistenciaTomada($id_curso, $fecha);
        
        $asistenciaBloqueada = false;
        $mensajeBloqueo = "";

        if ($fecha != date('Y-m-d')) {
            $asistenciaBloqueada = true;
            $mensajeBloqueo = "Solo se puede tomar asistencia de HOY.";
        } elseif (!$this->esHoraDeClase($infoCurso['horario'])) {
            $asistenciaBloqueada = true;
            $mensajeBloqueo = "No es hora de clase (" . ($infoCurso['horario'] ?? 'Sin horario') . ")";
        }
        
        $registros_asistencia = [];
        if ($asistencia_tomada) {
            $registros_asistencia = $this->asistencia->readAsistenciaPorFecha($id_curso, $fecha);
        }

        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/tomar_asistencia.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function guardarAsistencia() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->asistencia->guardarAsistencia($_POST['id_curso'], $_POST['fecha_asistencia'], $_POST['asistencia']);
            $_SESSION['success_message'] = "Asistencia guardada.";
        }
        header('Location: index.php?controller=Profesor&action=tomarAsistencia&id_curso=' . $_POST['id_curso']); exit();
    }

    public function verHistoricoAsistencia() {
        $id_curso = $_GET['id_curso'];
        
        // CORREGIDO: Usamos $infoCurso para coincidir con la vista
        $infoCurso = $this->curso->readOne($id_curso);
        
        $historico = $this->asistencia->readHistoricoPorCurso($id_curso);
        $datos_por_estudiante = []; $fechas = [];
        foreach ($historico as $reg) {
            $nombre = $reg['apellido'] . ', ' . $reg['nombre'];
            $datos_por_estudiante[$nombre][$reg['fecha']] = $reg['estado'];
            if (!in_array($reg['fecha'], $fechas)) $fechas[] = $reg['fecha'];
        }
        usort($fechas, function($a,$b){ return strtotime($a)-strtotime($b); });
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/ver_historico_asistencia.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }
}
?>