<?php 
/* Archivo: views/estudiante/ver_matriculas.php */
define('NOTA_APROBATORIA', 10.5);
if (!isset($horarioHelper)) { require_once 'utils/HorarioHelper.php'; $horarioHelper = new HorarioHelper(); }
?>
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-book-open me-2"></i> Gestión de Matrícula</h2>

    <?php if (isset($matriculaBloqueada) && $matriculaBloqueada == 1): ?>
        <div class="alert alert-warning border-2 border-warning d-flex align-items-center mb-4">
            <i class="fas fa-lock fa-2x me-3 text-warning"></i>
            <div>
                <h5 class="alert-heading fw-bold mb-0">Matrícula Cerrada</h5>
                <p class="mb-0">Tu matrícula ha sido finalizada o bloqueada por la administración. No puedes agregar más cursos.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['mensaje']['tipo']; ?> alert-dismissible fade show">
            <?php echo $_SESSION['mensaje']['texto']; unset($_SESSION['mensaje']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($infoPlan) && $infoPlan): ?>
        <div class="card mb-4 border-primary">
            <div class="card-header bg-primary text-white"><h5 class="mb-0"><i class="fas fa-check-circle me-2"></i> Mis Cursos Matriculados</h5></div>
            <div class="card-body p-0">
                <?php if (empty($misCursos)): ?>
                    <div class="p-3 text-muted text-center">Sin cursos matriculados.</div>
                <?php else: ?>
                    <table class="table table-striped mb-0">
                        <thead><tr><th>Curso</th><th>Ciclo</th><th>Horario</th><th>Profesor</th><th class="text-end">Estado</th></tr></thead>
                        <tbody>
                            <?php foreach ($misCursos as $mc): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo htmlspecialchars($mc['nombre_curso']); ?></td>
                                    <td><?php echo htmlspecialchars($mc['ciclo'] ?? '-'); ?></td>
                                    <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($mc['horario']); ?></span></td>
                                    <td><?php echo htmlspecialchars($mc['nombre_profesor'] . ' ' . $mc['apellido_profesor']); ?></td>
                                    <td class="text-end"><span class="badge bg-success">Inscrito</span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($matriculaBloqueada == 0): ?>
            <h4 class="text-secondary mt-5">Cursos Disponibles para Agregar</h4>
            <hr>
            <?php if (empty($cursosPorCiclo)): ?>
                <div class="alert alert-success text-center">¡Has inscrito todo lo disponible!</div>
            <?php else: ?>
                <div class="accordion" id="accordionMalla">
                    <?php foreach ($cursosPorCiclo as $ciclo => $cursos): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="h-<?php echo $ciclo; ?>">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#c-<?php echo $ciclo; ?>">Ciclo <?php echo $ciclo; ?></button>
                            </h2>
                            <div id="c-<?php echo $ciclo; ?>" class="accordion-collapse collapse show" data-bs-parent="#accordionMalla">
                                <div class="accordion-body p-0">
                                    <table class="table table-hover mb-0 align-middle">
                                        <thead class="table-light"><tr><th>Curso</th><th>Horario</th><th>Profesor</th><th class="text-end">Acción</th></tr></thead>
                                        <tbody>
                                            <?php foreach ($cursos as $curso): 
                                                $isBloq = false; $msg = "";
                                                if (isset($reglasPrerequisitos[$curso['id_curso']])) {
                                                    foreach ($reglasPrerequisitos[$curso['id_curso']] as $req) {
                                                        $id_req = $req['id_curso_requisito'];
                                                        if (!isset($historialPromedios[$id_req]) || $historialPromedios[$id_req] < NOTA_APROBATORIA) { $isBloq = true; $msg = "Falta: " . $req['nombre_curso_requisito']; break; }
                                                    }
                                                }
                                                if (!$isBloq && $horarioHelper->verificarConflictoConLista($curso['horario'], $horariosActuales)) { $isBloq = true; $msg = "Cruce horario"; }
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($curso['nombre_curso']); ?></td>
                                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($curso['horario'] ?? '-'); ?></span></td>
                                                <td><?php echo htmlspecialchars($curso['nombre_profesor'] . ' ' . $curso['apellido_profesor']); ?></td>
                                                <td class="text-end">
                                                    <?php if ($isBloq): ?>
                                                        <button class="btn btn-secondary btn-sm" disabled>Bloqueado</button>
                                                        <small class="text-danger d-block"><?php echo $msg; ?></small>
                                                    <?php else: ?>
                                                        <form method="POST" action="index.php?controller=Estudiante&action=matricularCurso">
                                                            <input type="hidden" name="id_curso" value="<?php echo $curso['id_curso']; ?>">
                                                            <button type="submit" class="btn btn-outline-success btn-sm"><i class="fas fa-plus"></i> Agregar</button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?> <?php else: ?>
        <div class="alert alert-danger">Sin plan de estudios.</div>
    <?php endif; ?>
    
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>