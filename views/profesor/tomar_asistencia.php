<?php
/*
 * Archivo: views/profesor/tomar_asistencia.php
 * (AÑADIDA: Lógica para bloquear el formulario si no es la hora)
 * (CORREGIDO: Errores 'class.')
 */
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2>Tomar Asistencia:</h2>
            <h3 class="text-primary"><?php echo htmlspecialchars($curso_info['nombre_curso']); ?></h3>
        </div>
    </div>
    
    <?php
    if (isset($_SESSION['mensaje'])) {
        echo '<div class="alert alert-' . $_SESSION['mensaje']['tipo'] . ' alert-dismissible fade show" role="alert">' . $_SESSION['mensaje']['texto'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['mensaje']);
    }
    ?>

    <form action="index.php?controller=Profesor&action=tomarAsistencia" method="POST" class="mb-4 p-3 bg-light rounded border">
        <input type="hidden" name="id_curso" value="<?php echo $curso_info['id_curso']; ?>">
        <div class="row align-items-end">
            <div class="col-md-4">
                <label for="fecha_asistencia" class="form-label"><b>Seleccionar Fecha:</b></label>
                <input type="date" name="fecha_asistencia" id="fecha_asistencia" class="form-control" value="<?php echo htmlspecialchars($fecha); ?>" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-2"></i> Consultar</button>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="index.php?controller=Profesor&action=verHistoricoAsistencia&id_curso=<?php echo $curso_info['id_curso']; ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-history me-2"></i> Ver Historial Completo
                </a>
            </div>
        </div>
    </form>
    
    <?php if ($asistencia_tomada): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Estudiante</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros_asistencia as $reg): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reg['nombre'] . ' ' . $reg['apellido']); ?></td>
                            <td>
                                <?php
                                $estado = $reg['estado'];
                                $badge_class = 'bg-secondary';
                                if ($estado == 'Presente') $badge_class = 'bg-success';
                                if ($estado == 'Ausente') $badge_class = 'bg-danger';
                                if ($estado == 'Tardanza') $badge_class = 'bg-warning text-dark';
                                echo "<span class='badge {$badge_class}'>{$estado}</span>";
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php elseif ($asistenciaBloqueada): ?>
        <div class="alert alert-danger">
            <h4 class="alert-heading"><i class="fas fa-lock me-2"></i> Asistencia Bloqueada</h4>
            <p><?php echo $mensajeBloqueo; ?></p>
        </div>

    <?php else: ?>
        <form action="index.php?controller=Profesor&action=guardarAsistencia" method="POST">
            <input type="hidden" name="id_curso" value="<?php echo $curso_info['id_curso']; ?>">
            <input type="hidden" name="fecha_asistencia" value="<?php echo htmlspecialchars($fecha); ?>">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Estudiante</th>
                            <th>Presente</th>
                            <th>Ausente</th>
                            <th>Tardanza</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantes as $est): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($est['nombre'] . ' ' . $est['apellido']); ?></td>
                                <?php $id_est = $est['id_usuario']; ?>
                                <td><input class="form-check-input" type="radio" name="asistencia[<?php echo $id_est; ?>]" value="Presente" checked></td>
                                <td><input class="form-check-input" type="radio" name="asistencia[<?php echo $id_est; ?>]" value="Ausente"></td>
                                <td><input class="form-check-input" type="radio" name="asistencia[<?php echo $id_est; ?>]" value="Tardanza"></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <div>
                    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
                </div>
                <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save me-2"></i> Guardar Asistencia</button>
            </div>
        </form>
    <?php endif; ?>

    <?php if ($asistencia_tomada || $asistenciaBloqueada): ?>
        <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
    <?php endif; ?>
</div>