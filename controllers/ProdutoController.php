<?php
require_once "../config/Conexao.php";
require_once "../models/Produto.php";
require_once "../models/Categoria.php";

class ProdutoController {
    private $produto;
    private $categoria;

    public function __construct() {
        $conexao = new Conexao();
        $pdo = $conexao->conectar();

        $this->categoria = new Categoria($pdo);
        $this->produto = new Produto($pdo);
    }

    public function index() {
        $produtos = $this->produto->listar();
        require __DIR__ . '/../views/produto/index.php';
    }

    public function listar() {
        // Agora mostrar TODOS (ativos e inativos) para permitir gerenciamento completo
        $dadosProduto = $this->produto->listar();
        require "../views/produto/listar.php";
    }

    public function salvar() {
    // Captura status ativo/inativo
    $ativo = strtoupper(trim($_POST['ativo'] ?? 'S')) === 'N' ? 'N' : 'S';

    // Normaliza e valida preço (ex: 1.234,56 → 1234.56)
    if (isset($_POST['preco'])) {
        $precoBr = trim($_POST['preco']);
    } elseif (isset($_POST['valor'])) { // retrocompatibilidade
        $precoBr = trim($_POST['valor']);
    } else {
        $precoBr = '0,00';
    }

    // Aceitar formatos: 123,45 ou 1.234,56
    $precoNumerico = str_replace('.', '', $precoBr); // remove separadores de milhar
    $precoNumerico = str_replace(',', '.', $precoNumerico); // converte vírgula decimal

    // Se campo vier vazio, considera 0
    if ($precoNumerico === '' || $precoNumerico === null) {
        $precoNumerico = '0';
    }

    // Validar formato numérico
    if (!is_numeric($precoNumerico)) {
        echo "<script>alert('Preço inválido. Verifique o formato.');history.back();</script>";
        exit;
    }

    // Formatar para duas casas decimais
    $precoFormatado = number_format((float)$precoNumerico, 2, '.', '');
    $_POST['preco'] = $precoFormatado;
    $_POST['ativo'] = $ativo;
    unset($_POST['valor']);

    // Validar categoria obrigatória
    if (empty($_POST['categoria_id']) || !ctype_digit((string)$_POST['categoria_id'])) {
        echo "<script>alert('Selecione uma categoria válida.');history.back();</script>";
        exit;
    }

    $arquivo = null;

    // Verifica se enviou imagem
    if (!empty($_FILES["imagem"]["name"])) {
        $extensao = pathinfo($_FILES["imagem"]["name"], PATHINFO_EXTENSION);
        $extensao = strtolower($extensao);

        // Gera nome único
        $arquivo = time() . "." . $extensao;
        $caminhoDestino = "../_arquivos/" . $arquivo;

        // Move o arquivo
        if (!move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminhoDestino)) {
            echo "<script>alert('Erro ao copiar arquivo.');</script>";
            exit;
        }
    }

    // Se não houve upload mas foi informada URL válida, usar a URL
    $urlInformada = trim($_POST['imagem_url'] ?? '');
    if (!$arquivo && $urlInformada) {
        $urlValida = filter_var($urlInformada, FILTER_VALIDATE_URL);
        $extOk = preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', parse_url($urlInformada, PHP_URL_PATH) ?? '');
        if ($urlValida && $extOk) {
            $_POST['imagem'] = $urlInformada; // salva diretamente a URL
        } else {
            echo "<script>alert('URL de imagem inválida. Use um endereço completo com extensão de imagem.');history.back();</script>";
            exit;
        }
    } else {
        $_POST["imagem"] = $arquivo; // pode ser null se não enviado
    }
    // Preservar imagem atual caso atualização sem nova imagem/URL
    if (!empty($_POST['id']) && empty($_POST['imagem']) && !empty($_POST['imagem_atual'])) {
        $_POST['imagem'] = $_POST['imagem_atual'];
    }
    unset($_POST['imagem_url']);
    unset($_POST['imagem_atual']);

    // Salva no banco de dados
    $salvou = $this->produto->salvar($_POST);

    if ($salvou) {
        echo "<script>
            alert('Produto salvo com sucesso!');
            window.location.href = 'produto/listar';
        </script>";
    } else {
        echo "<script>
            alert('Erro ao salvar produto!');
            history.back();
        </script>";
    }
}


    public function excluir($id) {
    try {
        $excluiu = $this->produto->excluir($id);

        if ($excluiu) {
            echo "<script>
                alert('Operação concluída. Se o produto fazia parte de vendas ele foi apenas desativado.');
                window.location.href = 'produto/listar';
            </script>";
        } else {
            echo "<script>
                alert('Erro ao excluir produto!');
                window.location.href = '/produto/listar';
            </script>";
        }
    } catch (Exception $e) {
        echo "<script>
            alert('Erro ao excluir: " . addslashes($e->getMessage()) . "');
            window.location.href = '/produto/listar';
        </script>";
    }
}
    public function detalhes($id) {
        $produto = $this->produto->buscarPorId($id);
        if (!$produto) {
            die("Produto não encontrado.");
        }
        require "../views/produto/detalhes.php";
    }
    public function editar($id) {
    $produto = $this->produto->buscarPorId($id);
    if (!$produto) {
        echo "<script>
            alert('Produto não encontrado!');
            window.location.href = 'produto/listar';
        </script>";
        exit;
    }

    $categorias = $this->categoria->listar(); // Caso queira mostrar dropdown de categoria
    require __DIR__ . '/../views/produto/index.php'; // Carrega o formulário preenchido
}

    // Toggle rápido de status (opcional)
    public function toggleStatus($id) {
        $produto = $this->produto->buscarPorId($id);
        if (!$produto) {
            echo "<script>alert('Produto não encontrado!');history.back();</script>"; return;
        }
        $novoStatus = ($produto->ativo === 'S') ? 'N' : 'S';
        $dados = [
            'nome' => $produto->nome,
            'descricao' => $produto->descricao,
            'categoria_id' => $produto->categoria_id,
            'preco' => $produto->preco,
            'imagem' => $produto->imagem,
            'ativo' => $novoStatus
        ];
        $this->produto->atualizar($produto->id, $dados);
        echo "<script>alert('Status alterado para " . ($novoStatus === 'S' ? 'Ativo' : 'Inativo') . "');window.location.href='produto/listar';</script>";
    }


}
