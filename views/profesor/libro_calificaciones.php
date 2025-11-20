<?php /* Archivo: views/profesor/libro_calificaciones.php */ ?>
<div class="container-fluid mt-4">
    <h2>Libro de Calificaciones: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h2>
    <hr>
    <?php if (isset($_SESSION['success_message'])) echo '<div class="alert alert-success alert-dismissible fade show">' . $_SESSION['success_message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>'; unset($_SESSION['success_message']); ?>

    <?php if (empty($listaEvaluaciones)): ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-circle me-2"></i> No has definido criterios de evaluación.
            <a href="index.php?controller=Profesor&action=gestionarEvaluaciones&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="alert-link">Configúralos aquí</a>.
        </div>
    <?php else: ?>
        <div class="alert alert-info"><i class="fas fa-info-circle me-2"></i> Guarda las notas columna por columna.</div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th class="text-start">Estudiante</th>
                        <?php foreach ($listaEvaluaciones as $eval): ?>
                            <th style="min-width: 150px;">
                                <?php echo htmlspecialchars($eval['nombre']); ?><br>
                                <span class="badge bg-secondary"><?php echo $eval['porcentaje']; ?>%</span>
                            </th>
                        <?php endforeach; ?>
                        <th class="bg-secondary text-white" style="width: 100px;">Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaEstudiantes as $est): 
                        $id_est = $est['id_usuario']; $promedio = 0; $pct_total = 0;
                    ?>
                        <tr>
                            <td class="text-start fw-bold"><?php echo htmlspecialchars($est['apellido'] . ', ' . $est['nombre']); ?></td>
                            
                            <?php foreach ($listaEvaluaciones as $eval): 
                                $id_eval = $eval['id_evaluacion'];
                                $nota = $notasGuardadas[$id_est][$id_eval] ?? null;
                                if ($nota !== null) { $promedio += $nota * ($eval['porcentaje'] / 100); $pct_total += $eval['porcentaje']; }
                            ?>
                                <td class="p-1">
                                    <input type="number" class="form-control form-control-sm text-center" 
                                           name="notas[<?php echo $id_est; ?>]" value="<?php echo $nota; ?>" 
                                           step="0.1" min="0" max="20" placeholder="-" 
                                           form="form-col-<?php echo $id_eval; ?>">
                                </td>
                            <?php endforeach; ?>
                            
                            <td class="bg-light fw-bold"><?php echo ($pct_total == 100) ? number_format($promedio, 2) : '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($listaEstudiantes)): ?><tr><td colspan="100%">No hay estudiantes matriculados.</td></tr><?php endif; ?>
                </tbody>
                
                <tfoot>
                    <tr>
                        <td></td>
                        <?php foreach ($listaEvaluaciones as $eval): ?>
                            <td class="p-1">
                                <form action="index.php?controller=Profesor&action=guardarNotasColumna" method="POST" id="form-col-<?php echo $eval['id_evaluacion']; ?>">
                                    <input type="hidden" name="id_curso" value="<?php echo $infoCurso['id_curso']; ?>">
                                    <input type="hidden" name="id_evaluacion" value="<?php echo $eval['id_evaluacion']; ?>">
                                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-save"></i> Guardar</button>
                                </form>
                            </td>
                        <?php endforeach; ?>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between mt-4 mb-5">
        <a href="index.php?controller=Profesor&action=panelCurso&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-secondary btn-lg">
            <i class="fas fa-arrow-left me-2"></i> Volver al Panel
        </a>

        <a href="index.php?controller=Profesor&action=gestionarEvaluaciones&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-success btn-lg">
            <i class="fas fa-plus-circle me-2"></i> Añadir Criterios a <?php echo htmlspecialchars($infoCurso['nombre_curso']); ?>
        </a>
    </div>
</div>