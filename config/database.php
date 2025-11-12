<?php
/*
 * Archivo: config/Database.php
 * Propósito: Clase para la conexión a la base de datos usando PDO.
 * Criterios: Criterio 3 (Base de Datos - PDO) y Criterio 6 (Seguridad)
 */

class Database {
    // Parámetros de la BD (Usar los de tu XAMPP)
    private $host = 'localhost';        // Generalmente 'localhost' en XAMPP
    private $db_name = 'db_gestion_academica'; // La BD que creamos
    private $username = 'root';         // Usuario por defecto de XAMPP
    private $password = '';             // Password por defecto de XAMPP (vacío)
    private $conn;

    /**
     * Obtiene la conexión a la base de datos.
     * @return PDO|null Retorna el objeto PDO de la conexión o null si falla.
     */
    public function getConnection() {
        $this->conn = null;
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4';

        try {
            // 1. Crear la instancia de PDO
            $this->conn = new PDO($dsn, $this->username, $this->password);

            // 2. Configurar atributos de PDO para seguridad y manejo de errores
            
            // Modo de error: Lanza excepciones en lugar de warnings (SILENCIOSO).
            // Esto es crucial para manejar errores de forma controlada.
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Modo de fetch: Devuelve los resultados como arrays asociativos.
            // Más limpio para convertir a JSON o trabajar en PHP.
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Desactivar "emulated prepares".
            // Esto fuerza a MySQL a hacer la preparación real de la consulta,
            // siendo un pilar fundamental para prevenir inyección SQL.
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        } catch(PDOException $exception) {
            // Manejo de error de conexión
            echo 'Error de conexión: ' . $exception->getMessage();
            return null;
        }

        return $this->conn;
    }
}
?>