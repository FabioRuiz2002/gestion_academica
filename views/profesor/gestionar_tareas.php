<?php /* Archivo: views/profesor/gestionar_tareas.php */ ?>
<div class="container mt-4">
    <h3>Tareas: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h3>
    <hr>
    <?php if (isset($_SESSION['success_message'])) echo '<div class="alert alert-success alert-dismissible fade show">' . $_SESSION['success_message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>'; unset($_SESSION['success_message']); ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">Nueva Tarea</div>
                <div class="card-body">
                    <form action="index.php?controller=Profesor&action=crearTarea" method="POST">
                        <input type="hidden" name="id_curso" value="<?php echo $infoCurso['id_curso']; ?>">
                        <div class="mb-3"><label>Título</label><input type="text" name="titulo" class="form-control" required></div>
                        <div class="mb-3"><label>Descripción</label><textarea name="descripcion" class="form-control" rows="3"></textarea></div>
                        <div class="mb-3"><label>Fecha Límite</label><input type="date" name="fecha_limite" class="form-control" required></div>
                        <button type="submit" class="btn btn-primary w-100">Crear Tarea</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">Lista de Tareas</div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if ($listaTareas->rowCount() == 0): ?><div class="p-3 text-muted">No hay tareas.</div><?php endif; ?>
                        <?php while ($t = $listaTareas->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><?php echo htmlspecialchars($t['titulo']); ?></h5>
                                    <small>Vence: <?php echo date('d/m/Y', strtotime($t['fecha_limite'])); ?></small>
                                </div>
                                <p class="mb-1 text-muted small"><?php echo htmlspecialchars($t['descripcion']); ?></p>
                                <div class="mt-2">
                                    <a href="index.php?controller=Profesor&action=verEntregas&id_tarea=<?php echo $t['id_tarea']; ?>" class="btn btn-sm btn-info text-white">Ver Entregas</a>
                                    <a href="index.php?controller=Profesor&action=eliminarTarea&id_tarea=<?php echo $t['id_tarea']; ?>&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>