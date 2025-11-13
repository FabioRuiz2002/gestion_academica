<?php
/*
 * Archivo: index.php
 * Propósito: Punto de entrada principal (Front Controller).
 * (Corregido: Se usa __DIR__ para cargar la configuración de forma segura)
 */

// Iniciar sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- ¡SOLUCIÓN A PRUEBA DE ERRORES! ---
// Cargar la configuración usando una ruta absoluta basada en __DIR__
require_once __DIR__ . '/config/config.php';

// Determinar el controlador y la acción
$controllerName = (isset($_GET['controller']) ? ucfirst($_GET['controller']) : 'Usuario') . 'Controller';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

// Construir la ruta al controlador (Ahora CONTROLLER_PATH está definido)
$controllerPath = CONTROLLER_PATH . $controllerName . '.php';

// Verificar si el archivo del controlador existe
if (file_exists($controllerPath)) {
    require_once $controllerPath;
    
    // Verificar si la clase del controlador existe
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        
        // Verificar si el método (acción) existe en el controlador
        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
        } else {
            echo "Error: La acción '{$actionName}' no existe en el controlador '{$controllerName}'.";
        }
    } else {
        echo "Error: La clase '{$controllerName}' no existe.";
    }
} else {
    echo "Error: El controlador '{$controllerName}' no se encontró en '{$controllerPath}'.";
}
?>