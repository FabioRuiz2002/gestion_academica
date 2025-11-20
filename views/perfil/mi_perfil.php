<?php
/* Archivo: views/perfil/mi_perfil.php */
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-user-circle me-2"></i> Mi Perfil</h2>
    </div>
    <hr>

    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['mensaje']['tipo']; ?> alert-dismissible fade show">
            <?php echo $_SESSION['mensaje']['texto']; unset($_SESSION['mensaje']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Datos Personales</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-user fa-5x text-secondary border rounded-circle p-3 bg-light"></i>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Nombre Completo:</strong><br> 
                            <?php echo htmlspecialchars(($datosUsuario['nombre'] ?? '') . ' ' . ($datosUsuario['apellido'] ?? '')); ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Correo Electrónico:</strong><br> 
                            <?php echo htmlspecialchars($datosUsuario['email'] ?? ''); ?>
                        </li>
                        <li class="list-group-item">
                            <strong>DNI:</strong><br> 
                            <?php echo htmlspecialchars($datosUsuario['dni'] ?? ''); ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Rol:</strong><br> 
                            <span class="badge bg-info text-dark">
                                <?php 
                                    $rol = $datosUsuario['id_rol'] ?? 0;
                                    if ($rol == 1) echo "Administrador";
                                    elseif ($rol == 2) echo "Profesor";
                                    elseif ($rol == 3) echo "Estudiante";
                                    else echo "Desconocido";
                                ?>
                            </span>
                        </li>
                        <?php if(($datosUsuario['id_rol'] ?? 0) == 3): ?>
                            <li class="list-group-item">
                                <strong>Código de Estudiante:</strong><br> 
                                <?php echo htmlspecialchars($datosUsuario['codigo_estudiante'] ?? 'Sin asignar'); ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-lock me-2"></i> Seguridad</h5>
                </div>
                <div class="card-body">
                    <h6 class="card-subtitle mb-3 text-muted">Cambiar Contraseña</h6>
                    <form action="index.php?controller=Perfil&action=cambiarPassword" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="nuevo_password" class="form-control" required minlength="8" placeholder="Mínimo 8 caracteres">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirmar Contraseña</label>
                            <input type="password" name="confirmar_password" class="form-control" required minlength="8" placeholder="Repite la contraseña">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key me-2"></i> Actualizar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white border-0">
                    <small class="text-muted"><i class="fas fa-info-circle"></i> Si cambias tu contraseña, deberás usar la nueva la próxima vez que inicies sesión.</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="javascript:history.back()" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver
        </a>
    </div>
</div>