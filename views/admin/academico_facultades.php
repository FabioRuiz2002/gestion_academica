<?php /* Archivo: views/admin/academico_facultades.php */ ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Facultades</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearFacultadModal"><i class="fas fa-plus"></i> Nueva</button>
    </div>
    <table class="table table-striped">
        <thead class="table-dark"><tr><th>Nombre</th><th>Acciones</th></tr></thead>
        <tbody>
            <?php while($row = $listaFacultades->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre_facultad']); ?></td>
                    <td>
                        <a href="index.php?controller=Academico&action=verFacultad&id_facultad=<?php echo $row['id_facultad']; ?>" class="btn btn-info btn-sm">Ver Escuelas</a>
                        <button class="btn btn-warning btn-sm" onclick="editF(<?php echo $row['id_facultad']; ?>)" data-bs-toggle="modal" data-bs-target="#editarFacultadModal"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm" onclick="delF(<?php echo $row['id_facultad']; ?>)"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="crearFacultadModal"><div class="modal-dialog"><div class="modal-content"><form action="index.php?controller=Academico&action=crearFacultad" method="POST"><div class="modal-body"><input type="text" name="nombre_facultad" class="form-control" placeholder="Nombre" required></div><div class="modal-footer"><button class="btn btn-primary">Guardar</button></div></form></div></div></div>

<div class="modal fade" id="editarFacultadModal"><div class="modal-dialog"><div class="modal-content"><form action="index.php?controller=Academico&action=editarFacultad" method="POST"><div class="modal-body"><input type="hidden" name="edit_id_facultad" id="ef_id"><input type="text" name="edit_nombre_facultad" id="ef_nom" class="form-control" required></div><div class="modal-footer"><button class="btn btn-primary">Actualizar</button></div></form></div></div></div>

<script>
function editF(id) { fetch('index.php?controller=Academico&action=getFacultad&id='+id).then(r=>r.json()).then(d=>{ document.getElementById('ef_id').value=d.id_facultad; document.getElementById('ef_nom').value=d.nombre_facultad; }); }
function delF(id) { if(confirm('¿Eliminar?')) fetch('index.php?controller=Academico&action=eliminarFacultad', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'id='+id}).then(r=>location.reload()); }
</script>