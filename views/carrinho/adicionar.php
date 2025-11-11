<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o ID foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<h3 style='color:red;text-align:center;'>ID do produto não informado!</h3>");
}
echo"<h2> teste</h2>";

$id = intval($_GET['id']);

// URL da API
$urlProduto = "http://localhost/ggnondrack/public/apis/produto.php?id={$id}";

// Busca o produto
$dadosProduto = json_decode(file_get_contents($urlProduto));

// Verifica se veio algo
if (!empty($dadosProduto) && !empty($dadosProduto->id)) {

    // Verifica se já existe o item no carrinho
    $qtde = $_SESSION["carrinho"][$id]["qtde"] ?? 0;
    $qtde++;

    // Caminho da imagem corrigido
    $imagem = !empty($dadosProduto->imagem) ? "../_arquivos/" . $dadosProduto->imagem : "";

    // Atualiza carrinho
    $_SESSION["carrinho"][$id] = [
        "id"     => $dadosProduto->id,
        "nome"   => $dadosProduto->nome,
        "qtde"   => $qtde,
        "valor"  => $dadosProduto->valor,
        "imagem" => $imagem
    ];

    // Redireciona de volta para o carrinho
    echo "<script>location.href='index.php?pagina=carrinho';</script>";

} else {
    echo "<h2 style='color:red;text-align:center;'>Produto inválido!</h2>";
}
?>
