<?php
    // iniciar a sessao
    session_start();

    // remover informações do usuário e esvaziar o carrinho ao sair
    unset($_SESSION["user"]);
    if (isset($_SESSION["carrinho"])) {
        unset($_SESSION["carrinho"]);
    }

    // redirecionar para a página inicial
    header("Location: index.php");