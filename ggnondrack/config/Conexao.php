<?php
class Conexao {
    private static $host = "localhost";
    private static $user = "root";
    private static $pass = "";
    private static $db   = "shop2b";

    public static function conectar() {
        try {
            // Correção: a string de conexão precisa estar toda dentro de aspas
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db . ";charset=utf8";
            $pdo = new PDO($dsn, self::$user, self::$pass);

            // Habilita erros do PDO para debug
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;

        } catch (PDOException $e) {
            die("Erro ao conectar no banco de dados: " . $e->getMessage());
        }
    }
}
