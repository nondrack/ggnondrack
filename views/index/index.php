<?php
require_once __DIR__ . '/../../config/Conexao.php';
require_once __DIR__ . '/../../models/Produto.php';
require_once __DIR__ . '/../../models/Categoria.php';

// Conexão com o banco
$pdo = Conexao::conectar();
$produtoModel = new Produto($pdo);
// obter apenas produtos ativos para exibição pública
$produtos = $produtoModel->listarAtivos();

// Puxar todas as categorias do banco
$categoriaModel = new Categoria($pdo);
// listar apenas categorias ativas para exibição pública
$categorias = $categoriaModel->listar(true); // array de objetos com id e nome

// construir mapa de categorias ativas para filtrar produtos que pertençam a categorias inativas
$categoriaIdsAtivas = array_map(function($c){ return $c->id; }, $categorias);
?>

<!-- BANNER HERO COM EFEITO NEON -->
<div class="hero-banner">
  <div class="hero-content">
    <h1 class="hero-title">
      <span class="text-purple-neon">DualCore</span> Tech
    </h1>
    <p class="hero-subtitle">Sua loja de tecnologia de alta performance</p>
    <a href="#produtos-section" class="btn btn-hero">
      <i class="fas fa-arrow-down me-2"></i> Explorar Produtos
    </a>
  </div>
</div>

<!-- CAROUSEL COM ESTILO MELHORADO -->
<div class="carousel-container">
  <div id="carouselExampleIndicators" class="carousel slide carousel-dark" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="5" aria-label="Slide 6"></button>
    </div>

    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="images/banner1.png" class="d-block mx-auto carousel-img" alt="Banner 1">
      </div>
      <div class="carousel-item">
        <img src="images/cpuRyzen.png" class="d-block mx-auto carousel-img" alt="CPU Ryzen">
      </div>
      <div class="carousel-item">
        <img src="images/nintendoSwitch2.png" class="d-block mx-auto carousel-img" alt="Nintendo Switch">
      </div>
      <div class="carousel-item">
        <img src="images/notebookGamer.png" class="d-block mx-auto carousel-img" alt="Notebook Gamer">
      </div>
      <div class="carousel-item">
        <img src="images/placaMaeAsusRog.png" class="d-block mx-auto carousel-img" alt="Placa Mãe Asus ROG">
      </div>
      <div class="carousel-item">
        <img src="images/ps5.png" class="d-block mx-auto carousel-img" alt="PlayStation 5">
      </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
  </div>
</div>


<div class="container my-5" id="produtos-section">
    <div class="section-header mb-5">
        <h2 class="section-title">
            <i class="fas fa-star me-2" style="color: #ffd700;"></i>
            <span class="text-neon-glow">Produtos</span> Disponíveis
        </h2>
        <p class="section-subtitle">Encontre os melhores produtos de tecnologia</p>
    </div>

    <!-- FILTRO POR CATEGORIA -->
    <div class="filter-section mb-5">
        <div class="filter-wrapper">
            <label for="filtro-categoria" class="filter-label">
                <i class="fas fa-filter me-2"></i> Filtrar por Categoria:
            </label>
      <select id="filtro-categoria" class="form-select filtro-categoria">
        <option value="todas">
          <i class="fas fa-th"></i> Todas as categorias
        </option>
        <?php
          // Criar mapa id => nome para exibir nomes das categorias nos produtos
          $categoriaMap = [];
          foreach ($categorias as $cat) {
            $categoriaMap[$cat->id] = $cat->nome;
        ?>
          <option value="<?= htmlspecialchars($cat->id) ?>"><?= htmlspecialchars($cat->nome) ?></option>
        <?php } ?>
      </select>
        </div>
    </div>

    <!-- LISTAGEM DE PRODUTOS -->
    <div class="row g-4 justify-content-center" id="produtos-container">
    <?php if (!empty($produtos)): ?>
      <?php foreach ($produtos as $produto): ?>
        <?php
          // Se o produto pertence a uma categoria inativa, pular (na exibição pública)
          $categoriaIdDoProduto = $produto->categoria_id ?? null;
          if (!empty($categoriaIdDoProduto) && !in_array($categoriaIdDoProduto, $categoriaIdsAtivas)) {
            continue;
          }
        ?>
                <?php
                    $caminhoServidor = __DIR__ . '/../../_arquivos/' . $produto->imagem;
                    $caminhoWeb = '../_arquivos/' . $produto->imagem;
                    $temImagem = !empty($produto->imagem) && file_exists($caminhoServidor);
                ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 produto-item" data-categoria="<?= htmlspecialchars($produto->categoria_id) ?>">
                    <div class="card produto-card h-100">
                        <!-- IMAGEM DO PRODUTO -->
                        <div class="produto-img-wrapper">
                            <a href="produto/detalhes/<?= $produto->id ?>" class="text-decoration-none">
                                <?php if($temImagem): ?>
                                    <img src="<?= $caminhoWeb ?>" class="produto-img" alt="<?= htmlspecialchars($produto->nome) ?>">
                                <?php else: ?>
                                    <div class="produto-sem-imagem">
                                        <i class="fas fa-image"></i> Sem imagem
                                    </div>
                                <?php endif; ?>
                            </a>
                            <div class="produto-badge">Novo</div>
                        </div>

                        <!-- CORPO DO CARD -->
                        <div class="card-body d-flex flex-column flex-grow-1">
                            <div class="produto-info">
                                <h5 class="card-title produto-nome">
                                    <?= htmlspecialchars($produto->nome) ?>
                                </h5>

                <?php if (!empty($produto->categoria_id)): ?>
                  <p class="categoria-badge">
                    <i class="fas fa-tag"></i>
                    <?= htmlspecialchars($categoriaMap[$produto->categoria_id] ?? $produto->categoria_id) ?>
                  </p>
                <?php endif; ?>

                                <p class="card-text descricao-produto">
                                    <?= substr($produto->descricao ?? "Sem descrição", 0, 80) ?>...
                                </p>
                            </div>

                            <!-- PREÇO E AÇÕES -->
                            <div class="produto-footer">
                                <p class="preco-produto">
                                    R$ <?= number_format($produto->valor, 2, ',', '.') ?>
                                </p>
                                
                                <div class="btn-group-vertical w-100 gap-2">
                                    <a href="produto/detalhes/<?= $produto->id ?>" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye me-1"></i> Ver Detalhes
                                    </a>
                                    <a href="javascript:void(0);" onclick="adicionarAoCarrinho(<?= $produto->id ?>)" class="btn btn-info btn-sm btn-add-cart">
                                        <i class="fas fa-shopping-cart me-1"></i> Adicionar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Nenhum produto encontrado.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Script para filtrar por categoria -->
<script>
document.getElementById('filtro-categoria').addEventListener('change', function() {
  const categoriaSelecionada = this.value;
  const itens = document.querySelectorAll('.produto-item');
  let visivel = 0;

  itens.forEach(item => {
    const categoriasProduto = (item.dataset.categoria || '').toString().split(',').map(c => c.trim());
    const mostrar = categoriaSelecionada === 'todas' || categoriasProduto.includes(categoriaSelecionada);

    if (mostrar) {
      item.style.display = 'block';
      item.style.animation = 'fadeIn 0.3s ease';
      visivel++;
    } else {
      item.style.display = 'none';
    }
  });

  // Mostrar mensagem se nenhum produto encontrado
  if (visivel === 0) {
    console.log('Nenhum produto encontrado nesta categoria');
  }
});
</script>
