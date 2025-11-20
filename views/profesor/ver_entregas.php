<?php /* Archivo: views/profesor/ver_entregas.php */ ?>
<div class="container mt-4">
    <h3>Entregas: <span class="text-muted"><?php echo htmlspecialchars($infoTarea['titulo']); ?></span></h3>
    <hr>
    <?php if (isset($_SESSION['success_message'])) echo '<div class="alert alert-success alert-dismissible fade show">' . $_SESSION['success_message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>'; unset($_SESSION['success_message']); ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>Estudiante</th><th>Fecha Entrega</th><th>Archivo</th><th>Calificación</th><th>Acción</th></tr>
            </thead>
            <tbody>
                <?php if ($listaEntregas->rowCount() == 0): ?><tr><td colspan="5" class="text-center">No hay entregas aún.</td></tr><?php endif; ?>
                <?php while ($e = $listaEntregas->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($e['nombre'] . ' ' . $e['apellido']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($e['fecha_entrega'])); ?></td>
                        <td><a href="<?php echo $e['ruta_archivo']; ?>" class="btn btn-sm btn-outline-secondary" download><i class="fas fa-download"></i> Descargar</a></td>
                        <form action="index.php?controller=Profesor&action=calificarEntrega" method="POST">
                            <input type="hidden" name="id_entrega" value="<?php echo $e['id_entrega']; ?>">
                            <input type="hidden" name="id_tarea" value="<?php echo $id_tarea; ?>">
                            <td>
                                <input type="number" name="calificacion" class="form-control form-control-sm" style="width: 80px;" value="<?php echo $e['calificacion']; ?>" min="0" max="20" step="0.1">
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary btn-sm">Calificar</button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-3"><a href="javascript:history.back()" class="btn btn-secondary">Volver</a></div>
</div>