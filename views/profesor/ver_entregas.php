<?php
/*
 * Archivo: views/profesor/ver_entregas.php
 * (CORREGIDO: 'class.' y botón "Volver" estandarizado)
 */
?>
<div class="container mt-4">
    <h2>Revisión de Entregas</h2>
    <h3 class="text-primary"><?php echo htmlspecialchars($infoTarea['titulo']); ?></h3>
    <p class="lead">Curso: <?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></p>
    <hr>

    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . htmlspecialchars($_SESSION['success_message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>
    
    <?php if (empty($listaEntregas)): ?>
        <div class="alert alert-info">Aún no hay entregas para esta tarea.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($listaEntregas as $entrega): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><?php echo htmlspecialchars($entrega['apellido'] . ', ' . $entrega['nombre']); ?></h5>
                        </div>
                        <div class="card-body">
                            <p>
                                <strong>Archivo:</strong> 
                                <a href="<?php echo htmlspecialchars($entrega['ruta_archivo']); ?>" target="_blank">
                                    <i class="fas fa-download me-1"></i> <?php echo htmlspecialchars($entrega['nombre_archivo']); ?>
                                </a>
                            </p>
                            <p><strong>Fecha de Entrega:</strong> <?php echo date('d/m/Y H:i', strtotime($entrega['fecha_entrega'])); ?></p>
                            
                            <form action="index.php?controller=Profesor&action=calificarEntrega" method="POST">
                                <input type="hidden" name="id_tarea" value="<?php echo $infoTarea['id_tarea']; ?>">
                                <input type="hidden" name="id_entrega" value="<?php echo $entrega['id_entrega']; ?>">
                                <div class="mb-3">
                                    <label for="calificacion-<?php echo $entrega['id_entrega']; ?>" class="form-label"><b>Calificación (0-20)</b></label>
                                    <input type="number" class="form-control" 
                                           id="calificacion-<?php echo $entrega['id_entrega']; ?>" 
                                           name="calificacion" 
                                           value="<?php echo htmlspecialchars($entrega['calificacion']); ?>" 
                                           min="0" max="20" step="0.5">
                                </div>
                                <div class="mb-3">
                                    <label for="comentario-<?php echo $entrega['id_entrega']; ?>" class="form-label">Comentario/Feedback</label>
                                    <textarea class="form-control" 
                                              id="comentario-<?php echo $entrega['id_entrega']; ?>" 
                                              name="comentario" 
                                              rows="2"><?php echo htmlspecialchars($entrega['comentario_profesor']); ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Guardar Calificación</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>