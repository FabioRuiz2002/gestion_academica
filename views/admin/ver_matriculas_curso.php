<?php
// Archivo: views/admin/ver_matriculas_curso.php
?>
<div class="container mt-4">
    <h2>Gestionar Matrículas</h2>
    <h3 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h3>
    <hr>
    
    <?php
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error_message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        unset($_SESSION['error_message']);
    }
    ?>

    <div class="row">
        <div class="col-md-6">
            <h4>Estudiantes No Inscritos</h4>
            <?php if (empty($listaNoInscritos)): ?>
                <div class="alert alert-info">No hay más estudiantes para matricular en este curso.</div>
            <?php else: ?>
                <form action="index.php?controller=Admin&action=matricularEstudiante" method="POST">
                    <input type="hidden" name="id_curso" value="<?php echo $infoCurso['id_curso']; ?>">
                    <div class="input-group mb-3">
                        <select name="id_estudiante" class="form-select">
                            <option value="">Selecciona un estudiante...</option>
                            <?php foreach ($listaNoInscritos as $est): ?>
                                <option value="<?php echo $est['id_usuario']; ?>">
                                    <?php echo htmlspecialchars($est['apellido'] . ', ' . $est['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i> Matricular
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <h4>Estudiantes Matriculados</h4>
            <?php if (empty($listaMatriculados)): ?>
                <div class="alert alert-secondary">Aún no hay estudiantes matriculados en este curso.</div>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($listaMatriculados as $est): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <?php echo htmlspecialchars($est['apellido'] . ', ' . $est['nombre']); ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars($est['email']); ?></small>
                            </div>
                            <a href="index.php?controller=Admin&action=desmatricularEstudiante&id_matricula=<?php echo $est['id_matricula']; ?>&id_curso=<?php echo $infoCurso['id_curso']; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Estás seguro de que deseas quitar esta matrícula?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    
    <a href="index.php?controller=Admin&action=gestionarMatriculas" class="btn btn-secondary mt-4">Volver a Cursos</a>
</div>