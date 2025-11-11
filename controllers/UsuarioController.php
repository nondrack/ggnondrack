<?php
require_once "../config/Conexao.php";
require_once "../models/Usuario.php";

class UsuarioController {

    private $usuario;

    public function __construct() {
        $pdo = Conexao::conectar();
        $this->usuario = new Usuario($pdo);
    }

    /**
     * Exibe a página de cadastro
     */
    public function cadastro() {
        require "../views/usuario/cadastro.php";
    }

    /**
     * Salva um novo usuário
     */
    public function salvar() {
        if ($_POST) {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');
            $confirmarSenha = trim($_POST['confirmarSenha'] ?? '');

            // Validações
            if (empty($nome)) {
                echo "<script>mensagem('Nome é obrigatório!','error','')</script>";
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<script>mensagem('E-mail inválido!','error','')</script>";
                return;
            }

            if (strlen($senha) < 6) {
                echo "<script>mensagem('Senha deve ter pelo menos 6 caracteres!','error','')</script>";
                return;
            }

            if ($senha !== $confirmarSenha) {
                echo "<script>mensagem('As senhas não conferem!','error','')</script>";
                return;
            }

            // Tentar cadastrar
            $resultado = $this->usuario->cadastrar($nome, $email, $senha);

            if ($resultado['status']) {
                echo "<script>mensagem('Cadastro realizado com sucesso! Você será redirecionado para o login.','success','login.php')</script>";
            } else {
                echo "<script>mensagem('{$resultado['mensagem']}','error','')</script>";
            }
        }
    }
}
?>

