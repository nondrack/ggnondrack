<?php
    $dadosCategoria = $this->categoria->getDados($id);
    //print_r($dadosCategoria);
    $id = $dadosCategoria->id ?? NULL;
    $nome = $dadosCategoria->nome ?? NULL;
    $ativo = $dadosCategoria->ativo ?? NULL;
?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                <h2>Cadastro de Categoria</h2>
            </div>
            <div class="float-end">
                <a href="categoria/index" class="btn btn-info">
                    Novo Registro
                </a>
                <a href="categoria/listar" class="btn btn-info">
                    Listar Registros
                </a>
            </div>
        </div>
        <div class="card-body">
            <form name="formCadastro" method="post" action="categoria/salvar" data-parsley-validate>
                <div class="row">
                    <div class="col-12 col-md-2">
                        <label for="id">ID:</label>
                        <input type="text" name="id" id="id"
                        class="form-control" readonly>
                    </div>
                    <div class="col-12 col-md-8">
                        <label for="nome">Nome da Categoria:</label>
                        <input type="text" name="nome" id="nome"
                        class="form-control" required
                        data-parsley-required-message="Preencha este campo">
                    </div>
                    <div class="col-12 col-md-2">
                        <label for="ativo">Ativo:</label>
                        <select name="ativo" id="ativo"
                        class="form-control" required 
                        data-parsley-required-message="Selecione uma opção">
                            <option value="">Selecione</option>
                            <option value="S">Sim</option>
                            <option value="N">Não</option>
                        </select>
                    </div>
                </div>
                <br>
                <button type="submit" class="btn btn-success float-end">
                    Salvar / Atualizar Dados
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    $("#id").val("<?=$id?>");
    $("#nome").val("<?=$nome?>");
    $("#ativo").val("<?=$ativo?>");
</script>