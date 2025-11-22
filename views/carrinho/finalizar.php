<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verificar se o usuário está logado
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
        header('Location: ../../public/login.php?erro=É necessário fazer login para finalizar a compra');
        exit;
    }

    if (!isset($_SESSION["carrinho"])) {
        echo "<script>Swal.fire({icon:'warning',title:'Carrinho Vazio',text:'Adicione produtos ao carrinho antes de finalizar.',confirmButtonColor:'#00eaff'}).then(()=>history.back());</script>";
    }

    // carregar configurações de pagamento
    $paymentConfig = [];
    if (file_exists(__DIR__ . '/../../config/payment.php')) {
        $paymentConfig = include __DIR__ . '/../../config/payment.php';
    }

    $mpToken = $paymentConfig['mercadopago']['access_token'] ?? null;
    $returnBase = $paymentConfig['mercadopago']['return_base_url'] ?? null;
    $notificationUrl = $paymentConfig['mercadopago']['notification_url'] ?? null;

    // Captura campos básicos enviados da tela de dados
    $nome = $_POST["nome"] ?? NULL;
    $email = $_POST["email"] ?? NULL;

    // Capturar e persistir campos de endereço (evitar perda nos próximos POSTs)
    $camposEndereco = ['cep','endereco','numero','bairro','cidade','estado','complemento','telefone','observacoes'];
    $enderecoDados = [];
    foreach ($camposEndereco as $c) {
        if (array_key_exists($c, $_POST)) {
            // Permitir campos vazios (serão salvos como string vazia) para evitar perder chaves
            $enderecoDados[$c] = trim((string)$_POST[$c]);
        }
    }
    // incluir nome e email no pacote
    if ($nome) { $enderecoDados['nome'] = $nome; }
    if ($email) { $enderecoDados['email'] = $email; }

    if (!empty($enderecoDados)) {
        // Mesclar se já existir sessão prévia
        $_SESSION['endereco_checkout'] = array_merge($_SESSION['endereco_checkout'] ?? [], $enderecoDados);
    }
    // Fallback sempre: garante que $enderecoDados possui todas as chaves esperadas
    if (isset($_SESSION['endereco_checkout'])) {
        // Usar array_merge para garantir inclusão de todos campos (o operador + descartava duplicados e ignorava os de endereço)
        foreach (array_merge(['nome','email'], $camposEndereco) as $c) {
            if (!isset($enderecoDados[$c]) || $enderecoDados[$c] === '') {
                if (isset($_SESSION['endereco_checkout'][$c])) {
                    $enderecoDados[$c] = $_SESSION['endereco_checkout'][$c];
                }
            }
        }
    }
    // Repopular variáveis principais
    $nome = $enderecoDados['nome'] ?? $nome;
    $email = $enderecoDados['email'] ?? $email;

    // Log opcional para debug (ver em error_log)
    if (!empty($_POST['debug_endereco']) || (isset($_GET['debug']) && $_GET['debug'] === 'endereco')) {
        error_log('[FINALIZAR] POST endereco => ' . json_encode($_POST));
        error_log('[FINALIZAR] SESSION endereco_checkout => ' . json_encode($_SESSION['endereco_checkout'] ?? []));
        error_log('[FINALIZAR] Usado para salvar => ' . json_encode($enderecoDados));
    }

    // Se o usuário clicou em "Simular Pagamento", criar venda local e limpar carrinho
    if (isset($_POST['simulate_payment']) && $_POST['simulate_payment'] == 1) {
        require_once __DIR__ . '/../../config/Conexao.php';
        require_once __DIR__ . '/../../models/Venda.php';
        require_once __DIR__ . '/../../models/Endereco.php';

        $pdo = Conexao::conectar();
        $vendaModel = new Venda($pdo);
        $enderecoModel = new Endereco($pdo);

        // Se usuário logado, usar id, caso contrário 0
        $usuarioId = $_SESSION['user']['id'] ?? 0;

        $vendaId = $vendaModel->criarVenda($usuarioId);

        // Salvar itens da venda
        if (!empty($_SESSION['carrinho'])) {
            $vendaModel->salvarItens($vendaId, $_SESSION['carrinho']);
        }
        
        // Salvar endereço vinculado à venda usando dados consolidados
        $enderecoModel->salvarParaVenda($vendaId, $enderecoDados, $usuarioId);

        // Limpar carrinho
        unset($_SESSION['carrinho']);

        echo "<script>Swal.fire({icon:'success',title:'Pagamento Confirmado!',text:'Venda #{$vendaId} criada com sucesso.',confirmButtonColor:'#00eaff',timer:3000}).then(()=>location.href='index.php');</script>";
        exit;
    }

    // Gerar PIX (criar venda pendente e mostrar QR) - etapa 1
    if (isset($_POST['generate_pix']) && $_POST['generate_pix'] == 1) {
        require_once __DIR__ . '/../../config/Conexao.php';
        require_once __DIR__ . '/../../models/Venda.php';
        require_once __DIR__ . '/../../models/Endereco.php';

        $pdo = Conexao::conectar();
        $vendaModel = new Venda($pdo);
        $enderecoModel = new Endereco($pdo);
        $usuarioId = $_SESSION['user']['id'] ?? 0;
        $pixVendaId = $vendaModel->criarVenda($usuarioId);

        // armazenar id temporariamente para exibir QR
        $generatedPixVendaId = $pixVendaId;
        // Salvar itens da venda recém-criada
        if (!empty($_SESSION['carrinho'])) {
            $vendaModel->salvarItens($pixVendaId, $_SESSION['carrinho']);
        }
        
        // Salvar endereço vinculado à venda (dados da sessão ou POST inicial)
        $enderecoModel->salvarParaVenda($pixVendaId, $enderecoDados, $usuarioId);
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

        echo "<script>Swal.fire({icon:'success',title:'Pagamento PIX Confirmado!',html:'<p>Venda <strong>#{$vendaIdConfirm}</strong> registrada como PAGA.</p>',confirmButtonColor:'#00eaff',timer:3000}).then(()=>location.href='index.php');</script>";
        exit;
    }

    // Validação mínima (somente na primeira chegada com os dados do formulário)
    if (!isset($_POST['generate_pix']) && !isset($_POST['simulate_payment']) && !isset($_POST['confirm_pix']) && !isset($_POST['check_mp'])) {
        if (empty($nome)) {
            echo "<script>Swal.fire({icon:'error',title:'Campo Obrigatório',text:'Por favor, preencha o nome.',confirmButtonColor:'#00eaff'}).then(()=>history.back());</script>";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>Swal.fire({icon:'error',title:'E-mail Inválido',text:'Por favor, digite um e-mail válido.',confirmButtonColor:'#00eaff'}).then(()=>history.back());</script>";
        }
    }

    $itens = [];

    // Helper para preço unitário (fallback preco -> valor)
    function precoUnitario(array $p): float {
        if (isset($p['preco']) && $p['preco'] !== '' && $p['preco'] !== null) {
            return (float)$p['preco'];
        }
        if (isset($p['valor']) && $p['valor'] !== '' && $p['valor'] !== null) {
            return (float)$p['valor'];
        }
        return 0.0;
    }

    foreach ($_SESSION["carrinho"] as $produto) {
        $unit = precoUnitario($produto);
        $itens[] = array(
            "title"       => $produto["nome"],
            "quantity"    => (int)$produto["qtde"],
            "currency_id" => "BRL",
            "unit_price"  => $unit
        );
    }

    // Calcular total do carrinho (usado para pagamentos)
    $total = 0;
    foreach ($_SESSION["carrinho"] as $p) {
        $total += (precoUnitario($p) * (int)$p['qtde']);
    }

    // Verificar status de pagamento Mercado Pago (consulta manual via botão)
    if (isset($_POST['check_mp']) && !empty($_POST['venda_id'])) {
        require_once __DIR__ . '/../../config/Conexao.php';
        require_once __DIR__ . '/../../models/Venda.php';

        $pdo = Conexao::conectar();
        $vendaModel = new Venda($pdo);
        $vendaIdCheck = (int)$_POST['venda_id'];
        $venda = $vendaModel->buscarPorId($vendaIdCheck);

        $txid = $venda->txid ?? null;
        if ($txid && strpos($txid, 'mp_') === 0) {
            $mpPaymentId = substr($txid, 3);

            // Consultar API do Mercado Pago
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/v1/payments/{$mpPaymentId}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer {$mpToken}"]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $pay = json_decode($response, true);
                $status = $pay['status'] ?? null;
                if ($status === 'approved') {
                    $vendaModel->finalizarVenda($vendaIdCheck, 'mercadopago', $mpPaymentId);
                    unset($_SESSION['carrinho']);
                    echo "<script>Swal.fire({icon:'success',title:'Pagamento Aprovado!',html:'<p>Venda <strong>#{$vendaIdCheck}</strong> confirmada via Mercado Pago.</p>',confirmButtonColor:'#00eaff',timer:3000}).then(()=>location.href='index.php');</script>";
                    exit;
                } else {
                    echo "<script>Swal.fire({icon:'info',title:'Aguardando Aprovação',text:'Status: " . htmlspecialchars($status) . "',confirmButtonColor:'#00eaff'}).then(()=>history.back());</script>";
                    exit;
                }
            } else {
                echo "<script>Swal.fire({icon:'error',title:'Erro na Consulta',text:'Erro ao consultar Mercado Pago (HTTP: {$httpCode}).',confirmButtonColor:'#00eaff'}).then(()=>history.back());</script>";
                exit;
            }
        } else {
            echo "<script>Swal.fire({icon:'warning',title:'TXID não encontrado',text:'TXID do Mercado Pago não encontrado para essa venda.',confirmButtonColor:'#00eaff'}).then(()=>history.back());</script>";
            exit;
        }
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

        // Se Mercado Pago e PIX habilitado, criar pagamento PIX via API e mostrar QR
        if ($usePix) {
            require_once __DIR__ . '/../../config/Conexao.php';
            require_once __DIR__ . '/../../models/Venda.php';

            $pdo = Conexao::conectar();
            $vendaModel = new Venda($pdo);
            $usuarioId = $_SESSION['user']['id'] ?? 0;

            // Criar pagamento PIX via Mercado Pago apenas se o usuário tiver solicitado geração (generatedPixVendaId)
            if (!empty($generatedPixVendaId)) {
                // usar a venda previamente criada no POST generate_pix
                $vendaIdForMp = $generatedPixVendaId;

                // Criar pagamento PIX via Mercado Pago
                $payment_mp = new MercadoPago\Payment();
            $payment_mp->transaction_amount = (float)$total;
            $payment_mp->description = "Compra #{$vendaIdForMp}";
            $payment_mp->payment_method_id = 'pix';
            $payment_mp->payer = array(
                'email' => $email,
                'first_name' => $nome
            );
            $payment_mp->metadata = array('venda_id' => $vendaIdForMp);

            $payment_mp->save();

            // Armazenar identificação do pagamento no campo txid da venda
            try {
                $stmt = $pdo->prepare("UPDATE venda SET txid = :tx WHERE id = :id");
                $stmt->execute([':tx' => 'mp_' . ($payment_mp->id ?? ''), ':id' => $vendaIdForMp]);
            } catch (Exception $e) {
                // não crítico
            }

                $generatedPixVendaId = $vendaIdForMp;

            // Tentar obter QR do response do Mercado Pago
            $qr_code = null;
            $qr_base64 = null;
            if (!empty($payment_mp->point_of_interaction)) {
                $poi = $payment_mp->point_of_interaction;
                if (!empty($poi->transaction_data)) {
                    $td = $poi->transaction_data;
                    $qr_code = $td->qr_code ?? null;
                    $qr_base64 = $td->qr_code_base64 ?? null;
                }
            }

            if (!empty($qr_base64)) {
                $qrUrl = 'data:image/png;base64,' . $qr_base64;
            } elseif (!empty($qr_code)) {
                $qrUrl = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($qr_code);
            } else {
                $qrUrl = null;
            }

        } else {
            // Crie um objeto de preferência (checkout web) quando não for PIX
            $preference = new MercadoPago\Preference();
            $preference->items = $itens;

            $payer = new MercadoPago\Payer();
            $payer->name = $nome;
            $payer->email = $email;
            $preference->payer = $payer;

            // Criar venda e salvar itens + endereço antes de gerar preferência (fluxo padrão web)
            require_once __DIR__ . '/../../config/Conexao.php';
            require_once __DIR__ . '/../../models/Venda.php';
            require_once __DIR__ . '/../../models/Endereco.php';
            $pdoVenda = Conexao::conectar();
            $vendaModelStd = new Venda($pdoVenda);
            $enderecoModelStd = new Endereco($pdoVenda);
            $usuarioIdStd = $_SESSION['user']['id'] ?? 0;
            $webVendaId = $vendaModelStd->criarVenda($usuarioIdStd);
            if (!empty($_SESSION['carrinho'])) {
                $vendaModelStd->salvarItens($webVendaId, $_SESSION['carrinho']);
            }
            $enderecoModelStd->salvarParaVenda($webVendaId, $enderecoDados, $usuarioIdStd);

            // Guardar referência se necessário
            $generatedPixVendaId = $webVendaId;

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
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pagamento - DualCore Tech</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/dark-theme.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/sweetalert2.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #0b0f19 0%, #1a1a2e 100%);
            min-height: 100vh;
        }
        .payment-card {
            background: #1a1a2e;
            border: 1px solid #333;
            border-radius: 15px;
            box-shadow: none;
            margin-bottom: 20px;
        }
        .payment-card:hover {
            border-color: #00eaff;
            box-shadow: none;
        }
        .payment-header {
            background: #16213e;
            border-bottom: 2px solid #333;
            padding: 20px;
            border-radius: 15px 15px 0 0;
        }
        .product-row {
            padding: 15px;
            border-bottom: 1px solid #333;
            transition: all 0.3s ease;
        }
        .product-row:last-child {
            border-bottom: none;
            border-radius: 0 0 15px 15px;
        }
        .product-row:hover {
            background: #222;
        }
        .payment-card .card-body {
            border-radius: 0 0 15px 15px;
            overflow: hidden;
        }
        .total-section {
            background: #16213e;
            padding: 25px;
            border-radius: 10px;
            margin: 20px 0;
            border: 2px solid #333;
        }
        .payment-method {
            background: #16213e;
            border: 2px solid #333;
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .payment-method:hover {
            border-color: #00eaff;
            background: #1a2332;
            transform: translateY(-2px);
        }
        .qr-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            display: inline-block;
            margin: 20px auto;
        }
        .btn-payment {
            background: linear-gradient(135deg, #00eaff, #0099cc);
            border: none;
            color: #000;
            font-weight: bold;
            padding: 15px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: none;
        }
        .btn-payment:hover {
            transform: translateY(-3px);
            box-shadow: none;
            background: linear-gradient(135deg, #0099cc, #00eaff);
        }
        .payment-icon {
            font-size: 3rem;
            color: #00eaff;
            margin-bottom: 15px;
        }
        .price-tag {
            background: linear-gradient(135deg, #00eaff, #0099cc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-4">
                    <a href="index.php">
                        <img src="images/logo.png" alt="DualCore Tech" style="height: 80px;">
                    </a>
                    <h2 class="text-light mt-3">
                        <i class="fas fa-credit-card me-2" style="color: #00eaff;"></i>
                        Finalizar Pagamento
                    </h2>
                </div>

                <div class="payment-card">
                    <div class="payment-header">
                        <h4 class="text-light mb-0">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Resumo do Pedido
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <?php
                            $total = 0;
                            if (isset($_SESSION["carrinho"]) && !empty($_SESSION["carrinho"])):
                                foreach ($_SESSION["carrinho"] as $dados):
                                    $unit = precoUnitario($dados);
                                    $subtotal = $unit * (int)$dados["qtde"];
                                    $total += $subtotal;
                        ?>
                                    <div class="product-row">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h6 class="text-light mb-1"><?= htmlspecialchars($dados["nome"]) ?></h6>
                                                <small class="text-muted">Quantidade: <?= $dados["qtde"] ?> x R$ <?= number_format($unit, 2, ',', '.') ?></small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <span class="badge bg-info fs-6">ID: <?= $dados["id"] ?></span>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <span class="text-success fw-bold fs-5">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                                            </div>
                                        </div>
                                    </div>
                        <?php
                                endforeach;
                            endif;
                        ?>
                    </div>
                </div>

                <div class="total-section text-center">
                    <h3 class="text-light mb-2">Total do Pedido</h3>
                    <div class="price-tag">R$ <?= number_format($total, 2, ',', '.') ?></div>
                    <p class="text-muted mt-2">
                        <i class="fas fa-shield-alt me-2"></i>
                        Pagamento 100% seguro
                    </p>
                </div>

                <div class="payment-card">
                    <div class="payment-header">
                        <h4 class="text-light mb-0">
                            <i class="fas fa-wallet me-2"></i>
                            Método de Pagamento
                        </h4>
                    </div>
                    <div class="card-body p-4">
                    <!-- Botão de pagamento -->
                    <?php if ($useMercadoPago): ?>
                        <?php if ($usePix): ?>
                            <?php if (!empty($generatedPixVendaId) && !empty($qrUrl)): ?>
                                <div class="payment-method text-center">
                                    <div class="payment-icon">
                                        <i class="fas fa-qrcode"></i>
                                    </div>
                                    <h5 class="text-light mb-3">PIX - Mercado Pago</h5>
                                    <p class="text-muted mb-4">Escaneie o QR Code ou use o código PIX</p>

                                    <div class="qr-container mx-auto">
                                        <img src="<?= htmlspecialchars($qrUrl) ?>" alt="QR Code PIX" style="width:300px;height:300px;" />
                                    </div>

                                    <div class="alert alert-info mt-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Valor a pagar: <strong class="text-dark">R$ <?= number_format($total, 2, ',', '.') ?></strong>
                                    </div>

                                    <div class="d-flex justify-content-center gap-3 mt-4">
                                        <form method="post" action="">
                                            <input type="hidden" name="venda_id" value="<?= $generatedPixVendaId ?>">
                                            <button type="submit" name="check_mp" value="1" class="btn btn-payment">
                                                <i class="fas fa-check-circle me-2"></i>
                                                Verificar Pagamento
                                            </button>
                                        </form>
                                        <form method="post" action="">
                                            <input type="hidden" name="venda_id" value="<?= $generatedPixVendaId ?>">
                                            <button type="submit" name="confirm_pix" value="1" class="btn btn-outline-light">
                                                <i class="fas fa-hand-point-up me-2"></i>
                                                Simular Confirmação
                                            </button>
                                        </form>
                                    </div>

                                    <p class="small text-muted mt-3">
                                        <i class="fas fa-shield-alt me-2"></i>
                                        Pagamento processado pelo Mercado Pago
                                    </p>
                                </div>
                            <?php else: ?>
                                <div class="payment-method text-center">
                                    <div class="payment-icon">
                                        <i class="fab fa-pix"></i>
                                    </div>
                                    <h5 class="text-light mb-3">Pagar com PIX</h5>
                                    <p class="text-muted mb-4">Pagamento instantâneo e seguro</p>
                                    
                                    <form method="post" action="">
                                        <input type="hidden" name="generate_pix" value="1">
                                        <?php foreach ($enderecoDados as $k => $v): ?>
                                            <input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>">
                                        <?php endforeach; ?>
                                        <button type="submit" class="btn btn-payment">
                                            <i class="fas fa-qrcode me-2"></i>
                                            Gerar Código PIX
                                        </button>
                                    </form>
                                    <p class="small text-muted mt-3">Você receberá o QR Code para pagamento</p>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="payment-method text-center">
                                <div class="payment-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <h5 class="text-light mb-3">Mercado Pago</h5>
                                <p class="text-muted mb-4">Cartão de crédito, débito ou boleto</p>
                                <script src="https://www.mercadopago.com.br/integrations/v1/web-payment-checkout.js"
                                        data-preference-id="<?php echo htmlspecialchars($preference->id); ?>"
                                        data-button-label="Pagar com Mercado Pago">
                                </script>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Se PIX estiver habilitado, oferecer opção PIX -->
                        <?php if ($usePix): ?>
                            <?php if (!empty($generatedPixVendaId)): ?>
                                <?php
                                    // Dados do PIX para exibição
                                    $pixKey = $pixConfig['merchant_key'] ?? '';
                                    $pixPlaceholder = in_array(strtoupper(trim($pixKey)), ['SUA_CHAVE_PIX_AQUI', 'SEU_PIX_AQUI', '']) ;
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
                                <div class="payment-method text-center">
                                    <div class="payment-icon">
                                        <i class="fab fa-pix"></i>
                                    </div>
                                    <h5 class="text-light mb-3">PIX - Pagamento Instantâneo</h5>
                                    <p class="text-muted mb-4">Escaneie o QR Code ou use o código PIX</p>

                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Valor a pagar: <strong class="text-dark">R$ <?= number_format($total, 2, ',', '.') ?></strong>
                                    </div>

                                    <div class="qr-container mx-auto">
                                        <img src="images/pix.jpg" alt="QR Code PIX" style="width:300px;height:300px;" />
                                    </div>

                                    <div class="alert alert-dark mt-3">
                                        <p class="text-muted mb-2"><small><i class="fas fa-copy me-2"></i>Copie e Cole:</small></p>
                                        <input type="text" class="form-control form-control-sm bg-dark text-light border-secondary" 
                                               value="00020126360014BR.GOV.BCB.PIX0114+55449970134435204000053039865802BR5925LUCAS FERNANDO BARBOSA DA6007ARARUNA622605222nGujMiKVViQjhYCRgjHKu6304ABFB" 
                                               readonly 
                                               onclick="this.select(); document.execCommand('copy'); Swal.fire({icon:'success',title:'Copiado!',text:'Código PIX copiado para a área de transferência.',confirmButtonColor:'#00eaff',timer:2000,showConfirmButton:false});" 
                                               style="font-size: 0.75rem; cursor: pointer;">
                                    </div>

                                    <form method="post" action="" class="mt-4">
                                        <input type="hidden" name="venda_id" value="<?= $generatedPixVendaId ?>">
                                        <button type="submit" name="confirm_pix" value="1" class="btn btn-payment">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Já realizei o pagamento
                                        </button>
                                    </form>

                                    <p class="small text-muted mt-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Este é um modo de teste. Clique no botão para simular a confirmação.
                                    </p>
                                </div>
                            <?php else: ?>
                                <div class="payment-method text-center">
                                    <div class="payment-icon">
                                        <i class="fab fa-pix"></i>
                                    </div>
                                    <h5 class="text-light mb-3">Pagar com PIX</h5>
                                    <p class="text-muted mb-4">Rápido, fácil e seguro</p>
                                    
                                    <form method="post" action="">
                                        <input type="hidden" name="generate_pix" value="1">
                                        <input type="hidden" name="nome" value="<?= htmlspecialchars($nome) ?>">
                                        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                                        <button type="submit" class="btn btn-payment">
                                            <i class="fas fa-qrcode me-2"></i>
                                            Gerar Código PIX
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="payment-method text-center">
                                <div class="payment-icon">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                                <h5 class="text-light mb-3">Simular Pagamento</h5>
                                <p class="text-muted mb-4">Modo de teste - Para desenvolvimento</p>
                                
                                <form method="post" action="">
                                    <input type="hidden" name="simulate_payment" value="1">
                                    <?php foreach ($enderecoDados as $k => $v): ?>
                                        <input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>">
                                    <?php endforeach; ?>
                                    <button type="submit" class="btn btn-payment">
                                        <i class="fas fa-play-circle me-2"></i>
                                        Simular Pagamento
                                    </button>
                                </form>
                                
                                <div class="alert alert-info mt-4">
                                    <i class="fas fa-lightbulb me-2"></i>
                                    <small>Configure o Mercado Pago em <code>config/payment.php</code></small>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="index.php?param=carrinho" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>
                        Voltar ao Carrinho
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/sweetalert2.js"></script>
</body>
</html>
