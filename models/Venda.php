<?php

class Venda {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function criarVenda($usuario_id) {
        // Verificar se o usuário existe
        if ($usuario_id <= 0) {
            throw new Exception("ID de usuário inválido");
        }
        
        $checkSql = "SELECT id FROM usuario WHERE id = :usuario_id AND ativo = 'S'";
        $checkStmt = $this->pdo->prepare($checkSql);
        $checkStmt->execute([':usuario_id' => $usuario_id]);
        
        if ($checkStmt->rowCount() === 0) {
            throw new Exception("Usuário não encontrado. Por favor, faça login antes de finalizar a compra.");
        }
        
        $stmt = $this->pdo->prepare("INSERT INTO venda (usuario_id, data_criacao, status) VALUES (:usuario_id, NOW(), 'aberta')");
        $stmt->execute([':usuario_id' => $usuario_id]);
        return $this->pdo->lastInsertId();
    }

    /**
     * Salva os itens da venda na tabela `item_venda`.
     * $itens deve ser um array onde cada item contém 'id' (produto_id), 'qtde' e 'valor'.
     */
    public function salvarItens($venda_id, array $itens) {
        // Remover itens antigos, se houver
        $this->pdo->beginTransaction();
        try {
            $del = $this->pdo->prepare("DELETE FROM item_venda WHERE venda_id = :venda_id");
            $del->execute([':venda_id' => $venda_id]);

            $ins = $this->pdo->prepare("INSERT INTO item_venda (venda_id, produto_id, quantidade, preco_unitario, subtotal) VALUES (:venda_id, :produto_id, :quantidade, :preco_unitario, :subtotal)");
            $updateEstoque = $this->pdo->prepare("UPDATE produto SET estoque = estoque - :quantidade WHERE id = :produto_id");
            $totalVenda = 0.0;
            
            foreach ($itens as $item) {
                // suportar tanto formato indexado quanto associativo
                $produtoId = $item['id'] ?? $item['produto_id'] ?? null;
                $qtde = (int)($item['qtde'] ?? $item['quantity'] ?? 1);
                $valor = (float)($item['valor'] ?? $item['unit_price'] ?? 0);
                $subtotal = $qtde * $valor;
                if (!$produtoId) continue;
                
                // Inserir item da venda
                $ins->execute([
                    ':venda_id' => $venda_id,
                    ':produto_id' => $produtoId,
                    ':quantidade' => $qtde,
                    ':preco_unitario' => $valor,
                    ':subtotal' => $subtotal
                ]);
                
                // Reduzir estoque do produto
                $updateEstoque->execute([
                    ':quantidade' => $qtde,
                    ':produto_id' => $produtoId
                ]);

                $totalVenda += $subtotal;
            }

            $this->pdo->commit();
            // Atualiza total da venda após commit dos itens
            try {
                $updTotal = $this->pdo->prepare("UPDATE venda SET valor_total = :total WHERE id = :id");
                $updTotal->execute([':total' => $totalVenda, ':id' => $venda_id]);
            } catch (PDOException $e) {
                // silenciosamente ignorar, não crítico para fluxo
            }
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function finalizarVenda($venda_id, $metodo_pagamento = 'pix', $txid = null) {
        $stmt = $this->pdo->prepare("UPDATE venda SET status = 'aguardando_pagamento', metodo_pagamento = :metodo, txid = :txid WHERE id = :id");
        $stmt->execute([
            ':id' => $venda_id,
            ':metodo' => $metodo_pagamento,
            ':txid' => $txid
        ]);
        return $stmt->rowCount() > 0;
    }
    
    public function confirmarPagamento($venda_id) {
        $stmt = $this->pdo->prepare("UPDATE venda SET status = 'paga', data_pagamento = NOW() WHERE id = :id");
        $stmt->execute([':id' => $venda_id]);
        return $stmt->rowCount() > 0;
    }

    public function buscarPorId($venda_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM venda WHERE id = :id");
        $stmt->execute([':id' => $venda_id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}