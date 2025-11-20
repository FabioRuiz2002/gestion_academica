<?php /* Archivo: views/profesor/gestionar_evaluaciones.php */ ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Criterios de Evaluación: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h3>
    </div>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white"><h5 class="mb-0">Nuevo Criterio</h5></div>
                <div class="card-body">
                    <form action="index.php?controller=Profesor&action=crearEvaluacion" method="POST">
                        <input type="hidden" name="id_curso" value="<?php echo $infoCurso['id_curso']; ?>">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Examen Parcial" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Peso / Porcentaje (%)</label>
                            <input type="number" name="porcentaje" class="form-control" min="1" max="<?php echo max(1, $porcentajeRestante); ?>" required>
                            <div class="form-text">Disponible: <strong><?php echo $porcentajeRestante; ?>%</strong></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" <?php echo ($porcentajeRestante <= 0) ? 'disabled' : ''; ?>>
                            <i class="fas fa-plus"></i> Añadir
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between">
                    <h5 class="mb-0">Estructura de Notas</h5>
                    <span class="badge <?php echo $porcentajeRestante == 0 ? 'bg-success' : 'bg-warning text-dark'; ?>">Total: <?php echo 100 - $porcentajeRestante; ?>%</span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($listaEvaluaciones)): ?>
                        <div class="p-4 text-center text-muted">No hay criterios definidos.</div>
                    <?php else: ?>
                        <table class="table table-hover mb-0">
                            <thead class="table-light"><tr><th>Criterio</th><th>Descripción</th><th>Peso</th><th>Acción</th></tr></thead>
                            <tbody>
                                <?php foreach ($listaEvaluaciones as $eval): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($eval['nombre']); ?></td>
                                        <td class="small text-muted"><?php echo htmlspecialchars($eval['descripcion']); ?></td>
                                        <td><span class="badge bg-info text-dark"><?php echo $eval['porcentaje']; ?>%</span></td>
                                        <td>
                                            <a href="index.php?controller=Profesor&action=eliminarEvaluacion&id_evaluacion=<?php echo $eval['id_evaluacion']; ?>&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Borrar? Se perderán las notas asociadas.')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                <?php if (!empty($listaEvaluaciones)): ?>
                    <div class="card-footer bg-white">
                        <a href="index.php?controller=Profesor&action=libroCalificaciones&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-success float-end">Ir al Libro de Notas <i class="fas fa-arrow-right"></i></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>