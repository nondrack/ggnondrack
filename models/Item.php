<?php
class Item {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar itens de uma venda
    public function listar($venda_id) {
        $sql = "SELECT i.id AS item_id, i.produto_id, i.quantidade AS qtde, i.preco_unitario AS valor, p.nome, p.imagem
                FROM item_venda i
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
            $sql = "SELECT id, quantidade AS qtde FROM item_venda WHERE venda_id = :venda_id AND produto_id = :produto_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':venda_id' => $venda_id,
                ':produto_id' => $produto_id
            ]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($item) {
                // Atualiza quantidade e subtotal
                $sql = "UPDATE item_venda SET quantidade = quantidade + :qtde, subtotal = (quantidade + :qtde) * preco_unitario WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':qtde' => $qtde,
                    ':id' => $item['id']
                ]);
            } else {
                // Insere novo item
                $subtotal = $qtde * $valor;
                $sql = "INSERT INTO item_venda (venda_id, produto_id, quantidade, preco_unitario, subtotal)
                        VALUES (:venda_id, :produto_id, :quantidade, :preco_unitario, :subtotal)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':venda_id' => $venda_id,
                    ':produto_id' => $produto_id,
                    ':quantidade' => $qtde,
                    ':preco_unitario' => $valor,
                    ':subtotal' => $subtotal
                ]);
            }
        } catch (Exception $e) {
            die("Erro ao adicionar item: " . $e->getMessage());
        }
    }

    // Excluir item
    public function excluir($item_id) {
        $sql = "DELETE FROM item_venda WHERE id = :item_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':item_id' => $item_id]);
    }

    // Limpar carrinho
    public function limpar($venda_id) {
        $sql = "DELETE FROM item_venda WHERE venda_id = :venda_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':venda_id' => $venda_id]);
    }
}
