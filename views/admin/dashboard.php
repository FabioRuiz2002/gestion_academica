<?php
/*
 * Archivo: views/admin/dashboard.php
 * Propósito: Vista principal del dashboard del Administrador con estadísticas.
 */
?>
<div class="container mt-4">
    <h1 class="mt-4">Panel de Administrador</h1>
    <p class="lead">Bienvenido(a), 
        <b><?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']); ?></b>.
    </p>
    <hr>
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Usuarios Registrados</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $estadisticas['total_usuarios']; ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Cursos Creados</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $estadisticas['total_cursos']; ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-book fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Profesores</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $estadisticas['total_profesores']; ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-user-tie fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Estudiantes Matriculados</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $estadisticas['total_estudiantes']; ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-user-graduate fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div> <hr>
    <h2>Accesos Directos</h2>
    <div class="list-group">
        <a href="index.php?controller=Admin&action=gestionarUsuarios" class="list-group-item list-group-item-action"><i class="fas fa-users-cog me-2"></i>Gestionar Usuarios</a>
        <a href="index.php?controller=Admin&action=gestionarCursos" class="list-group-item list-group-item-action"><i class="fas fa-book-open me-2"></i>Gestionar Cursos</a>
        <a href="index.php?controller=Admin&action=gestionarMatriculas" class="list-group-item list-group-item-action"><i class="fas fa-user-check me-2"></i>Gestionar Matrículas</a>
    </div>
</div>