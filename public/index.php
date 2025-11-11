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

    <!-- CSS Adicional para Tema Dark -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            background-color: #0b0f19;
            color: #fff;
        }

        /* Modal e Dialogs */
        .modal-content {
            background-color: #111827 !important;
            border: 1px solid #00eaff30 !important;
        }

        .modal-header {
            border-bottom: 1px solid #00eaff30 !important;
            background-color: #0b0f19 !important;
        }

        .modal-title {
            color: #fff !important;
        }

        .btn-close {
            filter: invert(1) !important;
        }

        /* Breadcrumb */
        .breadcrumb {
            background-color: transparent !important;
        }

        .breadcrumb-item.active {
            color: #00eaff !important;
        }

        .breadcrumb-item a {
            color: #00eaff;
        }

        /* Pagination */
        .pagination {
            --bs-pagination-bg: #111827;
            --bs-pagination-border-color: #00eaff30;
            --bs-pagination-hover-bg: #1a1f2e;
            --bs-pagination-hover-border-color: #00eaff;
            --bs-pagination-active-bg: #00eaff;
            --bs-pagination-active-border-color: #00eaff;
        }

        /* Dropdowns */
        .dropdown-menu {
            background-color: #111827 !important;
            border-color: #00eaff30 !important;
        }

        .dropdown-item {
            color: #fff;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background-color: #1a1f2e;
            color: #00eaff;
        }

        .dropdown-divider {
            border-color: #00eaff30;
        }

        /* Toast */
        .toast {
            background-color: #111827 !important;
            border: 1px solid #00eaff30;
        }

        .toast-body {
            color: #fff;
        }

        .toast-header {
            background-color: #0b0f19;
            border-bottom: 1px solid #00eaff30;
        }

        /* Popover */
        .popover {
            background-color: #111827;
            border: 1px solid #00eaff30;
        }

        .popover-header {
            background-color: #0b0f19;
            border-bottom: 1px solid #00eaff30;
            color: #00eaff;
        }

        .popover-body {
            color: #fff;
        }

        /* Tooltip */
        .tooltip-inner {
            background-color: #00eaff;
            color: #000;
        }

        /* Input Groups */
        .input-group-text {
            background-color: #111827 !important;
            border-color: #00eaff50 !important;
            color: #00eaff;
        }

        /* Progress Bars */
        .progress {
            background-color: #111827;
        }

        .progress-bar {
            background-color: #00eaff;
        }

        /* Badges */
        .badge {
            background-color: #00eaff;
            color: #000;
        }

        /* Alerts */
        .alert-light {
            background-color: #111827 !important;
            border-color: #00eaff30 !important;
            color: #fff !important;
        }

        .alert-dark {
            background-color: #0b0f19 !important;
            border-color: #00eaff50 !important;
            color: #fff !important;
        }

        /* Navbar customizada */
        .navbar-light .navbar-brand {
            color: #fff !important;
        }

        .navbar-light .navbar-nav .nav-link {
            color: #00eaff !important;
        }

        .navbar-light .navbar-nav .nav-link:hover {
            color: #fff !important;
        }

        /* Buttons Bootstrap Override */
        .btn-light {
            background-color: #111827 !important;
            border-color: #00eaff30 !important;
            color: #fff !important;
        }

        .btn-light:hover {
            background-color: #1a1f2e !important;
            border-color: #00eaff !important;
            color: #00eaff !important;
        }

        .btn-white {
            background-color: #111827 !important;
            border-color: #00eaff30 !important;
            color: #fff !important;
        }

        .btn-white:hover {
            background-color: #1a1f2e !important;
            border-color: #00eaff !important;
            color: #00eaff !important;
        }

        /* Limpar qualquer fundo branco restante */
        .bg-white {
            background-color: #111827 !important;
            color: #fff !important;
        }

        .bg-light {
            background-color: #0b0f19 !important;
            color: #fff !important;
        }

        .text-dark {
            color: #fff !important;
        }

        /* Linhas separadoras */
        hr {
            border-color: #00eaff30;
        }
    </style>
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