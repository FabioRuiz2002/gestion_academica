<?php
/*
 * Archivo: controllers/EstudianteController.php
 * Propósito: Controlador para el panel del Estudiante.
 * (Añadido módulo de 'generarReporte' con FPDF)
 */
require_once MODEL_PATH . 'Calificacion.php';
require_once MODEL_PATH . 'Matricula.php'; 
require_once MODEL_PATH . 'Usuario.php'; 
require_once MODEL_PATH . 'Asistencia.php';
require_once CONFIG_PATH . 'Database.php';

// Importante: Cargar la librería FPDF
// La cargaremos dentro de la función que la necesita.

class EstudianteController {

    private $db;
    private $calificacion;
    private $usuario;
    private $matricula;
    private $asistencia;

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
    }

    public function index() {
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/bienvenida_estudiante.php'; 
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function verCalificaciones() {
        $id_estudiante = $_SESSION['id_usuario'];
        $datosCalificaciones = $this->calificacion->readPorEstudiante($id_estudiante);
        require_once VIEW_PATH . 'layouts/header.php';
        $archivoVista = VIEW_PATH . 'estudiante/mis_calificaciones.php'; 
        if (file_exists($archivoVista)) {
            require_once $archivoVista;
        } else {
            echo "<div class='container mt-5 alert alert-danger'>Error: No se encuentra el archivo <b>$archivoVista</b>.</div>";
        }
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    public function verMatriculas() {
        $id_estudiante = $_SESSION['id_usuario'];
        $cursosMatriculados = $this->calificacion->readPorEstudiante($id_estudiante); 
        $cursosDisponibles = $this->matricula->readCursosDisponibles($id_estudiante);
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

    public function verAsistencias() {
        $id_estudiante = $_SESSION['id_usuario'];
        $datosAsistencias = $this->asistencia->readPorEstudiante($id_estudiante);
        $asistencias_agrupadas = [];
        foreach ($datosAsistencias as $reg) {
            $asistencias_agrupadas[$reg['nombre_curso']][] = $reg;
        }
        require_once VIEW_PATH . 'layouts/header.php';
        require_once VIEW_PATH . 'estudiante/mis_asistencias.php';
        require_once VIEW_PATH . 'layouts/footer.php';
    }

    /**
     * NUEVA ACCIÓN: Genera un Reporte PDF de Calificaciones
     */
    public function generarReporte() {
        // 1. Cargar la librería FPDF
        // Usamos ROOT_PATH de config.php para encontrar la librería
        require_once ROOT_PATH . 'lib/fpdf/fpdf.php';

        // 2. Obtener datos del estudiante de la sesión y la BD
        $id_estudiante = $_SESSION['id_usuario'];
        $nombreCompleto = $_SESSION['nombre'] . ' ' . $_SESSION['apellido'];
        $datosCalificaciones = $this->calificacion->readPorEstudiante($id_estudiante);

        // 3. Crear el objeto PDF
        // FPDF('P' = Portrait/Vertical, 'mm' = milímetros, 'A4' = tamaño de hoja)
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        
        // --- Encabezado del PDF ---
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'BOLETA DE CALIFICACIONES', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        // Usamos utf8_decode para manejar tildes y 'ñ'
        $pdf->Cell(0, 10, 'Estudiante: ' . utf8_decode($nombreCompleto), 0, 1, 'L');
        $pdf->Cell(0, 8, 'Fecha: ' . date('d/m/Y'), 0, 1, 'L');
        $pdf->Ln(10); // Añadir un salto de línea

        // --- Cabecera de la Tabla ---
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(230, 230, 230); // Fondo gris claro
        $pdf->Cell(95, 8, 'Curso', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Nota 1', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Nota 2', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Nota 3', 1, 0, 'C', true);
        $pdf->Cell(35, 8, 'Promedio Final', 1, 1, 'C', true); // 1 al final = salto de línea

        // --- Contenido de la Tabla ---
        $pdf->SetFont('Arial', '', 9);
        
        if (empty($datosCalificaciones)) {
            $pdf->Cell(0, 10, 'No hay calificaciones registradas.', 1, 1, 'C');
        } else {
            foreach ($datosCalificaciones as $fila) {
                $n1 = $fila['nota1'] ?? 0;
                $n2 = $fila['nota2'] ?? 0;
                $n3 = $fila['nota3'] ?? 0;
                $prom = ($n1 + $n2 + $n3) / 3;
                
                $pdf->Cell(95, 7, utf8_decode($fila['nombre_curso']), 1);
                $pdf->Cell(20, 7, number_format($n1, 2), 1, 0, 'C');
                $pdf->Cell(20, 7, number_format($n2, 2), 1, 0, 'C');
                $pdf->Cell(20, 7, number_format($n3, 2), 1, 0, 'C');
                $pdf->Cell(35, 7, number_format($prom, 2), 1, 1, 'C');
            }
        }
        
        // 4. Salida del PDF
        // 'D' fuerza la descarga del archivo con el nombre 'Reporte_Calificaciones.pdf'
        $pdf->Output('D', 'Reporte_Calificaciones.pdf');
        exit; // Es importante salir después de generar el PDF
    }
}
?>