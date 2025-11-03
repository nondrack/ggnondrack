<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2><i class="fas fa-tags me-2"></i><?= $dadosCategoria ? 'Editar Categoria' : 'Nova Categoria' ?></h2>
            <div>
                <a href="categoria/listar" class="btn btn-outline-neon btn-sm">
                    <i class="fas fa-list"></i> Listar
                </a>
            </div>
        </div>

        <div class="card-body">
            <form method="post" action="categoria/salvar" data-parsley-validate>
                <input type="hidden" name="id" value="<?= $dadosCategoria->id ?? '' ?>">

                <!-- Nome -->
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome da Categoria <span class="text-danger">*</span></label>
                    <input type="text" name="nome" id="nome" class="form-control"
                           value="<?= $dadosCategoria->nome ?? '' ?>"
                           placeholder="Digite o nome da categoria"
                           required data-parsley-required-message="Preencha este campo">
                </div>

                <!-- Ativo -->
                <div class="mb-3">
                    <label for="ativo" class="form-label">Ativo <span class="text-danger">*</span></label>
                    <select name="ativo" id="ativo" class="form-select" required data-parsley-required-message="Selecione uma opção">
                        <option value="">Selecione</option>
                        <option value="S" <?= ($dadosCategoria->ativo ?? '') === 'S' ? 'selected' : '' ?>>Sim</option>
                        <option value="N" <?= ($dadosCategoria->ativo ?? '') === 'N' ? 'selected' : '' ?>>Não</option>
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-neon">
                        <i class="fas fa-save"></i> <?= $dadosCategoria ? 'Atualizar' : 'Salvar' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* === Dark Neon Style === */
body {
    background-color: #0d1117;
    color: #c9d1d9;
    font-family: "Poppins", sans-serif;
}

.card {
    background-color: #161b22;
    border: 1px solid #1f6feb;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(31, 111, 235, 0.1);
    margin-top: 30px;
}

.card-header {
    background: linear-gradient(90deg, #0d6efd, #1f6feb);
    color: #fff;
    border-bottom: 1px solid #1f6feb;
    border-radius: 10px 10px 0 0;
    padding: 15px 20px;
}

.card-header h2 {
    font-size: 1.3rem;
    margin: 0;
    color: #fff;
}

.form-label, label {
    color: #58a6ff;
    font-weight: 500;
}

.form-control, .form-select {
    background-color: #0d1117;
    border: 1px solid #30363d;
    color: #c9d1d9;
    transition: 0.3s;
}

.form-control:focus, .form-select:focus {
    background-color: #0d1117;
    color: #fff;
    border-color: #1f6feb;
    box-shadow: 0 0 0 3px rgba(31, 111, 235, 0.25);
}

::placeholder {
    color: #8b949e;
}

.btn-neon {
    background-color: #1f6feb;
    border: none;
    color: #fff;
    font-weight: 500;
    transition: all 0.3s ease;
    border-radius: 5px;
}

.btn-neon:hover {
    background-color: #388bfd;
    box-shadow: 0 0 12px rgba(31, 111, 235, 0.6);
    color: #fff;
}

.btn-outline-neon {
    background-color: transparent;
    border: 1px solid #1f6feb;
    color: #58a6ff;
    transition: all 0.3s ease;
}

.btn-outline-neon:hover {
    background-color: #1f6feb;
    color: #fff;
    box-shadow: 0 0 10px rgba(31, 111, 235, 0.6);
}

.text-end {
    text-align: end;
}
</style>
