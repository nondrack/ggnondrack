<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0b0f19; box-shadow: 0 0 10px rgba(var(--neon-rgb,0,234,255),0.25);">
  <div class="container-fluid">

    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="index">
  <img src="images/logo.png" alt="DualCore Tech" style="height:70px; width:auto; filter: drop-shadow(0 0 5px var(--color-neon)); margin-right: 10px;">
      <span class="fw-bold text-purple-neon d-none d-sm-inline">DualCore Tech</span>
    </a>

    <!-- Botão hamburguer (mobile) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
      aria-controls="navbarResponsive" aria-expanded="false" aria-label="Alternar navegação">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Itens da navbar -->
    <div class="collapse navbar-collapse justify-content-center" id="navbarResponsive">
      <ul class="navbar-nav mb-2 mb-lg-0 d-flex align-items-center">
        <li class="nav-item mx-2">
          <a class="nav-link neon-link" href="index">Home</a>
        </li>
        <li class="nav-item mx-2">
          <a class="nav-link neon-link" href="categoria/listar">Categoria</a>
        </li>
        <li class="nav-item mx-2">
          <a class="nav-link neon-link" href="produto/listar">Produto</a>
        </li>

        <!-- Carrinho -->
        <li class="nav-item mx-2">
          <a class="nav-link neon-link position-relative" href="carrinho">
            <i class="fas fa-shopping-cart"></i> Carrinho
            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              0
            </span>
          </a>
        </li>
      </ul>

      <!-- Botão de Login/Logout (à direita em telas grandes) -->
      <div class="d-flex align-items-center ms-lg-3 mt-3 mt-lg-0 gap-2">
        <?php if (isset($_SESSION["user"])): ?>
          <!-- Usuário logado -->
          <span class="text-light d-none d-sm-inline me-2">
            <i class="fas fa-user-circle me-1"></i>
            <?= htmlspecialchars($_SESSION["user"]["nome"] ?? "Usuário") ?>
          </span>
          <a href="sair.php" class="btn btn-danger btn-sm">
            <i class="fas fa-sign-out-alt me-1"></i> Sair
          </a>
        <?php else: ?>
          <!-- Usuário não logado -->
          <a href="login.php" class="btn btn-success btn-sm">
            <i class="fas fa-sign-in-alt me-1"></i> Logar
          </a>
          <a href="index.php?param=usuario/cadastro" class="btn btn-outline-info btn-sm">
            <i class="fas fa-user-plus me-1"></i> Cadastro
          </a>
          <!-- Theme toggle -->
          <button id="theme-toggle" title="Alternar tema" class="btn btn-sm" style="background:transparent; border:1px solid var(--color-border); color:var(--color-neon);">
            <i id="theme-icon" class="fas fa-adjust"></i>
          </button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>




<style>
/* Neon links */
.neon-link {
  color: #b000ff !important;
  transition: all 0.3s ease;
}

.neon-link:hover {
  color: #fff !important;
  text-shadow: 0 0 10px #b000ff, 0 0 20px #b000ff, 0 0 40px #b000ff;
}

/* Ajuste do layout */
.navbar-nav .nav-item .nav-link {
  white-space: nowrap;
}

/* Efeito neon geral da navbar */
.navbar {
  border-bottom: 1px solid var(--color-border);
  background-color: #0b0f19 !important;
}

/* Navbar collapse (mobile) */
.navbar-collapse {
  background-color: #0b0f19 !important;
}

/* Centralizar conteúdo em telas grandes */
@media (min-width: 992px) {
  .navbar-nav {
    align-items: center;
  }
}

/* Ajustar padding em telas pequenas */
@media (max-width: 991px) {
  .navbar-brand img {
    height: 60px;
  }
  .navbar-nav .nav-item {
    text-align: center;
    margin: 0.5rem 0;
  }
}

/* Badge do carrinho */
#cart-count {
  background-color: #ff6b6b !important;
  color: #fff !important;
  font-weight: 700;
}

/* Botões da navbar */
.navbar .btn {
  transition: all 0.3s ease;
}

.navbar .btn-success {
  background-color: var(--color-neon) !important;
  border-color: var(--color-neon) !important;
  color: #000 !important;
  font-weight: 600;
}

.navbar .btn-success:hover {
  background-color: rgba(var(--neon-rgb), 0.85) !important;
  border-color: rgba(var(--neon-rgb), 0.85) !important;
  color: #000 !important;
  box-shadow: 0 0 15px rgba(var(--neon-rgb), 0.5);
}

/* Brand text */
.navbar-brand span.text-purple-neon {
  font-size: 1.2rem;
  font-weight: 700;
}
</style>

<script>
  // Atualizar contador do carrinho
  function atualizarContadorCarrinho() {
    fetch('index.php?action=get-cart-count', {
      method: 'GET',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      const cartCount = document.getElementById('cart-count');
      if (cartCount) {
        const quantidade = data.quantidade || 0;
        cartCount.textContent = quantidade;
        cartCount.style.display = quantidade > 0 ? 'block' : 'none';
      }
    })
    .catch(error => console.log('Erro ao atualizar carrinho:', error));
  }

  // Inicializar contador ao carregar a página
  document.addEventListener('DOMContentLoaded', atualizarContadorCarrinho);

  // Theme toggle: alterna entre neon azul (padrão) e neon roxo
  (function(){
    const btn = document.getElementById('theme-toggle');
    const icon = document.getElementById('theme-icon');
    if(!btn) return;

    function applyTheme(theme){
      if(theme === 'purple'){
        document.body.classList.add('theme-purple');
        icon.className = 'fas fa-moon';
        localStorage.setItem('siteTheme','purple');
      } else {
        document.body.classList.remove('theme-purple');
        icon.className = 'fas fa-sun';
        localStorage.setItem('siteTheme','blue');
      }
    }

    // Inicializa a partir do localStorage
    const saved = localStorage.getItem('siteTheme') || 'blue';
    applyTheme(saved);

    btn.addEventListener('click', function(){
      const current = document.body.classList.contains('theme-purple') ? 'purple' : 'blue';
      applyTheme(current === 'purple' ? 'blue' : 'purple');
    });
  })();
</script>
