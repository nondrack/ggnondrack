<?php
// Configurações de pagamento - preencha com suas credenciais
$config = [
    // Gateway suportado: 'mercadopago' (atual), 'mock' (apenas para testes)
    'gateway' => 'mercadopago',

    // Mercado Pago
    'mercadopago' => [
        // Access Token (ou public token) - use o token de sandbox para testes
        'access_token' => getenv('MP_ACCESS_TOKEN') ?: 'COLOQUE_SEU_ACCESS_TOKEN_AQUI',
        // URL base para callbacks/back_urls - ajuste para seu domínio local/produção
        'return_base_url' => getenv('RETURN_BASE_URL') ?: 'http://localhost/ggnondrack/public',
        // Notification URL (webhook)
        'notification_url' => getenv('MP_NOTIFICATION_URL') ?: 'http://localhost/ggnondrack/public/meli/notificacao.php',
        // Modo sandbox (true/false)
        'sandbox' => true,
    ],

    // Mock (apenas para testes locais sem integrar gateway)
    'mock' => [
        'enabled' => true
    ]
,
    // Configuração básica de PIX (simulado)
    'pix' => [
        // Habilitar opção PIX no checkout (true/false)
        'enabled' => true,
        // Chave PIX do recebedor (coloque sua chave real quando for para produção)
        'merchant_key' => getenv('PIX_KEY') ?: 'SUA_CHAVE_PIX_AQUI',
        // Nome e cidade do recebedor (apenas para exibição)
        'merchant_name' => 'Sua Loja LTDA',
        'merchant_city' => 'SuaCidade',
    ]
];

// Permite override local sem commitar segredos: copie `payment.local.php.example` para `payment.local.php` e preencha.
$localFile = __DIR__ . '/payment.local.php';
if (file_exists($localFile)) {
    $local = include $localFile;
    if (is_array($local)) {
        $config = array_replace_recursive($config, $local);
    }
}

return $config;
