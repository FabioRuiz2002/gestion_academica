<?php /* Archivo: views/estudiante/mis_asistencias.php */ ?>
<div class="container mt-4">
    <h3>Mi Asistencia: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h3>
    <hr>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (empty($asistencias)): ?>
                <div class="alert alert-info text-center">No hay registros de asistencia para este curso.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($asistencias as $row): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($row['fecha'])); ?></td>
                                    <td>
                                        <?php 
                                        if ($row['estado'] == 'P') echo '<span class="badge bg-success px-3 py-2">Presente</span>';
                                        elseif ($row['estado'] == 'T') echo '<span class="badge bg-warning text-dark px-3 py-2">Tardanza</span>';
                                        else echo '<span class="badge bg-danger px-3 py-2">Falta</span>';
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>