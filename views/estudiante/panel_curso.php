<?php
/*
 * Archivo: views/estudiante/panel_curso.php
 * (Añadido botón "Volver" estandarizado)
 */
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2>Panel del Curso:</h2>
            <h3 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h3>
        </div>
        
        <a href="index.php?controller=Estudiante&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver
        </a>
    </div>
    <p class="lead">Revisa tus calificaciones, asistencias y materiales.</p>
    <hr>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><i class="fas fa-clipboard-check me-2 text-primary"></i> Mis Calificaciones</h5>
                    <p class="card-text">Consulta tus notas, promedios y el detalle de tus calificaciones.</p>
                    <a href="index.php?controller=Estudiante&action=verCalificaciones&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-primary mt-auto">Ver Notas</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><i class="fas fa-user-check me-2 text-success"></i> Mis Asistencias</h5>
                    <p class="card-text">Revisa tu historial de asistencias, faltas y tardanzas.</p>
                    <a href="index.php?controller=Estudiante&action=verAsistencias&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-success mt-auto">Ver Asistencias</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><i class="fas fa-folder-open me-2 text-info"></i> Material de Clase</h5>
                    <p class="card-text">Descarga los archivos (PDF, PPT, etc.) subidos por tu profesor.</p>
                    <a href="index.php?controller=Estudiante&action=verMateriales&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-info text-white mt-auto">Ver Materiales</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><i class="fas fa-tasks me-2 text-warning"></i> Tareas y Entregas</h5>
                    <p class="card-text">Revisa las tareas asignadas, sube tus archivos y mira tus notas de tareas.</p>
                    <a href="index.php?controller=Estudiante&action=verTareas&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-warning mt-auto">Ver Tareas</a>
                </div>
            </div>
        </div>
    </div>
</div>