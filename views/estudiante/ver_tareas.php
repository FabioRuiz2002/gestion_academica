<?php
/*
 * Archivo: views/estudiante/ver_tareas.php
 * Propósito: Muestra la lista de tareas y permite subir entregas.
 */
?>
<div class="container mt-4">
    <h2 class="mb-4">Tareas: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h2>
    <hr>
    
    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . htmlspecialchars($_SESSION['success_message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['error_message']);
    }
    ?>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-tasks me-2"></i> Lista de Tareas Asignadas</h5>
        </div>
        <div class="card-body">
            <?php if (empty($listaTareas)): ?>
                <div class="alert alert-info mb-0">No hay tareas asignadas para este curso.</div>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($listaTareas as $tarea): ?>
                        <?php
                            // Verificamos si esta tarea (por su ID) ya existe en el array de entregas
                            $ya_entregado = isset($entregasHechas[$tarea['id_tarea']]);
                            $entrega = $ya_entregado ? $entregasHechas[$tarea['id_tarea']] : null;
                            $calificado = $ya_entregado && isset($entrega['calificacion']);
                            
                            // Comprobar si la fecha límite ha pasado
                            $fecha_limite_pasada = false;
                            if ($tarea['fecha_limite']) {
                                $fecha_limite_ts = strtotime($tarea['fecha_limite']);
                                $fecha_actual_ts = time();
                                if ($fecha_actual_ts > $fecha_limite_ts) {
                                    $fecha_limite_pasada = true;
                                }
                            }
                        ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-5">
                                    <h5 class="mb-1"><?php echo htmlspecialchars($tarea['titulo']); ?></h5>
                                    <p class="mb-1"><?php echo nl2br(htmlspecialchars($tarea['descripcion'])); ?></p>
                                    <small class="text-muted">
                                        Fecha Límite: 
                                        <?php if ($tarea['fecha_limite']): ?>
                                            <span class="<?php echo $fecha_limite_pasada ? 'text-danger fw-bold' : ''; ?>">
                                                <?php echo date('d/m/Y h:i A', strtotime($tarea['fecha_limite'])); ?>
                                            </span>
                                        <?php else: ?>
                                            Sin fecha límite
                                        <?php endif; ?>
                                    </small>
                                </div>
                                
                                <div class="col-md-7 align-self-center">
                                    <?php if ($calificado): ?>
                                        <div class="alert alert-primary p-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-check-double me-1"></i> <strong>Calificado</strong>
                                                    <br>
                                                    <small>Entregaste: <?php echo htmlspecialchars($entrega['nombre_archivo']); ?></small>
                                                </div>
                                                <div class="text-end">
                                                    Nota: <span class="badge bg-primary fs-6"><?php echo htmlspecialchars($entrega['calificacion']); ?> / 20</span>
                                                </div>
                                            </div>
                                            <?php if (!empty($entrega['comentario_profesor'])): ?>
                                            <hr class="my-1">
                                            <small>
                                                <strong>Comentario del Profesor:</strong>
                                                <?php echo htmlspecialchars($entrega['comentario_profesor']); ?>
                                            </small>
                                            <?php endif; ?>
                                        </div>
                                    <?php elseif ($ya_entregado): ?>
                                        <div class="alert alert-success text-center p-2">
                                            <i class="fas fa-check-circle me-1"></i> <strong>Entregado</strong>
                                            <br>
                                            <small>
                                                (<?php echo htmlspecialchars($entrega['nombre_archivo']); ?>) - Pendiente de calificación
                                            </small>
                                        </div>
                                    <?php elseif ($fecha_limite_pasada): ?>
                                        <div class="alert alert-danger text-center p-2">
                                            <i class="fas fa-times-circle me-1"></i> <strong>Fecha Límite Vencida</strong>
                                            <br>
                                            <small>No se admiten más entregas.</small>
                                        </div>
                                    <?php else: ?>
                                        <form action="index.php?controller=Estudiante&action=entregarTarea" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($infoCurso['id_curso']); ?>">
                                            <input type="hidden" name="id_tarea" value="<?php echo $tarea['id_tarea']; ?>">
                                            <div class="input-group">
                                                <input class="form-control form-control-sm" type="file" name="archivo_entrega" required>
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-upload me-1"></i> Entregar
                                                </button>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="index.php?controller=Estudiante&action=panelCurso&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al Panel del Curso
        </a>
    </div>
</div>