<?php
/*
 * Archivo: views/perfil/index.php
 * Propósito: Formulario para cambiar la contraseña.
 */
?>
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="card-title text-center mb-4">
                    <i class="fas fa-key me-2"></i> Cambiar mi Contraseña
                </h2>
                
                <?php
                if (isset($_SESSION['error_message'])) {
                    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                    unset($_SESSION['error_message']);
                }
                if (isset($_SESSION['success_message'])) {
                    echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
                    unset($_SESSION['success_message']);
                }
                ?>
                
                <form action="index.php?controller=Perfil&action=cambiarPassword" method="POST">
                    <div class="mb-3">
                        <label for="pass_actual" class="form-label">Contraseña Actual</label>
                        <input type="password" class="form-control" id="pass_actual" name="pass_actual" required>
                        <small class="form-text text-muted">Ingresa tu contraseña actual para verificar tu identidad.</small>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label for="pass_nuevo" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="pass_nuevo" name="pass_nuevo" required>
                    </div>
                    <div class="mb-3">
                        <label for="pass_confirmar" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="pass_confirmar" name="pass_confirmar" required>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Actualizar Contraseña</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>