<?php
// Inicializa sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui o controller
require_once "../controllers/CarrinhoController.php";

// Instancia o controller
$carrinho = new CarrinhoController();

// Pega os itens do carrinho
$itens = $carrinho->item->listar($carrinho->venda_id);
?>

<div class="container py-5">
    <div class="card shadow-lg border-0 bg-dark text-light rounded-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>🛒 Meu Carrinho</h2>
            <div>
                <a href="../produto/listar.php" class="btn btn-outline-light me-2">
                    <i class="fas fa-arrow-left"></i> Continuar Comprando
                </a>
                <a href="../carrinho/limpar.php" class="btn btn-danger">
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
                            <th>Produto</th>
                            <th>Preço Unitário</th>
                            <th>Quantidade</th>
                            <th>Subtotal</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        <?php foreach ($itens as $item): 
                            $subtotal = $item['valor'] * $item['qtde'];
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td>R$ <?= number_format($item['valor'], 2, ',', '.') ?></td>
                            <td><?= $item['qtde'] ?></td>
                            <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                            <td>
                                <a href="../carrinho/excluir.php?item_id=<?= $item['item_id'] ?>" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end">Total:</td>
                            <td colspan="2">R$ <?= number_format($total, 2, ',', '.') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <a href="../checkout.php" class="btn btn-success btn-lg">
                    <i class="fas fa-credit-card"></i> Finalizar Compra
                </a>
            </div>
            <?php else: ?>
                <p class="text-center text-muted fs-5 py-4">Seu carrinho está vazio.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
