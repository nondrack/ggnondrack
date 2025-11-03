<!-- CSS & JS do Summernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>

<style>
    /* ======= ESTILO GERAL ======= */
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
    }

    .card-header {
        background: linear-gradient(90deg, #0d6efd, #1f6feb);
        color: #fff;
        border-bottom: 1px solid #1f6feb;
        border-radius: 10px 10px 0 0;
    }

    .card-header h2 {
        font-size: 1.3rem;
        margin: 0;
    }

    /* ======= FORMULÁRIO ======= */
    .form-label {
        color: #58a6ff;
        font-weight: 500;
    }

    .form-control,
    .form-select {
        background-color: #0d1117;
        border: 1px solid #30363d;
        color: #c9d1d9;
        transition: 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: #0d1117;
        color: #fff;
        border-color: #1f6feb;
        box-shadow: 0 0 0 3px rgba(31, 111, 235, 0.25);
    }

    ::placeholder {
        color: #8b949e;
    }

    /* ======= BOTÕES ======= */
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

    /* ======= SUMMERNOTE DARK ======= */
    .note-editor {
        background-color: #0d1117 !important;
        color: #c9d1d9 !important;
        border: 1px solid #30363d !important;
        border-radius: 6px;
    }

    .note-toolbar {
        background-color: #161b22 !important;
        border-bottom: 1px solid #30363d !important;
    }

    .note-editable {
        background-color: #0d1117 !important;
        color: #c9d1d9 !important;
    }

    /* ======= TITULOS ======= */
    h2, h4 {
        color: #58a6ff;
    }
</style>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h2><i class="fas fa-box-open me-2"></i>Cadastro de Produto</h2>
            <div>
                <a href="produto/index" class="btn btn-outline-neon btn-sm me-2">
                    <i class="fas fa-plus-circle"></i> Novo
                </a>
                <a href="produto/listar" class="btn btn-outline-neon btn-sm">
                    <i class="fas fa-list"></i> Listar
                </a>
            </div>
        </div>

        <div class="card-body">
            <form name="formCadastro" method="post" action="produto/salvar"
                  data-parsley-validate enctype="multipart/form-data" novalidate>

                <div class="row g-3">
                    <!-- ID -->
                    <div class="col-12 col-md-2">
                        <label for="id" class="form-label">ID</label>
                        <input type="text" name="id" id="id" class="form-control text-center" readonly>
                    </div>

                    <!-- Nome -->
                    <div class="col-12 col-md-7">
                        <label for="nome" class="form-label">Nome do Produto <span class="text-danger">*</span></label>
                        <input type="text" name="nome" id="nome" class="form-control"
                               placeholder="Digite o nome do produto" required
                               data-parsley-required-message="Preencha este campo">
                    </div>

                    <!-- Categoria -->
                    <div class="col-12 col-md-3">
                        <label for="categoria_id" class="form-label">Categoria <span class="text-danger">*</span></label>
                        <select name="categoria_id" id="categoria_id" class="form-select" required
                                data-parsley-required-message="Selecione uma categoria">
                            <option value="">Selecione</option>
                            <?php
                            $dadosCategoria = $this->categoria->listar();
                            foreach ($dadosCategoria as $dados) {
                                echo "<option value='{$dados->id}'>{$dados->nome}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Descrição -->
                    <div class="col-12">
                        <label for="descricao" class="form-label">Descrição do Produto <span class="text-danger">*</span></label>
                        <textarea name="descricao" id="descricao" class="form-control"
                                  rows="6" required
                                  placeholder="Descreva o produto em detalhes..."
                                  data-parsley-required-message="Preencha este campo"></textarea>
                    </div>

                    <!-- Imagem -->
                    <div class="col-12 col-md-6">
                        <label for="imagem" class="form-label">Imagem do Produto</label>
                        <input type="file" name="imagem" id="imagem" class="form-control" accept="image/*">
                    </div>

                    <!-- Valor -->
                    <div class="col-12 col-md-3">
                        <label for="valor" class="form-label">Valor (R$)</label>
                        <input type="text" name="valor" id="valor" class="form-control"
                               placeholder="0,00"
                               data-parsley-pattern="^\d+(,\d{1,2})?$"
                               data-parsley-pattern-message="Informe um valor válido">
                    </div>

                    <!-- Botões -->
                    <div class="col-12 text-end mt-4">
                        <button type="reset" class="btn btn-outline-neon me-2">
                            <i class="fas fa-undo-alt"></i> Limpar
                        </button>
                        <button type="submit" class="btn btn-neon">
                            <i class="fas fa-save"></i> Salvar Produto
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Inicialização do Summernote -->
<script>
    $(document).ready(function() {
        $('#descricao').summernote({
            placeholder: 'Digite a descrição do produto...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['fontsize', 'color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
