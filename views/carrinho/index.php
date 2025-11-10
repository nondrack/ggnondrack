<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Itens do carrinho
$itens = $_SESSION["carrinho"] ?? [];
?>

<div class="container py-5">
    <div class="card shadow-lg border-0 bg-dark text-light rounded-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>🛒 Meu Carrinho</h2>
            <div>
                <a href="index.php?pagina=produto" class="btn btn-outline-light me-2">
                    <i class="fas fa-arrow-left"></i> Continuar Comprando
                </a>
                <a href="index.php?param=carrinho/limpar" class="btn btn-danger">
    <i class="fas fa-trash-alt"></i> Limpar Carrinho
</a>

            </div>
        </div>

        <div class="card-body">
            <?php if (!empty($itens)): ?>
                <div class="table-responsive">
                    <table class="table table-dark table-hover text-center align-middle">
                        <thead>
                            <tr>
                                <th>Imagem</th>
                                <th>Produto</th>
                                <th>Preço Unitário</th>
                                <th>Quantidade</th>
                                <th>Subtotal</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            <?php foreach ($itens as $id => $item): ?>
                                <?php
                                    $subtotal = $item['valor'] * $item['qtde'];
                                    $total += $subtotal;

                                    // Caminhos da imagem (igual ao usado na listagem)
                                    $caminhoServidor = __DIR__ . '/../../_arquivos/' . $item['imagem'];
                                    $caminhoWeb = '../_arquivos/' . $item['imagem'];
                                    $temImagem = !empty($item['imagem']) && file_exists($caminhoServidor);
                                ?>
                                <tr>
                                    <td>
                                        <?php if ($temImagem): ?>
                                            <img src="<?= $caminhoWeb ?>" alt="<?= htmlspecialchars($item['nome']) ?>" width="80" class="rounded border border-info shadow-sm">
                                        <?php else: ?>
                                            <div class="text-muted" style="font-size: 0.9rem;">Sem imagem</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($item['nome']) ?></td>
                                    <td>R$ <?= number_format($item['valor'], 2, ',', '.') ?></td>
                                    <td><?= $item['qtde'] ?></td>
                                    <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                                    <td>
                                        <a href="index.php?pagina=carrinho&metodo=remover&id=<?= $id ?>" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="fw-bold">
                                <td colspan="4" class="text-end">Total:</td>
                                <td colspan="2">R$ <?= number_format($total, 2, ',', '.') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-end mt-3">
                    <a href="index.php?pagina=checkout" class="btn btn-success btn-lg">
                        <i class="fas fa-credit-card"></i> Finalizar Compra
                    </a>
                </div>
            <?php else: ?>
                <p class="text-center text-muted fs-5 py-4">Seu carrinho está vazio.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.table thead {
    background-color: #00eaff33;
}
.table td, .table th {
    vertical-align: middle;
}
.btn-outline-light:hover {
    background-color: #00eaff;
    color: #000;
    box-shadow: 0 0 15px #00eaff;
}
</style>
