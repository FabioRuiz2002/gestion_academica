<?php 
/*
 * Archivo: views/profesor/panel_curso.php
 * (Actualizado enlace a 'libroCalificaciones')
 */
?>
<div class="container mt-4">
    <h1 class="mt-4">Gestionar Curso</h1>
    <h2 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h2>
    <p class="lead">
        Selecciona la tarea que deseas realizar para este curso.
    </p>
    <hr>
    <div class="row mt-5">
        
        <div class="col-md-3 mb-4">
            <div class="card shadow-lg p-3 text-center border-primary h-100">
                <i class="fas fa-book-open fa-4x text-primary mb-3"></i>
                <h5>Libro de Calificaciones</h5>
                <a href="index.php?controller=Profesor&action=libroCalificaciones&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-primary btn-sm mt-auto">
                    Ir al Libro
                </a>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-lg p-3 text-center border-info h-100">
                <i class="fas fa-calendar-check fa-4x text-info mb-3"></i>
                <h5>Gestionar Asistencia</h5>
                <a href="index.php?controller=Profesor&action=tomarAsistencia&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-info btn-sm mt-auto text-white">
                    Ir a Asistencia
                </a>
                <a href="index.php?controller=Profesor&action=verHistoricoAsistencia&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-link btn-sm mt-2">
                    Ver Historial
                </a>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card shadow-lg p-3 text-center border-success h-100">
                <i class="fas fa-file-upload fa-4x text-success mb-3"></i>
                <h5>Gestionar Materiales</h5>
                <a href="index.php?controller=Profesor&action=gestionarMateriales&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-success btn-sm mt-auto">
                    Ir a Materiales
                </a>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-lg p-3 text-center border-warning h-100">
                <i class="fas fa-tasks fa-4x text-warning mb-3"></i>
                <h5>Gestionar Tareas</h5>
                <a href="index.php?controller=Profesor&action=gestionarTareas&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-warning btn-sm mt-auto text-dark">
                    Ir a Tareas
                </a>
            </div>
        </div>

    </div>
    
    <div class="mt-3">
        <a href="index.php?controller=Profesor&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver a Mis Cursos
        </a>
    </div>
</div>