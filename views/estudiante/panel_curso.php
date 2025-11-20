<?php /* Archivo: views/estudiante/panel_curso.php */ ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h6 class="text-muted">Estás en:</h6>
            <h2 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h2>
            <p class="mb-0 text-muted small"><i class="far fa-clock"></i> <?php echo htmlspecialchars($infoCurso['horario']); ?></p>
        </div>
        <a href="index.php?controller=Estudiante&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al Inicio
        </a>
    </div>
    <hr>
    
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm border-0 bg-light hover-zoom">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3 text-primary"><i class="fas fa-chart-bar fa-3x"></i></div>
                    <h5 class="card-title">Calificaciones</h5>
                    <p class="card-text small text-muted">Mira tus notas parciales y promedio.</p>
                    <a href="index.php?controller=Estudiante&action=verCalificaciones&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-outline-primary mt-auto stretched-link">Ver Notas</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm border-0 bg-light hover-zoom">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3 text-success"><i class="fas fa-user-check fa-3x"></i></div>
                    <h5 class="card-title">Asistencias</h5>
                    <p class="card-text small text-muted">Consulta tus faltas y asistencias.</p>
                    <a href="index.php?controller=Estudiante&action=verAsistencias&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-outline-success mt-auto stretched-link">Ver Historial</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm border-0 bg-light hover-zoom">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3 text-info"><i class="fas fa-folder-open fa-3x"></i></div>
                    <h5 class="card-title">Materiales</h5>
                    <p class="card-text small text-muted">Descarga diapositivas y archivos.</p>
                    <a href="index.php?controller=Estudiante&action=verMateriales&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-outline-info mt-auto stretched-link">Ver Archivos</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm border-0 bg-light hover-zoom">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3 text-warning"><i class="fas fa-laptop-code fa-3x"></i></div>
                    <h5 class="card-title">Tareas</h5>
                    <p class="card-text small text-muted">Sube tus trabajos pendientes.</p>
                    <a href="index.php?controller=Estudiante&action=verTareas&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-outline-warning mt-auto stretched-link">Ver Tareas</a>
                </div>
            </div>
        </div>
    </div>
</div>
<style>.hover-zoom:hover{transform:scale(1.03);transition:transform .2s;}</style>