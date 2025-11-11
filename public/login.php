<?php
session_start();

// Se já está logado, redireciona para home
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}

// Processar formulário de login
if ($_POST) {
    require "../controllers/IndexController.php";
    $controller = new IndexController();
    $controller->verificar($_POST);
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DualCore Tech</title>

    <base href="http://<?=$_SERVER["SERVER_NAME"] . dirname($_SERVER["SCRIPT_NAME"]) . '/'?>">

    <link rel="shortcut icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-theme.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/components/views-inline.css">

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
                    history.back();
                } else {
                    location.href = pagina;
                }
            });
        }

        function mostrarSenha() {
            const input = document.getElementById("senha");
            input.type = input.type === "password" ? "text" : "password";
        }
    </script>

    <!-- login styles moved to css/components/views-inline.css -->
</head>

<body>
    <form name="formLogin" method="post" data-parsley-validate>
        <div class="login d-flex justify-content-center align-items-center vh-100">
            <div class="card p-4" style="width: 100%; max-width: 400px; background-color: #111827; border: 1px solid #00eaff40; box-shadow: 0 0 15px #00eaff20;">
                <div class="card-header text-center bg-transparent border-0">
                    <img src="images/logo.png" alt="DualCore Tech" class="w-50 mb-3" style="filter: drop-shadow(0 0 5px var(--color-neon));">
                    <h3 class="text-light">Fazer Login</h3>
                </div>
                <div class="card-body text-light">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i> E-mail:
                    </label>
                    <input type="email" name="email" id="email"
                        class="form-control neon-input" required
                        placeholder="seu@email.com"
                        data-parsley-required-message="Preencha este campo"
                        data-parsley-type-message="Digite um e-mail válido">

                    <br>
                    <label for="senha" class="form-label">
                        <i class="fas fa-lock me-2"></i> Senha:
                    </label>
                    <div class="input-group mb-3">
                        <input type="password" name="senha" id="senha"
                            class="form-control neon-input" required
                            placeholder="Digite sua senha"
                            data-parsley-required-message="Preencha este campo"
                            data-parsley-errors-container="#error">
                        <button type="button" class="btn btn-neon" onclick="mostrarSenha()">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div id="error"></div>
                    <br>

                    <!-- Botão de login -->
                    <button type="submit" class="btn btn-neon w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i> Efetuar Login
                    </button>

                    <!-- Botão de cadastro -->
                    <div class="d-grid gap-2 mb-3">
                        <a href="index.php?param=usuario/cadastro" class="btn btn-outline-neon text-center">
                            <i class="fas fa-user-plus me-2"></i> Criar Conta
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
