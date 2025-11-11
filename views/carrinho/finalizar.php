<?php
    session_start();

    if (!isset($_SESSION["carrinho"])) {
        echo "<script>alert('Carrinho vazio!');history.back();</script>";
    }

    // carregar configurações de pagamento
    $paymentConfig = [];
    if (file_exists(__DIR__ . '/../../config/payment.php')) {
        $paymentConfig = include __DIR__ . '/../../config/payment.php';
    }

    $mpToken = $paymentConfig['mercadopago']['access_token'] ?? null;
    $returnBase = $paymentConfig['mercadopago']['return_base_url'] ?? null;
    $notificationUrl = $paymentConfig['mercadopago']['notification_url'] ?? null;

    $nome = $_POST["nome"] ?? NULL;
    $email = $_POST["email"] ?? NULL;

    // Se o usuário clicou em "Simular Pagamento", criar venda local e limpar carrinho
    if (isset($_POST['simulate_payment']) && $_POST['simulate_payment'] == 1) {
        require_once __DIR__ . '/../../config/Conexao.php';
        require_once __DIR__ . '/../../models/Venda.php';

        $pdo = Conexao::conectar();
        $vendaModel = new Venda($pdo);

        // Se usuário logado, usar id, caso contrário 0
        $clienteId = $_SESSION['user']['id'] ?? 0;

        $vendaId = $vendaModel->criarVenda($clienteId);

        // Aqui você poderia salvar itens da venda em tabela separada (venda_itens) se existir.

        // Limpar carrinho
        unset($_SESSION['carrinho']);

        echo "<script>alert('Pagamento simulado com sucesso! Venda #{$vendaId} criada.'); location.href='index.php';</script>";
        exit;
    }

    // Gerar PIX (criar venda pendente e mostrar QR) - etapa 1
    if (isset($_POST['generate_pix']) && $_POST['generate_pix'] == 1) {
        require_once __DIR__ . '/../../config/Conexao.php';
        require_once __DIR__ . '/../../models/Venda.php';

        $pdo = Conexao::conectar();
        $vendaModel = new Venda($pdo);
        $clienteId = $_SESSION['user']['id'] ?? 0;
        $pixVendaId = $vendaModel->criarVenda($clienteId);

        // armazenar id temporariamente para exibir QR
        $generatedPixVendaId = $pixVendaId;
    }

    // Confirmar pagamento via PIX (simulado) - etapa 2
    if (isset($_POST['confirm_pix']) && !empty($_POST['venda_id'])) {
        require_once __DIR__ . '/../../config/Conexao.php';
        require_once __DIR__ . '/../../models/Venda.php';

        $pdo = Conexao::conectar();
        $vendaModel = new Venda($pdo);

        $vendaIdConfirm = (int)$_POST['venda_id'];
        $txidPayment = 'V' . $vendaIdConfirm; // referência baseada no ID da venda

        // Marcar a venda como paga
        $vendaModel->finalizarVenda($vendaIdConfirm, 'pix', $txidPayment);

        // Limpar carrinho
        unset($_SESSION['carrinho']);

        echo "<script>alert('Pagamento via PIX confirmado (simulado). Venda #{$vendaIdConfirm} registrada como PAGA.'); location.href='index.php';</script>";
        exit;
    }

    if (empty($nome)) {
        echo "<script>alert('Preencha o nome!');history.back();</script>";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Digite um e-mail válido!');history.back();</script>";
    }

    $itens = [];

    foreach ($_SESSION["carrinho"] as $produto) {
        
        $itens[] = array(
            "title"       => $produto["nome"],
            "quantity"    => (int)$produto["qtde"],
            "currency_id" => "BRL",
            "unit_price"  => (float)$produto["valor"]
        );

    }

    // PIX configurado?
    $usePix = false;
    $pixConfig = $paymentConfig['pix'] ?? null;
    if (!empty($pixConfig) && !empty($pixConfig['enabled'])) {
        $usePix = true;
    }

    // Se o token do Mercado Pago estiver configurado, preparar a preferência
    $useMercadoPago = !empty($mpToken) && strpos($mpToken, 'COLOQUE_SEU') === false;

    if ($useMercadoPago) {
        require __DIR__ . '/../../vendor/autoload.php'; // autoload do Composer
        MercadoPago\SDK::setAccessToken($mpToken);

        // Crie um objeto de preferência
        $preference = new MercadoPago\Preference();
        use MercadoPago\Payer;

        $preference->items = $itens;

        $payer = new Payer();
        $payer->name = $nome;
        $payer->email = $email;

        $preference->payer = $payer;

        // URL de retorno após o pagamento (usar base configurada)
        $base = rtrim($returnBase, '/');
        $preference->back_urls = array(
            "success" => $base . "/index.php?param=carrinho/sucesso",
            "failure" => $base . "/index.php?param=carrinho/falha",
            "pending" => $base . "/index.php?param=carrinho/pendente"
        );

        $preference->notification_url = $notificationUrl;
        $preference->auto_return = "approved"; // Retorno automático quando aprovado

        $preference->save();
    }

  

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - MeLi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

</head>
<body>
    <div class="container">
        <h1 class="text-center">
            <a href="index.php" title="MeLi">
                <img src="images/mercado-pago-logo.png" alt="Mercado Pago" width="300px">
            </a>
        </h1>
        <hr>
        <div class="card">
            <div class="card-body">
                <h2>Produtos do Carrinho:</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Produto</td>
                            <td>Qtde</td>
                            <td>Valor</td>
                            <td>Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $total = 0;
                            if (isset($_SESSION["carrinho"])) {
                                foreach ($_SESSION["carrinho"] as $dados) {
                                    $total = $total + ($dados["valor"] * $dados["qtde"]);
                                    ?>
                                    <tr>
                                        <td><?=$dados["id"]?></td>
                                        <td><?=$dados["nome"]?></td>
                                        <td><?=$dados["qtde"]?></td>
                                        <td><?=$dados["valor"]?></td>
                                        <td><?=$dados["valor"] * $dados["qtde"]?></td>
                                    </tr>
                                    <?php
                                }
                            }
                        ?>
                    </tbody>
                </table>
                <p>
                    <strong>Total da Compra: <?=$total?></strong>
                </p>
                <br>
                <p class="text-center">
                    <!-- Botão de pagamento -->
                    <?php if ($useMercadoPago): ?>
                        <script src="https://www.mercadopago.com.br/integrations/v1/web-payment-checkout.js"
                                data-preference-id="<?php echo htmlspecialchars($preference->id); ?>"
                                data-button-label="Pagar com Mercado Pago (Boleto, Cartão de Crédito ou Débito)">
                        </script>
                    <?php else: ?>
                        <!-- Se PIX estiver habilitado, oferecer opção PIX -->
                        <?php if ($usePix): ?>
                            <?php if (!empty($generatedPixVendaId)): ?>
                                <?php
                                    // Dados do PIX para exibição
                                    $pixKey = $pixConfig['merchant_key'] ?? '';
                                    $pixMerchant = $pixConfig['merchant_name'] ?? '';
                                    $pixCity = $pixConfig['merchant_city'] ?? '';
                                    $amount = number_format($total, 2, '.', '');

                                    // Montar payload EMV (BR Code) para PIX - formato "Copia e Cola"
                                    function montaCampo($id, $valor) {
                                        $len = str_pad(strlen($valor), 2, '0', STR_PAD_LEFT);
                                        return sprintf('%02s%s%s', $id, $len, $valor);
                                    }

                                    function calculaCRC16($payload) {
                                        $crc = 0xFFFF;
                                        $polinomio = 0x1021;
                                        $bytes = unpack('C*', $payload);
                                        foreach ($bytes as $b) {
                                            $crc ^= ($b << 8);
                                            for ($i = 0; $i < 8; $i++) {
                                                if ($crc & 0x8000) {
                                                    $crc = (($crc << 1) & 0xFFFF) ^ $polinomio;
                                                } else {
                                                    $crc = ($crc << 1) & 0xFFFF;
                                                }
                                            }
                                        }
                                        $hex = strtoupper(dechex($crc));
                                        return str_pad($hex, 4, '0', STR_PAD_LEFT);
                                    }

                                    // Campos básicos do BR Code
                                    $pixKeyField = $pixKey;
                                    $txid = 'V' . $generatedPixVendaId; // referencia/txid

                                    $merchantAccountInfo = montaCampo('00', 'BR.GOV.BCB.PIX') . montaCampo('01', $pixKeyField);

                                    $payload = '';
                                    $payload .= montaCampo('00', '01'); // Payload Format Indicator
                                    $payload .= montaCampo('01', '11'); // Point of Initiation (11 = static - copia e cola)
                                    $payload .= montaCampo('26', $merchantAccountInfo); // Merchant Account Info (pix)
                                    $payload .= montaCampo('52', '0000'); // Merchant Category Code (0000 = unspecified)
                                    $payload .= montaCampo('53', '986'); // Currency (986 = BRL)
                                    if (!empty($amount) && $amount > 0) {
                                        $payload .= montaCampo('54', number_format($amount, 2, '.', ''));
                                    }
                                    $payload .= montaCampo('58', 'BR');
                                    $merchantNameField = strtoupper(substr($pixMerchant, 0, 25));
                                    $payload .= montaCampo('59', $merchantNameField);
                                    $merchantCityField = strtoupper(substr($pixCity, 0, 15));
                                    $payload .= montaCampo('60', $merchantCityField);

                                    // Additional Data Field Template (62) com TXID (05)
                                    $additional = montaCampo('05', $txid);
                                    $payload .= montaCampo('62', $additional);

                                    // CRC (campo 63) - calcular sobre payload + '6304'
                                    $crcPayload = $payload . '6304';
                                    $crc = calculaCRC16($crcPayload);
                                    $payload .= montaCampo('63', $crc);

                                    $qrUrl = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($payload);
                                ?>
                                <h5>PIX - Gerar Código</h5>
                                <p>Chave: <strong><?= htmlspecialchars($pixKey) ?></strong></p>
                                <p>Nome: <strong><?= htmlspecialchars($pixMerchant) ?></strong> — Cidade: <strong><?= htmlspecialchars($pixCity) ?></strong></p>
                                <p>Valor: <strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></p>

                                <div class="text-center mb-3">
                                    <img src="<?= $qrUrl ?>" alt="QR Code PIX" style="width:300px;height:300px;" />
                                </div>

                                <p class="text-center">
                                    <form method="post" action="">
                                        <input type="hidden" name="venda_id" value="<?= $generatedPixVendaId ?>">
                                        <button type="submit" name="confirm_pix" value="1" class="btn btn-primary">Já realizei o pagamento (Simular confirmação)</button>
                                    </form>
                                </p>
                                <p class="small text-muted">Copie a chave e o valor e efetue o pagamento no seu app bancário. Este é um fluxo simulado; clique em "Já realizei o pagamento" para concluir.</p>
                            <?php else: ?>
                                <form method="post" action="">
                                    <input type="hidden" name="nome" value="<?= htmlspecialchars($nome) ?>">
                                    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                                    <button type="submit" name="generate_pix" value="1" class="btn btn-success">Gerar Código PIX</button>
                                </form>
                                <p class="small text-muted">Ao gerar o código, será criada uma venda pendente e um QR Code será exibido.</p>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Mercado Pago não configurado: mostrar botão de simulação local -->
                            <form method="post" action="">
                                <input type="hidden" name="nome" value="<?= htmlspecialchars($nome) ?>">
                                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                                <button type="submit" name="simulate_payment" value="1" class="btn btn-success">Simular Pagamento (modo de teste)</button>
                            </form>
                            <p class="small text-muted">Para ativar o Mercado Pago, configure `config/payment.php` com seu Access Token e instale o SDK via Composer (<code>composer require mercadopago/dx-php</code>).</p>
                        <?php endif; ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</body>
</html>