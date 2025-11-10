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

<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="5" aria-label="Slide 6"></button>
  </div>

  <div class="carousel-inner">
    <div class="carousel-item active text-center">
      <img src="images/banner1.png" class="d-block mx-auto w-50" alt="Banner 1">
    </div>
    <div class="carousel-item text-center">
      <img src="images/cpuRyzen.png" class="d-block mx-auto w-50" alt="CPU Ryzen">
    </div>
    <div class="carousel-item text-center">
      <img src="images/nintendoSwitch2.png" class="d-block mx-auto w-50" alt="Nintendo Switch">
    </div>
    <div class="carousel-item text-center">
      <img src="images/notebookGamer.png" class="d-block mx-auto w-50" alt="Notebook Gamer">
    </div>
    <div class="carousel-item text-center">
      <img src="images/placaMaeAsusRog.png" class="d-block mx-auto w-50" alt="Placa Mãe Asus ROG">
    </div>
    <div class="carousel-item text-center">
      <img src="images/ps5.png" class="d-block mx-auto w-50" alt="PlayStation 5">
    </div>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Anterior</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Próximo</span>
  </button>
</div>


<div class="container my-5">
    <h2 class="text-center mb-4" style="color:#00eaff; text-shadow:0 0 5px #00eaff;">Produtos Disponíveis</h2>


    <!-- Filtro por categoria -->
<div class="text-center mb-4 mt-4">
  <select id="filtro-categoria" 
          class="form-select w-auto mx-auto filtro-categoria">
      <option value="todas">Todas as categorias</option>
      <?php foreach ($categorias as $cat): ?>
          <option value="<?= htmlspecialchars($cat->nome) ?>"><?= htmlspecialchars($cat->nome) ?></option>
      <?php endforeach; ?>
  </select>
</div>

<!-- Listagem de produtos -->
<div class="row g-4 justify-content-center" id="produtos-container">
  <?php if (!empty($produtos)): ?>
      <?php foreach ($produtos as $produto): ?>
          <?php
              $caminhoServidor = __DIR__ . '/../../_arquivos/' . $produto->imagem;
              $caminhoWeb = '../_arquivos/' . $produto->imagem;
              $temImagem = !empty($produto->imagem) && file_exists($caminhoServidor);
          ?>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 produto-item" data-categoria="<?= htmlspecialchars($produto->categoria) ?>">
              <div class="card produto-card h-100 text-center">
                  <a href="produto/detalhes/<?= $produto->id ?>" class="text-decoration-none text-light">
                      <?php if($temImagem): ?>
                          <img src="<?= $caminhoWeb ?>" class="card-img-top produto-img" alt="<?= htmlspecialchars($produto->nome) ?>">
                      <?php else: ?>
                          <div class="produto-sem-imagem">Sem imagem</div>
                      <?php endif; ?>
                  </a>

                  <div class="card-body d-flex flex-column justify-content-between">
                      <div>
                          <h5 class="card-title mb-2"><?= htmlspecialchars($produto->nome) ?></h5>
                          <?php if (!empty($produto->categoria)): ?>
                              <p class="card-text text-muted mb-1" style="font-size:0.85rem;">
                                  Categoria: <span class="text-neon"><?= htmlspecialchars($produto->categoria) ?></span>
                              </p>
                          <?php endif; ?>
                          <p class="card-text descricao-produto">
                              <?= $produto->descricao ?? "Sem descrição" ?>
                          </p>
                      </div>
                      <div>
                          <p class="card-text preco-produto">R$ <?= number_format($produto->valor, 2, ',', '.') ?></p>
                          <a href="produto/detalhes/<?= $produto->id ?>" class="btn btn-outline-neon w-100 mb-2">Detalhes</a>
                          <a href="index.php?param=carrinho/adicionar/<?= $produto->id ?>" 
   class="btn btn-primary">Adicionar ao Carrinho</a>





                      </div>
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

<!-- Script para filtrar por categoria -->
<script>
document.getElementById('filtro-categoria').addEventListener('change', function() {
  const categoriaSelecionada = this.value.toLowerCase();
  document.querySelectorAll('.produto-item').forEach(item => {
    const categoriasProduto = item.dataset.categoria.toLowerCase().split(',').map(c => c.trim());
    item.style.display = categoriaSelecionada === 'todas' || categoriasProduto.includes(categoriaSelecionada)
      ? 'block' : 'none';
  });
});
</script>


<style>

/* ---------- Estrutura geral ---------- */
body {
  background-color: #0b0f19;
  font-family: 'Poppins', sans-serif;
  color: #fff;
}

/* ---------- Filtro ---------- */
.filtro-categoria {
  background-color: #111827;
  color: #00eaff;
  border: 1px solid #00eaff;
  border-radius: 8px;
  text-align: center;
  font-weight: 500;
  box-shadow: 0 0 10px #00eaff20;
  transition: 0.3s;
}
.filtro-categoria:hover {
  box-shadow: 0 0 15px #00eaff60;
}

/* ---------- Cards ---------- */
.produto-card {
  background-color: #111827;
  border: 1px solid #00eaff30;
  border-radius: 12px;
  box-shadow: 0 0 10px #00eaff20;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  overflow: hidden;
}

.produto-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 0 20px #00eaff50;
}

/* ---------- Imagens ---------- */
.produto-img {
  max-height: 180px;
  object-fit: contain;
  background-color: #0b0f19;
  border-radius: 8px;
  padding: 10px;
  transition: transform 0.4s ease;
}

.produto-card:hover .produto-img {
  transform: scale(1.05);
}

/* ---------- Sem imagem ---------- */
.produto-sem-imagem {
  height: 180px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #00eaff70;
  border: 1px dashed #00eaff50;
  border-radius: 8px;
}

/* ---------- Texto ---------- */
.card-title {
  color: #fff;
  font-weight: 600;
}

.text-neon {
  color: #00eaff;
}

.descricao-produto {
  font-size: 0.9rem;
  min-height: 50px;
  color: #ddd;
}

.preco-produto {
  color: #00eaff;
  font-weight: bold;
  margin-bottom: 0.5rem;
  font-size: 1.1rem;
}

/* ---------- Botões ---------- */
.btn-neon {
  background-color: #00eaff;
  border: none;
  color: #0b0f19;
  font-weight: bold;
  transition: 0.3s;
  border-radius: 6px;
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
  border-radius: 6px;
}

.btn-outline-neon:hover {
  background-color: #00eaff;
  color: #0b0f19;
  box-shadow: 0 0 20px #00eaff;
}

/* ---------- Responsividade ---------- */
@media (max-width: 768px) {
  .produto-img {
    max-height: 140px;
  }
  .descricao-produto {
    font-size: 0.85rem;
  }
  .preco-produto {
    font-size: 1rem;
  }
}

</style>
