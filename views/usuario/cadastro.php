<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4" style="width:100%; max-width:450px; background-color:#111827; border:1px solid var(--color-border); box-shadow:0 0 15px rgba(var(--neon-rgb),0.12);">
        <div class="card-header text-center bg-transparent border-0">
            <img src="images/logo.png" alt="DualCore Tech" style="height: 60px; width: auto; filter: drop-shadow(0 0 5px var(--color-neon));" class="mb-3">
            <h2 style="color:var(--color-neon);">Criar Conta</h2>
            <p style="color:#999; font-size: 0.9rem;">Preencha os dados abaixo para se cadastrar</p>
        </div>
        <div class="card-body text-light">
            <form method="post" action="cadastro.php" data-parsley-validate>
                <label for="nome" class="form-label">
                    <i class="fas fa-user me-2"></i> Nome Completo:
                </label>
                <input type="text" name="nome" id="nome" class="form-control neon-input" required 
                    placeholder="Seu nome completo"
                    data-parsley-required-message="O nome é obrigatório"
                    data-parsley-pattern="^[a-zA-Zá-ú\s]+$"
                    data-parsley-pattern-message="O nome deve conter apenas letras">

                <br>
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-2"></i> E-mail:
                </label>
                <input type="email" name="email" id="email" class="form-control neon-input" required 
                    placeholder="seu@email.com"
                    data-parsley-required-message="O e-mail é obrigatório"
                    data-parsley-type-message="Digite um e-mail válido">

                <br>
                <label for="senha" class="form-label">
                    <i class="fas fa-lock me-2"></i> Senha:
                </label>
                <div class="input-group mb-3">
                    <input type="password" name="senha" id="senha" class="form-control neon-input" required 
                        placeholder="Mínimo 6 caracteres"
                        data-parsley-required-message="A senha é obrigatória"
                        data-parsley-minlength="6"
                        data-parsley-minlength-message="A senha deve ter pelo menos 6 caracteres">
                    <button type="button" class="btn btn-neon" onclick="mostrarSenha()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <br>
                <label for="confirmarSenha" class="form-label">
                    <i class="fas fa-lock me-2"></i> Confirmar Senha:
                </label>
                <div class="input-group mb-3">
                    <input type="password" name="confirmarSenha" id="confirmarSenha" class="form-control neon-input" required 
                        placeholder="Confirme sua senha"
                        data-parsley-required-message="Confirme a senha"
                        data-parsley-equalto="#senha"
                        data-parsley-equalto-message="As senhas não conferem">
                    <button type="button" class="btn btn-neon" onclick="confirmarSenha()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <br>
                <button type="submit" class="btn btn-neon w-100 mb-3">
                    <i class="fas fa-user-plus me-2"></i> Criar Conta
                </button>

                <a href="login.php" class="btn btn-outline-neon w-100 text-center mb-2">
                    <i class="fas fa-sign-in-alt me-2"></i> Já Tenho Conta
                </a>

                <a href="index.php" class="btn btn-outline-secondary w-100 text-center">
                    <i class="fas fa-arrow-left me-2"></i> Voltar à Home
                </a>
            </form>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #0b0f19;
        color: #fff;
        font-family: 'Poppins', sans-serif;
    }

    .neon-input {
        background-color: #1a1f2e;
        border: 1px solid var(--color-border);
        color: #fff;
        transition: 0.3s;
    }

    .neon-input::placeholder {
        color: #ffffffb3;
        opacity: 1;
    }

    .neon-input:focus {
        border-color: var(--color-neon);
        box-shadow: 0 0 10px rgba(var(--neon-rgb),0.5);
        background-color: #10141f;
        color: #fff;
    }

    .btn-neon {
        background-color: var(--color-neon);
        border: none;
        color: #0b0f19;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn-neon:hover {
        background-color: rgba(var(--neon-rgb),0.85);
        box-shadow: 0 0 20px var(--color-neon);
        color: #fff;
    }

    .btn-outline-neon {
        border: 1px solid var(--color-neon);
        color: var(--color-neon);
        font-weight: bold;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
        padding: 10px;
        border-radius: 6px;
    }

    .btn-outline-neon:hover {
        background-color: var(--color-neon);
        color: #0b0f19;
        box-shadow: 0 0 20px var(--color-neon);
    }

    .btn-outline-secondary {
        border: 1px solid #6c757d;
        color: #999;
        font-weight: bold;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
        padding: 10px;
        border-radius: 6px;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
    }

    .card {
        border-radius: 12px;
    }

    .form-label {
        color: #fff;
    }

    .input-group-text {
        background-color: transparent;
        border: none;
    }
</style>

<script>
    function mostrarSenha() {
        const input = document.getElementById("senha");
        input.type = input.type === "password" ? "text" : "password";
    }

    function confirmarSenha() {
        const input = document.getElementById("confirmarSenha");
        input.type = input.type === "password" ? "text" : "password";
    }
</script>
