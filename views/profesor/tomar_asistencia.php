<?php /* Archivo: views/profesor/tomar_asistencia.php */ ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Asistencia: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h3>
        <a href="index.php?controller=Profesor&action=verHistoricoAsistencia&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-info text-white">Ver Historial</a>
    </div>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <form method="POST" action="index.php?controller=Profesor&action=tomarAsistencia" class="row g-3 align-items-center">
                <input type="hidden" name="id_curso" value="<?php echo $infoCurso['id_curso']; ?>">
                <div class="col-auto"><label class="fw-bold">Fecha:</label></div>
                <div class="col-auto">
                    <input type="date" name="fecha_asistencia" value="<?php echo $fecha; ?>" class="form-control" onchange="this.form.submit()">
                </div>
                <div class="col-auto">
                    <?php if ($asistencia_tomada): ?><span class="badge bg-success">Asistencia ya registrada</span><?php else: ?><span class="badge bg-warning text-dark">Pendiente</span><?php endif; ?>
                </div>
            </form>
        </div>
        
        <div class="card-body">
            <?php if ($asistenciaBloqueada): ?>
                <div class="alert alert-warning text-center"><?php echo $mensajeBloqueo; ?></div>
            <?php elseif (empty($estudiantes)): ?>
                <div class="alert alert-info text-center">No hay estudiantes matriculados.</div>
            <?php else: ?>
                <form action="index.php?controller=Profesor&action=guardarAsistencia" method="POST">
                    <input type="hidden" name="id_curso" value="<?php echo $infoCurso['id_curso']; ?>">
                    <input type="hidden" name="fecha_asistencia" value="<?php echo $fecha; ?>">
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light"><tr><th>Estudiante</th><th class="text-center">Presente</th><th class="text-center">Tardanza</th><th class="text-center">Falta</th></tr></thead>
                            <tbody>
                                <?php foreach ($estudiantes as $est): 
                                    $estado = 'P'; // Default
                                    if ($asistencia_tomada) {
                                        foreach ($registros_asistencia as $reg) {
                                            if ($reg['id_estudiante'] == $est['id_usuario']) { $estado = $reg['estado']; break; }
                                        }
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($est['apellido'] . ', ' . $est['nombre']); ?></td>
                                        <td class="text-center"><input class="form-check-input bg-success border-success" type="radio" name="asistencia[<?php echo $est['id_usuario']; ?>]" value="P" <?php echo ($estado=='P')?'checked':''; ?>></td>
                                        <td class="text-center"><input class="form-check-input bg-warning border-warning" type="radio" name="asistencia[<?php echo $est['id_usuario']; ?>]" value="T" <?php echo ($estado=='T')?'checked':''; ?>></td>
                                        <td class="text-center"><input class="form-check-input bg-danger border-danger" type="radio" name="asistencia[<?php echo $est['id_usuario']; ?>]" value="F" <?php echo ($estado=='F')?'checked':''; ?>></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Guardar Asistencia</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>