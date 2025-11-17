<?php
/*
 * Archivo: views/profesor/panel_curso.php
 * (CORREGIDO: 'class.' y botón "Volver" estandarizado)
 */
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2>Panel de Gestión:</h2>
            <h3 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h3>
        </div>
        
        <a href="index.php?controller=Profesor&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver
        </a>
    </div>
    <p class="lead">Seleccione una opción para administrar su curso.</p>
    <hr>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><i class="fas fa-book-reader me-2 text-primary"></i> Libro de Calificaciones</h5>
                    <p class="card-text">Gestione las notas (Nota 1, Nota 2) y sincronice el promedio de tareas.</p>
                    <a href="index.php?controller=Profesor&action=libroCalificaciones&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-primary mt-auto">Gestionar Notas</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><i class="fas fa-user-check me-2 text-success"></i> Control de Asistencia</h5>
                    <p class="card-text">Tome la asistencia del día o consulte el historial de asistencias del curso.</p>
                    <div class="mt-auto">
                        <a href="index.php?controller=Profesor&action=tomarAsistencia&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-success">Tomar Asistencia</a>
                        <a href="index.php?controller=Profesor&action=verHistoricoAsistencia&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-outline-secondary ms-2">Ver Historial</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><i class="fas fa-folder-open me-2 text-info"></i> Material de Clase</h5>
                    <p class="card-text">Suba y gestione los archivos (PDF, PPT, etc.) para sus estudiantes.</p>
                    <a href="index.php?controller=Profesor&action=gestionarMateriales&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-info text-white mt-auto">Gestionar Materiales</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><i class="fas fa-tasks me-2 text-warning"></i> Tareas y Entregas</h5>
                    <p class="card-text">Cree nuevas tareas y califique las entregas de sus estudiantes.</p>
                    <a href="index.php?controller=Profesor&action=gestionarTareas&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-warning mt-auto">Gestionar Tareas</a>
                </div>
            </div>
        </div>
    </div>
</div>