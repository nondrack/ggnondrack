<?php

    class Usuario {

        private $pdo;

        public function __construct($pdo)
         {
            $this->pdo = $pdo;
         }

        public function getDadosEmail($email) {
            $sql = "select id, nome, email, senha from usuario
                where ativo = 'S' and email = :email
                limit 1";
            $consulta = $this->pdo->prepare($sql);
            $consulta->bindParam(":email", $email);
            $consulta->execute();

            return $consulta->fetch(PDO::FETCH_OBJ);
            }
        public function cadastrar($nome, $email, $senha){
        try {
        // Verifica se já existe um usuário com esse e-mail
        $sql = "SELECT id FROM usuario WHERE email = :email LIMIT 1";
        $consulta = $this->pdo->prepare($sql);
        $consulta->bindParam(":email", $email);
        $consulta->execute();

        if ($consulta->fetch(PDO::FETCH_OBJ)) {
            // Já existe um usuário com o mesmo e-mail
            return ["status" => false, "mensagem" => "E-mail já cadastrado."];
            }

        // Criptografa a senha
        $hashSenha = password_hash($senha, PASSWORD_DEFAULT);

        // Insere o novo usuário (agora em uma única tabela)
        $sql = "INSERT INTO usuario (nome, email, senha, tipo, ativo) 
                VALUES (:nome, :email, :senha, 'cliente', 'S')";
        $consulta = $this->pdo->prepare($sql);
        $consulta->bindParam(":nome", $nome);
        $consulta->bindParam(":email", $email);
        $consulta->bindParam(":senha", $hashSenha);
        
        if ($consulta->execute()) {
            return ["status" => true, "mensagem" => "Usuário cadastrado com sucesso!"];
        } else {
            return ["status" => false, "mensagem" => "Erro ao cadastrar usuário."];
        }

        } catch (PDOException $e) {
        return ["status" => false, "mensagem" => "Erro: " . $e->getMessage()];
        }
    
    }
    

}