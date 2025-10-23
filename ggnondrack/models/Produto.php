<?php
class Produto {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Lista todos os produtos
     */
    public function listar() {
        try {
            $sql = "SELECT * FROM produto ORDER BY id DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die("Erro ao listar produtos: " . $e->getMessage());
        }
    }

    /**
     * Busca um produto pelo ID
     */
    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM produto WHERE id = :id LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die("Erro ao buscar produto: " . $e->getMessage());
        }
    }

    /**
     * Salva (insere) um novo produto
     */
    public function salvar($dados) {
        try {
            $sql = "INSERT INTO produto (nome, valor, imagem, categoria_id)
                    VALUES (:nome, :valor, :imagem, :categoria_id)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":nome", $dados["nome"]);
            $stmt->bindValue(":valor", $dados["valor"]);
            $stmt->bindValue(":imagem", $dados["imagem"] ?? null);
            $stmt->bindValue(":categoria_id", $dados["categoria_id"] ?? null);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao salvar produto: " . $e->getMessage());
        }
    }

    /**
     * Atualiza um produto existente
     */
    public function atualizar($id, $dados) {
        try {
            $sql = "UPDATE produto
                    SET nome = :nome, valor = :valor, imagem = :imagem, categoria_id = :categoria_id 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":nome", $dados["nome"]);
            $stmt->bindValue(":valor", $dados["valor"]);
            $stmt->bindValue(":imagem", $dados["imagem"] ?? null);
            $stmt->bindValue(":categoria_id", $dados["categoria_id"] ?? null);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao atualizar produto: " . $e->getMessage());
        }
    }

    /**
     * Exclui um produto pelo ID
     */
    public function excluir($id) {
        try {
            $sql = "DELETE FROM produto WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao excluir produto: " . $e->getMessage());
        }
    }
}
