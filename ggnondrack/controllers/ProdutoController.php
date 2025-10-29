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
        $dadosProduto = $this->produto->listar();
        require "../views/produto/listar.php";
    }

    public function salvar() {
    // Converte o valor para formato decimal correto (ex: 1.234,56 → 1234.56)
    $valor = str_replace(".", "", $_POST["valor"]);
    $valor = str_replace(",", ".", $valor);
    $_POST["valor"] = $valor;

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

    $_POST["imagem"] = $arquivo;

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
                alert('Produto excluído com sucesso!');
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


}
