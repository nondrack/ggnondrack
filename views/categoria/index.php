<div class="container categoria-form">
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
<!-- categoria form styles moved to public/css/components/views-inline.css (scoped) -->
