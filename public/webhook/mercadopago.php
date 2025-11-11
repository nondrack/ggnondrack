<?php
// Webhook para notificações do Mercado Pago
// Recebe notificações e finaliza venda quando pagamento aprovado

require_once __DIR__ . '/../../config/Conexao.php';
require_once __DIR__ . '/../../models/Venda.php';

// Carregar config
$paymentConfig = [];
if (file_exists(__DIR__ . '/../../config/payment.php')) {
    $paymentConfig = include __DIR__ . '/../../config/payment.php';
}
$mpToken = $paymentConfig['mercadopago']['access_token'] ?? null;

$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Determinar o payment_id recebido (vários formatos podem existir)
$paymentId = null;
if (!empty($data['type']) && $data['type'] === 'payment' && !empty($data['data']['id'])) {
    $paymentId = $data['data']['id'];
} elseif (!empty($_GET['id'])) {
    $paymentId = $_GET['id'];
}

if (!$paymentId) {
    http_response_code(400);
    echo json_encode(['error' => 'payment_id not provided']);
    exit;
}

// Buscar detalhes do pagamento via API do Mercado Pago
if (empty($mpToken)) {
    http_response_code(500);
    echo json_encode(['error' => 'Mercado Pago access token not configured']);
    exit;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/v1/payments/{$paymentId}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer {$mpToken}"]); 
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not fetch payment details', 'http_code' => $httpCode, 'response' => $response]);
    exit;
}

$payment = json_decode($response, true);
$status = $payment['status'] ?? null;

// Tentar obter venda_id a partir de metadata
$vendaId = null;
if (!empty($payment['metadata']['venda_id'])) {
    $vendaId = (int)$payment['metadata']['venda_id'];
}

if ($status === 'approved' && $vendaId) {
    $pdo = Conexao::conectar();
    $vendaModel = new Venda($pdo);
    $vendaModel->finalizarVenda($vendaId, 'mercadopago', $paymentId);

    echo json_encode(['ok' => true, 'venda' => $vendaId]);
    exit;
}

// Se não foi aprovado, apenas retornar OK
http_response_code(200);
echo json_encode(['ok' => false, 'status' => $status]);

?>