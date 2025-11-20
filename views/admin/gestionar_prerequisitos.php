<?php /* Archivo: views/admin/gestionar_prerequisitos.php */ ?>
<div class="container mt-4">
    <h3>Prerrequisitos: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h3>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <form action="index.php?controller=Academico&action=addPrerequisito" method="POST">
                <input type="hidden" name="id_plan" value="<?php echo $infoPlan['id_plan_estudio']; ?>">
                <input type="hidden" name="id_curso_principal" value="<?php echo $infoCurso['id_curso']; ?>">
                <div class="input-group">
                    <select name="id_curso_requisito" class="form-select" required>
                        <option value="">Seleccione requisito...</option>
                        <?php foreach($listaCursosDisponibles as $c): ?><option value="<?php echo $c['id_curso']; ?>"><?php echo htmlspecialchars($c['nombre_curso']); ?></option><?php endforeach; ?>
                    </select>
                    <button class="btn btn-success">Añadir</button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <ul class="list-group">
                <?php foreach($listaRequisitos as $r): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <?php echo htmlspecialchars($r['nombre_requisito']); ?>
                        <a href="index.php?controller=Academico&action=deletePrerequisito&id_prerequisito=<?php echo $r['id_prerequisito']; ?>&id_curso=<?php echo $infoCurso['id_curso']; ?>&id_plan=<?php echo $infoPlan['id_plan_estudio']; ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <a href="index.php?controller=Academico&action=gestionarMalla&id_plan=<?php echo $infoPlan['id_plan_estudio']; ?>" class="btn btn-secondary mt-3">Volver a Malla</a>
</div>