<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle</title>

    <base href="http://<?=$_SERVER["SERVER_NAME"] . $_SERVER["SCRIPT_NAME"]?>">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/sweetalert2.min.css">
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
            }).then((result) => {
                if (icone == "error") {
                    history.back();
                } else {
                    location.href = pagina;
                }
            });
        }
    </script>
</head>

<body>
    <?php
    //se nao esta logado - mostrar o login
    //se nao esta logado, mas esta validando, validar
    //se esta logado mostrar a tela de home
    if (($_POST) && (!isset($_SESSION["user"]))) {
        //validar os dados

        require "../controllers/IndexController.php";
        $controller = new IndexController();
        $controller->verificar($_POST);
    } else if (!isset($_SESSION["user"])) {
        //mostrar a tela de login

        require "../views/index/login.php";
    } else if (isset($_SESSION)) {
        //mostra a tela de home
        
        require "menu.php";
        

        //echo $_GET["param"];

        if (isset($_GET["param"])) {
            //produto/index/1
            $param = explode("/", $_GET["param"]);
        }
        $controller = $param[0] ?? "index";
        $view = $param[1] ?? "index";
        $id = $param[2] ?? NULL;

        $controller = ucfirst($controller)."Controller";

        //verificar se existe o controller
        if (file_exists("../controllers/{$controller}.php")) {
            require "../controllers/{$controller}.php";

            $control = new $controller();
            $control->$view($id);

        } else {
            require "../views/index/erro.php";
        }
        

    } else {
        //mostra erro

    }
    
    ?>
</body>

</html>