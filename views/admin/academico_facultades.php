<?php
/*
 * Archivo: views/admin/academico_facultades.php
 * (Botón "Volver" estandarizado)
 */
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Gestión Académica: Facultades</h2>
        
        <a href="index.php?controller=Admin&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver
        </a>
    </div>
    <p class="lead">Este es el primer nivel. Haz clic en "Ver Escuelas" para gestionar las carreras de una facultad.</p>
    
    <?php
    if (isset($_SESSION['error_message_academico'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error_message_academico'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        unset($_SESSION['error_message_academico']);
    }
    ?>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Facultades</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearFacultadModal">
                <i class="fas fa-plus me-1"></i> Crear Facultad
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre de la Facultad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaFacultades as $fac): ?>
                        <tr>
                            <td><?php echo $fac['id_facultad']; ?></td>
                            <td><?php echo htmlspecialchars($fac['nombre_facultad']); ?></td>
                            <td>
                                <a href="index.php?controller=Academico&action=verFacultad&id_facultad=<?php echo $fac['id_facultad']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i> Ver Escuelas
                                </a>
                                <button class="btn btn-warning btn-sm" onclick="cargarDatosFacultad(<?php echo $fac['id_facultad']; ?>)" data-bs-toggle="modal" data-bs-target="#editarFacultadModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="confirmarEliminarFacultad(<?php echo $fac['id_facultad']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="crearFacultadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Academico&action=crearFacultad" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nueva Facultad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_facultad" class="form-label">Nombre de la Facultad</label>
                        <input type="text" class="form-control" id="nombre_facultad" name="nombre_facultad" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Facultad</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editarFacultadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Academico&action=editarFacultad" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Facultad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_facultad" name="edit_id_facultad">
                    <div class="mb-3">
                        <label for="edit_nombre_facultad" class="form-label">Nombre de la Facultad</label>
                        <input type="text" class="form-control" id="edit_nombre_facultad" name="edit_nombre_facultad" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function cargarDatosFacultad(id) {
    fetch('index.php?controller=Academico&action=getFacultad&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.error) { alert(data.error); } 
            else {
                document.getElementById('edit_id_facultad').value = data.id_facultad;
                document.getElementById('edit_nombre_facultad').value = data.nombre_facultad;
            }
        });
}
function confirmarEliminarFacultad(id) {
    if (confirm('¿Estás seguro de que deseas eliminar esta facultad? Solo funcionará si no tiene escuelas asociadas.')) {
        fetch('index.php?controller=Academico&action=eliminarFacultad', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) { window.location.reload(); } 
            else { alert('Error al eliminar: ' + data.message); }
        });
    }
}
</script>