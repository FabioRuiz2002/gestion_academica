<?php /* Archivo: views/profesor/ver_historico_asistencia.php */ ?>
<div class="container mt-4">
    <h3>Historial de Asistencia: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h3>
    <hr>
    
    <?php if (empty($fechas)): ?>
        <div class="alert alert-info">No hay registros de asistencia aún.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-center">
                <thead class="table-dark">
                    <tr>
                        <th class="text-start">Estudiante</th>
                        <?php foreach ($fechas as $f): ?>
                            <th><small><?php echo date('d/m', strtotime($f)); ?></small></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos_por_estudiante as $nombre => $asistencias): ?>
                        <tr>
                            <td class="text-start"><?php echo htmlspecialchars($nombre); ?></td>
                            <?php foreach ($fechas as $f): 
                                $estado = $asistencias[$f] ?? '-';
                                $color = ($estado == 'P') ? 'text-success' : (($estado == 'F') ? 'text-danger' : (($estado == 'T') ? 'text-warning' : 'text-muted'));
                                $icon = ($estado == 'P') ? 'check-circle' : (($estado == 'F') ? 'times-circle' : (($estado == 'T') ? 'clock' : 'minus'));
                            ?>
                                <td><i class="fas fa-<?php echo $icon.' '.$color; ?>"></i></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <div class="mt-3"><a href="index.php?controller=Profesor&action=tomarAsistencia&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-secondary">Volver</a></div>
</div>