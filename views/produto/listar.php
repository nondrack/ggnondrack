<div class="container py-5">
    <div class="card shadow-lg border-0 bg-dark text-light rounded-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">🖥️ Listagem de Produtos</h2>
                            <p class="mb-0 text-muted small">Gerencie seus produtos — editar, atualizar e excluir itens</p>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="produto/index" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-plus"></i> Novo
                            </a>
                            <a href="produto/listar" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-sync-alt"></i> Atualizar
                            </a>
                        </div>
                    </div>
                </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover text-center align-middle produto-list-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Valor</th>
                            <th>Imagem</th>
                            <th>Opções</th>
                        </tr>

                            <!-- Busca client-side para facilitar encontrar produtos -->
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const input = document.getElementById('produto-search');
                                if (!input) return;

                                input.addEventListener('input', function() {
                                    const term = this.value.trim().toLowerCase();
                                    const rows = document.querySelectorAll('#produtos-tbody tr');
                                    rows.forEach(row => {
                                        const nome = (row.children[1] && row.children[1].textContent || '').toLowerCase();
                                        const matches = nome.indexOf(term) !== -1;
                                        row.style.display = matches ? '' : 'none';
                                    });
                                });
                            });
                            </script>

                            <!-- product list styles moved to public/css/components/views-inline.css -->
                        <?php
                        $dadosProduto = $this->produto->listar() ?? [];

                        if (!empty($dadosProduto)):
                            foreach ($dadosProduto as $produto):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($produto->id ?? '') ?></td>
                            <td class="text-start"><?= htmlspecialchars($produto->nome ?? '') ?></td>
                            <td>R$ <?= number_format($produto->valor ?? 0, 2, ',', '.') ?></td>
                            <td>
                                <?php if (!empty($produto->imagem)): ?>
                                    <img src="../_arquivos/<?= htmlspecialchars($produto->imagem) ?>" 
                                         alt="<?= htmlspecialchars($produto->nome ?? 'Produto') ?>" 
                                         class="img-produto rounded">
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Sem imagem</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- BOTÃO EDITAR CORRIGIDO -->
                                    <a href="produto/index/<?= urlencode($produto->id) ?>" class="btn btn-sm btn-primary me-2 shadow-sm btn-circle" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                <!-- BOTÃO EXCLUIR -->
                                <button type="button" class="btn btn-sm btn-danger shadow-sm btn-circle" title="Excluir" onclick="excluir(<?= (int)$produto->id ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                </td>
            </tr>
                        <?php
                            endforeach;
                        else:
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
</div>

<script>
function excluir(id) {
    Swal.fire({
        title: "Deseja realmente excluir este item?",
        text: "Esta ação não poderá ser desfeita!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, excluir",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#00bcd4",
        cancelButtonColor: "#d33"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'produto/excluir/' + id;
        }
    });
}
</script>
<!-- product list styles moved to public/css/components/views-inline.css -->
