<?php
/**
 * Script de correção de preços/descrições legadas.
 * Objetivo: produtos inseridos antes da correção podem ter:
 *  - preco = 0
 *  - descricao contendo apenas um número que era o preço original
 * Estratégia: se preco=0 e descricao casar com padrão de número/valor monetário,
 * então mover valor para preco e limpar descrição (ou manter se quiser).
 */

require_once __DIR__ . '/../config/Conexao.php';
$pdo = Conexao::conectar();

$regex = '/^\s*(\d+[\.,]\d{1,2}|\d+)\s*$/';

echo "Iniciando correção de produtos legados...\n";

$sql = "SELECT id, nome, descricao, preco FROM produto";
$stmt = $pdo->query($sql);
$produtos = $stmt->fetchAll(PDO::FETCH_OBJ);

$corrigidos = 0;
foreach ($produtos as $p) {
    if ((float)$p->preco == 0 && preg_match($regex, $p->descricao ?? '', $m)) {
        $valorOriginal = str_replace(['.', ','], ['.', '.'], $m[1]);
        // Se veio formato brasileiro (ex: 1.234,56) isso simples não cobre; fazer tratamento:
        $v = $m[1];
        // Remove milhar
        $v = preg_replace('/\.(?=\d{3}(\D|$))/', '', $v);
        // Troca vírgula decimal
        $v = str_replace(',', '.', $v);
        $precoFinal = (float)$v;

        $upd = $pdo->prepare("UPDATE produto SET preco = :preco, descricao = '' WHERE id = :id");
        $upd->execute([':preco' => $precoFinal, ':id' => $p->id]);
        echo "Corrigido produto ID {$p->id} -> preco={$precoFinal}\n";
        $corrigidos++;
    }
}

echo "\nCorreção concluída. Total corrigido: {$corrigidos}\n";
