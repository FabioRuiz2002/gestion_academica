<?php
/*
 * Archivo: views/estudiante/ver_matriculas.php
 * (Botón "Volver" estandarizado con el componente)
 */
 
 define('NOTA_APROBATORIA', 10.5); 
?>
<div class="container mt-4">
    <h2 class.mb-4"><i class="fas fa-book-open me-2"></i> Matrícula en Línea</h2>
    
    <?php 
    if (isset($_SESSION['mensaje'])): ?>
        <div class.alert alert-<?php echo $_SESSION['mensaje']['tipo']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['mensaje']['texto']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <?php if (isset($infoPlan) && $infoPlan): ?>
        <h3 class.text-primary"><?php echo htmlspecialchars($infoPlan['nombre_plan']); ?></h3>
        <p class.lead">
            Escuela de: <?php echo htmlspecialchars($infoPlan['nombre_escuela']); ?>
            (<?php echo htmlspecialchars($infoPlan['nombre_facultad']); ?>)
        </p>
        <hr>
        
        <?php if (empty($cursosPorCiclo)): ?>
            <div class.alert alert-success mt-4">
                <i class.fas fa-check-circle me-2"></i> ¡Felicidades! Ya te has matriculado en todos los cursos disponibles de tu malla.
            </div>
        <?php else: ?>
            <p>Selecciona los cursos en los que deseas matricularte. El sistema validará los prerrequisitos.</p>
            <div class.accordion" id="accordionMalla">
                
                <?php foreach ($cursosPorCiclo as $ciclo => $cursos): ?>
                    <div class.accordion-item">
                        <h2 class.accordion-header" id="heading-<?php echo $ciclo; ?>">
                            <button class.accordion-button fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $ciclo; ?>" aria-expanded="true">
                                Ciclo <?php echo $ciclo; ?>
                            </button>
                        </h2>
                        <div id="collapse-<?php echo $ciclo; ?>" class.accordion-collapse collapse show" data-bs-parent="#accordionMalla">
                            <div class.accordion-body">
                                <table class.table table-striped table-hover align-middle">
                                    <thead class.table-light">
                                        <tr>
                                            <th>Curso</th>
                                            <th>Horario</th>
                                            <th>Profesor</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cursos as $curso): ?>
                                            <?php
                                            $isBloqueado = false;
                                            $requisitosFaltantes = [];
                                            if (isset($reglasPrerequisitos[$curso['id_curso']])) {
                                                foreach ($reglasPrerequisitos[$curso['id_curso']] as $req) {
                                                    $id_curso_req = $req['id_curso_requisito'];
                                                    if (!isset($historialPromedios[$id_curso_req]) || $historialPromedios[$id_curso_req] < NOTA_APROBATORIA) {
                                                        $isBloqueado = true;
                                                        $requisitosFaltantes[] = htmlspecialchars($req['nombre_curso_requisito']);
                                                    }
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($curso['nombre_curso']); ?></td>
                                                <td><?php echo htmlspecialchars($curso['horario'] ?? 'No definido'); ?></td>
                                                <td><?php echo htmlspecialchars($curso['nombre_profesor'] . ' ' . $curso['apellido_profesor']); ?></td>
                                                <td>
                                                    <?php if ($isBloqueado): ?>
                                                        <button class.btn btn-secondary btn-sm" disabled>
                                                            <i class.fas fa-lock me-1"></i> Bloqueado
                                                        </button>
                                                        <div class.form-text text-danger small">
                                                            Requiere: <?php echo implode(', ', $requisitosFaltantes); ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <form method="POST" action="index.php?controller=Estudiante&action=matricularCurso" class.mb-0">
                                                            <input type="hidden" name="id_curso" value="<?php echo $curso['id_curso']; ?>">
                                                            <button type="submit" class.btn btn-success btn-sm">
                                                                <i class.fas fa-user-plus me-1"></i> Matricular
                                                            </button>
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
        
    <?php endif; ?>
    
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
    
</div>