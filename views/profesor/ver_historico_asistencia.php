<?php
/*
 * Archivo: views/profesor/ver_historico_asistencia.php
 * (CORREGIDO: 'class.' y botón "Volver" estandarizado)
 */
?>
<div class="container mt-4">
    <h2>Historial de Asistencia:</h2>
    <h3 class="text-primary"><?php echo htmlspecialchars($curso_info['nombre_curso']); ?></h3>
    <hr>
    
    <?php if (empty($datos_por_estudiante)): ?>
        <div class="alert alert-info">Aún no se ha registrado ninguna asistencia para este curso.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" style="font-size: 0.9em;">
                <thead class="table-dark">
                    <tr>
                        <th style="min-width: 200px;">Estudiante</th>
                        <?php foreach ($fechas as $fecha): ?>
                            <th class="text-center"><?php echo date('d/m', strtotime($fecha)); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos_por_estudiante as $nombre => $asistencias): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($nombre); ?></td>
                            <?php foreach ($fechas as $fecha): ?>
                                <?php
                                $estado = $asistencias[$fecha] ?? '-';
                                $badge_class = 'bg-secondary';
                                if ($estado == 'Presente') $badge_class = 'bg-success';
                                if ($estado == 'Ausente') $badge_class = 'bg-danger';
                                if ($estado == 'Tardanza') $badge_class = 'bg-warning text-dark';
                                echo "<td class='text-center'><span class='badge {$badge_class}'>{$estado[0]}</span></td>";
                                ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <span class="badge bg-success me-2">P = Presente</span>
            <span class="badge bg-danger me-2">A = Ausente</span>
            <span class="badge bg-warning text-dark me-2">T = Tardanza</span>
        </div>
    <?php endif; ?>
    
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>