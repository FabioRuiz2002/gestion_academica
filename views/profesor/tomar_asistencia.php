<?php
/*
 * Archivo: views/profesor/tomar_asistencia.php
 * (Corregido el botón 'Volver')
 */
$fecha_display = date('d/m/Y', strtotime($fecha));
function getEstadoClass($estado) {
    switch ($estado) {
        case 'Presente': return 'btn-success';
        case 'Ausente': return 'btn-danger';
        case 'Tardanza': return 'btn-warning';
        default: return 'btn-secondary';
    }
}
?>
<div class="container mt-4">
    <h2 class="mb-4">Asistencia: <span class="text-primary"><?php echo htmlspecialchars($curso_info['nombre_curso']); ?></span></h2>
    <p class="lead">Profesor: <?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']); ?></p>
    <?php 
    if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['mensaje']['tipo']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['mensaje']['texto']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>
    <div class="card p-3 mb-4 shadow-sm">
        <form method="POST" action="index.php?controller=Profesor&action=tomarAsistencia">
            <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($id_curso); ?>">
            <div class="row align-items-end">
                <div class="col-md-6 mb-3">
                    <label for="fecha_asistencia" class="form-label fw-bold">Fecha de la Clase</label>
                    <input type="date" class="form-control" id="fecha_asistencia" name="fecha_asistencia" 
                           value="<?php echo htmlspecialchars($fecha); ?>" required max="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="fas fa-calendar-alt me-2"></i> Cambiar Fecha
                    </button>
                </div>
            </div>
        </form>
    </div>
    <h4 class="mb-3">Registro para el día: <span class="badge bg-dark"><?php echo $fecha_display; ?></span></h4>
    <?php if ($asistencia_tomada): ?>
        <div class="alert alert-info">La asistencia de esta fecha ya fue registrada.</div>
        <ul class="list-group shadow-sm">
            <?php foreach ($registros_asistencia as $registro): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo htmlspecialchars($registro['apellido'] . ', ' . $registro['nombre']); ?>
                    <span class="badge <?php echo getEstadoClass($registro['estado']); ?> rounded-pill p-2">
                        <?php echo htmlspecialchars($registro['estado']); ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif (empty($estudiantes)): ?>
        <div class="alert alert-warning">No hay estudiantes matriculados en este curso.</div>
    <?php else: ?>
        <form method="POST" action="index.php?controller=Profesor&action=guardarAsistencia">
            <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($id_curso); ?>">
            <input type="hidden" name="fecha_asistencia" value="<?php echo htmlspecialchars($fecha); ?>">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Estudiante</th>
                            <th>Correo Electrónico</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantes as $estudiante): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($estudiante['apellido'] . ', ' . $estudiante['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($estudiante['email']); ?></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <input type="radio" class="btn-check" name="asistencia[<?php echo $estudiante['id_usuario']; ?>]" 
                                               id="p_<?php echo $estudiante['id_usuario']; ?>" value="Presente" autocomplete="off" checked>
                                        <label class="btn btn-outline-success" for="p_<?php echo $estudiante['id_usuario']; ?>">P</label>
                                        <input type="radio" class="btn-check" name="asistencia[<?php echo $estudiante['id_usuario']; ?>]" 
                                               id="a_<?php echo $estudiante['id_usuario']; ?>" value="Ausente" autocomplete="off">
                                        <label class="btn btn-outline-danger" for="a_<?php echo $estudiante['id_usuario']; ?>">A</label>
                                        <input type="radio" class="btn-check" name="asistencia[<?php echo $estudiante['id_usuario']; ?>]" 
                                               id="t_<?php echo $estudiante['id_usuario']; ?>" value="Tardanza" autocomplete="off">
                                        <label class="btn btn-outline-warning" for="t_<?php echo $estudiante['id_usuario']; ?>">T</label>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-success btn-lg mt-3 w-100">
                <i class="fas fa-save me-2"></i> Guardar Asistencia del Día (<?php echo $fecha_display; ?>)
            </button>
        </form>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="index.php?controller=Profesor&action=panelCurso&id_curso=<?php echo $curso_info['id_curso']; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al Curso
        </a>
    </div>
</div>