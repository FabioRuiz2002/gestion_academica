<?php 
/*
 * Archivo: views/layouts/header.php
 * Propósito: Cabecera principal, Navbar dinámica.
 * (CORREGIDO: Añadido menú desplegable con 'Mi Perfil')
 */
if (session_status() == PHP_SESSION_NONE) { session_start(); }

$esAutenticado = isset($_SESSION['id_usuario']);
$nombreUsuario = $esAutenticado ? htmlspecialchars($_SESSION['nombre']) : '';
$rol = $esAutenticado ? $_SESSION['id_rol'] : 0;

// Lógica del enlace principal
$homeLink = 'index.php'; // Default (login page)
if ($esAutenticado) {
    if ($rol == 1) { $homeLink = 'index.php?controller=Admin&action=index'; }
    elseif ($rol == 2) { $homeLink = 'index.php?controller=Profesor&action=index'; }
    elseif ($rol == 3) { $homeLink = 'index.php?controller=Estudiante&action=index'; }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Académica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .footer { margin-top: 50px; padding: 20px 0; background-color: #f8f9fa; color: #6c757d; }
        .card .border-left-primary { border-left: .25rem solid #4e73df!important; }
        .card .border-left-success { border-left: .25rem solid #1cc88a!important; }
        .card .border-left-info { border-left: .25rem solid #36b9cc!important; }
        .card .border-left-warning { border-left: .25rem solid #f6c23e!important; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?php echo $homeLink; ?>">
            <i class="fas fa-graduation-cap me-2"></i> Gestión Académica
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if ($esAutenticado): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle btn btn-outline-light" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i> Hola, <b><?php echo $nombreUsuario; ?></b>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="<?php echo $homeLink; ?>">
                                    <i class="fas fa-chart-line fa-fw me-2"></i> Mi Panel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.php?controller=Perfil&action=index">
                                    <i class="fas fa-key fa-fw me-2"></i> Cambiar Contraseña
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="index.php?controller=Usuario&action=logout">
                                    <i class="fas fa-sign-out-alt fa-fw me-2"></i> Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-success" href="index.php?controller=Usuario&action=index">
                            <i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container">