<div class="container py-5">
    <div class="card shadow-lg border-0 bg-dark text-light rounded-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>🖥️ Listagem de Produtos</h2>
            <div>
                <a href="produto/index" class="btn btn-outline-light me-2">
                    <i class="fas fa-plus"></i> Novo Produto
                </a>
                <a href="produto/listar" class="btn btn-outline-light">
                    <i class="fas fa-sync-alt"></i> Atualizar Lista
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover text-center">
                    <thead>
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

                        if (!empty($dadosProduto)):
                            foreach ($dadosProduto as $produto):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($produto->id ?? '') ?></td>
                            <td><?= htmlspecialchars($produto->nome ?? '') ?></td>
                            <td>R$ <?= number_format($produto->valor ?? 0, 2, ',', '.') ?></td>
                            <td>
                                <?php if (!empty($produto->imagem)): ?>
                                    <img src="../_arquivos/<?= htmlspecialchars($produto->imagem) ?>" 
                                         alt="<?= htmlspecialchars($produto->nome ?? 'Produto') ?>" 
                                         class="img-produto">
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Sem imagem</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- BOTÃO EDITAR CORRIGIDO -->
                           <a href="produto/index/<?= urlencode($produto->id) ?>" 
   class="btn btn-sm btn-primary me-2 shadow-sm" 
   title="Editar">
    <i class="fas fa-edit"></i> Editar
</a>

                                <!-- BOTÃO EXCLUIR -->
                                <button type="button" 
                                        class="btn btn-sm btn-danger shadow-sm" 
                                        title="Excluir" 
                                        onclick="excluir(<?= (int)$produto->id ?>)">
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

<style>
body {
    background-color: #0a0f1a;
    font-family: 'Poppins', sans-serif;
}

.card {
    backdrop-filter: blur(12px);
    border: 1px solid rgba(0, 255, 255, 0.2);
}

.table th, .table td {
    vertical-align: middle;
}

.btn-outline-light {
    border-radius: 30px;
    transition: all 0.3s ease;
}
.btn-outline-light:hover {
    background-color: #00bcd4;
    color: #fff;
    box-shadow: 0 0 10px #00bcd4;
}

.btn-primary {
    background: linear-gradient(90deg, #00b4d8, #0077b6);
    border: none;
    border-radius: 30px;
    transition: 0.3s;
}
.btn-primary:hover {
    box-shadow: 0 0 10px #00b4d8;
}

.btn-danger {
    background: linear-gradient(90deg, #ff4b2b, #ff416c);
    border: none;
    border-radius: 30px;
    transition: 0.3s;
}
.btn-danger:hover {
    box-shadow: 0 0 10px #ff4b2b;
}

.img-produto {
    max-width: 100px;
    max-height: 80px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 255, 255, 0.4);
    transition: transform 0.3s ease;
}
.img-produto:hover {
    transform: scale(1.05);
    box-shadow: 0 0 15px rgba(0, 255, 255, 0.7);
}
</style>
