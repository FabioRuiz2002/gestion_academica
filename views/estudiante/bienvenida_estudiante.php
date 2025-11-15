<?php
/*
 * Archivo: views/estudiante/bienvenida_estudiante.php
 * (Corregido el error de tipeo $diferencia->inver)
 */

function tiempoRestante($fecha_limite) {
    $ahora = new DateTime();
    $limite = new DateTime($fecha_limite);
    $diferencia = $ahora->diff($limite);

    // --- ¡AQUÍ ESTABA EL ERROR! (CORREGIDO) ---
    if ($diferencia->invert) { return "Vencido"; } // 'invert' es 1 si la fecha ya pasó

    if ($diferencia->d == 0) {
        if ($diferencia->h > 0) {
            return "Vence hoy (en " . $diferencia->h . "h)";
        } else {
            return "Vence hoy (en " . $diferencia->i . "m)";
        }
    }
    if ($diferencia->d == 1) { return "Vence mañana"; }
    return "Vence en " . $diferencia->d . " días";
}
?>
<div class="container mt-4">
    <h1 class="mt-4">Mi Panel de Estudiante</h1>
    <p class="lead">Bienvenido(a), 
        <b><?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']); ?></b>.
    </p>
    <a href="index.php?controller=Estudiante&action=verMatriculas" class="btn btn-primary mb-3">
        <i class="fas fa-plus-circle me-2"></i> Inscribirse en Cursos Nuevos
    </a>
    <a href="index.php?controller=Estudiante&action=generarReporte" class="btn btn-info text-white mb-3" target="_blank">
        <i class="fas fa-file-pdf me-2"></i> Descargar Boleta de Notas
    </a>
    <hr>
    
    <div class="row mt-4">
        
        <div class="col-lg-8">
            <h4><i class="fas fa-book me-2"></i> Mis Cursos Matriculados</h4>
            <?php if (empty($cursosMatriculados)): ?>
                <div class="alert alert-info">No estás matriculado en ningún curso.</div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($cursosMatriculados as $curso): ?>
                        <a href="index.php?controller=Estudiante&action=panelCurso&id_curso=<?php echo $curso['id_curso']; ?>" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><?php echo htmlspecialchars($curso['nombre_curso']); ?></h5>
                                <small class="text-muted"><?php echo htmlspecialchars($curso['horario']); ?></small>
                            </div>
                            <i class="fas fa-chevron-right text-primary"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-bell me-2"></i> Tareas Próximas</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($listaTareasProximas)): ?>
                        <p class="text-muted text-center">¡Buen trabajo! No tienes tareas pendientes.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($listaTareasProximas as $tarea): ?>
                                <a href="index.php?controller=Estudiante&action=verTareas&id_curso=<?php echo $tarea['id_curso']; ?>" 
                                   class="list-group-item list-group-item-action">
                                    <strong><?php echo htmlspecialchars($tarea['titulo']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($tarea['nombre_curso']); ?></small><br>
                                    <span class="badge bg-danger">
                                        <?php echo tiempoRestante($tarea['fecha_limite']); ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
    </div>
</div>