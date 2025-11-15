<?php 
/*
 * Archivo: views/estudiante/panel_curso.php
 * Propósito: Panel de gestión para un curso específico (vista de estudiante).
 * (Añadida la tarjeta 'Entregar Tareas')
 */
?>
<div class="container mt-4">
    <h1 class="mt-4">Detalle del Curso</h1>
    <h2 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h2>
    <p class="lead">
        Horario: <?php echo htmlspecialchars($infoCurso['horario'] ?? 'No definido'); ?>
    </p>
    <hr>
    <div class="row mt-5">
        
        <div class="col-md-3 mb-4">
            <div class="card shadow-lg p-3 text-center border-primary h-100">
                <i class="fas fa-chart-bar fa-4x text-primary mb-3"></i>
                <h5>Mis Calificaciones</h5>
                <a href="index.php?controller=Estudiante&action=verCalificaciones&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-primary btn-sm mt-auto">
                    Ver Notas
                </a>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-lg p-3 text-center border-info h-100">
                <i class="fas fa-calendar-check fa-4x text-info mb-3"></i>
                <h5>Mi Asistencia</h5>
                <a href="index.php?controller=Estudiante&action=verAsistencias&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-info btn-sm mt-auto text-white">
                    Ver Asistencia
                </a>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card shadow-lg p-3 text-center border-success h-100">
                <i class="fas fa-file-download fa-4x text-success mb-3"></i>
                <h5>Material de Clase</h5>
                <a href="index.php?controller=Estudiante&action=verMateriales&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-success btn-sm mt-auto">
                    Ver Materiales
                </a>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-lg p-3 text-center border-warning h-100">
                <i class="fas fa-tasks fa-4x text-warning mb-3"></i>
                <h5>Entregar Tareas</h5>
                <a href="index.php?controller=Estudiante&action=verTareas&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-warning btn-sm mt-auto text-dark">
                    Ver Tareas
                </a>
            </div>
        </div>

    </div>
    
    <div class="mt-3">
        <a href="index.php?controller=Estudiante&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver a Mis Cursos
        </a>
    </div>
</div>