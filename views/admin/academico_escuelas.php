<?php /* Archivo: views/admin/academico_escuelas.php */ ?>
<div class="container mt-4">
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php?controller=Academico&action=index">Facultades</a></li><li class="breadcrumb-item active"><?php echo htmlspecialchars($infoFacultad['nombre_facultad']); ?></li></ol></nav>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Escuelas de <?php echo htmlspecialchars($infoFacultad['nombre_facultad']); ?></h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearEscuelaModal"><i class="fas fa-plus"></i> Nueva Escuela</button>
    </div>
    <table class="table table-striped">
        <thead class="table-dark"><tr><th>Nombre</th><th>Acciones</th></tr></thead>
        <tbody>
            <?php while($row = $listaEscuelas->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre_escuela']); ?></td>
                    <td>
                        <a href="index.php?controller=Academico&action=verEscuela&id_escuela=<?php echo $row['id_escuela']; ?>" class="btn btn-info btn-sm">Planes</a>
                        <button class="btn btn-warning btn-sm" onclick="editE(<?php echo $row['id_escuela']; ?>)" data-bs-toggle="modal" data-bs-target="#editarEscuelaModal"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm" onclick="delE(<?php echo $row['id_escuela']; ?>)"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="crearEscuelaModal"><div class="modal-dialog"><div class="modal-content"><form action="index.php?controller=Academico&action=crearEscuela" method="POST"><input type="hidden" name="id_facultad" value="<?php echo $infoFacultad['id_facultad']; ?>"><div class="modal-body"><input type="text" name="nombre_escuela" class="form-control" placeholder="Nombre Escuela" required></div><div class="modal-footer"><button class="btn btn-primary">Guardar</button></div></form></div></div></div>

<div class="modal fade" id="editarEscuelaModal"><div class="modal-dialog"><div class="modal-content"><form action="index.php?controller=Academico&action=editarEscuela" method="POST"><input type="hidden" name="id_facultad_redirect" value="<?php echo $infoFacultad['id_facultad']; ?>"><input type="hidden" name="edit_id_facultad" value="<?php echo $infoFacultad['id_facultad']; ?>"><div class="modal-body"><input type="hidden" name="edit_id_escuela" id="ee_id"><input type="text" name="edit_nombre_escuela" id="ee_nom" class="form-control" required></div><div class="modal-footer"><button class="btn btn-primary">Actualizar</button></div></form></div></div></div>

<script>
function editE(id) { fetch('index.php?controller=Academico&action=getEscuela&id='+id).then(r=>r.json()).then(d=>{ document.getElementById('ee_id').value=d.id_escuela; document.getElementById('ee_nom').value=d.nombre_escuela; }); }
function delE(id) { if(confirm('¿Eliminar?')) fetch('index.php?controller=Academico&action=eliminarEscuela', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'id='+id+'&id_facultad_redirect=<?php echo $infoFacultad['id_facultad']; ?>'}).then(r=>location.reload()); }
</script>