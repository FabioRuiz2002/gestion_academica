<?php
/*
 * Archivo: config/config.php
 * Propósito: Definición de constantes y configuración de la base de datos.
 * (Corregido: Se usa dirname(__DIR__) para rutas automáticas)
 */

// Configuración de la Base de Datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_gestion_academica');
define('DB_USER', 'root');
define('DB_PASS', ''); // Tu contraseña de MySQL (generalmente vacía en XAMPP)

// --- ¡SOLUCIÓN A PRUEBA DE ERRORES! ---
// Definición de rutas (Paths) usando la constante mágica __DIR__
// __DIR__ es la carpeta actual (C:\xampp\htdocs\gestion_academica\config)
// dirname(__DIR__) es la carpeta padre (C:\xampp\htdocs\gestion_academica)
define('ROOT_PATH', dirname(__DIR__) . '/'); 

define('CONTROLLER_PATH', ROOT_PATH . 'controllers/');
define('MODEL_PATH', ROOT_PATH . 'models/');
define('VIEW_PATH', ROOT_PATH . 'views/');
define('CONFIG_PATH', ROOT_PATH . 'config/'); // Esta línea ahora funcionará

// Configuración de la URL base
define('BASE_URL', 'http://localhost/gestion_academica/');

?>