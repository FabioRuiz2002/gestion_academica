<?php
/*
 * Archivo: views/admin/gestionar_malla.php
 * (CORREGIDO: Errores 'class.')
 * (CORREGIDO: 'Undefined variable $listaProfesores' en el modal)
 */
?>
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?controller=Academico&action=index">Gestión Académica</a></li>
            <li class="breadcrumb-item"><a href="index.php?controller=Academico&action=verFacultad&id_facultad=<?php echo $infoPlan['id_facultad']; ?>"><?php echo htmlspecialchars($infoPlan['nombre_facultad']); ?></a></li>
            <li class="breadcrumb-item"><a href="index.php?controller=Academico&action=verEscuela&id_escuela=<?php echo $infoPlan['id_escuela']; ?>"><?php echo htmlspecialchars($infoPlan['nombre_escuela']); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($infoPlan['nombre_plan']); ?></li>
        </ol>
    </nav>

    <h2 class="mb-4">Gestionar Malla Curricular</h2>
    <h3 class="text-primary"><?php echo htmlspecialchars($infoPlan['nombre_plan']); ?></h3>
    <p class="lead">
        Escuela de: <?php echo htmlspecialchars($infoPlan['nombre_escuela']); ?>
        (<?php echo htmlspecialchars($infoPlan['nombre_facultad']); ?>)
    </p>
    <hr>
    
    <?php
    if (isset($_SESSION['error_message_academico'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error_message_academico'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        unset($_SESSION['error_message_academico']);
    }
    ?>

    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Asignar Nuevo Curso</h5>
                </div>
                <div class="card-body">
                    <form action="index.php?controller=Academico&action=asignarCursoPlan" method="POST">
                        <input type="hidden" name="id_plan_estudio" value="<?php echo $infoPlan['id_plan_estudio']; ?>">
                        
                        <div class="mb-3">
                            <label for="id_curso" class="form-label">Curso</label>
                            <select name="id_curso" class="form-select" required>
                                <option value="">Selecciona un curso disponible...</option>
                                <?php if (empty($cursosDisponibles)): ?>
                                    <option value="" disabled>No hay más cursos en el repositorio.</option>
                                <?php endif; ?>
                                <?php foreach ($cursosDisponibles as $curso): ?>
                                    <option value="<?php echo $curso['id_curso']; ?>">
                                        <?php echo htmlspecialchars($curso['nombre_curso']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small>Solo se muestran cursos que no están ya en esta malla.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="ciclo" class="form-label">Ciclo</label>
                            <input type="number" name="ciclo" class="form-control" min="1" max="12" required placeholder="Ej: 1">
                        </div>
                        
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i> Asignar a la Malla
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <h4>Cursos en esta Malla</h4>
            
            <?php if (empty($cursosEnPlan)): ?>
                <div class="alert alert-secondary">Aún no hay cursos asignados a esta malla.</div>
            <?php else: ?>
                <ul class="list-group">
                    <?php 
                    $ciclo_actual = 0;
                    foreach ($cursosEnPlan as $curso): 
                        if ($curso['ciclo'] != $ciclo_actual) {
                            $ciclo_actual = $curso['ciclo'];
                            echo '<li class="list-group-item list-group-item-dark"><strong>CICLO ' . $ciclo_actual . '</strong></li>';
                        }
                    ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <?php echo htmlspecialchars($curso['nombre_curso']); ?>
                                <br>
                                <small class="text-muted">
                                    Prof: <?php echo htmlspecialchars($curso['nombre_profesor'] . ' ' . $curso['apellido_profesor']); ?>
                                    
                                    <button class="btn btn-link btn-sm p-0 ms-1" 
                                            onclick="abrirModalProfesor(<?php echo $curso['id_curso']; ?>, <?php echo $curso['id_profesor']; ?>, '<?php echo htmlspecialchars($curso['nombre_curso'], ENT_QUOTES); ?>')"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editarProfesorModal"
                                            title="Cambiar Profesor">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </small>
                            </div>
                            <div>
                                <a href="index.php?controller=Academico&action=gestionarPrerequisitos&id_curso=<?php echo $curso['id_curso']; ?>&id_plan=<?php echo $infoPlan['id_plan_estudio']; ?>" 
                                   class="btn btn-info btn-sm text-white" title="Prerrequisitos">
                                    <i class="fas fa-code-branch"></i> Req.
                                </a>
                                <a href="index.php?controller=Academico&action=quitarCursoPlan&id_cursos_plan=<?php echo $curso['id_cursos_plan']; ?>&id_plan=<?php echo $infoPlan['id_plan_estudio']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   title="Quitar de la malla"
                                   onclick="return confirm('¿Estás seguro de que deseas quitar este curso de la malla?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
    
</div>

<div class="modal fade" id="editarProfesorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Academico&action=editarProfesorCurso" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Profesor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_plan" value="<?php echo $infoPlan['id_plan_estudio']; ?>">
                    <input type="hidden" name="id_curso" id="modal_prof_id_curso">
                    
                    <div class="mb-3">
                        <label class="form-label">Curso:</label>
                        <input type="text" class="form-control" id="modal_prof_nombre_curso" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_prof_id_profesor" class="form-label">Nuevo Profesor:</label>
                        <select class="form-select" id="modal_prof_id_profesor" name="id_profesor" required>
                            <option value="">Seleccione un profesor...</option>
                            <?php foreach ($listaProfesores as $profesor): ?>
                                <option value="<?php echo $profesor['id_usuario']; ?>">
                                    <?php echo htmlspecialchars($profesor['nombre'] . ' ' . $profesor['apellido']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalProfesor(idCurso, idProfesorActual, nombreCurso) {
    document.getElementById('modal_prof_id_curso').value = idCurso;
    document.getElementById('modal_prof_nombre_curso').value = nombreCurso;
    document.getElementById('modal_prof_id_profesor').value = idProfesorActual;
}
</script>