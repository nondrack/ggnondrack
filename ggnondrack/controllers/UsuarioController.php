<?php
require_once "../config/conexao.php";
require_once "../models/Usuario.php";

class UsuarioController {

    private $usuario;

    public function __construct($pdo) {
        $this->usuario = new Usuario($pdo);
    }

    // =====================================
    // LOGIN DO USUÁRIO
    // =====================================
    public function login() {
    if ($_POST) {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $usuario = $this->usuario->getDadosEmail($email);

        if ($usuario && password_verify($senha, $usuario->senha)) {
            // Sucesso
            session_start();
            $_SESSION['user'] = [
                'id' => $usuario->id,
                'nome' => $usuario->nome,
                'email' => $usuario->email
            ];
            header("Location: ../../public/index.php");
        } else {
            session_start();
            $_SESSION['erro'] = "E-mail ou senha incorretos!";
            header("Location: ../../views/index/login.php");
        }
    }
}

    // =====================================
    // CADASTRO DE USUÁRIO
    // =====================================
       public function cadastro() {
        require "../views/usuario/cadastro.php";
    }
    
    public function salvar() {
        if ($_POST) {
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            // Verificar se email já existe
            if ($this->usuario->getDadosEmail($email)) {
                $_SESSION['erro'] = "E-mail já cadastrado!";
                header("Location: cadastro.php");
                exit;
            }

            $cadastrado = $this->usuario->cadastrar($nome, $email, $senha);

            if ($cadastrado) {
                $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
                header("Location: login.php");
            } else {
                $_SESSION['erro'] = "Erro ao cadastrar usuário.";
                header("Location: cadastro.php");
            }
        }
    }
    }



