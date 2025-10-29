<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0b0f19; box-shadow: 0 0 10px #00ffff40;">
    <div class="container-fluid d-flex justify-content-center">
        <div class="d-flex align-items-center">
            <!-- Logo -->
            <a class="navbar-brand me-4" href="index">
                <img src="images/logo.png" alt="DualCore Tech" style="height:50px; width:auto; filter: drop-shadow(0 0 5px #00eaff);">
            </a>

            <!-- Links -->
            <ul class="navbar-nav me-4 mb-2 mb-lg-0 d-flex flex-row">
                <li class="nav-item mx-2">
                    <a class="nav-link neon-link" href="index">Home</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link neon-link" href="categoria/listar">Categoria</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link neon-link" href="produto/listar">Produto</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link neon-link" href="usuario">Usuários</a>
                </li>
                <!-- Botão Carrinho -->
                <li class="nav-item mx-2">
                    <a class="nav-link neon-link position-relative" href="carrinho/listar">
                        <i class="fas fa-shopping-cart"></i> Carrinho
                        <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            0
                        </span>
                    </a>
                </li>
            </ul>

            <!-- Usuário -->
            <div class="d-flex align-items-center">
                <span class="text-light me-3">
                    Olá <?=$_SESSION["user"]["nome"]?></span>
                <a href="sair.php" class="btn btn-danger btn-sm">
                    <i class="fas fa-power-off"></i> Sair
                </a>
            </div>
        </div>
    </div>
</nav>



<style>
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
</style>
