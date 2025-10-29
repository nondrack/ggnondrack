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
    
    // Outros métodos como:
    // public function buscarPorId($venda_id) { ... }
    // public function finalizarVenda($venda_id) { ... }
}