<?php /* Archivo: views/profesor/bienvenida_profesor.php */ ?>
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="h-100 p-5 text-white bg-dark rounded-3">
                <h2>Bienvenido, Profesor <?php echo htmlspecialchars($_SESSION['apellido']); ?></h2>
                <p>Panel de gestión académica. Seleccione un curso para comenzar.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <h4 class="mb-3 border-bottom pb-2">Mis Cursos Asignados</h4>
            <?php if (empty($cursosAgrupados)): ?>
                <div class="alert alert-info">No tienes cursos asignados actualmente.</div>
            <?php else: ?>
                <div class="accordion" id="accordionCursos">
                    <?php foreach ($cursosAgrupados as $facultad => $escuelas): $fid = md5($facultad); ?>
                        <?php foreach ($escuelas as $escuela => $planes): ?>
                            <?php foreach ($planes as $plan => $ciclos): ?>
                                <?php foreach ($ciclos as $ciclo => $cursos): ?>
                                    <div class="card mb-3 shadow-sm border-start border-primary border-4">
                                        <div class="card-body">
                                            <h6 class="text-muted text-uppercase small"><?php echo htmlspecialchars($escuela . ' - ' . $plan); ?> (Ciclo <?php echo $ciclo; ?>)</h6>
                                            <div class="list-group list-group-flush">
                                                <?php foreach ($cursos as $curso): ?>
                                                    <a href="index.php?controller=Profesor&action=panelCurso&id_curso=<?php echo $curso['id_curso']; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h5 class="mb-1 text-primary"><?php echo htmlspecialchars($curso['nombre_curso']); ?></h5>
                                                            <small><i class="far fa-clock me-1"></i> <?php echo htmlspecialchars($curso['horario']); ?></small>
                                                        </div>
                                                        <i class="fas fa-chevron-right text-muted"></i>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <h4 class="mb-3 border-bottom pb-2">Entregas Recientes</h4>
            <?php if (empty($listaEntregasPendientes)): ?>
                <p class="text-muted">No hay entregas nuevas sin calificar.</p>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($listaEntregasPendientes as $entrega): ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?php echo htmlspecialchars($entrega['nombre_tarea']); ?></h6>
                                <small class="text-muted"><?php echo date('d/m', strtotime($entrega['fecha_entrega'])); ?></small>
                            </div>
                            <p class="mb-1 small"><?php echo htmlspecialchars($entrega['nombre'] . ' ' . $entrega['apellido']); ?></p>
                            <small class="text-primary"><?php echo htmlspecialchars($entrega['nombre_curso']); ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>