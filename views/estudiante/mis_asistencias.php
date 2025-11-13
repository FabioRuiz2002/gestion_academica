<?php
/*
 * Archivo: views/estudiante/mis_asistencias.php
 * Propósito: Muestra el historial de asistencia del estudiante.
 */

// Función de ayuda para los badges de estado
function getAsistenciaBadgeEstudiante($estado) {
    switch ($estado) {
        case 'Presente': 
            return '<span class="badge bg-success"><i class="fas fa-check me-1"></i> Presente</span>';
        case 'Ausente': 
            return '<span class="badge bg-danger"><i class="fas fa-times me-1"></i> Ausente</span>';
        case 'Tardanza': 
            return '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Tardanza</span>';
        default: 
            return '<span class="badge bg-secondary">' . htmlspecialchars($estado) . '</span>';
    }
}
?>

<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-calendar-check me-2"></i> Mi Historial de Asistencia</h2>

    <?php if (empty($asistencias_agrupadas)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Aún no tienes registros de asistencia.
        </div>
    <?php else: ?>
        <p class="lead">Aquí puedes ver tu récord de asistencia detallado por cada curso.</p>
        
        <div class="accordion" id="accordionAsistencias">
            
            <?php $i = 0; ?>
            <?php foreach ($asistencias_agrupadas as $nombre_curso => $registros): ?>
                <?php $i++; ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-<?php echo $i; ?>">
                        <button class="accordion-button <?php echo ($i > 1) ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapse-<?php echo $i; ?>" aria-expanded="<?php echo ($i == 1) ? 'true' : 'false'; ?>" 
                                aria-controls="collapse-<?php echo $i; ?>">
                            <strong><?php echo htmlspecialchars($nombre_curso); ?></strong>
                        </button>
                    </h2>
                    <div id="collapse-<?php echo $i; ?>" class="accordion-collapse collapse <?php echo ($i == 1) ? 'show' : ''; ?>" 
                         aria-labelledby="heading-<?php echo $i; ?>" data-bs-parent="#accordionAsistencias">
                        <div class="accordion-body">
                            <table class="table table-sm table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($registros as $reg): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($reg['fecha'])); ?></td>
                                            <td><?php echo getAsistenciaBadgeEstudiante($reg['estado']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="index.php?controller=Estudiante&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al Panel
        </a>
    </div>
</div>