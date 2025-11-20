<?php 
/* Archivo: views/estudiante/mis_calificaciones.php */
$promedio_acumulado = 0; 
$porcentaje_total = 0;
?>
<div class="container mt-4">
    <h3>Calificaciones: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h3>
    <hr>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white"><h5 class="mb-0">Detalle de Notas</h5></div>
                <div class="card-body p-0">
                    <?php if (empty($listaNotas)): ?>
                        <div class="p-4 text-center text-muted">El profesor no ha configurado evaluaciones aún.</div>
                    <?php else: ?>
                        <table class="table table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr><th>Evaluación</th><th class="text-center">Peso</th><th class="text-center">Nota</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listaNotas as $n): 
                                    $peso = (float)$n['porcentaje'];
                                    $nota = $n['calificacion'];
                                    $porcentaje_total += $peso;
                                    if ($nota !== null) $promedio_acumulado += (float)$nota * ($peso / 100);
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($n['nombre']); ?></td>
                                    <td class="text-center"><?php echo $peso; ?>%</td>
                                    <td class="text-center fw-bold">
                                        <?php if ($nota !== null): ?>
                                            <span class="badge bg-primary fs-6"><?php echo number_format($nota, 2); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card text-center bg-light h-100 border-0">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="text-muted">Promedio Final</h5>
                    <?php if ($porcentaje_total == 100): ?>
                        <h1 class="display-1 fw-bold my-3"><?php echo number_format($promedio_acumulado, 2); ?></h1>
                        <?php if ($promedio_acumulado >= 10.5): ?>
                            <span class="badge bg-success fs-5">APROBADO</span>
                        <?php else: ?>
                            <span class="badge bg-danger fs-5">DESAPROBADO</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <h1 class="display-3 fw-bold my-3 text-muted">-</h1>
                        <small class="text-warning fw-bold">Cálculo pendiente</small>
                        <p class="small mt-1">Las evaluaciones deben sumar 100%.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>