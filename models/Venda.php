<?php

class Venda {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function criarVenda($cliente_id) {
        $stmt = $this->pdo->prepare("INSERT INTO venda (cliente_id, data, status) VALUES (:cliente_id, NOW(), 'aberta')");
        $stmt->execute([':cliente_id' => $cliente_id]);
        return $this->pdo->lastInsertId();
    }

    /**
     * Salva os itens da venda na tabela `item`.
     * $itens deve ser um array onde cada item contém 'id' (produto_id), 'qtde' e 'valor'.
     */
    public function salvarItens($venda_id, array $itens) {
        // Remover itens antigos, se houver
        $this->pdo->beginTransaction();
        try {
            $del = $this->pdo->prepare("DELETE FROM item WHERE venda_id = :venda_id");
            $del->execute([':venda_id' => $venda_id]);

            $ins = $this->pdo->prepare("INSERT INTO item (venda_id, produto_id, qtde, valor) VALUES (:venda_id, :produto_id, :qtde, :valor)");
            foreach ($itens as $item) {
                // suportar tanto formato indexado quanto associativo
                $produtoId = $item['id'] ?? $item['produto_id'] ?? null;
                $qtde = (int)($item['qtde'] ?? $item['quantity'] ?? 1);
                $valor = (float)($item['valor'] ?? $item['unit_price'] ?? 0);
                if (!$produtoId) continue;
                $ins->execute([
                    ':venda_id' => $venda_id,
                    ':produto_id' => $produtoId,
                    ':qtde' => $qtde,
                    ':valor' => $valor
                ]);
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function finalizarVenda($venda_id, $metodo_pagamento = 'pix', $txid = null) {
        $stmt = $this->pdo->prepare("UPDATE venda SET status = 'paga', metodo_pagamento = :metodo, txid = :txid, data_pagamento = NOW() WHERE id = :id");
        $stmt->execute([
            ':id' => $venda_id,
            ':metodo' => $metodo_pagamento,
            ':txid' => $txid
        ]);
        return $stmt->rowCount() > 0;
    }

    public function buscarPorId($venda_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM venda WHERE id = :id");
        $stmt->execute([':id' => $venda_id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}