<?php
/*
 * Archivo: views/estudiante/ver_tareas.php
 * (Botón "Volver" estandarizado con el componente)
 */
?>
<div class="container mt-4">
    <h2>Tareas y Entregas</h2>
    <h3 class.text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h3>
    <hr>
    
    <?php /* (Mensajes de éxito/error) */ ?>

    <?php if (empty($listaTareas)): ?>
        <div class.alert alert-info">Aún no hay tareas asignadas para este curso.</div>
    <?php else: ?>
        <div class.accordion" id="accordionTareas">
            <?php foreach ($listaTareas as $tarea): 
                $id_tarea = $tarea['id_tarea'];
                $entrega_hecha = $entregasHechas[$id_tarea] ?? null;
            ?>
                <div class.accordion-item">
                    <h2 class.accordion-header" id="heading-<?php echo $id_tarea; ?>">
                        <button class.accordion-button <?php echo $entrega_hecha ? 'collapsed' : ''; ?>" 
                                type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapse-<?php echo $id_tarea; ?>">
                            <?php if ($entrega_hecha && isset($entrega_hecha['calificacion'])): ?>
                                <span class.badge bg-success me-2">Calificado: <?php echo $entrega_hecha['calificacion']; ?></span>
                            <?php elseif ($entrega_hecha): ?>
                                <span class.badge bg-primary me-2">Entregado</span>
                            <?php else: ?>
                                <span class.badge bg-warning text-dark me-2">Pendiente</span>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($tarea['titulo']); ?>
                        </button>
                    </h2>
                    <div id="collapse-<?php echo $id_tarea; ?>" 
                         class.="accordion-collapse collapse <?php echo !$entrega_hecha ? 'show' : ''; ?>" 
                         data-bs-parent="#accordionTareas">
                        <div class.card-body">
                            <p><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($tarea['descripcion'])); ?></p>
                            <p><strong>Fecha Límite:</strong> 
                                <?php echo $tarea['fecha_limite'] ? date('d/m/Y H:i', strtotime($tarea['fecha_limite'])) : 'Sin límite'; ?>
                            </p>
                            <hr>
                            
                            <?php if ($entrega_hecha): ?>
                                <h5 class.text-success">Tu Entrega:</h5>
                                <p>
                                    <strong>Archivo:</strong> 
                                    <a href="<?php echo htmlspecialchars($entrega_hecha['ruta_archivo']); ?>" target="_blank">
                                        <i class.fas fa-download me-1"></i> <?php echo htmlspecialchars($entrega_hecha['nombre_archivo']); ?>
                                    </a>
                                </p>
                                <p><strong>Entregado el:</strong> <?php echo date('d/m/Y H:i', strtotime($entrega_hecha['fecha_entrega'])); ?></p>
                                <?php if (isset($entrega_hecha['calificacion'])): ?>
                                    <p class.fs-4"><strong>Calificación: <?php echo htmlspecialchars($entrega_hecha['calificacion']); ?> / 20</strong></p>
                                    <?php if ($entrega_hecha['comentario_profesor']): ?>
                                        <div class.alert alert-info">
                                            <strong>Feedback del Profesor:</strong>
                                            <p class.mb-0"><?php echo nl2br(htmlspecialchars($entrega_hecha['comentario_profesor'])); ?></p>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p class.text-muted">Tu entrega aún no ha sido calificada.</p>
                                <?php endif; ?>
                                
                            <?php else: ?>
                                <h5 class.text-danger">Subir Entrega:</h5>
                                <form action="index.php?controller=Estudiante&action=entregarTarea" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id_curso" value="<?php echo $infoCurso['id_curso']; ?>">
                                    <input type="hidden" name="id_tarea" value="<?php echo $id_tarea; ?>">
                                    <div class.mb-3">
                                        <label for="archivo_entrega-<?php echo $id_tarea; ?>" class="form-label">Seleccionar archivo</label>
                                        <input type="file" class.form-control" name="archivo_entrega" id="archivo_entrega-<?php echo $id_tarea; ?>" required>
                                    </div>
                                    <button type="submit" class.btn btn-primary">
                                        <i class.fas fa-upload me-1"></i> Entregar Tarea
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>