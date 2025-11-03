<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0b0f19; box-shadow: 0 0 10px #00ffff40;">
  <div class="container-fluid">

    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="index">
      <img src="images/logo.png" alt="DualCore Tech" style="height:70px; width:auto; filter: drop-shadow(0 0 5px #00eaff); margin-right: 10px;">
      <span class="fw-bold text-light d-none d-sm-inline">DualCore Tech</span>
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
          <a class="nav-link neon-link position-relative" href="carrinho/listar">
            <i class="fas fa-shopping-cart"></i> Carrinho
            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              0
            </span>
          </a>
        </li>
      </ul>

      <!-- Botão de Login (à direita em telas grandes) -->
      <div class="d-flex align-items-center ms-lg-3 mt-3 mt-lg-0">
        <a href="./index/login" class="btn btn-success btn-sm">
          <i class="fas fa-user"></i> Logar
        </a>
      </div>
    </div>
  </div>
</nav>




<style>
/* Neon links */
/* Neon links */
.neon-link {
  color: #00eaff !important;
  transition: all 0.3s ease;
}

.neon-link:hover {
  color: #fff !important;
  text-shadow: 0 0 10px #00eaff, 0 0 20px #00eaff, 0 0 40px #00eaff;
}

/* Ajuste do layout */
.navbar-nav .nav-item .nav-link {
  white-space: nowrap;
}

/* Efeito neon geral da navbar */
.navbar {
  border-bottom: 1px solid #00eaff30;
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

</style>
