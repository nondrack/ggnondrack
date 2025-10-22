

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                <h2>Cadastro de Produto</h2>
            </div>
            <div class="float-end">
                <a href="produto/index" class="btn btn-info">
                    Novo Registro
                </a>
                <a href="produto/listar" class="btn btn-info">
                    Listar Registros
                </a>
            </div>
        </div>
        <div class="card-body">
            <form name="formCadastro" method="post" action="produto/salvar"
            data-parsley-validade enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12 col-md-2">
                        <label for="id">ID:</label>
                        <input type="text" name="id" id="id"
                        class="form-control" readonly>
                    </div>
                    <div class="col-12 col-md-7">
                        <label for="nome">Nome do Produto:</label>
                        <input type="text" name="nome" id="nome"
                        class="form-control" required
                        data-parsley-required-message="Preencha este campo">
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="categoria_id">Categoria:</label>
                        <select name="categoria_id" id="categoria_id" class="form-control"
                        data-parsley-required-message="Selecione uma categoria">
                            <option value="">Selecione</option>
                            <?php
                                $dadosCategoria = $this->categoria->listar();
                                foreach($dadosCategoria as $dados) {
                                    echo "<option value='{$dados->id}'>{$dados->nome}</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="descricao">Descrição do Produto:</label>
                        <textarea name="descricao" id="descricao" class="form-control"
                        required data-parsley-required-message="Preencha este campo" 
                        rows="5"></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#descricao').summernote();
    });
</script>