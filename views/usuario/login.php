<?php
// Archivo: views/usuario/login.php
?>
<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="card-title text-center mb-4">Iniciar Sesión</h2>
                
                <?php
                if (isset($_SESSION['error_message'])) {
                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
                    unset($_SESSION['error_message']);
                }
                ?>
                
                <form action="index.php?controller=Usuario&action=login" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Acceder</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                 <small>admin@correo.com | 123456</small><br>
                 <small>profe@correo.com | 123456</small><br>
                 <small>estudiante@correo.com | 123456</small>
            </div>
        </div>
    </div>
</div>