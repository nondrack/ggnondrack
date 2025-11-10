<?php
    header("Content-Type: applycation/json");

    $id = $_GET["id"]??NULL;
    $categoria = $_GET["categoria"] ?? NULL;
    
    require "../../config/Conexao.php";

    $db = new Conexao();
    $pdo = $db->conectar();

    if(!empty($categoria)){

        $sql="select * from produto where ativo = 'S' AND categoria_id = :categoria order by nome";
        $consulta = $pdo->prepare($sql);
        $consulta->bindParam(":categoria", $categoria);
        $consulta->execute();

        $dadosProduto = $consulta->fetchALL(PDO::FETCH__ASSONC);

    }else if(!empty($id)){

        $sql = "select * from produto where ativo='S' AND id=:id limit 1";
        $consulta = $pdo->prepare($sql);
        $consulta->bindParam(":id", $id);
        $consulta->execute();

        $dadosProduto = $consulta->fetch(PDO::FETCH_ASSOC);

    }else{
        $sql = "select * from produto where ativo='S' order by nome";
        $consulta = $pdo->prepare($sql);
        $consulta->execute();

        $dadosProduto = $consulta->fetchALL(PDO::FETCH_ASSOC);
    }
    
    echo json_encode($dadosProduto);