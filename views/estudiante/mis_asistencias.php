<?php
/*
 * Archivo: views/estudiante/mis_asistencias.php
 * (Botón "Volver" estandarizado con el componente)
 */
?>
<div class="container mt-4">
    <h2>Mis Asistencias</h2>
    <h3 class.text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h3>
    <hr>
    
    <?php if (empty($datosAsistencias)): ?>
        <div class="alert alert-info">Aún no se han registrado asistencias para este curso.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_presente = 0;
                    $total_ausente = 0;
                    $total_tardanza = 0;
                    
                    foreach ($datosAsistencias as $asistencia): 
                        $estado = $asistencia['estado'];
                        $badge_class = 'bg-secondary';
                        if ($estado == 'Presente') {
                            $badge_class = 'bg-success';
                            $total_presente++;
                        }
                        if ($estado == 'Ausente') {
                            $badge_class = 'bg-danger';
                            $total_ausente++;
                        }
                        if ($estado == 'Tardanza') {
                            $badge_class = 'bg-warning text-dark';
                            $total_tardanza++;
                        }
                    ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($asistencia['fecha'])); ?></td>
                            <td><span class="badge <?php echo $badge_class; ?> fs-6"><?php echo $estado; ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            <h4>Resumen:</h4>
            <span class="badge bg-success me-2 fs-6">Presente: <?php echo $total_presente; ?></span>
            <span class="badge bg-danger me-2 fs-6">Ausente: <?php echo $total_ausente; ?></span>
            <span class="badge bg-warning text-dark me-2 fs-6">Tardanza: <?php echo $total_tardanza; ?></span>
        </div>
    <?php endif; ?>
    
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>