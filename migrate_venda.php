<?php
/**
 * Script de migração: adicionar colunas à tabela 'venda'
 * Executa uma única vez para estender o schema
 */

require_once __DIR__ . '/config/Conexao.php';

try {
    $pdo = Conexao::conectar();

    // Lista de colunas a adicionar (apenas se não existirem)
    $columns = [
        'metodo_pagamento' => "ALTER TABLE venda ADD COLUMN metodo_pagamento VARCHAR(50) DEFAULT NULL",
        'txid' => "ALTER TABLE venda ADD COLUMN txid VARCHAR(100) DEFAULT NULL",
        'data_pagamento' => "ALTER TABLE venda ADD COLUMN data_pagamento DATETIME DEFAULT NULL"
    ];

    foreach ($columns as $colName => $sql) {
        try {
            $pdo->exec($sql);
            echo "✓ Coluna '{$colName}' adicionada com sucesso.<br>";
        } catch (PDOException $e) {
            // Se a coluna já existe, pula (código de erro 1060)
            if (strpos($e->getMessage(), '1060') !== false) {
                echo "ℹ Coluna '{$colName}' já existe.<br>";
            } else {
                throw $e;
            }
        }
    }

    echo "<br><strong>Migração concluída!</strong> A tabela 'venda' agora possui os campos de pagamento.";

} catch (PDOException $e) {
    echo "Erro na migração: " . htmlspecialchars($e->getMessage());
    exit(1);
}
?>
