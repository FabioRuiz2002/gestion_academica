<?php /* Archivo: views/estudiante/mis_tareas.php */ ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Tareas: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h3>
    </div>
    <hr>

    <?php if (isset($_SESSION['mensaje_tarea'])): ?>
        <div class="alert alert-<?php echo $_SESSION['mensaje_tarea']['tipo']; ?> alert-dismissible fade show">
            <?php echo $_SESSION['mensaje_tarea']['texto']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['mensaje_tarea']); ?>
    <?php endif; ?>

    <?php if ($listaTareas->rowCount() == 0): ?>
        <div class="alert alert-info text-center p-4">
            <i class="fas fa-clipboard-check fa-2x mb-2"></i><br>
            No hay tareas asignadas para este curso por el momento.
        </div>
    <?php else: ?>
        <div class="row">
            <?php while ($tarea = $listaTareas->fetch(PDO::FETCH_ASSOC)): 
                $entregada = false;
                $miEntrega = null;
                foreach ($listaEntregas as $e) {
                    if ($e['id_tarea'] == $tarea['id_tarea']) { 
                        $entregada = true; 
                        $miEntrega = $e; 
                        break; 
                    }
                }
                $vencida = (strtotime($tarea['fecha_limite']) < time()) && !$entregada;
            ?>
                <div class="col-md-12 mb-3">
                    <div class="card shadow-sm <?php echo $entregada ? 'border-success' : ($vencida ? 'border-danger' : 'border-warning'); ?>">
                        <div class="card-header d-flex justify-content-between align-items-center bg-white">
                            <h5 class="mb-0 text-dark"><?php echo htmlspecialchars($tarea['titulo']); ?></h5>
                            <?php if ($entregada): ?>
                                <span class="badge bg-success"><i class="fas fa-check"></i> Entregada</span>
                            <?php elseif ($vencida): ?>
                                <span class="badge bg-danger"><i class="fas fa-times"></i> Vencida</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pendiente</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($tarea['descripcion'])); ?></p>
                            <p class="small text-muted mb-3">
                                <strong>Fecha límite:</strong> <?php echo date('d/m/Y', strtotime($tarea['fecha_limite'])); ?>
                            </p>

                            <?php if ($entregada): ?>
                                <div class="alert alert-secondary mb-0">
                                    <strong><i class="fas fa-file-upload"></i> Archivo enviado:</strong> 
                                    <a href="<?php echo $miEntrega['ruta_archivo']; ?>" download><?php echo htmlspecialchars($miEntrega['nombre_archivo']); ?></a>
                                    <br>
                                    <small>Fecha de envío: <?php echo date('d/m/Y H:i', strtotime($miEntrega['fecha_entrega'])); ?></small>
                                    
                                    <?php if($miEntrega['calificacion'] !== null): ?>
                                        <div class="mt-2 pt-2 border-top border-secondary">
                                            <strong>Nota:</strong> <span class="badge bg-primary fs-6"><?php echo $miEntrega['calificacion']; ?></span>
                                            <?php if($miEntrega['comentario_profesor']): ?>
                                                <br><small class="text-dark">Feedback: <?php echo htmlspecialchars($miEntrega['comentario_profesor']); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <br><small class="text-muted">Esperando calificación...</small>
                                    <?php endif; ?>
                                </div>
                            <?php elseif (!$vencida): ?>
                                <form action="index.php?controller=Estudiante&action=entregarTarea" method="POST" enctype="multipart/form-data" class="p-3 bg-light rounded">
                                    <input type="hidden" name="id_curso" value="<?php echo $infoCurso['id_curso']; ?>">
                                    <input type="hidden" name="id_tarea" value="<?php echo $tarea['id_tarea']; ?>">
                                    <label class="form-label small fw-bold">Subir tu trabajo:</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" name="archivo_entrega" required>
                                        <button class="btn btn-primary" type="submit">Enviar Tarea</button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-danger mb-0">El plazo para entregar esta tarea ha vencido.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
    
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>