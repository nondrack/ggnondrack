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
                        <input type="text" name="id" id="id" class="form-control text-center" readonly value="<?= isset($produto) ? htmlspecialchars($produto->id) : '' ?>">
                    </div>

                    <!-- Nome -->
                    <div class="col-12 col-md-7">
                        <label for="nome" class="form-label">Nome do Produto <span class="text-danger">*</span></label>
               <input type="text" name="nome" id="nome" class="form-control"
                   placeholder="Digite o nome do produto" required
                   value="<?= isset($produto) ? htmlspecialchars($produto->nome) : '' ?>"
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
                            $categoriaAtual = isset($produto) ? $produto->categoria_id : null;
                            foreach ($dadosCategoria as $dados) {
                                $sel = ($categoriaAtual == $dados->id) ? 'selected' : '';
                                echo "<option value='{$dados->id}' {$sel}>{$dados->nome}</option>";
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
                                  data-parsley-required-message="Preencha este campo"><?= isset($produto) ? htmlspecialchars($produto->descricao) : '' ?></textarea>
                    </div>

                    <!-- Imagem (Upload ou URL) -->
                    <div class="col-12 col-md-6">
                        <label class="form-label">Imagem do Produto</label>
                        <div class="nav nav-pills mb-2" id="imgTab" role="tablist">
                            <button class="nav-link active" id="tab-upload" data-mode="upload" type="button">Upload</button>
                            <button class="nav-link" id="tab-url" data-mode="url" type="button">URL</button>
                        </div>
                        <div id="area-upload">
                            <input type="file" name="imagem" id="imagem" class="form-control" accept="image/*">
                            <input type="hidden" name="imagem_atual" value="<?= isset($produto) ? htmlspecialchars($produto->imagem) : '' ?>">
                            <?php if(isset($produto) && !empty($produto->imagem)): ?>
                                <?php $isUrl = preg_match('/^https?:\/\//i', $produto->imagem); $srcPreview = $isUrl ? $produto->imagem : ('../_arquivos/' . $produto->imagem); ?>
                                <div class="mt-2">
                                    <img src="<?= htmlspecialchars($srcPreview) ?>" alt="Imagem atual" style="max-height:120px;object-fit:contain;border:1px solid #444;padding:4px;border-radius:4px;" />
                                    <small class="text-muted d-block">Imagem atual. Envie outra ou informe URL para substituir.</small>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div id="area-url" style="display:none;">
                            <input type="text" name="imagem_url" id="imagem_url" class="form-control" placeholder="https://exemplo.com/imagem.jpg">
                            <small class="text-muted">Cole uma URL iniciando com http ou https apontando para uma imagem.</small>
                        </div>
                    </div>

                    <!-- Preço -->
                    <div class="col-12 col-md-3">
                        <label for="preco" class="form-label">Preço (R$)</label>
                        <?php
                            $precoForm = '';
                            if (isset($produto)) {
                                $valorBr = number_format((float)($produto->preco ?? $produto->valor ?? 0), 2, ',', '.');
                                $precoForm = $valorBr;
                            }
                        ?>
                        <input type="text" name="preco" id="preco" class="form-control"
                               placeholder="0,00"
                               value="<?= $precoForm ?>"
                               data-parsley-pattern="^\d{1,3}(\.\d{3})*(,\d{2})?$|^\d+(,\d{2})?$"
                               data-parsley-pattern-message="Informe um preço válido (ex: 199,90 ou 1.234,56)">
                    </div>

                    <!-- Estoque -->
                    <div class="col-12 col-md-2">
                        <label for="estoque" class="form-label">Estoque</label>
                        <input type="number" name="estoque" id="estoque" class="form-control"
                               placeholder="0" min="0" step="1"
                               value="<?= isset($produto) ? (int)$produto->estoque : 0 ?>"
                               data-parsley-type="integer"
                               data-parsley-type-message="Informe um número inteiro">
                    </div>

                    <!-- Status -->
                    <div class="col-12 col-md-2">
                        <label for="ativo" class="form-label">Status</label>
                        <select name="ativo" id="ativo" class="form-select">
                            <?php $ativoAtual = isset($produto) ? strtoupper($produto->ativo) : 'S'; ?>
                            <option value="S" <?= $ativoAtual === 'S' ? 'selected' : '' ?>>Ativo</option>
                            <option value="N" <?= $ativoAtual === 'N' ? 'selected' : '' ?>>Inativo</option>
                        </select>
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

    // Máscara para campo preço
        if ($.fn.inputmask) {
            $('#preco').inputmask({ alias: 'numeric', groupSeparator: '.', radixPoint: ',', digits: 2, autoGroup: true, rightAlign: false, allowMinus: false });
        }

        // Alternância Upload/URL
        $('#imgTab button').on('click', function(){
            $('#imgTab button').removeClass('active');
            $(this).addClass('active');
            const mode = $(this).data('mode');
            if (mode === 'upload') {
                $('#area-upload').show();
                $('#area-url').hide();
                $('#imagem_url').val('');
            } else {
                $('#area-upload').hide();
                $('#area-url').show();
                $('#imagem').val('');
            }
        });

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

        // Preview para URL
        $('#imagem_url').on('input', function(){
            const url = $(this).val().trim();
            $('#imagem-preview').remove();
            if (/^https?:\/\/.+\.(jpg|jpeg|png|gif|webp|svg)$/i.test(url)) {
                const img = $('<img id="imagem-preview" class="img-preview" />').attr('src', url);
                $(this).after(img);
            }
        });
    });
</script>
