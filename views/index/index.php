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
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= htmlspecialchars($cat->nome) ?>">
                        <?= htmlspecialchars($cat->nome) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- LISTAGEM DE PRODUTOS -->
    <div class="row g-4 justify-content-center" id="produtos-container">
        <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <?php
                    $caminhoServidor = __DIR__ . '/../../_arquivos/' . $produto->imagem;
                    $caminhoWeb = '../_arquivos/' . $produto->imagem;
                    $temImagem = !empty($produto->imagem) && file_exists($caminhoServidor);
                ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 produto-item" data-categoria="<?= htmlspecialchars($produto->categoria) ?>">
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

                                <?php if (!empty($produto->categoria)): ?>
                                    <p class="categoria-badge">
                                        <i class="fas fa-tag"></i>
                                        <?= htmlspecialchars($produto->categoria) ?>
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
  const categoriaSelecionada = this.value.toLowerCase();
  const itens = document.querySelectorAll('.produto-item');
  let visivel = 0;

  itens.forEach(item => {
    const categoriasProduto = item.dataset.categoria.toLowerCase().split(',').map(c => c.trim());
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

<style>
/* ====== ANIMAÇÕES ====== */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes glow {
  0%, 100% {
    text-shadow: 0 0 10px var(--color-neon), 0 0 20px var(--color-neon);
  }
  50% {
    text-shadow: 0 0 20px var(--color-neon), 0 0 40px var(--color-neon), 0 0 60px var(--color-neon);
  }
}

/* ====== HERO BANNER ====== */
.hero-banner {
  background: linear-gradient(135deg, #0b0f19 0%, #1a1f2e 50%, #0b0f19 100%);
  border-bottom: 2px solid var(--color-neon);
  padding: 60px 20px;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.hero-banner::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(circle at 30% 50%, var(--color-neon-06) 0%, transparent 50%);
  pointer-events: none;
}

.hero-content {
  position: relative;
  z-index: 1;
  animation: slideDown 0.8s ease;
}

.hero-title {
  font-size: 3rem;
  font-weight: 800;
  margin-bottom: 10px;
  color: #fff;
}

.text-neon-glow {
  color: var(--color-neon);
  animation: glow 2s ease-in-out infinite;
}

.hero-subtitle {
  font-size: 1.2rem;
  color: #ddd;
  margin-bottom: 30px;
}

.btn-hero {
  background: linear-gradient(135deg, var(--color-neon) 0%, rgba(var(--neon-rgb),0.7) 100%);
  border: none;
  color: #000;
  font-weight: 600;
  padding: 12px 30px;
  border-radius: 50px;
  transition: all 0.3s ease;
  display: inline-block;
}

.btn-hero:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 30px var(--color-neon-40);
  color: #000;
}

/* ====== CAROUSEL ====== */
.carousel-container {
  background: linear-gradient(to bottom, #0b0f19 0%, #111827 100%);
  padding: 40px 0;
  border-bottom: 1px solid var(--color-border-light);
}

#carouselExampleIndicators {
  background: rgba(17, 24, 39, 0.5);
  border-radius: 15px;
  padding: 20px;
  margin: 0 auto;
}

.carousel-img {
  max-height: 300px;
  object-fit: contain;
  filter: drop-shadow(0 0 10px var(--color-neon-25));
  transition: filter 0.3s ease;
}

#carouselExampleIndicators:hover .carousel-img {
  filter: drop-shadow(0 0 20px var(--color-neon-60));
}

.carousel-indicators [data-bs-target] {
  background-color: var(--color-neon-50);
  border-radius: 50%;
  transition: all 0.3s ease;
}

.carousel-indicators [data-bs-target].active {
  background-color: var(--color-neon);
  box-shadow: 0 0 10px var(--color-neon);
}

/* ====== SEÇÃO DE PRODUTOS ====== */
.section-header {
  text-align: center;
  animation: slideDown 0.6s ease;
}

.section-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #fff;
  margin-bottom: 10px;
}

.section-subtitle {
  font-size: 1.1rem;
  color: #999;
  margin: 0;
}

/* ====== FILTRO ====== */
.filter-section {
  display: flex;
  justify-content: center;
}

.filter-wrapper {
  display: flex;
  align-items: center;
  gap: 15px;
  flex-wrap: wrap;
  justify-content: center;
}

.filter-label {
  color: var(--color-neon);
  font-weight: 600;
  margin: 0;
  white-space: nowrap;
}

.filtro-categoria {
  background-color: #111827 !important;
  color: var(--color-neon) !important;
  border: 2px solid var(--color-neon) !important;
  border-radius: 8px;
  text-align: center;
  font-weight: 500;
  padding: 8px 15px;
  box-shadow: 0 0 10px var(--color-neon-20);
  transition: all 0.3s ease;
  min-width: 250px;
}

.filtro-categoria:hover {
  box-shadow: 0 0 20px var(--color-neon-50);
  border-color: var(--color-neon);
}

.filtro-categoria:focus {
  background-color: #111827 !important;
  color: #00eaff !important;
  border-color: #00eaff !important;
  box-shadow: 0 0 25px var(--color-neon-60);
}

.filtro-categoria option {
  background-color: #111827;
  color: #00eaff;
}

/* ====== CARDS DE PRODUTOS ====== */
.produto-card {
  background-color: #111827;
  border: 1px solid var(--color-border);
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  transition: all 0.3s ease;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.produto-card:hover {
  transform: translateY(-8px);
  border-color: var(--color-neon-60);
  box-shadow: 0 15px 35px var(--color-neon-20);
}

/* ====== IMAGEM DO PRODUTO ====== */
.produto-img-wrapper {
  position: relative;
  overflow: hidden;
  height: 200px;
  background: linear-gradient(135deg, #0b0f19 0%, #1a1f2e 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  border-bottom: 1px solid var(--color-border-light);
}

.produto-img-wrapper a {
  display: 100%;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.produto-img {
  max-height: 180px;
  max-width: 95%;
  object-fit: contain;
  transition: transform 0.4s ease, filter 0.3s ease;
  filter: brightness(0.95);
}

.produto-card:hover .produto-img {
  transform: scale(1.1);
  filter: brightness(1.1) drop-shadow(0 0 10px var(--color-neon-25));
}

.produto-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  background: linear-gradient(135deg, #00eaff 0%, #00b8cc 100%);
  color: #000;
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 700;
  box-shadow: 0 4px 10px rgba(0, 234, 255, 0.3);
}

.produto-sem-imagem {
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: #00eaff70;
  gap: 10px;
  font-weight: 500;
}

.produto-sem-imagem i {
  font-size: 2.5rem;
  opacity: 0.5;
}

/* ====== CORPO DO CARD ====== */
.produto-info {
  flex-grow: 1;
}

.produto-nome {
  color: #fff;
  font-weight: 700;
  font-size: 1rem;
  line-height: 1.3;
  margin-bottom: 8px;
}

.categoria-badge {
  display: inline-block;
  background: rgba(0, 234, 255, 0.1);
  color: #00eaff;
  padding: 3px 8px;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 600;
  margin-bottom: 8px;
  margin: 0 0 8px 0;
}

.descricao-produto {
  font-size: 0.85rem;
  min-height: 40px;
  color: #bbb;
  line-height: 1.4;
}

/* ====== RODAPÉ DO CARD ====== */
.produto-footer {
  border-top: 1px solid #00eaff20;
  padding-top: 12px;
  margin-top: 12px;
}

.preco-produto {
  color: #00eaff;
  font-weight: 700;
  margin-bottom: 12px;
  font-size: 1.25rem;
}

.btn-group-vertical {
  gap: 8px !important;
}

.btn-outline-info {
  border: 1.5px solid #00eaff;
  color: #00eaff;
  background-color: transparent;
  font-weight: 600;
  border-radius: 6px;
  transition: all 0.3s ease;
}

.btn-outline-info:hover {
  background-color: transparent;
  color: #fff;
  border-color: #fff;
  box-shadow: 0 0 15px rgba(0, 234, 255, 0.4);
}

.btn-info {
  background: linear-gradient(135deg, #00eaff 0%, #00b8cc 100%);
  border: none;
  color: #000;
  font-weight: 700;
  border-radius: 6px;
  transition: all 0.3s ease;
}

.btn-info:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0, 234, 255, 0.4);
  color: #000;
}

.btn-add-cart {
  font-size: 0.9rem;
}

/* ====== ESTADO VAZIO ====== */
.empty-state {
  text-align: center;
  padding: 60px 20px;
}

.empty-state i {
  font-size: 4rem;
  color: #00eaff30;
  margin-bottom: 20px;
}

.empty-state p {
  color: #999;
  font-size: 1.1rem;
}

/* ====== RESPONSIVIDADE ====== */
@media (max-width: 768px) {
  .hero-title {
    font-size: 2rem;
  }

  .hero-subtitle {
    font-size: 1rem;
  }

  .section-title {
    font-size: 2rem;
  }

  .filter-wrapper {
    flex-direction: column;
    align-items: stretch;
  }

  .filtro-categoria {
    min-width: 100%;
  }

  .produto-img-wrapper {
    height: 160px;
  }

  .produto-img {
    max-height: 150px;
  }

  .carousel-img {
    max-height: 250px;
  }
}

@media (max-width: 480px) {
  .hero-banner {
    padding: 40px 15px;
  }

  .hero-title {
    font-size: 1.5rem;
  }

  .section-title {
    font-size: 1.5rem;
  }

  .produto-nome {
    font-size: 0.9rem;
  }

  .descricao-produto {
    font-size: 0.8rem;
    min-height: 30px;
  }

  .preco-produto {
    font-size: 1.1rem;
  }
}
</style>
