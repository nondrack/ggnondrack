<?php
    require "../config/Conexao.php";
    require "../models/Usuario.php";

    class IndexController {

        private $usuario;

        public function __construct()
        {
            $conexao = new Conexao();
            $pdo = $conexao->conectar();
            $this->usuario = new Usuario($pdo);
        }

        public function index() {
            require "../views/index/index.php";
        }

        public function verificar($dados) {
            $email = trim($dados["email"] ?? NULL);
            $senha = trim($dados["senha"] ?? NULL);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<script>mensagem('E-mail inválido','error','')</script>";
            } else if (empty($senha)) {
                echo "<script>mensagem('Senha inválida','error','')</script>";
            }

            $dadosUsuario = $this->usuario->getDadosEmail($email);

            //print_r($dadosUsuario);
            //verificar se trouxe alguma coisa
            if (empty($dadosUsuario->id)) {
                echo "<script>mensagem('Usuário inválido','error','')</script>";
            } else if(!password_verify($senha, $dadosUsuario->senha)) {
                echo "<script>mensagem('Senha inválida','error','')</script>";
            } else {

                //guardar informacoes em uma sessao
                $_SESSION["user"] = array(
                    "id" => $dadosUsuario->id,
                    "nome" => $dadosUsuario->nome
                );
                //redireciono a tela
                echo "<script>location.href='index.php'</script>";

            }
        }

    }