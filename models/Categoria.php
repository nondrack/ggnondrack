<?php

    class Categoria {

        private $pdo;

        public function __construct($pdo)
        {
            $this->pdo = $pdo;
        }

        public function salvar($dados) {
            //se o i for vazio dá um insert
            //se o id não estiver vazio dá um update
            $id = $dados["id"];
            $nome = $dados["nome"];
            $ativo = $dados["ativo"];

            if (empty($id)) {
                $sql = "insert into categoria (nome, ativo)
                values (:nome, :ativo)";
                $consulta = $this->pdo->prepare($sql);
                $consulta->bindParam(":nome", $nome);
                $consulta->bindParam(":ativo", $ativo);
            } else {
                $sql = "update categoria set nome = :nome,
                ativo = :ativo where id = :id limit 1";
                $consulta = $this->pdo->prepare($sql);
                $consulta->bindParam(":nome", $nome);
                $consulta->bindParam(":id", $id);
                $consulta->bindParam(":ativo", $ativo);
            }

            if ($consulta->execute()) {
                echo "<script>mensagem('Sucesso!','ok','categoria/listar');</script>";
            } else {
                echo "<script>mensagem('Erro ao inserir','error','');</script>";
            }
        }

        /**
         * Lista categorias.
         * @param bool $apenasAtivos Se true, retorna apenas categorias com ativo = 1
         * @return array
         */
        public function listar($apenasAtivos = false) {
            if ($apenasAtivos) {
                $sql = "select * from categoria where ativo = 1 order by nome";
                $consulta = $this->pdo->prepare($sql);
            } else {
                $sql = "select * from categoria order by nome";
                $consulta = $this->pdo->prepare($sql);
            }

            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_OBJ);
        }

        public function getDados($id) {
            $sql = "select * from categoria where id = :id limit 1";
            $consulta = $this->pdo->prepare($sql);
            $consulta->bindParam(":id", $id);
            $consulta->execute();

            return $consulta->fetch(PDO::FETCH_OBJ);
        }

        public function excluir($id) {
    $stmt = $this->pdo->prepare("DELETE FROM categoria WHERE id = ?");
    return $stmt->execute([$id]);
}
    
}


    //fim da classe