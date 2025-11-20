<?php /* Archivo: views/profesor/gestionar_materiales.php */ ?>
<div class="container mt-4">
    <h3>Materiales: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h3>
    <hr>
    <?php if (isset($_SESSION['success_message'])) echo '<div class="alert alert-success alert-dismissible fade show">' . $_SESSION['success_message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>'; unset($_SESSION['success_message']); ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">Subir Archivo</div>
                <div class="card-body">
                    <form action="index.php?controller=Profesor&action=subirMaterial" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_curso" value="<?php echo $infoCurso['id_curso']; ?>">
                        <div class="mb-3">
                            <label class="form-label">Seleccionar Archivo</label>
                            <input type="file" name="archivo_material" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Subir</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">Archivos del Curso</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php if ($listaMateriales->rowCount() == 0): ?><li class="list-group-item text-muted">No hay archivos subidos.</li><?php endif; ?>
                        <?php while ($mat = $listaMateriales->fetch(PDO::FETCH_ASSOC)): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-alt text-secondary me-2"></i>
                                    <a href="<?php echo $mat['ruta_archivo']; ?>" target="_blank"><?php echo htmlspecialchars($mat['nombre_archivo']); ?></a>
                                </div>
                                <a href="index.php?controller=Profesor&action=eliminarMaterial&id_material=<?php echo $mat['id_material']; ?>&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar?')"><i class="fas fa-trash"></i></a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>