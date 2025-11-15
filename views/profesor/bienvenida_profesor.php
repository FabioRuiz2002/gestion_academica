<?php
/*
 * Archivo: views/profesor/bienvenida_profesor.php
 * (Actualizado a dashboard dinámico con cursos agrupados)
 */
?>
<div class.container mt-4">
    <h1 class="mt-4">Panel de Profesor</h1>
    <p class="lead">Bienvenido(a), 
        <b><?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']); ?></b>.
    </p>
    <hr>
    
    <div class="row mt-4">
        
        <div class="col-lg-8">
            <h4><i class="fas fa-book-open me-2"></i> Mis Cursos Asignados</h4>
            <?php if (empty($cursosAgrupados)): ?>
                <div class="alert alert-info">No tienes cursos asignados actualmente.</div>
            <?php else: ?>
                
                <div class="accordion" id="accordionFacultades">
                    <?php foreach ($cursosAgrupados as $nombreFacultad => $escuelas): ?>
                        <div class.accordion-item">
                            <h2 class="accordion-header" id="heading-fac-<?php echo preg_replace('/[^a-z0-9]/i', '', $nombreFacultad); ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#collapse-fac-<?php echo preg_replace('/[^a-z0-9]/i', '', $nombreFacultad); ?>">
                                    <i class="fas fa-building me-2"></i> <?php echo htmlspecialchars($nombreFacultad); ?>
                                </button>
                            </h2>
                            <div id="collapse-fac-<?php echo preg_replace('/[^a-z0-9]/i', '', $nombreFacultad); ?>" class="accordion-collapse collapse" 
                                 data-bs-parent="#accordionFacultades">
                                <div class="accordion-body">
                                    
                                    <div class="accordion" id="accordionEscuelas-<?php echo preg_replace('/[^a-z0-9]/i', '', $nombreFacultad); ?>">
                                        <?php foreach ($escuelas as $nombreEscuela => $planes): ?>
                                            <div class.accordion-item">
                                                <h2 class="accordion-header" id="heading-esc-<?php echo preg_replace('/[^a-z0-9]/i', '', $nombreEscuela); ?>">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                                            data-bs-target="#collapse-esc-<?php echo preg_replace('/[^a-z0-9]/i', '', $nombreEscuela); ?>">
                                                        <i class="fas fa-graduation-cap me-2"></i> <?php echo htmlspecialchars($nombreEscuela); ?>
                                                    </button>
                                                </h2>
                                                <div id="collapse-esc-<?php echo preg_replace('/[^a-z0-9]/i', '', $nombreEscuela); ?>" class="accordion-collapse collapse" 
                                                     data-bs-parent="#accordionEscuelas-<?php echo preg_replace('/[^a-z0-9]/i', '', $nombreFacultad); ?>">
                                                    <div class="accordion-body">
                                                        
                                                        <?php foreach ($planes as $nombrePlan => $ciclos): ?>
                                                            <h6 class.text-muted"><?php echo htmlspecialchars($nombrePlan); ?></h6>
                                                            <?php foreach ($ciclos as $ciclo => $cursos): ?>
                                                                <strong class="d-block mt-2">Ciclo <?php echo $ciclo; ?></strong>
                                                                <div class.list-group">
                                                                    <?php foreach ($cursos as $curso): ?>
                                                                        <a href="index.php?controller=Profesor&action=panelCurso&id_curso=<?php echo $curso['id_curso']; ?>" 
                                                                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                                            <div>
                                                                                <h5 class="mb-1"><?php echo htmlspecialchars($curso['nombre_curso']); ?></h5>
                                                                                <small class="text-muted"><?php echo htmlspecialchars($curso['horario']); ?></small>
                                                                            </div>
                                                                            <i class="fas fa-chevron-right text-primary"></i>
                                                                        </a>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php endforeach; ?>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>
        </div>
        
        <div class="col-lg-4">
            <div class.card shadow-sm border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-inbox me-2"></i> Entregas por Calificar</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($listaEntregasPendientes)): ?>
                        <p class="text-muted text-center">¡Excelente! No tienes entregas pendientes de calificar.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($listaEntregasPendientes as $entrega): ?>
                                <a href="index.php?controller=Profesor&action=verEntregas&id_tarea=<?php echo $entrega['id_tarea']; ?>" 
                                   class="list-group-item list-group-item-action">
                                    <strong><?php echo htmlspecialchars($entrega['apellido'] . ', ' . $entrega['nombre']); ?></strong><br>
                                    <small class="text-muted">
                                        Entregó: "<?php echo htmlspecialchars($entrega['nombre_tarea']); ?>"
                                        (<?php echo htmlspecialchars($entrega['nombre_curso']); ?>)
                                    </small>
                                </a>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
    </div>
</div>