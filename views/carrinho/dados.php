<?php
    session_start();

    // Se não há carrinho, redirecionar
    if (!isset($_SESSION["carrinho"]) || empty($_SESSION["carrinho"])) {
        echo "<script>alert('Carrinho vazio!'); location.href='index.php?param=carrinho';</script>";
        exit;
    }

    // Se o usuário clicou em "Continuar", redirecionar para finalizar com POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome']) && isset($_POST['email'])) {
        // Os dados serão repassados via POST em um formulário oculto
        // nada a fazer aqui, apenas exibir o formulário
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dados de Entrega - Loja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Informações de Entrega</h2>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="index.php?param=carrinho/finalizar">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nome" name="nome" required placeholder="Seu nome completo">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="seu.email@exemplo.com">
                            </div>

                            <hr>

                            <h5 class="mb-3">Resumo do Carrinho</h5>
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Qtde</th>
                                        <th>Valor Unit.</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $total = 0;
                                        if (isset($_SESSION["carrinho"])) {
                                            foreach ($_SESSION["carrinho"] as $produto) {
                                                $subtotal = $produto["valor"] * $produto["qtde"];
                                                $total += $subtotal;
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($produto["nome"]) ?></td>
                                                    <td><?= $produto["qtde"] ?></td>
                                                    <td>R$ <?= number_format($produto["valor"], 2, ',', '.') ?></td>
                                                    <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">TOTAL:</th>
                                        <th>R$ <?= number_format($total, 2, ',', '.') ?></th>
                                    </tr>
                                </tfoot>
                            </table>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <a href="index.php?param=carrinho" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Voltar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-credit-card"></i> Ir para Pagamento
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
