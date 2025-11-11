<?php
session_start();

// Endpoint para obter quantidade do carrinho
if (isset($_GET['action']) && $_GET['action'] === 'get-cart-count') {
    header('Content-Type: application/json');
    $quantidade = 0;
    if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
        foreach ($_SESSION['carrinho'] as $item) {
            $quantidade += $item['qtde'] ?? 0;
        }
    }
    echo json_encode(['quantidade' => $quantidade]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DualCore Tech</title>

    <base href="http://<?=$_SERVER["SERVER_NAME"] . dirname($_SERVER["SCRIPT_NAME"]) . '/'?>">

    <link rel="shortcut icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-theme.css">
    <link rel="stylesheet" href="css/style.css">

    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.inputmask.min.js"></script>
    <script src="js/jquery.maskedinput-1.2.1.js"></script>
    <script src="js/parsley.min.js"></script>
    <script src="js/sweetalert2.js"></script>

    <script>
        //conter os meus script
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

        // Verificar se precisa fazer login para adicionar ao carrinho
        function adicionarAoCarrinho(produtoId) {
            // Verificar se o usuário está logado
            const usuarioLogado = <?= isset($_SESSION["user"]) ? 'true' : 'false' ?>;
            
            if (!usuarioLogado) {
                Swal.fire({
                    title: 'Login Necessário',
                    text: 'Você precisa fazer login para adicionar produtos ao carrinho!',
                    icon: 'warning',
                    background: '#111827',
                    color: '#fff',
                    showCancelButton: true,
                    confirmButtonColor: getComputedStyle(document.documentElement).getPropertyValue('--color-neon'),
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-sign-in-alt"></i> Fazer Login',
                    cancelButtonText: '<i class="fas fa-times"></i> Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = 'login.php';
                    }
                });
                return false;
            }
            
            // Se está logado, redireciona para adicionar ao carrinho
            location.href = 'index.php?param=carrinho/adicionar/' + produtoId;
            
            // Atualizar contador após adicionar
            setTimeout(() => {
                atualizarContadorCarrinho();
            }, 500);
            
            return false;
        }
    </script>

    <link rel="stylesheet" href="css/components/views-inline.css">
</head>

<body>
    <?php
    // Processar formulário de login
    if (($_POST) && (!isset($_SESSION["user"]))) {
        require "../controllers/IndexController.php";
        $controller = new IndexController();
        $controller->verificar($_POST);
    }

    // NOVA LÓGICA: Não forçar login na página inicial
    // Permitir navegação sem login
    require "menu.php";

    if (isset($_GET["param"])) {
        $param = explode("/", $_GET["param"]);
    }
    
    $controller = $param[0] ?? "index";
    $view = $param[1] ?? "index";
    $id = $param[2] ?? NULL;

    $controller = ucfirst($controller)."Controller";

    // Verificar se existe o controller
    if (file_exists("../controllers/{$controller}.php")) {
        require "../controllers/{$controller}.php";

        $control = new $controller();
        $control->$view($id);
    } else {
        require "../views/index/erro.php";
    }
    ?>
</body>

</html>