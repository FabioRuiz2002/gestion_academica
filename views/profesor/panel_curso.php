<?php /* Archivo: views/profesor/panel_curso.php */ ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="text-muted">Gestionando Curso:</h5>
            <h2 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h2>
            <p class="lead mb-0"><i class="far fa-clock me-1"></i> <?php echo htmlspecialchars($infoCurso['horario']); ?></p>
        </div>
    </div>
    <hr>

    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm border-0 bg-light hover-zoom">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3 text-primary"><i class="fas fa-clipboard-list fa-3x"></i></div>
                    <h5 class="card-title">Calificaciones</h5>
                    <p class="card-text small text-muted">Gestionar notas y criterios.</p>
                    <div class="mt-auto">
                        <a href="index.php?controller=Profesor&action=libroCalificaciones&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-primary w-100 stretched-link">
                            Ingresar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm border-0 bg-light hover-zoom">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3 text-success"><i class="fas fa-user-check fa-3x"></i></div>
                    <h5 class="card-title">Asistencia</h5>
                    <p class="card-text small text-muted">Registro diario y control.</p>
                    <div class="mt-auto">
                        <a href="index.php?controller=Profesor&action=tomarAsistencia&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-success w-100 stretched-link">
                            Ingresar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm border-0 bg-light hover-zoom">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3 text-info"><i class="fas fa-folder-open fa-3x"></i></div>
                    <h5 class="card-title">Materiales</h5>
                    <p class="card-text small text-muted">Archivos y recursos.</p>
                    <div class="mt-auto">
                        <a href="index.php?controller=Profesor&action=gestionarMateriales&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-info text-white w-100 stretched-link">
                            Ingresar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm border-0 bg-light hover-zoom">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3 text-warning"><i class="fas fa-laptop-code fa-3x"></i></div>
                    <h5 class="card-title">Tareas</h5>
                    <p class="card-text small text-muted">Actividades y entregas.</p>
                    <div class="mt-auto">
                        <a href="index.php?controller=Profesor&action=gestionarTareas&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-warning w-100 stretched-link">
                            Ingresar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>
<style>.hover-zoom:hover{transform:scale(1.03);transition:transform .2s;}</style>