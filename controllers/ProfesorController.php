<?php
/*
 * Archivo: controllers/ProfesorController.php
 * (CORREGIDO: Errores de sintaxis)
 * (ACTUALIZADO: 'index' usa cursos agrupados)
 */

require_once MODEL_PATH . 'Curso.php';
require_once MODEL_PATH . 'Matricula.php'; 
require_once MODEL_PATH . 'Calificacion.php';
require_once MODEL_PATH . 'Asistencia.php'; 
require_once MODEL_PATH . 'Material.php';
require_once MODEL_PATH . 'Tarea.php';
require_once MODEL_PATH . 'Entrega.php';
require_once CONFIG_PATH . 'Database.php';

class ProfesorController {

    private $db;
    private $curso;
    private $matricula;
    private $calificacion;
    private $asistencia;
    private $material;
    private $tarea;
    private $entrega;

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
        $this->material = new Material($this->db);
        $this->tarea = new Tarea($this->db);
        $this->entrega = new Entrega($this->db);
    }

    /**
     * ACCIÓN PRINCIPAL (MODIFICADA)
     */
    public function index() {
        $id_profesor = $_SESSION['id_usuario'];
        
        // 1. Cargar cursos (NUEVA FUNCIÓN)
        $cursosAgrupados = $this->curso->readCursosAgrupadosPorProfesor($id_profesor);
        
        // 2. Cargar entregas pendientes (Widget)
        $listaEntregasPendientes = $this->entrega->readEntregasRecientesPorProfesor($id_profesor);
        
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/bienvenida_profesor.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function panelCurso() {
        if (!isset($_GET['id_curso'])) {
            header('Location: index.php?controller=Profesor&action=index');
            exit();
        }
        $id_curso = $_GET['id_curso'];
        $infoCurso = $this->curso->readOne($id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/panel_curso.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    // -------------------------------------------------------------------
    // --- ACCIONES DE LIBRO DE CALIFICACIONES ---
    // -------------------------------------------------------------------

    public function libroCalificaciones() {
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
        require_once VIEW_PATH . 'profesor/libro_calificaciones.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function guardarCalificaciones() {
        $id_curso = $_POST['id_curso'] ?? 0;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id_curso > 0 && isset($_POST['calificaciones'])) {
            $calificaciones_post = $_POST['calificaciones'];
            
            foreach ($calificaciones_post as $id_estudiante => $notas) {
                $this->calificacion->id_curso = $id_curso;
                $this->calificacion->id_estudiante = $id_estudiante; 
                $this->calificacion->nota1 = $notas['nota1'] ?? 0;
                $this->calificacion->nota2 = $notas['nota2'] ?? 0;
                
                $this->calificacion->guardar();
            }
            $_SESSION['success_message'] = "¡Notas 1 y 2 guardadas exitosamente!";
        } else {
            $_SESSION['error_message'] = "Error: No se recibieron datos válidos.";
        }
        header('Location: index.php?controller=Profesor&action=libroCalificaciones&id_curso=' . $id_curso);
        exit();
    }

    public function sincronizarTareas() {
        $id_curso = $_GET['id_curso'] ?? 0;
        if ($id_curso > 0) {
            if ($this->calificacion->sincronizarPromedioTareas($id_curso, $this->entrega)) {
                $_SESSION['success_message'] = "¡Promedio de Tareas sincronizado exitosamente!";
            } else {
                $_SESSION['error_message'] = "Error al sincronizar las notas de las tareas.";
            }
        } else {
            $_SESSION['error_message'] = "Error: Curso no válido.";
        }
        header('Location: index.php?controller=Profesor&action=libroCalificaciones&id_curso=' . $id_curso);
        exit();
    }

    // -------------------------------------------------------------------
    // --- ACCIONES DE ASISTENCIA ---
    // -------------------------------------------------------------------
    
    public function tomarAsistencia() {
        $id_curso = isset($_GET['id_curso']) ? $_GET['id_curso'] : (isset($_POST['id_curso']) ? $_POST['id_curso'] : null);
        $fecha = isset($_POST['fecha_asistencia']) ? $_POST['fecha_asistencia'] : date('Y-m-d');
        if (!$id_curso) {
             header('Location: index.php?controller=Profesor&action=index');
             exit();
        }
        $curso_info = $this->curso->readOne($id_curso);
        $estudiantes = $this->matricula->readEstudiantesPorCurso($id_curso);
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
            header('Location: index.php?controller=Profesor&action=index');
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

    // -------------------------------------------------------------------
    // --- ACCIONES DE MATERIALES ---
    // -------------------------------------------------------------------
    
    public function gestionarMateriales() {
        if (!isset($_GET['id_curso'])) {
            header('Location: index.php?controller=Profesor&action=index');
            exit();
        }
        $id_curso = $_GET['id_curso'];
        $infoCurso = $this->curso->readOne($id_curso);
        $listaMateriales = $this->material->readPorCurso($id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/gestionar_materiales.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function subirMaterial() {
        $id_curso = $_POST['id_curso'] ?? 0;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id_curso > 0 && isset($_FILES['archivo_material'])) {
            $archivo = $_FILES['archivo_material'];
            if ($archivo['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error_message'] = "Error al subir el archivo (Código: " . $archivo['error'] . ").";
                header('Location: index.php?controller=Profesor&action=gestionarMateriales&id_curso=' . $id_curso);
                exit();
            }
            $carpeta_destino = ROOT_PATH . 'uploads/';
            $nombre_archivo_original = basename($archivo['name']);
            $nombre_archivo_seguro = time() . "_" . preg_replace("/[^a-zA-Z0-9.\-_]/", "", $nombre_archivo_original);
            $ruta_destino_completa = $carpeta_destino . $nombre_archivo_seguro;
            $ruta_bd = 'uploads/' . $nombre_archivo_seguro; 
            if (move_uploaded_file($archivo['tmp_name'], $ruta_destino_completa)) {
                $this->material->id_curso = $id_curso;
                $this->material->nombre_archivo = $nombre_archivo_original; 
                $this->material->ruta_archivo = $ruta_bd; 
                if ($this->material->crear()) {
                    $_SESSION['success_message'] = "¡Archivo subido exitosamente!";
                } else {
                    $_SESSION['error_message'] = "Error al guardar el registro en la base de datos.";
                    unlink($ruta_destino_completa); 
                }
            } else {
                $_SESSION['error_message'] = "Error: No se pudo mover el archivo a la carpeta de destino.";
            }
        } else {
            $_SESSION['error_message'] = "Error: Solicitud inválida.";
        }
        header('Location: index.php?controller=Profesor&action=gestionarMateriales&id_curso=' . $id_curso);
        exit();
    }

    public function eliminarMaterial() {
        $id_material = $_GET['id_material'] ?? 0;
        $id_curso = $_GET['id_curso'] ?? 0;
        if ($id_material > 0 && $id_curso > 0) {
            $material = $this->material->readOne($id_material);
            if ($material) {
                $this->material->id_material = $id_material;
                if ($this->material->delete()) {
                    $ruta_fisica = ROOT_PATH . $material['ruta_archivo'];
                    if (file_exists($ruta_fisica)) {
                        unlink($ruta_fisica);
                    }
                    $_SESSION['success_message'] = "Material eliminado exitosamente.";
                } else {
                    $_SESSION['error_message'] = "Error al eliminar el registro de la BD.";
                }
            } else {
                $_SESSION['error_message'] = "Error: No se encontró el material.";
            }
        } else {
            $_SESSION['error_message'] = "Error: Petición inválida.";
        }
        header('Location: index.php?controller=Profesor&action=gestionarMateriales&id_curso=' . $id_curso);
        exit();
    }

    // -------------------------------------------------------------------
    // --- ACCIONES DE TAREAS ---
    // -------------------------------------------------------------------

    public function gestionarTareas() {
        if (!isset($_GET['id_curso'])) {
            header('Location: index.php?controller=Profesor&action=index');
            exit();
        }
        $id_curso = $_GET['id_curso'];
        $infoCurso = $this->curso->readOne($id_curso);
        $listaTareas = $this->tarea->readPorCurso($id_curso);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/gestionar_tareas.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function crearTarea() {
        $id_curso = $_POST['id_curso'] ?? 0;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id_curso > 0 && !empty($_POST['titulo'])) {
            $this->tarea->id_curso = $id_curso;
            $this->tarea->titulo = $_POST['titulo'];
            $this->tarea->descripcion = $_POST['descripcion'];
            $this->tarea->fecha_limite = !empty($_POST['fecha_limite']) ? $_POST['fecha_limite'] : null;
            if ($this->tarea->crear()) {
                $_SESSION['success_message'] = "Tarea creada exitosamente.";
            } else {
                $_SESSION['error_message'] = "Error al crear la tarea.";
            }
        } else {
            $_SESSION['error_message'] = "Error: El título es obligatorio.";
        }
        header('Location: index.php?controller=Profesor&action=gestionarTareas&id_curso=' . $id_curso);
        exit();
    }

    public function eliminarTarea() {
        $id_tarea = $_GET['id_tarea'] ?? 0;
        $id_curso = $_GET['id_curso'] ?? 0;
        if ($id_tarea > 0 && $id_curso > 0) {
            $this->tarea->id_tarea = $id_tarea;
            if ($this->tarea->delete()) {
                $_SESSION['success_message'] = "Tarea eliminada exitosamente. Todas las entregas asociadas han sido borradas.";
            } else {
                $_SESSION['error_message'] = "Error al eliminar la tarea.";
            }
        } else {
            $_SESSION['error_message'] = "Error: Petición inválida.";
        }
        header('Location: index.php?controller=Profesor&action=gestionarTareas&id_curso=' . $id_curso);
        exit();
    }

    public function verEntregas() {
        if (!isset($_GET['id_tarea'])) {
            header('Location: index.php?controller=Profesor&action=index');
            exit();
        }
        $id_tarea = $_GET['id_tarea'];
        $infoTarea = $this->tarea->readOne($id_tarea);
        if (!$infoTarea) {
            header('Location: index.php?controller=Profesor&action=index');
            exit();
        }
        $listaEntregas = $this->entrega->readPorTarea($id_tarea);
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'profesor/ver_entregas.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }
    
    // -------------------------------------------------------------------
    // --- ACCIÓN DE CALIFICAR TAREAS ---
    // -------------------------------------------------------------------
    
    public function calificarEntrega() {
        $id_tarea = $_POST['id_tarea'] ?? 0;
        $id_entrega = $_POST['id_entrega'] ?? 0;

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id_tarea > 0 && $id_entrega > 0) {
            
            $this->entrega->id_entrega = $id_entrega;
            $this->entrega->calificacion = $_POST['calificacion'] ?? null;
            $this->entrega->comentario_profesor = $_POST['comentario'] ?? null;

            if ($this->entrega->calificar()) {
                $_SESSION['success_message'] = "Calificación guardada exitosamente.";
            } else {
                $_SESSION['error_message'] = "Error al guardar la calificación.";
            }
        } else {
            $_SESSION['error_message'] = "Error: Petición inválida.";
        }
        
        header('Location: index.php?controller=Profesor&action=verEntregas&id_tarea=' . $id_tarea);
        exit();
    }
}
?>