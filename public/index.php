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

// Endpoint para restaurar carrinho após login
if (isset($_GET['action']) && $_GET['action'] === 'restore-cart' && isset($_SESSION['user'])) {
    header('Content-Type: application/json');
    $input = file_get_contents('php://input');
    $carrinhoTemp = json_decode($input, true);
    
    if ($carrinhoTemp && is_array($carrinhoTemp)) {
        // Mesclar com carrinho existente (se houver)
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        
        foreach ($carrinhoTemp as $id => $item) {
            if (isset($_SESSION['carrinho'][$id])) {
                // Produto já existe, somar quantidades
                $_SESSION['carrinho'][$id]['qtde'] += $item['qtde'];
            } else {
                // Adicionar novo produto
                $_SESSION['carrinho'][$id] = $item;
            }
        }
        
        echo json_encode(['success' => true, 'message' => 'Carrinho restaurado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    }
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

        // Adicionar produto ao carrinho (funciona com ou sem login)
        function adicionarAoCarrinho(produtoId) {
            // Redireciona para adicionar ao carrinho (controller decide se salva na sessão ou localStorage)
            location.href = 'index.php?param=carrinho/adicionar/' + produtoId;
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