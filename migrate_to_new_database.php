<?php
/**
 * Script de Migração: shop2b → ggnondrack_shop
 * Importa dados do banco antigo para o novo banco organizado
 */

require_once __DIR__ . '/config/Conexao.php';

try {
    // Conectar ao banco ANTIGO
    $pdoOld = new PDO("mysql:host=localhost;dbname=shop2b;charset=utf8mb4", "root", "");
    $pdoOld->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Conectar ao banco NOVO
    $pdoNew = new PDO("mysql:host=localhost;dbname=ggnondrack_shop;charset=utf8mb4", "root", "");
    $pdoNew->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "====================================\n";
    echo "MIGRAÇÃO: shop2b → ggnondrack_shop\n";
    echo "====================================\n\n";
    
    // 1. MIGRAR USUÁRIOS (unificar usuario + cliente)
    echo "1. Migrando usuários...\n";
    $pdoNew->exec("SET FOREIGN_KEY_CHECKS=0");
    $pdoNew->exec("TRUNCATE TABLE usuario");
    $pdoNew->exec("SET FOREIGN_KEY_CHECKS=1");
    
    $usuarios = $pdoOld->query("SELECT * FROM usuario")->fetchAll(PDO::FETCH_ASSOC);
    $stmtUsuario = $pdoNew->prepare(
        "INSERT INTO usuario (id, nome, email, senha, tipo, ativo) 
         VALUES (:id, :nome, :email, :senha, 'cliente', :ativo)"
    );
    
    foreach ($usuarios as $u) {
        $stmtUsuario->execute([
            ':id' => $u['id'],
            ':nome' => $u['nome'],
            ':email' => $u['email'],
            ':senha' => $u['senha'],
            ':ativo' => $u['ativo']
        ]);
    }
    echo "   ✓ " . count($usuarios) . " usuários migrados\n\n";
    
    // 2. MIGRAR CATEGORIAS
    echo "2. Migrando categorias...\n";
    $pdoNew->exec("SET FOREIGN_KEY_CHECKS=0");
    $pdoNew->exec("TRUNCATE TABLE categoria");
    $pdoNew->exec("SET FOREIGN_KEY_CHECKS=1");
    
    $categorias = $pdoOld->query("SELECT * FROM categoria")->fetchAll(PDO::FETCH_ASSOC);
    $stmtCategoria = $pdoNew->prepare(
        "INSERT INTO categoria (id, nome, ativo) 
         VALUES (:id, :nome, :ativo)"
    );
    
    foreach ($categorias as $c) {
        $stmtCategoria->execute([
            ':id' => $c['id'],
            ':nome' => $c['nome'],
            ':ativo' => $c['ativo']
        ]);
    }
    echo "   ✓ " . count($categorias) . " categorias migradas\n\n";
    
    // 3. MIGRAR PRODUTOS
    echo "3. Migrando produtos...\n";
    $pdoNew->exec("SET FOREIGN_KEY_CHECKS=0");
    $pdoNew->exec("TRUNCATE TABLE produto");
    $pdoNew->exec("SET FOREIGN_KEY_CHECKS=1");
    
    $produtos = $pdoOld->query("SELECT * FROM produto")->fetchAll(PDO::FETCH_ASSOC);
    $stmtProduto = $pdoNew->prepare(
        "INSERT INTO produto (id, nome, descricao, categoria_id, preco, estoque, imagem, ativo) 
         VALUES (:id, :nome, :descricao, :categoria_id, :preco, :estoque, :imagem, :ativo)"
    );
    
    foreach ($produtos as $p) {
        $stmtProduto->execute([
            ':id' => $p['id'],
            ':nome' => $p['nome'],
            ':descricao' => $p['descricao'] ?? null,
            ':categoria_id' => $p['categoria_id'],
            ':preco' => $p['preco'] ?? $p['valor'] ?? 0.00,
            ':estoque' => $p['estoque'] ?? $p['qtd'] ?? 0,
            ':imagem' => $p['imagem'] ?? $p['img'] ?? null,
            ':ativo' => $p['ativo']
        ]);
    }
    echo "   ✓ " . count($produtos) . " produtos migrados\n\n";
    
    // 4. MIGRAR VENDAS
    echo "4. Migrando vendas...\n";
    $pdoNew->exec("SET FOREIGN_KEY_CHECKS=0");
    $pdoNew->exec("TRUNCATE TABLE venda");
    $pdoNew->exec("SET FOREIGN_KEY_CHECKS=1");
    
    // Verificar se tabela venda existe no banco antigo
    $tableExists = $pdoOld->query("SHOW TABLES LIKE 'venda'")->rowCount() > 0;
    
    if ($tableExists) {
        $vendas = $pdoOld->query("SELECT * FROM venda")->fetchAll(PDO::FETCH_ASSOC);
        $stmtVenda = $pdoNew->prepare(
            "INSERT INTO venda (id, usuario_id, status, metodo_pagamento, txid, data_criacao, data_pagamento) 
             VALUES (:id, :usuario_id, :status, :metodo_pagamento, :txid, :data_criacao, :data_pagamento)"
        );
        
        foreach ($vendas as $v) {
            // Converter cliente_id para usuario_id (são os mesmos IDs)
            $stmtVenda->execute([
                ':id' => $v['id'],
                ':usuario_id' => $v['cliente_id'],
                ':status' => $v['status'] ?? 'aberta',
                ':metodo_pagamento' => $v['metodo_pagamento'] ?? null,
                ':txid' => $v['txid'] ?? null,
                ':data_criacao' => $v['data'] ?? date('Y-m-d H:i:s'),
                ':data_pagamento' => $v['data_pagamento'] ?? null
            ]);
        }
        echo "   ✓ " . count($vendas) . " vendas migradas\n\n";
    } else {
        echo "   ⚠ Tabela 'venda' não existe no banco antigo\n\n";
    }
    
    // 5. MIGRAR ITENS (se existirem)
    echo "5. Migrando itens de venda...\n";
    $pdoNew->exec("SET FOREIGN_KEY_CHECKS=0");
    $pdoNew->exec("TRUNCATE TABLE item_venda");
    $pdoNew->exec("SET FOREIGN_KEY_CHECKS=1");
    
    $itemTableExists = $pdoOld->query("SHOW TABLES LIKE 'item'")->rowCount() > 0;
    
    if ($itemTableExists && $tableExists) {
        $itens = $pdoOld->query("SELECT * FROM item")->fetchAll(PDO::FETCH_ASSOC);
        $stmtItem = $pdoNew->prepare(
            "INSERT INTO item_venda (venda_id, produto_id, quantidade, preco_unitario, subtotal) 
             VALUES (:venda_id, :produto_id, :quantidade, :preco_unitario, :subtotal)"
        );
        
        foreach ($itens as $i) {
            $qtd = $i['qtde'];
            $preco = $i['valor'];
            $subtotal = $qtd * $preco;
            
            $stmtItem->execute([
                ':venda_id' => $i['venda_id'],
                ':produto_id' => $i['produto_id'],
                ':quantidade' => $qtd,
                ':preco_unitario' => $preco,
                ':subtotal' => $subtotal
            ]);
        }
        echo "   ✓ " . count($itens) . " itens migrados\n\n";
    } else {
        echo "   ⚠ Tabela 'item' não existe no banco antigo\n\n";
    }
    
    echo "====================================\n";
    echo "✓ MIGRAÇÃO CONCLUÍDA COM SUCESSO!\n";
    echo "====================================\n\n";
    echo "Banco novo: ggnondrack_shop\n";
    echo "Melhorias:\n";
    echo "  - Unificação usuario/cliente\n";
    echo "  - Timestamps automáticos\n";
    echo "  - Tipos DECIMAL para valores\n";
    echo "  - Status de venda expandidos\n";
    echo "  - Índices para performance\n";
    echo "  - Nomenclatura clara (item_venda)\n\n";
    
} catch (Exception $e) {
    echo "\n❌ ERRO: " . $e->getMessage() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
    echo "Arquivo: " . $e->getFile() . "\n";
    exit(1);
}
