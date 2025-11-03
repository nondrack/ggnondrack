<?php

    class Peoduto{
        private $pdo;

        public function __construct($pdo){
            $this ->pdo = $pdo;
        }

        public function salvar($dados){
            if(empty($dados["id"])){
                $sql = "insert into produto (nome,categoria_id,descricao, imagem, valor, ativo)
                values (:nome, :categoria_id, :descricao, :imagem, :valor, :ativo)";
                $consulta = $this->pdo->prepare($sql);
                $consulta ->bindParam(":nome", $dados["nome"]);
                $consulta ->bindParam(":categoria_id", $dados["categoria_id"]);
                $consulta ->bindParam(":descricao", $dados["descricao"]);
                $consulta ->bindParam(":imagem", $dados["imagem"]);
                $consulta ->bindParam(":valor", $dados["valor"]);
                $consulta ->bindParam(":ativo", $dados["ativo"]);
                $consulta ->bindParam(":id",$dados["id"]);
            }else if(!empty($dados["imagem"])){
                $sql = "update produto set nome= :nome, categoria_id = :categoria_id, descricao = :descreicao, valor = :valor,
                ativo = :ativo where id = :id limit 1";
                $consulta = $this->pdo->prepare($sql);
                $consulta ->bindParam(":nome", $dados["nome"]);
                $consulta ->bindParam(":categoria_id", $dados["categoria_id"]);
                $consulta ->bindParam(":descricao", $dados["descricao"]);
                $consulta ->bindParam(":imagem", $dados["imagem"]);
                $consulta ->bindParam(":valor", $dados["valor"]);
                $consulta ->bindParam(":ativo", $dados["ativo"]);
                $consulta ->bindParam(":id",$dados["id"]);

            }

            return $consulta->execute();
            
        }
    }