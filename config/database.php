<?php
/*
 * Archivo: config/Database.php
 * Propósito: Clase para la conexión a la base de datos (PDO).
 * (Corregido el error de tipeo '$this-db_name')
 */

class Database {
    
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // --- ¡AQUÍ ESTABA EL ERROR! (CORREGIDO) ---
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>