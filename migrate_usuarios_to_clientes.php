<?php
/**
 * Script de migração: copia usuários que não existem em cliente
 * Execute este arquivo uma vez para sincronizar os dados
 */

require_once __DIR__ . '/config/Conexao.php';

try {
    $pdo = Conexao::conectar();
    
    echo "Iniciando migração de usuários para clientes...\n";
    
    // Buscar usuários que não existem em cliente
    $sql = "SELECT u.id, u.nome, u.email, u.senha, u.ativo 
            FROM usuario u 
            LEFT JOIN cliente c ON u.id = c.id 
            WHERE c.id IS NULL";
    
    $stmt = $pdo->query($sql);
    $usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    if (empty($usuarios)) {
        echo "Nenhum usuário precisa ser migrado. Todos já existem como clientes.\n";
        exit;
    }
    
    echo "Encontrados " . count($usuarios) . " usuários para migrar.\n";
    
    $pdo->beginTransaction();
    
    $inseridos = 0;
    foreach ($usuarios as $usuario) {
        $insertSql = "INSERT INTO cliente (id, nome, email, senha, ativo) 
                      VALUES (:id, :nome, :email, :senha, :ativo)";
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute([
            ':id' => $usuario->id,
            ':nome' => $usuario->nome,
            ':email' => $usuario->email,
            ':senha' => $usuario->senha,
            ':ativo' => $usuario->ativo
        ]);
        $inseridos++;
        echo "Migrado: {$usuario->nome} ({$usuario->email})\n";
    }
    
    $pdo->commit();
    
    echo "\nMigração concluída com sucesso!\n";
    echo "Total de registros inseridos em cliente: $inseridos\n";
    
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "ERRO na migração: " . $e->getMessage() . "\n";
    exit(1);
}
