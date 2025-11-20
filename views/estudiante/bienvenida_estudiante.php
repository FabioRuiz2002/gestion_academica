<?php /* Archivo: views/estudiante/bienvenida_estudiante.php */ ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="jumbotron p-4 p-md-5 text-white rounded bg-dark mb-4">
                <div class="col-md-12 px-0">
                    <h1 class="display-4 fst-italic">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>
                    <p class="lead my-3">Panel de Estudiante. Gestiona tu matrícula, revisa tus notas y entrega tus tareas.</p>
                    <p class="lead mb-0"><a href="index.php?controller=Estudiante&action=verMatriculas" class="text-white fw-bold">Ir a Matrícula...</a></p>
                </div>
            </div>

            <h3 class="mb-3">Próximas Tareas</h3>
            <?php if (empty($listaTareasProximas)): ?>
                <div class="alert alert-success">¡No tienes tareas pendientes próximas!</div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($listaTareasProximas as $tarea): ?>
                        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><?php echo htmlspecialchars($tarea['titulo']); ?></h5>
                                <small class="text-muted">Curso: <?php echo htmlspecialchars($tarea['nombre_curso']); ?></small>
                            </div>
                            <span class="badge bg-warning text-dark rounded-pill">
                                <?php echo date('d/m/Y', strtotime($tarea['fecha_limite'])); ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">Mi Horario</div>
                <div class="card-body">
                    <?php if (empty($cursosMatriculados)): ?>
                        <p class="text-muted">No estás matriculado en cursos con horario asignado.</p>
                    <?php else: ?>
                        <ul class="list-unstyled">
                            <?php foreach ($cursosMatriculados as $horario): ?>
                                <li class="mb-2"><i class="fas fa-clock me-2 text-primary"></i> <?php echo htmlspecialchars($horario); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>