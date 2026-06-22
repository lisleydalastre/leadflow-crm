<?php
/**
 * Database - Clase de conexión PDO (Singleton)
 * 
 * Garantiza una única instancia de conexión durante
 * todo el ciclo de vida de la petición HTTP.
 */
class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    /**
     * Retorna la instancia PDO compartida.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $cfg = require BASE_PATH . '/config/config.php';
            $db  = $cfg['db'];
            $dsn = "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}";

            try {
                self::$instance = new PDO($dsn, $db['user'], $db['pass'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                // En producción registrar en log, nunca exponer credenciales
                http_response_code(500);
                die('Error de conexión a la base de datos.');
            }
        }
        return self::$instance;
    }
}
