<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4" style="width:100%; max-width:400px; background-color:#111827; border:1px solid #00eaff40; box-shadow:0 0 15px #00eaff20;">
        <div class="card-header text-center bg-transparent border-0">
            <h2 style="color:#00eaff;">Cadastrar Usuário</h2>
        </div>
        <div class="card-body text-light">
            <form method="post" action="../controllers/UsuarioController.php?action=salvar" data-parsley-validate>
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" class="form-control neon-input" required placeholder="Digite seu nome">

                <br>
                <label for="email">E-mail:</label>
                <input type="email" name="email" id="email" class="form-control neon-input" required placeholder="Digite seu e-mail">

                <br>
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" class="form-control neon-input" required placeholder="Digite sua senha">

                <br>
                <button type="submit" class="btn btn-neon w-100">Cadastrar</button>
            </form>

            <a href="?param=index/index" class="btn btn-outline-neon w-100 text-center mt-2">Voltar ao Login</a>
        </div>
    </div>
</div>
