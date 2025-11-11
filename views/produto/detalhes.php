<?php
// $produto já vem do controller
// Caminhos das imagens
$caminhoServidor = __DIR__ . '/../../_arquivos/' . $produto->imagem;
$caminhoWeb = '../_arquivos/' . $produto->imagem;

// Garantir que $imagens seja sempre um array
$imagens = [];
if (!empty($produto->imagem) && file_exists($caminhoServidor)) {
    $imagens[] = $produto->imagem;
} else {
    $imagens[] = null;
}
?>

<div class="container my-5">
    <div class="card mx-auto p-3" style="max-width: 950px; background-color:#111827; border:1px solid #00eaff40; box-shadow:0 0 25px #00eaff30; border-radius:12px;">
        <div class="row g-3">
            <!-- Galeria de imagens -->
            <div class="col-md-6">
                <div class="text-center mb-3">
                    <?php if ($imagens[0]): ?>
                        <img id="imagem-principal" src="<?= $caminhoWeb ?>" class="img-fluid" style="max-height:350px; object-fit:contain; border-radius:8px;">
                    <?php else: ?>
                        <div class="text-muted" style="height:350px; display:flex; align-items:center; justify-content:center; border:1px dashed #00eaff;">
                            Sem imagem
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Detalhes do produto -->
            <div class="col-md-6 text-light">
                <h2 style="color:#00eaff;"><?= htmlspecialchars($produto->nome) ?></h2>
                <p class="text-light mb-3" style="font-weight:bold; font-size:1.5rem;">
                    R$ <?= number_format($produto->valor, 2, ',', '.') ?>
                </p>

                <!-- Descrição -->
                <div class="mb-3">
                    <p><?= nl2br(htmlspecialchars($produto->descricao ?? "Sem descrição")) ?></p>
                </div>

                <!-- Botões -->
                <a href="javascript:void(0);" onclick="adicionarAoCarrinho(<?= (int)$produto->id ?>)" class="btn btn-neon w-100 mb-2" role="button">
                    Comprar
                </a>
                <a href="index" class="btn btn-outline-neon w-100">
                    Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Script para trocar a imagem principal ao clicar na miniatura -->
<script>
document.querySelectorAll('.mini-img').forEach(img => {
    img.addEventListener('click', function() {
        document.getElementById('imagem-principal').src = this.src;
    });
});
</script>
<!-- product details styles moved to public/css/components/views-inline.css -->
