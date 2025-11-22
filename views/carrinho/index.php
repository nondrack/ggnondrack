<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se não está logado, usar carrinho temporário do localStorage via JavaScript
// Se está logado, usar carrinho da sessão
$usuarioLogado = isset($_SESSION['user']);

// Itens do carrinho
$itens = $_SESSION["carrinho"] ?? [];
$total = 0;

// Função helper para obter preço unitário com fallback (preco -> valor)
function precoUnitarioCarrinho(array $item): float {
    // Usa 'preco' se existir e for > 0, senão tenta 'valor'
    if (isset($item['preco']) && $item['preco'] !== '' && $item['preco'] !== null) {
        return (float)$item['preco'];
    }
    if (isset($item['valor']) && $item['valor'] !== '' && $item['valor'] !== null) {
        return (float)$item['valor'];
    }
    return 0.0;
}

// Calcular total com fallback
foreach ($itens as $item) {
    $total += precoUnitarioCarrinho($item) * (int)$item['qtde'];
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
                                    $unit = precoUnitarioCarrinho($item);
                                    $subtotalItem = $unit * (int)$item['qtde'];
                                    $isUrlImg = !empty($item['imagem']) && preg_match('/^https?:\/\//i', $item['imagem']);
                                    $caminhoServidor = $isUrlImg ? null : (__DIR__ . '/../../_arquivos/' . $item['imagem']);
                                    $caminhoWeb = $isUrlImg ? $item['imagem'] : ('../_arquivos/' . $item['imagem']);
                                    $temImagem = !empty($item['imagem']) && ($isUrlImg || ($caminhoServidor && file_exists($caminhoServidor)));
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
                                                        <strong>R$ <?= number_format($unit, 2, ',', '.') ?></strong>
                                                        <span class="text-muted ms-2" style="font-size: 0.9rem;">por unidade</span>
                                                    </p>
                                                    <p class="text-light" style="font-size: 0.9rem;">
                                                        <span class="badge bg-secondary">ID: <?= $id ?></span>
                                                    </p>
                                                </div>

                                                <!-- QUANTIDADE -->
                                                <div class="col-md-2 col-4">
                                                    <?php
                                                        // Buscar estoque real do produto
                                                        require_once __DIR__ . '/../../models/Produto.php';
                                                        require_once __DIR__ . '/../../config/Conexao.php';
                                                        $pdoTemp = Conexao::conectar();
                                                        $produtoTemp = new Produto($pdoTemp);
                                                        $produtoEstoque = $produtoTemp->buscarPorId($id);
                                                        $estoqueMax = (int)($produtoEstoque->estoque ?? 0);
                                                    ?>
                                                    <form method="POST" action="index.php?param=carrinho/atualizar/<?= $id ?>" class="d-flex align-items-center justify-content-center">
                                                        <div class="btn-group qty-control" role="group" data-id="<?= $id ?>">
                                                            <button type="button" class="btn btn-sm btn-outline-info qty-minus" data-id="<?= $id ?>">−</button>
                                                            <input type="number" name="quantidade" value="<?= $item['qtde'] ?>" class="form-control form-control-sm text-center qty-input" style="width: 50px;" min="1" max="<?= $estoqueMax ?>" data-max="<?= $estoqueMax ?>">
                                                            <button type="button" class="btn btn-sm btn-outline-info qty-plus" data-id="<?= $id ?>" data-max="<?= $estoqueMax ?>">+</button>
                                                        </div>
                                                    </form>
                                                    <?php if ($estoqueMax > 0): ?>
                                                        <small class="text-muted d-block text-center mt-1" style="font-size: 0.75rem;">
                                                            <?php if ($estoqueMax <= 5): ?>
                                                                <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Apenas <?= $estoqueMax ?> disponível</span>
                                                            <?php else: ?>
                                                                <span class="text-info"><?= $estoqueMax ?> disponível</span>
                                                            <?php endif; ?>
                                                        </small>
                                                    <?php endif; ?>
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
                            <a href="#produtos-section" class="btn btn-outline-info">
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
        <?php if (!isset($_SESSION['user'])): ?>
            // Usuário não logado - salvar carrinho e redirecionar para login
            Swal.fire({
                title: 'Login Necessário',
                text: 'Você precisa fazer login para finalizar a compra.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: getComputedStyle(document.documentElement).getPropertyValue('--color-neon'),
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-sign-in-alt"></i> Fazer Login',
                cancelButtonText: '<i class="fas fa-times"></i> Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Salvar carrinho temporário e redirecionar para login
                    localStorage.setItem('carrinho_temp', JSON.stringify(<?= json_encode($itens) ?>));
                    location.href = 'login.php?redirect=carrinho/dados';
                }
            });
        <?php else: ?>
            // Usuário logado - prosseguir normalmente
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
                    location.href = 'index.php?param=carrinho/dados';
                }
            });
        <?php endif; ?>
    }

    // Aumentar quantidade
    document.querySelectorAll('.qty-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('form');
            const input = form.querySelector('.qty-input');
            const maxQty = parseInt(this.dataset.max || input.max);
            const currentQty = parseInt(input.value);
            
            if (currentQty >= maxQty) {
                Swal.fire({
                    title: 'Estoque Máximo!',
                    text: 'Você já tem a quantidade máxima disponível (' + maxQty + ' unidades).',
                    icon: 'warning',
                    confirmButtonColor: '#ffc107',
                    background: '#111827',
                    color: '#fff'
                });
                return;
            }
            
            input.value = currentQty + 1;
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
