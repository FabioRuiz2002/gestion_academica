<?php /* Archivo: views/admin/reportes.php */ ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h2><i class="fas fa-file-alt me-2"></i> Reporte General de Matriculados</h2>
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print me-2"></i> Imprimir Reporte</button>
    </div>
    
    <?php if (empty($datosReporte)): ?>
        <div class="alert alert-info">No hay estudiantes matriculados en el sistema.</div>
    <?php else: ?>
        <?php foreach ($datosReporte as $curso => $alumnos): ?>
            <div class="card mb-4 shadow-sm break-page">
                <div class="card-header bg-dark text-white d-flex justify-content-between">
                    <h5 class="mb-0"><?php echo htmlspecialchars($curso); ?></h5>
                    <span class="badge bg-light text-dark"><?php echo count($alumnos); ?> Alumnos</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead><tr><th>Código</th><th>Alumno</th><th>Horario</th><th>Fecha Inscripción</th></tr></thead>
                        <tbody>
                            <?php foreach ($alumnos as $a): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($a['codigo_estudiante']); ?></td>
                                    <td><?php echo htmlspecialchars($a['apellido'] . ', ' . $a['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($a['horario']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($a['fecha_matricula'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="no-print"><?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?></div>
</div>
<style>@media print { .no-print { display: none !important; } .break-page { break-inside: avoid; } }</style>