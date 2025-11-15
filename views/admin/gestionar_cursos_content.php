<?php
/*
 * Archivo: views/admin/gestionar_cursos_content.php
 * Propósito: Contenido de la pestaña 4 (Cursos)
 * (Este archivo es llamado por 'gestionar_academico.php')
 */
?>

<p>Repositorio general de todos los cursos que se pueden dictar. Estos cursos luego se asignan a una Malla.</p>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Todos los Cursos</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearCursoModal">
        <i class="fas fa-plus me-2"></i> Crear Nuevo Curso
    </button>
</div>

<?php
if (isset($_SESSION['error_message_curso'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error_message_curso'] . '</div>';
    unset($_SESSION['error_message_curso']);
}
?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre del Curso</th>
                <th>Horario</th>
                <th>Profesor</th>
                <th>Año</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Reiniciamos el puntero de $listaCursos 
            if ($listaCursos) {
                $listaCursos->execute(); // Re-ejecuta la consulta
                while ($row = $listaCursos->fetch(PDO::FETCH_ASSOC)): 
            ?>
                <tr>
                    <td><?php echo $row['id_curso']; ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_curso']); ?></td>
                    <td><?php echo htmlspecialchars($row['horario']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_profesor'] . ' ' . $row['apellido_profesor']); ?></td>
                    <td><?php echo htmlspecialchars($row['anio_academico']); ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="cargarDatosCurso(<?php echo $row['id_curso']; ?>)" data-bs-toggle="modal" data-bs-target="#editarCursoModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="confirmarEliminarCurso(<?php echo $row['id_curso']; ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php 
                endwhile; 
            }
            ?>
        </tbody>
    </table>
</div>