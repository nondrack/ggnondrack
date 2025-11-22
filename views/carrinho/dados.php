<?php
// Evita Notice de sessão já iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se não há carrinho, redirecionar
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    echo "<script>alert('Carrinho vazio!'); location.href='index.php?param=carrinho';</script>";
    exit;
}

// Helper preço unitário (fallback preco -> valor)
function precoUnitarioDados(array $p): float {
    if (isset($p['preco']) && $p['preco'] !== '' && $p['preco'] !== null) {
        return (float)$p['preco'];
    }
    if (isset($p['valor']) && $p['valor'] !== '' && $p['valor'] !== null) {
        return (float)$p['valor'];
    }
    return 0.0;
}

$total = 0;
$itensResumo = [];
foreach ($_SESSION['carrinho'] as $p) {
    $unit = precoUnitarioDados($p);
    $subtotal = $unit * (int)$p['qtde'];
    $total += $subtotal;
    $itensResumo[] = [
        'nome' => $p['nome'],
        'qtde' => $p['qtde'],
        'unit' => $unit,
        'subtotal' => $subtotal,
        'imagem' => $p['imagem'] ?? ''
    ];
}

// Simulação simples de frete (pode evoluir futuramente)
$frete = ($total > 0) ? 29.90 : 0.0;
$desconto = 0.0; // Placeholder para cupons futuros
$totalFinal = $total + $frete - $desconto;
?>
<div class="checkout-wrapper container py-5">
    <div class="row g-4">
        <!-- Coluna Formulário -->
        <div class="col-lg-7">
            <div class="card neon-card shadow-lg border-0">
                <div class="card-header bg-transparent border-bottom border-info d-flex justify-content-between align-items-center flex-wrap">
                    <h3 class="mb-0 text-light">
                        <i class="fas fa-map-marked-alt me-2" style="color:#00eaff"></i>
                        Informações de Entrega
                    </h3>
                    <span class="badge bg-info bg-opacity-25 text-info">
                        <?= count($itensResumo) ?> itens no pedido
                    </span>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?param=carrinho/finalizar" id="formEntrega" data-parsley-validate novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-light" for="nome">Nome Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control neon-input" name="nome" id="nome" required data-parsley-required-message="Informe seu nome completo" placeholder="Seu nome">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-light" for="email">E-mail <span class="text-danger">*</span></label>
                                <input type="email" class="form-control neon-input" name="email" id="email" required data-parsley-type="email" data-parsley-required-message="Informe um e-mail" data-parsley-type-message="E-mail inválido" placeholder="seu.email@exemplo.com">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-light" for="cep">CEP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control neon-input" name="cep" id="cep" required placeholder="00000-000" data-parsley-required-message="Informe o CEP">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label text-light" for="endereco">Endereço <span class="text-danger">*</span></label>
                                <input type="text" class="form-control neon-input" name="endereco" id="endereco" required placeholder="Rua / Avenida" data-parsley-required-message="Informe o endereço">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-light" for="numero">Número <span class="text-danger">*</span></label>
                                <input type="text" class="form-control neon-input" name="numero" id="numero" required placeholder="Número" data-parsley-required-message="Informe o número">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label text-light" for="bairro">Bairro <span class="text-danger">*</span></label>
                                <input type="text" class="form-control neon-input" name="bairro" id="bairro" required placeholder="Bairro" data-parsley-required-message="Informe o bairro">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-light" for="cidade">Cidade <span class="text-danger">*</span></label>
                                <input type="text" class="form-control neon-input" name="cidade" id="cidade" required placeholder="Cidade" data-parsley-required-message="Informe a cidade">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-light" for="estado">UF <span class="text-danger">*</span></label>
                                <select class="form-select neon-input" name="estado" id="estado" required data-parsley-required-message="Selecione UF">
                                    <option value="">UF</option>
                                    <?php
                                    $ufs = ["AC","AL","AP","AM","BA","CE","DF","ES","GO","MA","MT","MS","MG","PA","PB","PR","PE","PI","RJ","RN","RS","RO","RR","SC","SP","SE","TO"];
                                    foreach ($ufs as $uf) echo "<option value='$uf'>$uf</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label text-light" for="complemento">Complemento</label>
                                <input type="text" class="form-control neon-input" name="complemento" id="complemento" placeholder="Apartamento / Referência">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-light" for="telefone">Telefone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control neon-input" name="telefone" id="telefone" required placeholder="(00) 00000-0000" data-parsley-required-message="Informe o telefone">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-light" for="observacoes">Observações</label>
                                <textarea class="form-control neon-input" name="observacoes" id="observacoes" rows="3" placeholder="Alguma instrução adicional para a entrega?"></textarea>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-between flex-wrap gap-2">
                            <a href="index.php?param=carrinho" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Voltar ao Carrinho
                            </a>
                            <button type="submit" class="btn btn-neon">
                                <i class="fas fa-credit-card me-1"></i> Ir para Pagamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Coluna Resumo -->
        <div class="col-lg-5">
            <div class="card neon-card shadow-lg border-0 h-100">
                <div class="card-header bg-transparent border-bottom border-info d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-light">
                        <i class="fas fa-receipt me-2" style="color:#00eaff"></i>Resumo do Pedido
                    </h4>
                </div>
                <div class="card-body">
                    <div class="cart-items-scroll mb-3">
                        <?php foreach ($itensResumo as $i): ?>
                            <div class="d-flex align-items-center py-2 border-bottom border-dark small-item">
                                <?php
                                $img = $i['imagem'] ?? '';
                                $isUrl = $img && preg_match('/^https?:\/\//i', $img);
                                $src = $isUrl ? $img : ($img ? '../_arquivos/' . $img : '');
                                ?>
                                <div class="me-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;background:#1e293b;border:1px solid #00eaff20;border-radius:8px;overflow:hidden;">
                                    <?php if ($src): ?>
                                        <img src="<?= htmlspecialchars($src) ?>" alt="Imagem" style="max-width:100%;max-height:100%;object-fit:cover;">
                                    <?php else: ?>
                                        <i class="fas fa-image text-muted"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-light fw-semibold" style="font-size:.9rem;line-height:1.2;">
                                        <?= htmlspecialchars($i['nome']) ?>
                                    </div>
                                    <div class="text-muted" style="font-size:.7rem;">Qtde: <?= $i['qtde'] ?> • R$ <?= number_format($i['unit'],2,',','.') ?></div>
                                </div>
                                <div class="text-success fw-bold" style="font-size:.8rem;">R$ <?= number_format($i['subtotal'],2,',','.') ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item bg-transparent d-flex justify-content-between text-light">
                            <span>Subtotal</span>
                            <span>R$ <?= number_format($total,2,',','.') ?></span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between text-light">
                            <span>Frete</span>
                            <span>R$ <?= number_format($frete,2,',','.') ?></span>
                        </li>
                        <?php if ($desconto > 0): ?>
                        <li class="list-group-item bg-transparent d-flex justify-content-between text-success">
                            <span>Desconto</span>
                            <span>- R$ <?= number_format($desconto,2,',','.') ?></span>
                        </li>
                        <?php endif; ?>
                        <li class="list-group-item bg-transparent d-flex justify-content-between text-neon fw-bold fs-5">
                            <span>Total</span>
                            <span>R$ <?= number_format($totalFinal,2,',','.') ?></span>
                        </li>
                    </ul>
                    <div class="alert alert-info py-2 mb-0" style="background:#0b0f19;border:1px solid #00eaff40;color:#cbd5e1;font-size:.8rem;">
                        <i class="fas fa-info-circle me-1"></i>
                        O frete é um valor simulado. Você poderá revisar antes de pagar.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .checkout-wrapper {min-height:70vh;}
    .neon-card {background:linear-gradient(145deg,#111827 0%,#0b0f19 100%);border:1px solid #00eaff30;border-radius:16px;}
    .neon-card:hover {border-color:#00eaff80;box-shadow:0 0 18px #00eaff25;}
    .neon-input {background:#1e293b!important;border:1px solid #334155;color:#e2e8f0;}
    .neon-input:focus {border-color:#00eaff;box-shadow:0 0 0 0.15rem rgba(0,234,255,.25);color:#fff;}
    .cart-items-scroll {max-height:270px;overflow-y:auto;padding-right:4px;}
    .cart-items-scroll::-webkit-scrollbar {width:6px;}
    .cart-items-scroll::-webkit-scrollbar-track {background:#1e293b;border-radius:8px;}
    .cart-items-scroll::-webkit-scrollbar-thumb {background:#00eaff40;border-radius:8px;}
    .btn-neon {background:#00eaff;color:#000;font-weight:600;border:none;}
    .btn-neon:hover {background:#26f3ff;color:#000;}
    .text-neon {color:#00eaff;}
    @media (max-width:991.98px){.checkout-wrapper .neon-card{margin-bottom:1rem}}
</style>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/parsley.min.js"></script>
<script src="js/jquery.inputmask.min.js"></script>
<script>
// Máscaras básicas CEP e telefone
$(function(){
    if ($.fn.inputmask) {
        $('#cep').inputmask('99999-999');
        $('#telefone').inputmask({'mask': '(99) 99999-9999'});
    }
});

// Auto-busca de CEP (ViaCEP) – opcional
document.getElementById('cep').addEventListener('blur', async function(){
    const cep = this.value.replace(/\D/g,'');
    if (cep.length !== 8) return;
    try {
        const r = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        if (!r.ok) return;
        const data = await r.json();
        if (data.erro) return;
        document.getElementById('endereco').value = data.logradouro || '';
        document.getElementById('bairro').value = data.bairro || '';
        document.getElementById('cidade').value = data.localidade || '';
        document.getElementById('estado').value = data.uf || '';
    } catch(e){/* silencioso */}
});
</script>
