<?php
// Archivo: views/estudiante/mis_calificaciones.php
// (MODIFICADO: 'Nota 3' ahora es 'Promedio de Tareas')
?>
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-list-alt me-2"></i> Mis Calificaciones</h2>
    <h3 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h3>
    
    <?php if (empty($datosCalificacion) || (!is_numeric($datosCalificacion['nota1']) && !is_numeric($datosCalificacion['nota2']) && !is_numeric($datosCalificacion['nota3']))): ?>
        <div class="alert alert-info mt-4">
            <i class="fas fa-info-circle me-2"></i> Aún no tienes calificaciones registradas para este curso.
        </div>
    <?php else: ?>
        <?php
            $n1 = $datosCalificacion['nota1'] ?? 0;
            $n2 = $datosCalificacion['nota2'] ?? 0;
            $n3 = $datosCalificacion['nota3'] ?? 0; // Este es el Prom. Tareas
            $promedio = ($n1 + $n2 + $n3) / 3;
        ?>
        <div class="table-responsive mt-4">
            <table class="table table-striped table-hover table-bordered shadow-sm" style="max-width: 600px;">
                <thead class="table-dark">
                    <tr>
                        <th>Tipo de Nota</th>
                        <th class="text-center">Calificación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Nota 1</td>
                        <td class="text-center"><?php echo htmlspecialchars($n1); ?></td>
                    </tr>
                    <tr>
                        <td>Nota 2</td>
                        <td class="text-center"><?php echo htmlspecialchars($n2); ?></td>
                    </tr>
                    <tr>
                        <td>Promedio de Tareas</td> <td class="text-center"><?php echo htmlspecialchars(number_format($n3, 2)); ?></td>
                    </tr>
                    <tr class="table-light">
                        <td class="fw-bold">Promedio Final</td>
                        <td class="text-center fw-bold fs-5"><?php echo number_format($promedio, 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="index.php?controller=Estudiante&action=panelCurso&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al Panel del Curso
        </a>
    </div>
</div>