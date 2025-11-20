<?php /* Archivo: views/profesor/panel_curso.php */ ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="text-muted">Gestionando Curso:</h5>
            <h2 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h2>
            <p class="lead mb-0"><?php echo htmlspecialchars($infoCurso['horario']); ?></p>
        </div>
        <a href="index.php?controller=Profesor&action=index" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> Volver</a>
    </div>
    <hr>

    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm border-0 bg-light">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3 text-primary"><i class="fas fa-clipboard-list fa-3x"></i></div>
                    <h5 class="card-title">Calificaciones</h5>
                    <p class="card-text small text-muted">Configura criterios de evaluación y asigna notas.</p>
                    <div class="mt-auto">
                        <a href="index.php?controller=Profesor&action=gestionarEvaluaciones&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-outline-primary w-100 mb-2">Configurar Criterios</a>
                        <a href="index.php?controller=Profesor&action=libroCalificaciones&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-primary w-100">Libro de Notas</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm border-0 bg-light">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3 text-success"><i class="fas fa-user-check fa-3x"></i></div>
                    <h5 class="card-title">Asistencia</h5>
                    <p class="card-text small text-muted">Registra la asistencia diaria o revisa el historial.</p>
                    <div class="mt-auto">
                        <a href="index.php?controller=Profesor&action=tomarAsistencia&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-success w-100 mb-2">Tomar Asistencia</a>
                        <a href="index.php?controller=Profesor&action=verHistoricoAsistencia&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-outline-success w-100">Ver Historial</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm border-0 bg-light">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3 text-info"><i class="fas fa-folder-open fa-3x"></i></div>
                    <h5 class="card-title">Materiales</h5>
                    <p class="card-text small text-muted">Sube sílabos, diapositivas y lecturas para los alumnos.</p>
                    <a href="index.php?controller=Profesor&action=gestionarMateriales&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-info text-white mt-auto w-100">Gestionar Archivos</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-center shadow-sm border-0 bg-light">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3 text-warning"><i class="fas fa-laptop-code fa-3x"></i></div>
                    <h5 class="card-title">Tareas</h5>
                    <p class="card-text small text-muted">Crea tareas, asigna fechas límite y califica entregas.</p>
                    <a href="index.php?controller=Profesor&action=gestionarTareas&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-warning mt-auto w-100">Gestionar Tareas</a>
                </div>
            </div>
        </div>
    </div>
</div>