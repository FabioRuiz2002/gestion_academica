<?php
// Archivo: views/profesor/ver_historico_asistencia.php
function getAsistenciaBadge($estado) {
    switch ($estado) {
        case 'Presente': return '<span class="badge bg-success">P</span>';
        case 'Ausente': return '<span class="badge bg-danger">A</span>';
        case 'Tardanza': return '<span class="badge bg-warning text-dark">T</span>';
        default: return '<span class="badge bg-secondary">-</span>';
    }
}
?>
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-history me-2"></i> Historial de Asistencia: <span class="text-primary"><?php echo htmlspecialchars($curso_info['nombre_curso']); ?></span></h2>
    <p class="lead">Vista histórica de las asistencias tomadas para este curso.</p>
    <?php if (empty($fechas)): ?>
        <div class="alert alert-warning">Aún no se ha tomado ninguna asistencia para este curso.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm shadow-sm">
                <thead class="table-dark sticky-top">
                    <tr>
                        <th style="min-width: 200px;">Estudiante</th>
                        <?php foreach ($fechas as $fecha_item): ?>
                            <th class="text-center" style="min-width: 70px;">
                                <?php echo date('d/M', strtotime($fecha_item)); ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos_por_estudiante as $nombre_estudiante => $registros): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($nombre_estudiante); ?></td>
                            <?php foreach ($fechas as $fecha_item): ?>
                                <td class="text-center">
                                    <?php
                                    $estado = $registros[$fecha_item] ?? '';
                                    echo getAsistenciaBadge($estado);
                                    ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <div class="mt-4">
        <a href="index.php?controller=Profesor&action=verCursosAsistencia" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver a Selección de Curso
        </a>
    </div>
</div>