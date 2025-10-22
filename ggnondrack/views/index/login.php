<form name="formLogin" method="post" data-parsley-validate>
    <div class="login d-flex justify-content-center align-items-center vh-100" style="background-color: #0b0f19;">
        <div class="card p-4" style="width: 100%; max-width: 400px; background-color: #111827; border: 1px solid #00eaff40; box-shadow: 0 0 15px #00eaff20;">
            <div class="card-header text-center bg-transparent border-0">
                <img src="images/logo.png" alt="Painel de Controle" class="w-50 mb-3" style="filter: drop-shadow(0 0 5px #00eaff);">
            </div>
            <div class="card-body text-light">
                <label for="email" class="form-label">Digite o e-mail:</label>
                <input type="email" name="email" id="email"
                    class="form-control neon-input" required
                    placeholder="Digite o e-mail"
                    data-parsley-required-message="Preencha este campo"
                    data-parsley-type-message="Digite um e-mail válido">

                <br>
                <label for="senha" class="form-label">Digite sua senha:</label>
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
                <button type="submit" class="btn btn-neon w-100">
                    Efetuar Login
                </button>
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
    color: #fff; /* texto digitado branco */
    transition: 0.3s;
}

/* cor do placeholder */
.neon-input::placeholder {
    color: #ffffffb3; /* branco suave */
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

.card {
    border-radius: 12px;
}

.login {
    padding: 20px;
}
</style>


