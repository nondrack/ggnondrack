<div class="container py-5">
    <div class="card shadow-lg border-0 bg-dark text-light rounded-4">
        <div class="card-header bg-gradient-to-r from-blue-700 to-cyan-500 text-white d-flex justify-content-between align-items-center rounded-top-4">
            <h2 class="mb-0">📂 Listagem de Categorias</h2>
            <div>
                <a href="categoria/index" class="btn btn-outline-light me-2">
                    <i class="fas fa-plus"></i> Nova Categoria
                </a>
                <a href="categoria/listar" class="btn btn-outline-light">
                    <i class="fas fa-list"></i> Atualizar Lista
                </a>
            </div>
        </div>
        <div class="card-body bg-dark rounded-bottom-4 p-4">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle rounded-3 overflow-hidden">
                    <thead class="bg-gradient-to-r from-cyan-700 to-blue-600 text-white text-center">
                        <tr>
                            <th>ID</th>
                            <th>Nome da Categoria</th>
                            <th>Ativo</th>
                            <th>Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $dadosCategoria = $this->categoria->listar();
                        foreach ($dadosCategoria as $dados) {
                            $status = ($dados->ativo == "S") ? "Sim" : "Não";
                        ?>
                            <tr class="text-center">
                                <td><?= $dados->id ?></td>
                                <td><?= $dados->nome ?></td>
                                <td>
                                    <?php if ($status == "Sim") { ?>
                                        <span class="badge bg-success px-3 py-2">Ativo</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger px-3 py-2">Inativo</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="categoria/index/<?= $dados->id ?>" class="btn btn-sm btn-primary me-2 shadow-sm">
    <i class="fas fa-edit"></i> Editar
</a>

                                    <a href="javascript:excluir(<?= $dados->id ?>)" class="btn btn-sm btn-danger shadow-sm">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
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
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, excluir",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                location.href = 'categoria/excluir/' + id;
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

    .badge {
        font-size: 0.9rem;
    }
</style>
