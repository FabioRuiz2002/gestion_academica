<?php
/*
 * Archivo: views/estudiante/mis_calificaciones.php
 * (Botón "Volver" estandarizado con el componente)
 */

// Calcular Promedio Final
$n1 = $datosCalificacion['nota1'] ?? 0;
$n2 = $datosCalificacion['nota2'] ?? 0;
$n3 = $datosCalificacion['nota3'] ?? 0; // Prom. Tareas
$promedio_final = ($n1 + $n2 + $n3) / 3;
$estado = ($promedio_final >= 10.5) ? 'Aprobado' : 'Desaprobado';
$estado_class = ($promedio_final >= 10.5) ? 'bg-success' : 'bg-danger';
?>
<div class="container mt-4">
    <h2>Mis Calificaciones</h2>
    <h3 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h3>
    <hr>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class.mb-0">Resumen de Notas</h5>
                </div>
                <div class.card-body">
                    <table class.table table-striped">
                        <tbody>
                            <tr>
                                <th>Nota 1 (Parcial)</th>
                                <td class="fs-5"><b><?php echo number_format($n1, 2); ?></b></td>
                            </tr>
                            <tr>
                                <th>Nota 2 (Final)</th>
                                <td class="fs-5"><b><?php echo number_format($n2, 2); ?></b></td>
                            </tr>
                            <tr>
                                <th>Promedio Tareas (N3)</th>
                                <td class.fs-5"><b><?php echo number_format($n3, 2); ?></b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-header">
                    <h5 class="mb-0">Promedio Final</h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h1 class="display-3 fw-bold"><?php echo number_format($promedio_final, 2); ?></h1>
                    <span class="badge <?php echo $estado_class; ?> fs-5"><?php echo $estado; ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>