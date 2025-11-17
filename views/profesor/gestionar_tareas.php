<?php
/*
 * Archivo: views/profesor/gestionar_tareas.php
 * (CORREGIDO: 'class.' y botón "Volver" estandarizado)
 */
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2>Tareas y Entregas:</h2>
            <h3 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h3>
        </div>
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
    
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">Crear Nueva Tarea</h5>
        </div>
        <div class="card-body">
            <form action="index.php?controller=Profesor&action=crearTarea" method="POST">
                <input type="hidden" name="id_curso" value="<?php echo $infoCurso['id_curso']; ?>">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título de la Tarea</label>
                    <input type="text" class="form-control" name="titulo" id="titulo" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                    <textarea class="form-control" name="descripcion" id="descripcion" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="fecha_limite" class="form-label">Fecha Límite (Opcional)</label>
                    <input type="datetime-local" class="form-control" name="fecha_limite" id="fecha_limite">
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Crear Tarea</button>
            </form>
        </div>
    </div>
    
    <h4>Tareas Creadas</h4>
    <?php if (empty($listaTareas)): ?>
        <div class="alert alert-info">Aún no has creado ninguna tarea para este curso.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Título</th>
                        <th>Fecha Límite</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaTareas as $tarea): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tarea['titulo']); ?></td>
                            <td>
                                <?php echo $tarea['fecha_limite'] ? date('d/m/Y H:i', strtotime($tarea['fecha_limite'])) : 'Sin límite'; ?>
                            </td>
                            <td>
                                <a href="index.php?controller=Profesor&action=verEntregas&id_tarea=<?php echo $tarea['id_tarea']; ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-inbox me-1"></i> Ver Entregas
                                </a>
                                <a href="index.php?controller=Profesor&action=eliminarTarea&id_tarea=<?php echo $tarea['id_tarea']; ?>&id_curso=<?php echo $infoCurso['id_curso']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Estás seguro de que deseas eliminar esta tarea? Todas las entregas de los alumnos serán borradas permanentemente.');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>