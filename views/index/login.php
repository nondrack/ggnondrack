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

<style>
body {
    background-color: #0b0f19;
    color: #fff;
    font-family: 'Poppins', sans-serif;
}

/* Campos com texto e placeholder brancos */
.neon-input {
    background-color: #1a1f2e;
    border: 1px solid #00eaff60;
    color: #fff;
    transition: 0.3s;
}

.neon-input::placeholder {
    color: #ffffffb3;
    opacity: 1;
}

.neon-input:focus {
    border-color: #00eaff;
    box-shadow: 0 0 10px #00eaff80;
    background-color: #10141f;
    color: #fff;
}

/* Botão neon */
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

/* Botão "Cadastrar-se" com borda neon */
.btn-outline-neon {
    border: 1px solid #00eaff;
    color: #00eaff;
    font-weight: bold;
    transition: 0.3s;
    text-decoration: none;
    display: inline-block;
    padding: 10px;
    border-radius: 6px;
}

.btn-outline-neon:hover {
    background-color: #00eaff;
    color: #0b0f19;
    box-shadow: 0 0 20px #00eaff;
}

.card {
    border-radius: 12px;
}

.login {
    padding: 20px;
}
</style>

<script>
function mostrarSenha() {
    const input = document.getElementById("senha");
    input.type = input.type === "password" ? "text" : "password";
}
</script>
