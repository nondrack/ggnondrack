<?php
session_start();

// Se já está logado, redireciona para home
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}

// Processar formulário de cadastro
if ($_POST) {
    require "../config/Conexao.php";
    require "../models/Usuario.php";

    $pdo = Conexao::conectar();
    $usuario = new Usuario($pdo);

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $confirmarSenha = trim($_POST['confirmarSenha'] ?? '');

    // Validações
    if (empty($nome)) {
        $_SESSION['erro'] = "Nome é obrigatório!";
        header("Location: cadastro.php");
        exit;
    }

    if (!preg_match("/^[a-zA-Zá-ú\s]+$/", $nome)) {
        $_SESSION['erro'] = "Nome deve conter apenas letras!";
        header("Location: cadastro.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['erro'] = "E-mail inválido!";
        header("Location: cadastro.php");
        exit;
    }

    if (strlen($senha) < 6) {
        $_SESSION['erro'] = "Senha deve ter pelo menos 6 caracteres!";
        header("Location: cadastro.php");
        exit;
    }

    if ($senha !== $confirmarSenha) {
        $_SESSION['erro'] = "As senhas não conferem!";
        header("Location: cadastro.php");
        exit;
    }

    // Tentar cadastrar
    $resultado = $usuario->cadastrar($nome, $email, $senha);

    if ($resultado['status']) {
        $_SESSION['sucesso'] = $resultado['mensagem'];
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['erro'] = $resultado['mensagem'];
        header("Location: cadastro.php");
        exit;
    }
}

// Recuperar mensagens de sessão
$erro = $_SESSION['erro'] ?? null;
$sucesso = $_SESSION['sucesso'] ?? null;
unset($_SESSION['erro']);
unset($_SESSION['sucesso']);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - DualCore Tech</title>

    <base href="http://<?=$_SERVER["SERVER_NAME"] . dirname($_SERVER["SCRIPT_NAME"]) . '/'?>">

    <link rel="shortcut icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-theme.css">
    <link rel="stylesheet" href="css/style.css">

    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/parsley.min.js"></script>
    <script src="js/sweetalert2.js"></script>

    <script>
        function mensagem(titulo, icone, pagina) {
            Swal.fire({
                title: titulo,
                icon: icone,
                background: '#111827',
                color: '#fff',
                confirmButtonColor: getComputedStyle(document.documentElement).getPropertyValue('--color-neon'),
                confirmButtonText: 'Ok'
            }).then((result) => {
                if (icone == "error") {
                    // Não redireciona em caso de erro
                } else {
                    location.href = pagina;
                }
            });
        }

        function mostrarSenha() {
            const input = document.getElementById("senha");
            input.type = input.type === "password" ? "text" : "password";
        }

        function confirmarSenha() {
            const input = document.getElementById("confirmarSenha");
            input.type = input.type === "password" ? "text" : "password";
        }
    </script>

    <style>
        body {
            background-color: #0b0f19;
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }

        /* Campos com texto e placeholder brancos */
        .neon-input {
            background-color: #1a1f2e;
            border: 1px solid #00eaff60;
            color: #fff;
            transition: 0.3s;
        }

        .neon-input::placeholder {
            color: #ffffffb3;
            opacity: 1;
        }

        .neon-input:focus {
            border-color: #00eaff;
            box-shadow: 0 0 10px #00eaff80;
            background-color: #10141f;
            color: #fff;
        }

        /* Botão neon */
        .btn-neon {
            background-color: #00eaff;
            border: none;
            color: #0b0f19;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-neon:hover {
            background-color: #00b8cc;
            box-shadow: 0 0 20px #00eaff;
            color: #fff;
        }

        /* Botão outline neon */
        .btn-outline-neon {
            border: 1px solid #00eaff;
            color: #00eaff;
            font-weight: bold;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
            padding: 10px;
            border-radius: 6px;
        }

        .btn-outline-neon:hover {
            background-color: #00eaff;
            color: #0b0f19;
            box-shadow: 0 0 20px #00eaff;
        }

        .btn-outline-secondary {
            border: 1px solid #6c757d;
            color: #999;
            font-weight: bold;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
            padding: 10px;
            border-radius: 6px;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
        }

        .card {
            border-radius: 12px;
        }

        .login {
            padding: 20px;
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .alert-danger {
            background-color: rgba(255, 107, 107, 0.1);
            border: 1px solid #ff6b6b;
            color: #ff6b6b;
        }

        .alert-success {
            background-color: rgba(0, 234, 255, 0.1);
            border: 1px solid #00eaff;
            color: #00eaff;
        }
    </style>
</head>

<body>
    <form name="formCadastro" method="post" data-parsley-validate>
        <div class="login d-flex justify-content-center align-items-center vh-100">
            <div class="card p-4" style="width: 100%; max-width: 450px; background-color: #111827; border: 1px solid #00eaff40; box-shadow: 0 0 15px #00eaff20;">
                <div class="card-header text-center bg-transparent border-0">
                    <img src="images/logo.png" alt="DualCore Tech" class="w-50 mb-3" style="filter: drop-shadow(0 0 5px var(--color-neon));">
                    <h3 class="text-light">Criar Conta</h3>
                    <p class="text-muted small">Preencha os dados abaixo para se cadastrar</p>
                </div>
                <div class="card-body text-light">
                    <?php if ($erro): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= htmlspecialchars($erro) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($sucesso): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= htmlspecialchars($sucesso) ?>
                        </div>
                    <?php endif; ?>

                    <label for="nome" class="form-label">
                        <i class="fas fa-user me-2"></i> Nome Completo:
                    </label>
                    <input type="text" name="nome" id="nome"
                        class="form-control neon-input" required
                        placeholder="Seu nome completo"
                        data-parsley-required-message="O nome é obrigatório"
                        data-parsley-pattern="^[a-zA-Zá-ú\s]+$"
                        data-parsley-pattern-message="O nome deve conter apenas letras">

                    <br>
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i> E-mail:
                    </label>
                    <input type="email" name="email" id="email"
                        class="form-control neon-input" required
                        placeholder="seu@email.com"
                        data-parsley-required-message="O e-mail é obrigatório"
                        data-parsley-type-message="Digite um e-mail válido">

                    <br>
                    <label for="senha" class="form-label">
                        <i class="fas fa-lock me-2"></i> Senha:
                    </label>
                    <div class="input-group mb-3">
                        <input type="password" name="senha" id="senha"
                            class="form-control neon-input" required
                            placeholder="Mínimo 6 caracteres"
                            data-parsley-required-message="A senha é obrigatória"
                            data-parsley-minlength="6"
                            data-parsley-minlength-message="A senha deve ter pelo menos 6 caracteres">
                        <button type="button" class="btn btn-neon" onclick="mostrarSenha()">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <br>
                    <label for="confirmarSenha" class="form-label">
                        <i class="fas fa-lock me-2"></i> Confirmar Senha:
                    </label>
                    <div class="input-group mb-3">
                        <input type="password" name="confirmarSenha" id="confirmarSenha"
                            class="form-control neon-input" required
                            placeholder="Confirme sua senha"
                            data-parsley-required-message="Confirme a senha"
                            data-parsley-equalto="#senha"
                            data-parsley-equalto-message="As senhas não conferem">
                        <button type="button" class="btn btn-neon" onclick="confirmarSenha()">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <br>

                    <!-- Botão de cadastro -->
                    <button type="submit" class="btn btn-neon w-100 mb-3">
                        <i class="fas fa-user-plus me-2"></i> Criar Conta
                    </button>

                    <!-- Botão de login -->
                    <div class="d-grid gap-2 mb-3">
                        <a href="login.php" class="btn btn-outline-neon text-center">
                            <i class="fas fa-sign-in-alt me-2"></i> Já Tenho Conta
                        </a>
                    </div>

                    <!-- Botão de voltar -->
                    <div class="d-grid">
                        <a href="index.php" class="btn btn-outline-secondary text-center">
                            <i class="fas fa-arrow-left me-2"></i> Voltar à Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>

</html>
