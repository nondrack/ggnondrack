<!-- CSS & JS do Summernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>

<!-- product form styles moved to public/css/components/views-inline.css -->

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

<!-- Inicialização e melhorias JS -->
<script>
    $(document).ready(function() {
        // Summernote dark
        $('#descricao').summernote({
            placeholder: 'Digite a descrição do produto...',
            tabsize: 2,
            height: 220,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['fontsize', 'color', 'bold', 'italic']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    // permitir upload via backend se implementar
                }
            }
        });

        // Mascara para campo valor (usar máscara brasileira)
        if ($.fn.inputmask) {
            $('#valor').inputmask({ alias: 'numeric', groupSeparator: '.', radixPoint: ',', digits: 2, autoGroup: true, rightAlign: false, allowMinus: false });
        }

        // Preview de imagem selecionada
        $('#imagem').on('change', function(e) {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(ev) {
                // remover preview anterior
                $('#imagem-preview').remove();
                const img = $('<img id="imagem-preview" class="img-preview" />').attr('src', ev.target.result);
                $('#imagem').after(img);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
