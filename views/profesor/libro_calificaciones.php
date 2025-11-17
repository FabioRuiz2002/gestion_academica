<?php
/*
 * Archivo: views/profesor/libro_calificaciones.php
 * (CORREGIDO: 'class.' y botón "Volver" estandarizado)
 */
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2>Libro de Calificaciones:</h2>
            <h3 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h3>
        </div>
        <a href="index.php?controller=Profesor&action=generarReporteCurso&id_curso=<?php echo $infoCurso['id_curso']; ?>" 
           class="btn btn-danger" 
           target="_blank">
            <i class="fas fa-file-pdf me-2"></i> Descargar Sábana de Notas (PDF)
        </a>
    </div>

    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <div>
            <h5 class="alert-heading">Sincronizar Promedio de Tareas</h5>
            <p class="mb-0">Haga clic aquí para calcular el promedio de todas las tareas calificadas y actualizar la columna "Prom. Tareas".</p>
        </div>
        <a href="index.php?controller=Profesor&action=sincronizarTareas&id_curso=<?php echo $infoCurso['id_curso']; ?>" 
           class="btn btn-primary btn-lg" 
           onclick="return confirm('Esto recalculará el promedio de tareas para TODOS los estudiantes de este curso. ¿Continuar?');">
            <i class="fas fa-sync-alt me-2"></i> Sincronizar
        </a>
    </div>
    
    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . htmlspecialchars($_SESSION['success_message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>
    
    <form action="index.php?controller=Profesor&action=guardarCalificaciones" method="POST">
        <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($infoCurso['id_curso']); ?>">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Estudiante</th>
                        <th>Email</th>
                        <th>Nota 1</th>
                        <th>Nota 2</th>
                        <th>Prom. Tareas (Auto)</th>
                        <th>Promedio Final</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($listaEstudiantes)) {
                        foreach ($listaEstudiantes as $est) {
                            $id_est = $est['id_usuario'];
                            $notas = $calificaciones[$id_est] ?? null;
                            $n1 = $notas['nota1'] ?? 0;
                            $n2 = $notas['nota2'] ?? 0;
                            $n3 = $notas['nota3'] ?? 0;
                            $prom = ($n1 + $n2 + $n3) / 3;
                            
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($est['nombre'] . ' ' . $est['apellido']) . '</td>';
                            echo '<td>' . htmlspecialchars($est['email']) . '</td>';
                            echo '<td><input type="number" class="form-control" name="calificaciones[' . $id_est . '][nota1]" value="' . htmlspecialchars($n1) . '" min="0" max="20" step="0.1"></td>';
                            echo '<td><input type="number" class="form-control" name="calificaciones[' . $id_est . '][nota2]" value="' . htmlspecialchars($n2) . '" min="0" max="20" step="0.1"></td>';
                            echo '<td><input type="number" class="form-control" name="calificaciones[' . $id_est . '][nota3]" value="' . htmlspecialchars($n3) . '" readonly disabled></td>';
                            echo '<td class="text-center align-middle"><strong>' . number_format($prom, 2) . '</strong></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center">No hay estudiantes matriculados en este curso.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between mt-3">
            
            <div>
                <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
            </div>
            
            <?php if (!empty($listaEstudiantes)): ?>
                <button type="submit" class="btn btn-primary btn-lg">Guardar (Nota 1 y 2)</button>
            <?php endif; ?>
        </div>
    </form>
</div>