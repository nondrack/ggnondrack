<?php
class Item {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar itens de uma venda
    public function listar($venda_id) {
        $sql = "SELECT i.id AS item_id, i.produto_id, i.qtde, i.valor, p.nome, p.imagem
                FROM item i
                JOIN produto p ON i.produto_id = p.id
                WHERE i.venda_id = :venda_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':venda_id' => $venda_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Adicionar ou atualizar item
    public function adicionar($venda_id, $produto_id, $qtde, $valor) {
        try {
            // Verifica se a venda existe
            $stmt = $this->pdo->prepare("SELECT id FROM venda WHERE id = :venda_id");
            $stmt->execute([':venda_id' => $venda_id]);
            if (!$stmt->fetch()) {
                throw new Exception("Venda não encontrada: impossível adicionar item.");
            }

            // Verifica se o produto já está no carrinho
            $sql = "SELECT id, qtde FROM item WHERE venda_id = :venda_id AND produto_id = :produto_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':venda_id' => $venda_id,
                ':produto_id' => $produto_id
            ]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($item) {
                // Atualiza quantidade
                $sql = "UPDATE item SET qtde = qtde + :qtde WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':qtde' => $qtde,
                    ':id' => $item['id']
                ]);
            } else {
                // Insere novo item
                $sql = "INSERT INTO item (venda_id, produto_id, qtde, valor)
                        VALUES (:venda_id, :produto_id, :qtde, :valor)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':venda_id' => $venda_id,
                    ':produto_id' => $produto_id,
                    ':qtde' => $qtde,
                    ':valor' => $valor
                ]);
            }
        } catch (Exception $e) {
            die("Erro ao adicionar item: " . $e->getMessage());
        }
    }

    // Excluir item
    public function excluir($item_id) {
        $sql = "DELETE FROM item WHERE id = :item_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':item_id' => $item_id]);
    }

    // Limpar carrinho
    public function limpar($venda_id) {
        $sql = "DELETE FROM item WHERE venda_id = :venda_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':venda_id' => $venda_id]);
    }
}
