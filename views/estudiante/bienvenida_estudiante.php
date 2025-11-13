<?php 
/*
 * Archivo: views/estudiante/bienvenida_estudiante.php
 * (Añadida la tarjeta de Asistencias)
 */
?>
<div class="container mt-4">
    <h1 class="mt-4">Bienvenido(a) a tu Panel de Estudiante</h1>
    <p class="lead">
        Hola, <b><?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']); ?></b>. 
        Utiliza la navegación superior o las tarjetas a continuación.
    </p>
    <div class="row mt-5">
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-lg p-4 text-center border-primary h-100">
                <i class="fas fa-chart-bar fa-5x text-primary mb-3"></i>
                <h2>Consulta tus Notas</h2>
                <p>Haz clic para ver el detalle de todas tus asignaturas y calificaciones registradas.</p>
                <a href="index.php?controller=Estudiante&action=verCalificaciones" class="btn btn-primary btn-lg mt-auto">
                    <i class="fas fa-list-alt me-2"></i> Ver Mis Calificaciones
                </a>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-lg p-4 text-center border-success h-100">
                <i class="fas fa-user-plus fa-5x text-success mb-3"></i>
                <h2>Gestionar Matrícula</h2>
                <p>Matricúlate en nuevos cursos o revisa tu lista actual de asignaturas inscritas.</p>
                <a href="index.php?controller=Estudiante&action=verMatriculas" class="btn btn-success btn-lg mt-auto">
                    <i class="fas fa-book me-2"></i> Matricular Cursos
                </a>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-lg p-4 text-center border-info h-100">
                <i class="fas fa-calendar-check fa-5x text-info mb-3"></i>
                <h2>Mis Asistencias</h2>
                <p>Revisa tu récord de asistencias (presentes, ausentes y tardanzas) en tus cursos.</p>
                <a href="index.php?controller=Estudiante&action=verAsistencias" class="btn btn-info btn-lg mt-auto text-white">
                    <i class="fas fa-calendar-alt me-2"></i> Ver Mis Asistencias
                </a>
            </div>
        </div>

    </div>
</div>