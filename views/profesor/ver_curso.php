<?php
/*
 * Archivo: views/profesor/ver_curso.php
 * Propósito: Vista para que el profesor ingrese/edite calificaciones.
 * (Actualizado a 'nota3' y con cálculo de Promedio)
 */
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Registrar Calificaciones: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h2>
        <a href="index.php?controller=Profesor&action=verCursosCalificaciones" class="btn btn-secondary">Volver a Cursos</a>
    </div>
    <hr>
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
                        <th>Nota 3</th>
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
                            echo '<td><input type="number" class="form-control" name="calificaciones[' . $id_est . '][nota3]" value="' . htmlspecialchars($n3) . '" min="0" max="20" step="0.1"></td>';
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
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary btn-lg">Guardar Calificaciones</button>
            </div>
        <?php endif; ?>
    </form>
</div>