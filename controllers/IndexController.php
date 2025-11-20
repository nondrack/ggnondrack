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
            if (session_status() === PHP_SESSION_NONE) session_start();
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
                // Se já existia um usuário diferente logado, esvaziar o carrinho
                if (isset($_SESSION["user"]) && ($_SESSION["user"]["id"] ?? null) !== $dadosUsuario->id) {
                    if (isset($_SESSION["carrinho"])) unset($_SESSION["carrinho"]);
                }

                //guardar informacoes em uma sessao
                $_SESSION["user"] = array(
                    "id" => $dadosUsuario->id,
                    "nome" => $dadosUsuario->nome,
                    "tipo" => $dadosUsuario->tipo
                );
                
                // Verificar se há redirect após login (ex: finalizar carrinho)
                if (isset($_SESSION['redirect_after_login'])) {
                    $proximaPagina = 'index.php?param=' . $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                } else {
                    // Redirecionar para a página anterior ou home
                    $proximaPagina = $_SESSION['proximaPagina'] ?? 'index.php';
                    unset($_SESSION['proximaPagina']);
                }
                
                echo "<script>
                    // Restaurar carrinho temporário do localStorage se existir
                    const carrinhoTemp = localStorage.getItem('carrinho_temp');
                    if (carrinhoTemp) {
                        // Enviar carrinho para o servidor via AJAX
                        fetch('index.php?action=restore-cart', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: carrinhoTemp
                        }).then(() => {
                            localStorage.removeItem('carrinho_temp');
                            location.href='{$proximaPagina}';
                        });
                    } else {
                        location.href='{$proximaPagina}';
                    }
                </script>";
            }
        }

    }