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