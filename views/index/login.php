<form name="formLogin" method="post" action="../../../public/login.php" data-parsley-validate>
    <div class="login d-flex justify-content-center align-items-center vh-100" style="background-color: #0b0f19;">
        <div class="card p-4" style="width: 100%; max-width: 400px; background-color: #111827; border: 1px solid #00eaff40; box-shadow: 0 0 15px #00eaff20;">
            <div class="card-header text-center bg-transparent border-0">
                <img src="images/logo.png" alt="Painel de Controle" class="w-50 mb-3" style="filter: drop-shadow(0 0 5px var(--color-neon));">
                <h3 class="text-light">Fazer Login</h3>
            </div>
            <div class="card-body text-light">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-2"></i> E-mail:
                </label>
                <input type="email" name="email" id="email"
                    class="form-control neon-input" required
                    placeholder="seu@email.com"
                    data-parsley-required-message="Preencha este campo"
                    data-parsley-type-message="Digite um e-mail válido">

                <br>
                <label for="senha" class="form-label">
                    <i class="fas fa-lock me-2"></i> Senha:
                </label>
                <div class="input-group mb-3">
                    <input type="password" name="senha" id="senha"
                        class="form-control neon-input" required
                        placeholder="Digite sua senha"
                        data-parsley-required-message="Preencha este campo"
                        data-parsley-errors-container="#error">
                    <button type="button" class="btn btn-neon" onclick="mostrarSenha()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div id="error"></div>
                <br>

                <!-- Botão de login -->
                <button type="submit" class="btn btn-neon w-100 mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Efetuar Login
                </button>

                <!-- Botão de cadastro -->
                <div class="d-grid gap-2 mb-3">
                    <a href="index.php?param=usuario/cadastro" class="btn btn-outline-neon text-center">
                        <i class="fas fa-user-plus me-2"></i> Criar Conta
                    </a>
                </div>

                <!-- Botão de voltar -->
                <div class="d-grid">
                    <a href="index.php" class="btn btn-outline-secondary text-center">
                        <i class="fas fa-arrow-left me-2"></i> Voltar à Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    </form>

<!-- styles moved to public/css/components/views-inline.css -->

<script>
function mostrarSenha() {
    const input = document.getElementById("senha");
    input.type = input.type === "password" ? "text" : "password";
}
</script>
