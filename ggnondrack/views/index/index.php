<?php
require_once __DIR__ . '/../../config/Conexao.php';
require_once __DIR__ . '/../../models/Produto.php';
require_once __DIR__ . '/../../models/Categoria.php';

// Conexão com o banco
$pdo = Conexao::conectar();
$produtoModel = new Produto($pdo);
$produtos = $produtoModel->listar();

// Puxar todas as categorias do banco
$categoriaModel = new Categoria($pdo);
$categorias = $categoriaModel->listar(); // array de objetos com id e nome
?>

<div class="container my-5">
    <h2 class="text-center mb-4" style="color:#00eaff; text-shadow:0 0 5px #00eaff;">Produtos Disponíveis</h2>

    <!-- Filtro por categoria -->
    <div class="text-center mb-4">
        <select id="filtro-categoria" class="form-select w-auto mx-auto" style="background-color:#111827; color:#00eaff; border:1px solid #00eaff; text-align:center;">
            <option value="todas">Todas as categorias</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= htmlspecialchars($cat->nome) ?>"><?= htmlspecialchars($cat->nome) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="row" id="produtos-container">
        <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <?php
                    $caminhoServidor = __DIR__ . '/../../_arquivos/' . $produto->imagem;
                    $caminhoWeb = '../_arquivos/' . $produto->imagem;
                    $temImagem = !empty($produto->imagem) && file_exists($caminhoServidor);
                ?>
                <div class="col-12 col-md-4 mb-4 produto-item" data-categoria="<?= htmlspecialchars($produto->categoria) ?>">
                    <div class="card p-3" style="background-color:#111827; border:1px solid #00eaff40; box-shadow:0 0 10px #00eaff20;">
                        <a href="produto/detalhes/<?= $produto->id ?>" style="text-decoration:none;">
                            <?php if($temImagem): ?>
                                <img src="<?= $caminhoWeb ?>" 
                                     class="card-img-top mb-2" 
                                     style="max-height:150px; object-fit:contain; border-radius:6px;">
                            <?php else: ?>
                                <div class="text-muted text-center mb-2" style="height:150px; display:flex; align-items:center; justify-content:center; border:1px dashed #00eaff;">
                                    Sem imagem
                                </div>
                            <?php endif; ?>
                        </a>

                        <div class="card-body text-center text-light">
                            <a href="produto/detalhes/<?= $produto->id ?>" style="text-decoration:none;">
                                <h5 class="card-title"><?= htmlspecialchars($produto->nome) ?></h5>
                            </a>

                            <!-- Categoria -->
                            <?php if (!empty($produto->categoria)): ?>
                                <p class="card-text text-muted mb-1" style="font-size:0.85rem;">
                                    Categoria: <span style="color:#00eaff;"><?= htmlspecialchars($produto->categoria) ?></span>
                                </p>
                            <?php endif; ?>

                            <p class="card-text text-light" style="font-size:0.9rem; min-height:50px;">
                                <?= $produto->descricao ?? "Sem descrição" ?>
                            </p>

                            <p class="card-text" style="color:#00eaff; font-weight:bold;">
                                R$ <?= number_format($produto->valor, 2, ',', '.') ?>
                            </p>

                            <a href="produto/detalhes/<?= $produto->id ?>" class="btn btn-outline-neon w-100 mb-2">
                                Detalhes
                            </a>

                           <a href="carrinho/adicionar/<?= $produto->id ?>" class="btn btn-neon w-100">
    Comprar
</a>


                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted">
                Nenhum produto encontrado.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Script para filtrar por categoria -->
<script>
document.getElementById('filtro-categoria').addEventListener('change', function() {
    const categoriaSelecionada = this.value.toLowerCase();
    document.querySelectorAll('.produto-item').forEach(item => {
        const categoriasProduto = item.dataset.categoria.toLowerCase().split(',').map(c => c.trim());
        if(categoriaSelecionada === 'todas' || categoriasProduto.includes(categoriaSelecionada)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>

<style>
.btn-neon {
    background-color: #00eaff;
    border: none;
    color: #0b0f19;
    font-weight: bold;
    transition: 0.3s;
}
.btn-neon:hover {
    background-color: #00b8cc;
    box-shadow: 0 0 20px #00eaff;
    color: #fff;
}

.btn-outline-neon {
    border: 1px solid #00eaff;
    color: #00eaff;
    font-weight: bold;
    transition: 0.3s;
}
.btn-outline-neon:hover {
    background-color: #00eaff;
    color: #0b0f19;
    box-shadow: 0 0 20px #00eaff;
}

.card {
    border-radius: 12px;
}

.card img {
    border-radius: 6px;
}

body {
    background-color: #0b0f19;
    font-family: 'Poppins', sans-serif;
}

.form-select {
    border-radius: 8px;
    padding: 0.375rem 0.75rem;
    text-align: center;
}
</style>
