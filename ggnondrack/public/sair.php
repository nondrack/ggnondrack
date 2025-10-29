<?php
    //iniciar a sessao
    session_start();
    //apagar a sessao user
    unset($_SESSION["user"]);
    //redirecionar para o index / login
    header("Location: index.php");