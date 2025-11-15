<?php
/*
 * Archivo: views/estudiante/mis_asistencias.php
 * Propósito: Muestra el historial de asistencia (MODIFICADO para un solo curso).
 */

function getAsistenciaBadgeEstudiante($estado) {
    switch ($estado) {
        case 'Presente': return '<span class="badge bg-success"><i class="fas fa-check me-1"></i> Presente</span>';
        case 'Ausente': return '<span class="badge bg-danger"><i class="fas fa-times me-1"></i> Ausente</span>';
        case 'Tardanza': return '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Tardanza</span>';
        default: return '<span class="badge bg-secondary">' . htmlspecialchars($estado) . '</span>';
    }
}
?>

<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-calendar-check me-2"></i> Mi Historial de Asistencia</h2>
    <h3 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h3>
    
    <?php if (empty($datosAsistencias)): ?>
        <div class="alert alert-info mt-4">
            <i class="fas fa-info-circle me-2"></i> Aún no tienes registros de asistencia para este curso.
        </div>
    <?php else: ?>
        <div class="table-responsive mt-4">
            <table class="table table-sm table-striped table-hover" style="max-width: 400px;">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datosAsistencias as $reg): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($reg['fecha'])); ?></td>
                            <td><?php echo getAsistenciaBadgeEstudiante($reg['estado']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="index.php?controller=Estudiante&action=panelCurso&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al Curso
        </a>
    </div>
</div>