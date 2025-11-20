<?php /* Archivo: views/admin/dashboard.php */ ?>
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h2>Panel de Administración</h2>
            <p class="text-muted">Bienvenido al sistema de gestión académica.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-users me-2"></i>Usuarios</h5>
                    <h2 class="display-4"><?php echo $estadisticas['total_usuarios']; ?></h2>
                    <p class="card-text">Total registrados</p>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="index.php?controller=Admin&action=gestionarUsuarios" class="text-white text-decoration-none stretched-link">Gestionar</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-book me-2"></i>Cursos</h5>
                    <h2 class="display-4"><?php echo $estadisticas['total_cursos']; ?></h2>
                    <p class="card-text">En repositorio</p>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="index.php?controller=Admin&action=gestionarCursos" class="text-white text-decoration-none stretched-link">Gestionar</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-chalkboard-teacher me-2"></i>Profesores</h5>
                    <h2 class="display-4"><?php echo $estadisticas['total_profesores']; ?></h2>
                    <p class="card-text">Activos</p>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="index.php?controller=Admin&action=gestionarUsuarios" class="text-white text-decoration-none stretched-link">Ver Lista</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-info h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-user-graduate me-2"></i>Estudiantes</h5>
                    <h2 class="display-4"><?php echo $estadisticas['total_estudiantes']; ?></h2>
                    <p class="card-text">Matriculados</p>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="#" class="text-white text-decoration-none">Ver Reportes</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-sitemap me-2 text-primary"></i>Gestión Académica</h5>
                </div>
                <div class="card-body">
                    <p>Administra la estructura de la universidad: Facultades, Escuelas y Planes de Estudio.</p>
                    <a href="index.php?controller=Academico&action=index" class="btn btn-outline-primary">Ir a Gestión Académica</a>
                </div>
            </div>
        </div>
    </div>
</div>