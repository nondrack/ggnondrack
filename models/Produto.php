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
            // Lista todos (inclui inativos para contexto administrativo)
            $sql = "SELECT * FROM produto ORDER BY id DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die("Erro ao listar produtos: " . $e->getMessage());
        }
    }
     public function listarAtivos() {
        $stmt = $this->pdo->prepare("SELECT * FROM produto WHERE ativo = 'S' ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
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
    if (!empty($dados["id"])) {
        return $this->atualizar($dados["id"], $dados);
    }

    try {
    // Ordem correta conforme schema: (nome, descricao, categoria_id, preco, estoque, imagem, ativo)
    // Usamos estoque=0 e ativo='S' por padrão; compatibilidade com campo 'valor' → preco
    $sql = "INSERT INTO produto (nome, descricao, categoria_id, preco, estoque, imagem, ativo)
        VALUES (:nome, :descricao, :categoria_id, :preco, :estoque, :imagem, :ativo)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":nome", $dados["nome"]);
    $preco = $dados['preco'] ?? $dados['valor'] ?? 0;
    $stmt->bindValue(":preco", $preco);
        $stmt->bindValue(":descricao", strip_tags($dados["descricao"] ?? ''));
        $stmt->bindValue(":categoria_id", $dados["categoria_id"] ?? null);
    $stmt->bindValue(":imagem", $dados["imagem"] ?? null);
    $stmt->bindValue(":ativo", strtoupper($dados['ativo'] ?? 'S') === 'N' ? 'N' : 'S');
        $stmt->bindValue(":estoque", $dados['estoque'] ?? 0, PDO::PARAM_INT);
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
        // Atualiza mantendo campos existentes (sem remover descrição/ativo/estoque se não enviados)
        $sql = "UPDATE produto
            SET nome = :nome, descricao = :descricao, categoria_id = :categoria_id, preco = :preco, estoque = :estoque, imagem = :imagem, ativo = :ativo
            WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":nome", $dados["nome"]);
        $preco = $dados['preco'] ?? $dados['valor'] ?? 0;
        $stmt->bindValue(":preco", $preco);
            $stmt->bindValue(":descricao", strip_tags($dados['descricao'] ?? ''));
            $stmt->bindValue(":categoria_id", $dados["categoria_id"] ?? null);
            $stmt->bindValue(":estoque", $dados['estoque'] ?? 0, PDO::PARAM_INT);
            // Ajuste de nome se reativar produto previamente marcado como [DESATIVADO]
            $nome = $dados['nome'] ?? '';
            $ativoFlag = strtoupper($dados['ativo'] ?? 'S');
            if ($ativoFlag === 'S' && str_ends_with($nome, ' [DESATIVADO]')) {
                $nome = substr($nome, 0, -14); // remove sufixo
            } elseif ($ativoFlag === 'N' && !str_ends_with($nome, ' [DESATIVADO]')) {
                // Não renomear automaticamente ao inativar manualmente para evitar poluição visual
            }
            $stmt->bindValue(":nome", $nome);
            $stmt->bindValue(":imagem", $dados["imagem"] ?? null);
            $stmt->bindValue(":ativo", $ativoFlag === 'N' ? 'N' : 'S');
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao atualizar produto: " . $e->getMessage());
        }
    }

    /**
     * Exclui um produto pelo ID (exclusão lógica - soft delete)
     * Produtos em vendas não podem ser excluídos fisicamente, apenas desativados
     */
    public function excluir($id) {
        try {
            // Verificar se o produto está em alguma venda
            $sqlCheck = "SELECT COUNT(*) as total FROM item_venda WHERE produto_id = :id";
            $stmtCheck = $this->pdo->prepare($sqlCheck);
            $stmtCheck->bindValue(":id", $id, PDO::PARAM_INT);
            $stmtCheck->execute();
            $result = $stmtCheck->fetch(PDO::FETCH_OBJ);
            
            if ($result->total > 0) {
                // Produto está em vendas, fazer exclusão lógica (desativar + renomear para evitar reutilização)
                $sql = "UPDATE produto SET ativo = 'N', nome = CONCAT(nome, ' [DESATIVADO]') WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":id", $id, PDO::PARAM_INT);
                $executed = $stmt->execute();
                
                if ($executed) {
                    // Limpar produto de possíveis carrinhos em sessão
                    if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
                        $_SESSION['carrinho'] = array_filter(
                            $_SESSION['carrinho'],
                            function($item) use ($id) {
                                return ($item['id'] ?? $item['produto_id'] ?? null) != $id;
                            }
                        );
                    }
                    echo "<script>alert('Produto desativado! Ele participa de vendas e não pode ser removido definitivamente.');</script>";
                }
                return $executed;
            } else {
                // Produto não está em vendas, pode excluir fisicamente
                $sql = "DELETE FROM produto WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":id", $id, PDO::PARAM_INT);
                return $stmt->execute();
            }
        } catch (PDOException $e) {
            // Se ainda assim houver erro de FK, fazer soft delete
            if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
                $sql = "UPDATE produto SET ativo = 'N', nome = CONCAT(nome, ' [DESATIVADO]') WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":id", $id, PDO::PARAM_INT);
                $executed = $stmt->execute();
                
                if ($executed) {
                    if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
                        $_SESSION['carrinho'] = array_filter(
                            $_SESSION['carrinho'],
                            function($item) use ($id) {
                                return ($item['id'] ?? $item['produto_id'] ?? null) != $id;
                            }
                        );
                    }
                    echo "<script>alert('Produto desativado! Ele participa de vendas e foi removido de carrinhos.');</script>";
                }
                return $executed;
            }
            die("Erro ao excluir produto: " . $e->getMessage());
        }
    }
     
}
