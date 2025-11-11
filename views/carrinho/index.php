<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Itens do carrinho
$itens = $_SESSION["carrinho"] ?? [];
$total = 0;

// Calcular total
foreach ($itens as $item) {
    $total += $item['valor'] * $item['qtde'];
}

// Simular taxas e descontos
$subtotal = $total;
$taxa = 0; // Sem taxa
$desconto = 0; // Pode ser atualizado dinamicamente
$totalFinal = $subtotal - $desconto;
?>

<div class="container py-5">
    <div class="row">
        <!-- COLUNA PRINCIPAL - ITENS -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 bg-dark text-light rounded-4 mb-4">
                <div class="card-header bg-gradient border-bottom border-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">
                            <i class="fas fa-shopping-cart me-2" style="color: #00eaff;"></i> Meu Carrinho
                        </h2>
                        <span class="badge bg-info fs-6">
                            <?= count($itens) ?> itens
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <?php if (!empty($itens)): ?>
                        <div class="row g-3">
                            <?php foreach ($itens as $id => $item): ?>
                                <?php
                                    $subtotalItem = $item['valor'] * $item['qtde'];
                                    $caminhoServidor = __DIR__ . '/../../_arquivos/' . $item['imagem'];
                                    $caminhoWeb = '../_arquivos/' . $item['imagem'];
                                    $temImagem = !empty($item['imagem']) && file_exists($caminhoServidor);
                                ?>
                                <div class="col-12">
                                    <div class="card bg-secondary bg-opacity-25 border-info border-opacity-50 rounded-3">
                                        <div class="card-body">
                                            <div class="row align-items-center g-3">
                                                <!-- IMAGEM -->
                                                <div class="col-md-2 col-4">
                                                    <?php if ($temImagem): ?>
                                                        <img src="<?= $caminhoWeb ?>" alt="<?= htmlspecialchars($item['nome']) ?>" class="img-fluid rounded-2 border border-info shadow-sm">
                                                    <?php else: ?>
                                                        <div class="bg-secondary rounded-2 d-flex align-items-center justify-content-center" style="height: 100px;">
                                                            <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- INFORMAÇÕES -->
                                                <div class="col-md-5 col-8">
                                                    <h5 class="text-light mb-2">
                                                        <?= htmlspecialchars($item['nome']) ?>
                                                    </h5>
                                                    <p class="text-info mb-2">
                                                        <strong>R$ <?= number_format($item['valor'], 2, ',', '.') ?></strong>
                                                        <span class="text-muted ms-2" style="font-size: 0.9rem;">por unidade</span>
                                                    </p>
                                                    <p class="text-light" style="font-size: 0.9rem;">
                                                        <span class="badge bg-secondary">ID: <?= $id ?></span>
                                                    </p>
                                                </div>

                                                <!-- QUANTIDADE -->
                                                <div class="col-md-2 col-4">
                                                    <form method="POST" action="index.php?param=carrinho/atualizar/<?= $id ?>" class="d-flex align-items-center justify-content-center">
                                                        <div class="btn-group qty-control" role="group" data-id="<?= $id ?>">
                                                            <button type="button" class="btn btn-sm btn-outline-info qty-minus" data-id="<?= $id ?>">−</button>
                                                            <input type="number" name="quantidade" value="<?= $item['qtde'] ?>" class="form-control form-control-sm text-center qty-input" style="width: 50px;" min="1" max="999">
                                                            <button type="button" class="btn btn-sm btn-outline-info qty-plus" data-id="<?= $id ?>">+</button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!-- SUBTOTAL -->
                                                <div class="col-md-2 col-4 text-center">
                                                    <div>
                                                        <p class="text-muted mb-1" style="font-size: 0.9rem;">Subtotal</p>
                                                        <p class="text-success fw-bold fs-5">
                                                            R$ <?= number_format($subtotalItem, 2, ',', '.') ?>
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- AÇÕES -->
                                                <div class="col-12 col-md-1 text-center">
                                                    <a href="index.php?param=carrinho/remover/<?= $id ?>" class="btn btn-sm btn-danger" title="Remover do carrinho" onclick="return confirm('Tem certeza?');">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="index.php?param=produto/index" class="btn btn-outline-info">
                                <i class="fas fa-arrow-left me-2"></i> Continuar Comprando
                            </a>
                            <a href="index.php?param=carrinho/limpar" class="btn btn-outline-danger" onclick="return confirm('Deseja limpar o carrinho?');">
                                <i class="fas fa-trash-alt me-2"></i> Limpar Carrinho
                            </a>
                        </div>

                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #00eaff33;"></i>
                            <p class="text-muted fs-5 mt-3">Seu carrinho está vazio.</p>
                            <a href="#produtos-section" class="btn btn-info mt-3">
                                <i class="fas fa-shopping-bag me-2"></i> Começar a Comprar
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- COLUNA LATERAL - RESUMO -->
        <?php if (!empty($itens)): ?>
            <div class="col-lg-4">
                <div class="card shadow-lg border-0 bg-dark text-light rounded-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-gradient border-bottom border-info">
                        <h4 class="mb-0">
                            <i class="fas fa-receipt me-2" style="color: #00eaff;"></i> Resumo do Pedido
                        </h4>
                    </div>

                    <div class="card-body">
                        <!-- ITENS -->
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-light">Subtotal:</span>
                            <span class="text-light fw-bold">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                        </div>

                        <!-- DESCONTO (se houver) -->
                        <?php if ($desconto > 0): ?>
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom border-secondary">
                                <span class="text-success">Desconto:</span>
                                <span class="text-success fw-bold">-R$ <?= number_format($desconto, 2, ',', '.') ?></span>
                            </div>
                        <?php endif; ?>

                        <!-- TOTAL FINAL -->
                        <div class="d-flex justify-content-between mt-3 pt-3 border-top border-info">
                            <span class="fs-5 fw-bold">Total:</span>
                            <span class="fs-5 fw-bold text-success">R$ <?= number_format($totalFinal, 2, ',', '.') ?></span>
                        </div>

                        <!-- INFORMAÇÕES ADICIONAIS -->
                        <div class="mt-4 pt-4 border-top border-secondary">
                            <p class="text-muted small mb-2">
                                <i class="fas fa-info-circle me-2"></i> Quantidade total de itens: <strong><?php
                                    $qty = 0;
                                    foreach ($itens as $item) {
                                        $qty += $item['qtde'];
                                    }
                                    echo $qty;
                                ?></strong>
                            </p>
                        </div>

                        <!-- BOTÃO FINALIZAR COMPRA -->
                        <button class="btn btn-success btn-lg w-100 mt-4" onclick="finalizarCompra()">
                            <i class="fas fa-credit-card me-2"></i> Finalizar Compra
                        </button>

                        <!-- BOTÃO CONTINUAR COMPRANDO (mobile) -->
                        <a href="index.php?param=produto/index" class="btn btn-outline-info w-100 mt-2 d-lg-none">
                            <i class="fas fa-shopping-bag me-2"></i> Continuar
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

<script>
    function finalizarCompra() {
        Swal.fire({
            title: 'Confirmar Compra?',
            text: 'Você deseja prosseguir para o checkout?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: getComputedStyle(document.documentElement).getPropertyValue('--color-neon'),
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check"></i> Sim, Confirmar',
            cancelButtonText: '<i class="fas fa-times"></i> Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // location.href = 'index.php?param=checkout';
                alert('Checkout em desenvolvimento!');
            }
        });
    }

    // Aumentar quantidade
    document.querySelectorAll('.qty-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('form');
            const input = form.querySelector('.qty-input');
            input.value = parseInt(input.value) + 1;
            form.submit();
        });
    });

    // Diminuir quantidade
    document.querySelectorAll('.qty-minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('form');
            const input = form.querySelector('.qty-input');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                form.submit();
            }
        });
    });
</script>
