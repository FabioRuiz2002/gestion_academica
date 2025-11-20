<?php /* Archivo: views/estudiante/bienvenida_estudiante.php */ ?>
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 bg-dark text-white rounded shadow-sm d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold">¡Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!</h1>
                    <p class="mb-0 text-light opacity-75">Bienvenido a tu aula virtual.</p>
                </div>
                <a href="index.php?controller=Estudiante&action=verMatriculas" class="btn btn-primary fw-bold">
                    <i class="fas fa-book-medical me-2"></i> Matrícula en Línea
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <h4 class="mb-3 text-primary"><i class="fas fa-graduation-cap me-2"></i>Mis Cursos</h4>
            
            <?php if (empty($misCursos)): ?>
                <div class="alert alert-info py-4 text-center">
                    <i class="fas fa-info-circle fa-2x mb-3 d-block"></i>
                    Aún no te has matriculado en ningún curso. 
                    <br><a href="index.php?controller=Estudiante&action=verMatriculas" class="alert-link">Ir a Matrícula</a>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($misCursos as $curso): ?>
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm border-0 hover-card">
                                <div class="card-body">
                                    <h5 class="card-title text-truncate text-primary fw-bold" title="<?php echo htmlspecialchars($curso['nombre_curso']); ?>">
                                        <?php echo htmlspecialchars($curso['nombre_curso']); ?>
                                    </h5>
                                    <p class="card-text small text-muted mb-2">
                                        <i class="far fa-clock me-1"></i> <?php echo htmlspecialchars($curso['horario']); ?>
                                    </p>
                                    <p class="card-text small mb-3">
                                        <i class="far fa-user me-1"></i> Prof. <?php echo htmlspecialchars($curso['apellido_profesor']); ?>
                                    </p>
                                    <a href="index.php?controller=Estudiante&action=panelCurso&id_curso=<?php echo $curso['id_curso']; ?>" class="btn btn-outline-primary w-100 stretched-link">
                                        Entrar al Curso <i class="fas fa-chevron-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <h5 class="mb-0 text-danger"><i class="fas fa-exclamation-circle me-2"></i>Pendientes</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php if (empty($listaTareasProximas)): ?>
                        <div class="list-group-item text-center text-muted py-4">
                            <i class="fas fa-check-circle text-success mb-2"></i><br>¡Todo al día!
                        </div>
                    <?php else: ?>
                        <?php foreach ($listaTareasProximas as $tarea): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 text-truncate" title="<?php echo htmlspecialchars($tarea['titulo']); ?>">
                                        <?php echo htmlspecialchars($tarea['titulo']); ?>
                                    </h6>
                                    <small class="text-danger fw-bold">
                                        <?php echo date('d/m', strtotime($tarea['fecha_limite'])); ?>
                                    </small>
                                </div>
                                <small class="text-muted d-block text-truncate">
                                    <?php echo htmlspecialchars($tarea['nombre_curso']); ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($misCursos)): ?>
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <h5 class="mb-0 text-secondary"><i class="far fa-calendar-alt me-2"></i>Mi Horario</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <?php foreach ($misCursos as $c): ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><?php echo substr($c['nombre_curso'], 0, 15) . '...'; ?></span>
                                <span class="fw-bold"><?php echo $c['horario']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<style>
.hover-card:hover { transform: translateY(-3px); transition: transform 0.2s; border-color: #0d6efd !important; }
</style>