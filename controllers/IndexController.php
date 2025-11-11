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
                return;
            } else if (empty($senha)) {
                echo "<script>mensagem('Senha inválida','error','')</script>";
                return;
            }

            $dadosUsuario = $this->usuario->getDadosEmail($email);

            //verificar se trouxe alguma coisa
            if (empty($dadosUsuario->id)) {
                echo "<script>mensagem('Usuário não encontrado','error','')</script>";
                return;
            } else if(!password_verify($senha, $dadosUsuario->senha)) {
                echo "<script>mensagem('Senha inválida','error','')</script>";
                return;
            } else {
                //guardar informacoes em uma sessao
                $_SESSION["user"] = array(
                    "id" => $dadosUsuario->id,
                    "nome" => $dadosUsuario->nome
                );
                
                // Redirecionar para a página anterior ou home
                $proximaPagina = $_SESSION['proximaPagina'] ?? 'index.php';
                unset($_SESSION['proximaPagina']);
                
                echo "<script>location.href='{$proximaPagina}'</script>";
            }
        }

    }