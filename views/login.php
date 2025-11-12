<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="fw-light my-4">Inicio de Sesión</h3>
            </div>
            <div class="card-body">
                
                <?php
                // Mostrar mensaje de error si existe (enviado desde el controlador)
                if (isset($_GET['error']) && $_GET['error'] == 1) {
                    echo '<div class="alert alert-danger" role="alert">
                            Email o contraseña incorrectos.
                        </div>';
                }
                ?>

                <form action="index.php?controller=Usuario&action=autenticar" method="POST">
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputEmail" name="email" type="email" placeholder="name@example.com" required />
                        <label for="inputEmail">Correo Electrónico</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Contraseña" required />
                        <label for="inputPassword">Contraseña</label>
                    </div>
                    
                    <div class="d-grid mt-4 mb-0">
                        <button type="submit" class="btn btn-primary btn-lg">Ingresar</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                <div class="small">¿Olvidaste tu contraseña? (Función no implementada)</div>
            </div>
        </div>
    </div>
</div>