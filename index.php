<?php
/*
 * Archivo: index.php
 * Propósito: Controlador Frontal (Front Controller).
 * Punto de entrada único para todas las solicitudes.
 * Carga el controlador y ejecuta la acción solicitada.
 * Criterios: Criterio 1 (Arquitectura MVC)
 */

// 1. Iniciar la sesión
// Fundamental para manejar la autenticación (login, roles)
session_start();

// 2. Definir constantes de rutas
// (Opcional pero muy recomendado para no tener problemas con "includes")
define('BASE_PATH', __DIR__ . '/');
define('CONTROLLER_PATH', BASE_PATH . 'controllers/');
define('MODEL_PATH', BASE_PATH . 'models/');
define('VIEW_PATH', BASE_PATH . 'views/');

// 3. Lógica del Enrutador (Router) Básico

// Obtener el controlador. Por defecto 'UsuarioController' (para el login)
// La URL será: index.php?controller=Usuario
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'Usuario';

// Obtener la acción (método). Por defecto 'index' (mostrar login)
// La URL será: index.php?controller=Usuario&action=index
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

// 4. Formatear nombres
// Ej: 'Usuario' -> 'UsuarioController'
$controllerFile = $controllerName . 'Controller.php';
$controllerClassName = $controllerName . 'Controller';

// 5. Cargar el archivo del controlador
$controllerFilePath = CONTROLLER_PATH . $controllerFile;

if (file_exists($controllerFilePath)) {
    
    // Incluir el archivo del controlador
    require_once $controllerFilePath;

    // 6. Verificar si la clase y el método existen
    if (class_exists($controllerClassName)) {
        
        $controllerInstance = new $controllerClassName();

        if (method_exists($controllerInstance, $actionName)) {
            // 7. Ejecutar la acción (método)
            $controllerInstance->$actionName();
        } else {
            // Error: Método (acción) no encontrado
            echo "Error: La acción '{$actionName}' no existe en el controlador '{$controllerClassName}'.";
        }
    } else {
        // Error: Clase del controlador no encontrada
        echo "Error: La clase '{$controllerClassName}' no fue encontrada en '{$controllerFile}'.";
    }
} else {
    // Error: Archivo del controlador no encontrado
    echo "Error: El archivo del controlador '{$controllerFile}' no existe.";
}
?>