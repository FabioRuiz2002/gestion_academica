<?php
/*
 * Archivo: views/profesor/ver_entregas.php
 * Propósito: Muestra la lista de entregas y permite calificarlas.
 */
?>
<div class="container mt-4">
    <h2 class="mb-4">Entregas de Tarea: <span class="text-primary"><?php echo htmlspecialchars($infoTarea['titulo']); ?></span></h2>
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
            <h5 class="mb-0"><i class="fas fa-inbox me-2"></i> Archivos Recibidos</h5>
        </div>
        <div class="card-body">
            <?php if (empty($listaEntregas)): ?>
                <div class="alert alert-info mb-0">Ningún estudiante ha entregado esta tarea aún.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Estudiante</th>
                                <th>Archivo Entregado</th>
                                <th>Calificación (sobre 20)</th>
                                <th>Comentario</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listaEntregas as $entrega): ?>
                                <tr>
                                    <form action="index.php?controller=Profesor&action=calificarEntrega" method="POST">
                                        <input type="hidden" name="id_entrega" value="<?php echo $entrega['id_entrega']; ?>">
                                        <input type="hidden" name="id_tarea" value="<?php echo $infoTarea['id_tarea']; ?>">
                                        
                                        <td>
                                            <?php echo htmlspecialchars($entrega['apellido'] . ', ' . $entrega['nombre']); ?><br>
                                            <small class="text-muted">Entregado: <?php echo date('d/m/Y H:i', strtotime($entrega['fecha_entrega'])); ?></small>
                                        </td>
                                        
                                        <td>
                                            <a href="<?php echo htmlspecialchars($entrega['ruta_archivo']); ?>" 
                                               download="<?php echo htmlspecialchars($entrega['nombre_archivo']); ?>" 
                                               target="_blank" 
                                               class="btn btn-success btn-sm">
                                                <i class="fas fa-download me-1"></i> (<?php echo htmlspecialchars($entrega['nombre_archivo']); ?>)
                                            </a>
                                        </td>
                                        
                                        <td>
                                            <input type="number" class="form-control form-control-sm" 
                                                   name="calificacion" 
                                                   value="<?php echo htmlspecialchars($entrega['calificacion']); ?>" 
                                                   min="0" max="20" step="0.5" 
                                                   style="width: 80px;">
                                        </td>
                                        
                                        <td>
                                            <textarea class="form-control form-control-sm" 
                                                      name="comentario" 
                                                      rows="2"><?php echo htmlspecialchars($entrega['comentario_profesor']); ?></textarea>
                                        </td>
                                        
                                        <td>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="index.php?controller=Profesor&action=gestionarTareas&id_curso=<?php echo $infoTarea['id_curso']; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver a Tareas
        </a>
    </div>
</div>