<?php
// Archivo: views/estudiante/mis_calificaciones.php
// (Añadido botón de Descargar PDF)
?>
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-list-alt me-2"></i> Mis Calificaciones</h2>
    
    <?php if (empty($datosCalificaciones)): ?>
        <div class="alert alert-info"><i class="fas fa-info-circle me-2"></i> No tienes calificaciones registradas aún.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Curso</th>
                        <th class="text-center">Nota 1</th>
                        <th class="text-center">Nota 2</th>
                        <th class="text-center">Nota 3</th>
                        <th class="text-center">Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datosCalificaciones as $fila): ?>
                        <?php 
                            $n1 = $fila['nota1'] ?? null;
                            $n2 = $fila['nota2'] ?? null;
                            $n3 = $fila['nota3'] ?? null;
                            $promedio = '-';
                            if (is_numeric($n1) && is_numeric($n2) && is_numeric($n3)) {
                                $prom = ($n1 + $n2 + $n3) / 3;
                                $promedio = number_format($prom, 2);
                            }
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fila['nombre_curso']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($n1 ?? '-'); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($n2 ?? '-'); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($n3 ?? '-'); ?></td>
                            <td class="text-center fw-bold"><?php echo $promedio; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="mt-3 d-flex justify-content-between">
        <a href="index.php?controller=Estudiante&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al Inicio
        </a>
        
        <?php if (!empty($datosCalificaciones)): ?>
            <a href="index.php?controller=Estudiante&action=generarReporte" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf me-2"></i> Descargar Reporte PDF
            </a>
        <?php endif; ?>
    </div>
</div>