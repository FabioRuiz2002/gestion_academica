<?php 
/*
 * Archivo: views/profesor/bienvenida_profesor.php
 * Propósito: Vista de bienvenida para el Profesor con acceso a módulos.
 */
?>
<div class="container mt-4">
    <h1 class="mt-4">Panel del Profesor</h1>
    <p class="lead">
        Bienvenido(a), <b><?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']); ?></b>. 
        Selecciona una tarea para comenzar.
    </p>
    <hr>
    <div class="row mt-5">
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg p-4 text-center border-primary h-100">
                <i class="fas fa-edit fa-5x text-primary mb-3"></i>
                <h2>Registrar Calificaciones</h2>
                <p>Accede a tus cursos para ingresar o modificar las notas de tus estudiantes.</p>
                <a href="index.php?controller=Profesor&action=verCursosCalificaciones" class="btn btn-primary btn-lg mt-auto">
                    <i class="fas fa-list-ol me-2"></i> Seleccionar Curso
                </a>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg p-4 text-center border-info h-100">
                <i class="fas fa-calendar-check fa-5x text-info mb-3"></i>
                <h2>Tomar Asistencia</h2>
                <p>Registra la presencia o ausencia de tus estudiantes en la clase del día.</p>
                <a href="index.php?controller=Profesor&action=verCursosAsistencia" class="btn btn-info btn-lg mt-auto text-white">
                    <i class="fas fa-list me-2"></i> Seleccionar Curso
                </a>
            </div>
        </div>
    </div>
</div>