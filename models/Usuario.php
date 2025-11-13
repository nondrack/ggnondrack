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
        public function cadastrar($nome, $email, $senha)
            {
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

        // Inicia transação para garantir que usuário e cliente sejam criados juntos
        $this->pdo->beginTransaction();
        
        try {
            // Insere o novo usuário
            $sql = "INSERT INTO usuario (nome, email, senha, ativo) 
                    VALUES (:nome, :email, :senha, 'S')";
            $consulta = $this->pdo->prepare($sql);
            $consulta->bindParam(":nome", $nome);
            $consulta->bindParam(":email", $email);
            $consulta->bindParam(":senha", $hashSenha);
            $consulta->execute();
            
            $usuarioId = $this->pdo->lastInsertId();
            
            // Também insere na tabela cliente com o mesmo ID
            $sqlCliente = "INSERT INTO cliente (id, nome, email, senha, ativo) 
                          VALUES (:id, :nome, :email, :senha, 'S')";
            $consultaCliente = $this->pdo->prepare($sqlCliente);
            $consultaCliente->bindParam(":id", $usuarioId);
            $consultaCliente->bindParam(":nome", $nome);
            $consultaCliente->bindParam(":email", $email);
            $consultaCliente->bindParam(":senha", $hashSenha);
            $consultaCliente->execute();
            
            $this->pdo->commit();
            return ["status" => true, "mensagem" => "Usuário cadastrado com sucesso!"];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ["status" => false, "mensagem" => "Erro ao cadastrar usuário: " . $e->getMessage()];
        }

        } catch (PDOException $e) {
        return ["status" => false, "mensagem" => "Erro: " . $e->getMessage()];
        }
    
    }
    

}