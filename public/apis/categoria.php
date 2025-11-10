<?php
    header("Content-Type: applycation/json");

    require "../../config/Conexao.php";

    $db = new Conexao();
    $pdo = $db->conectar();

    $sql = "select * from. categoria where ativo = 'S'";
    $consulta = $pdo->prepare($sql);
    $consulta->execute();

     $dadosCategoria = $consulta->fetchALL(PDO::FETCH_ASSOC);

     echo json_encode($dadosCategoria);