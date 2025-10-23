<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Listagem de Produtos:</h2>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Valor</th>
                        <th>Imagem</th>
                        <th>Opções</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $dadosProduto = $this->produto->listar() ?? [];

                    if (!empty($dadosProduto)) :
                        foreach ($dadosProduto as $produto) :
                    ?>
                            <tr class="text-center">
                                <td><?= htmlspecialchars($produto->id ?? '') ?></td>
                                <td><?= htmlspecialchars($produto->nome ?? '') ?></td>
                                <td>R$ <?= number_format($produto->valor ?? 0, 2, ',', '.') ?></td>
                                <td>
                                    <?php if (!empty($produto->imagem)) : ?>
                                        <img src="../_arquivos/<?= htmlspecialchars($produto->imagem) ?>" 
                                             alt="<?= htmlspecialchars($produto->nome ?? 'Produto') ?>" 
                                             style="max-width: 100px; max-height: 80px; border-radius: 6px; box-shadow: 0 0 5px rgba(0,0,0,0.3);">
                                    <?php else : ?>
                                        <span class="text-muted fst-italic">Sem imagem</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="produto/editar/<?= urlencode($produto->id) ?>" 
                                       class="btn btn-success btn-sm" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm" 
                                            title="Excluir" 
                                            onclick="excluir(<?= (int)$produto->id ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                    else :
                        ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                Nenhum produto encontrado.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function excluir(id) {
        Swal.fire({
            title: "Deseja realmente excluir este item?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sim, excluir",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'produto/excluir/' + id;
            }
        });
    }
</script>
