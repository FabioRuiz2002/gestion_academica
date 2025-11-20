<?php /* Archivo: views/admin/gestionar_malla.php */ ?>
<div class="container mt-4">
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php?controller=Academico&action=index">Inicio</a></li><li class="breadcrumb-item active">Malla Curricular</li></ol></nav>
    <h3 class="text-primary"><?php echo htmlspecialchars($infoPlan['nombre_plan']); ?></h3>
    <hr>
    <?php if(isset($_SESSION['error_message_academico'])): ?><div class="alert alert-danger"><?php echo $_SESSION['error_message_academico']; unset($_SESSION['error_message_academico']); ?></div><?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Asignar Curso</div>
                <div class="card-body">
                    <form action="index.php?controller=Academico&action=asignarCursoPlan" method="POST">
                        <input type="hidden" name="id_plan_estudio" value="<?php echo $infoPlan['id_plan_estudio']; ?>">
                        <div class="mb-3">
                            <label>Curso</label>
                            <select name="id_curso" class="form-select" required>
                                <?php foreach($cursosDisponibles as $c): ?><option value="<?php echo $c['id_curso']; ?>"><?php echo htmlspecialchars($c['nombre_curso']); ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3"><label>Ciclo</label><input type="number" name="ciclo" class="form-control" min="1" max="10" required></div>
                        <button class="btn btn-success w-100">Asignar</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <h4>Cursos en Malla</h4>
            <ul class="list-group">
                <?php $ciclo=0; foreach($cursosEnPlan as $c): 
                    if($c['ciclo'] != $ciclo) { $ciclo=$c['ciclo']; echo "<li class='list-group-item list-group-item-secondary fw-bold'>Ciclo $ciclo</li>"; }
                ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo htmlspecialchars($c['nombre_curso']); ?></strong><br>
                        <small class="text-muted">Horario: <?php echo $c['horario']; ?> | Prof: <?php echo $c['apellido_profesor']; ?> 
                        <a href="#" onclick="setProf(<?php echo $c['id_curso']; ?>, <?php echo $c['id_profesor']; ?>)" data-bs-toggle="modal" data-bs-target="#profModal"><i class="fas fa-pen"></i></a></small>
                    </div>
                    <div>
                        <a href="index.php?controller=Academico&action=gestionarPrerequisitos&id_curso=<?php echo $c['id_curso']; ?>&id_plan=<?php echo $infoPlan['id_plan_estudio']; ?>" class="btn btn-info btn-sm text-white">Req</a>
                        <a href="index.php?controller=Academico&action=quitarCursoPlan&id_cursos_plan=<?php echo $c['id_cursos_plan']; ?>&id_plan=<?php echo $infoPlan['id_plan_estudio']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Quitar?')"><i class="fas fa-trash"></i></a>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<div class="modal fade" id="profModal"><div class="modal-dialog"><div class="modal-content"><form action="index.php?controller=Academico&action=editarProfesorCurso" method="POST"><input type="hidden" name="id_plan" value="<?php echo $infoPlan['id_plan_estudio']; ?>"><input type="hidden" name="id_curso" id="modal_id_curso"><div class="modal-body"><label>Seleccionar Profesor</label><select name="id_profesor" id="modal_id_profesor" class="form-select"><?php foreach($listaProfesores as $p): ?><option value="<?php echo $p['id_usuario']; ?>"><?php echo htmlspecialchars($p['apellido'].' '.$p['nombre']); ?></option><?php endforeach; ?></select></div><div class="modal-footer"><button class="btn btn-primary">Guardar</button></div></form></div></div></div>

<script>
function setProf(cursoId, profId) { document.getElementById('modal_id_curso').value=cursoId; document.getElementById('modal_id_profesor').value=profId; }
</script>