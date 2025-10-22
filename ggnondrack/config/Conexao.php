<?php
    class Conexao {
        private static $host = "localhost";
        private static $user = "root";
        private static $pass = "";
        private static $db = "shop2b";

        public static function conectar() {
            try {
                //tentar conectar no banco de dados
                return new PDO("mysql:host=".self::$host.";
                                dbname=".self::$db.";
                                charset=utf8",
                                self::$user,
                                self::$pass);

            } catch(PDOException $e) {
                //mensagem de erro caso não consiga
                die("Erro ao conectar no banco de dados. Erro: {$e->getMessage()}");
            }
        }
    }