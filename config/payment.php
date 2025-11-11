<?php
// Configurações de pagamento - preencha com suas credenciais
return [
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
