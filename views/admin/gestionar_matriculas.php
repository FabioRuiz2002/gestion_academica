<?php
// Archivo: views/admin/gestionar_matriculas.php
?>
<div class="container mt-4">
    <h2 class="mb-4">Gestionar Matrículas</h2>
    <p class="lead">Selecciona un curso para ver y administrar los estudiantes matriculados.</p>
    
    <div class="list-group">
        <?php while ($row = $listaCursos->fetch(PDO::FETCH_ASSOC)): ?>
            <a href="index.php?controller=Admin&action=verMatriculasCurso&id_curso=<?php echo $row['id_curso']; ?>" 
               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1"><?php echo htmlspecialchars($row['nombre_curso']); ?></h5>
                    <small class="text-muted"><?php echo htmlspecialchars($row['nombre_profesor'] . ' ' . $row['apellido_profesor']); ?> (<?php echo $row['anio_academico']; ?>)</small>
                </div>
                <i class="fas fa-chevron-right"></i>
            </a>
        <?php endwhile; ?>
    </div>
    
    <a href="index.php?controller=Admin&action=index" class="btn btn-secondary mt-4">Volver al Panel</a>
</div>