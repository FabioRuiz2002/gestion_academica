<?php /* Archivo: views/admin/academico_planes.php */ ?>
<div class="container mt-4">
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php?controller=Academico&action=index">Facultades</a></li><li class="breadcrumb-item"><a href="index.php?controller=Academico&action=verFacultad&id_facultad=<?php echo $infoFacultad['id_facultad']; ?>"><?php echo $infoFacultad['nombre_facultad']; ?></a></li><li class="breadcrumb-item active"><?php echo $infoEscuela['nombre_escuela']; ?></li></ol></nav>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Planes de Estudio</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearPlanModal"><i class="fas fa-plus"></i> Nuevo Plan</button>
    </div>
    <table class="table table-striped">
        <thead class="table-dark"><tr><th>Nombre</th><th>Año</th><th>Acciones</th></tr></thead>
        <tbody>
            <?php while($row = $listaPlanes->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre_plan']); ?></td>
                    <td><?php echo htmlspecialchars($row['anio']); ?></td>
                    <td>
                        <a href="index.php?controller=Academico&action=gestionarMalla&id_plan=<?php echo $row['id_plan_estudio']; ?>" class="btn btn-success btn-sm">Malla</a>
                        <button class="btn btn-warning btn-sm" onclick="editP(<?php echo $row['id_plan_estudio']; ?>)" data-bs-toggle="modal" data-bs-target="#editarPlanModal"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm" onclick="delP(<?php echo $row['id_plan_estudio']; ?>)"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="crearPlanModal"><div class="modal-dialog"><div class="modal-content"><form action="index.php?controller=Academico&action=crearPlan" method="POST"><input type="hidden" name="id_escuela" value="<?php echo $infoEscuela['id_escuela']; ?>"><div class="modal-body"><div class="mb-3"><label>Nombre</label><input type="text" name="nombre_plan" class="form-control" required></div><div class="mb-3"><label>Año</label><input type="number" name="anio" class="form-control" required></div></div><div class="modal-footer"><button class="btn btn-primary">Guardar</button></div></form></div></div></div>

<div class="modal fade" id="editarPlanModal"><div class="modal-dialog"><div class="modal-content"><form action="index.php?controller=Academico&action=editarPlan" method="POST"><input type="hidden" name="id_escuela_redirect" value="<?php echo $infoEscuela['id_escuela']; ?>"><input type="hidden" name="edit_id_escuela" value="<?php echo $infoEscuela['id_escuela']; ?>"><div class="modal-body"><input type="hidden" name="edit_id_plan_estudio" id="ep_id"><div class="mb-3"><label>Nombre</label><input type="text" name="edit_nombre_plan" id="ep_nom" class="form-control" required></div><div class="mb-3"><label>Año</label><input type="number" name="edit_anio" id="ep_anio" class="form-control" required></div></div><div class="modal-footer"><button class="btn btn-primary">Actualizar</button></div></form></div></div></div>

<script>
function editP(id) { fetch('index.php?controller=Academico&action=getPlan&id='+id).then(r=>r.json()).then(d=>{ document.getElementById('ep_id').value=d.id_plan_estudio; document.getElementById('ep_nom').value=d.nombre_plan; document.getElementById('ep_anio').value=d.anio; }); }
function delP(id) { if(confirm('¿Eliminar?')) fetch('index.php?controller=Academico&action=eliminarPlan', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'id='+id+'&id_escuela_redirect=<?php echo $infoEscuela['id_escuela']; ?>'}).then(r=>location.reload()); }
</script>