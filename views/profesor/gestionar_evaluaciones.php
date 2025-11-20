<?php /* Archivo: views/profesor/gestionar_evaluaciones.php */ ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="text-muted">Configuración</h5>
            <h3>Criterios de Evaluación: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h3>
        </div>
    </div>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Nuevo Criterio</h5>
                </div>
                <div class="card-body">
                    <form action="index.php?controller=Profesor&action=crearEvaluacion" method="POST">
                        <input type="hidden" name="id_curso" value="<?php echo $infoCurso['id_curso']; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Examen Parcial" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Descripción (Opcional)</label>
                            <textarea name="descripcion" class="form-control" rows="2" placeholder="Temas abarcados..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Peso / Porcentaje (%)</label>
                            <div class="input-group">
                                <input type="number" name="porcentaje" class="form-control" min="1" max="<?php echo max(1, $porcentajeRestante); ?>" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text mt-2">
                                Disponible: <span class="badge <?php echo $porcentajeRestante > 0 ? 'bg-success' : 'bg-danger'; ?>"><?php echo $porcentajeRestante; ?>%</span>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100" <?php echo ($porcentajeRestante <= 0) ? 'disabled' : ''; ?>>
                            <i class="fas fa-save me-2"></i> Guardar Criterio
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-secondary"><i class="fas fa-list-ul me-2"></i>Estructura Actual</h5>
                    <span class="badge <?php echo $porcentajeRestante == 0 ? 'bg-success' : 'bg-warning text-dark'; ?> fs-6">
                        Total Asignado: <?php echo 100 - $porcentajeRestante; ?>%
                    </span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($listaEvaluaciones)): ?>
                        <div class="p-5 text-center text-muted">
                            <i class="fas fa-clipboard-list fa-3x mb-3 text-light-gray"></i><br>
                            No hay criterios definidos para este curso.<br>
                            Comienza agregando uno desde el formulario.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Criterio</th>
                                        <th>Descripción</th>
                                        <th class="text-center">Peso</th>
                                        <th class="text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($listaEvaluaciones as $eval): ?>
                                        <tr>
                                            <td class="fw-bold"><?php echo htmlspecialchars($eval['nombre']); ?></td>
                                            <td class="small text-muted"><?php echo htmlspecialchars($eval['descripcion']); ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-info text-dark px-3 py-2 rounded-pill"><?php echo $eval['porcentaje']; ?>%</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="index.php?controller=Profesor&action=eliminarEvaluacion&id_evaluacion=<?php echo $eval['id_evaluacion']; ?>&id_curso=<?php echo $infoCurso['id_curso']; ?>" 
                                                   class="btn btn-outline-danger btn-sm" 
                                                   onclick="return confirm('¿Estás seguro de eliminar este criterio? Se borrarán todas las notas asociadas a él.')"
                                                   title="Eliminar Criterio">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($listaEvaluaciones)): ?>
                    <div class="card-footer bg-light text-end p-3">
                        <a href="index.php?controller=Profesor&action=libroCalificaciones&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-success">
                            <i class="fas fa-th-list me-2"></i> Ir al Libro de Notas
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>