<?php
/*
 * Archivo: views/profesor/libro_calificaciones.php
 * Propósito: Vista para el Libro de Calificaciones (N1, N2, Prom. Tareas)
 * (MODIFICADO: Botón 'Volver al Curso' movido y renombrado)
 */
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2>Libro de Calificaciones:</h2>
            <h3 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h3>
        </div>
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
                            $n3 = $notas['nota3'] ?? 0; // Este es ahora el Prom. Tareas
                            $prom = ($n1 + $n2 + $n3) / 3;
                            
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($est['nombre'] . ' ' . $est['apellido']) . '</td>';
                            echo '<td>' . htmlspecialchars($est['email']) . '</td>';
                            
                            // Nota 1 (Editable)
                            echo '<td><input type="number" class="form-control" name="calificaciones[' . $id_est . '][nota1]" value="' . htmlspecialchars($n1) . '" min="0" max="20" step="0.1"></td>';
                            
                            // Nota 2 (Editable)
                            echo '<td><input type="number" class="form-control" name="calificaciones[' . $id_est . '][nota2]" value="' . htmlspecialchars($n2) . '" min="0" max="20" step="0.1"></td>';
                            
                            // Nota 3 (Deshabilitada)
                            echo '<td><input type="number" class="form-control" name="calificaciones[' . $id_est . '][nota3]" value="' . htmlspecialchars($n3) . '" readonly disabled></td>';
                            
                            // Promedio Final
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
        <?php if (!empty($listaEstudiantes)): ?>
            <div class="d-flex justify-content-between mt-3">
                <a href="index.php?controller=Profesor&action=panelCurso&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i> Volver al Curso
                </a>
                <button type="submit" class="btn btn-primary btn-lg">Guardar (Nota 1 y 2)</button>
            </div>
        <?php endif; ?>
    </form>
</div>