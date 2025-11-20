<?php
require_once __DIR__ . '/../config/Conexao.php';
require_once __DIR__ . '/../models/Produto.php';

class CarrinhoController {
    private $produto;

    public function __construct() {
        $pdo = Conexao::conectar();
        $this->produto = new Produto($pdo);
    }

    /**
     * Adiciona um produto ao carrinho
     */
    public function adicionar($id = null) {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!$id) {
            echo "<script>alert('ID do produto não informado!'); history.back();</script>";
            return;
        }

        // Buscar dados do produto
        $produto = $this->produto->buscarPorId($id);

        if (!$produto) {
            echo "<script>alert('Produto não encontrado!'); history.back();</script>";
            return;
        }

        // Adicionar ao carrinho (funciona com ou sem login)
        // Inicializar carrinho se não existir
        if (!isset($_SESSION["carrinho"])) {
            $_SESSION["carrinho"] = [];
        }

        // Verificar se o produto já está no carrinho
        if (isset($_SESSION["carrinho"][$id])) {
            // Aumentar quantidade
            $_SESSION["carrinho"][$id]["qtde"]++;
        } else {
            // Adicionar novo item (usar preco; manter 'valor' por compatibilidade)
            $preco = $produto->preco ?? $produto->valor ?? 0;
            $_SESSION["carrinho"][$id] = [
                "id"       => $produto->id,
                "nome"     => $produto->nome,
                "qtde"     => 1,
                "preco"    => $preco,
                "valor"    => $preco,
                "imagem"   => $produto->imagem,
                "descricao"=> $produto->descricao ?? '',
                "categoria"=> $produto->categoria_id
            ];
        }

        // Redirecionar para o carrinho com mensagem
        echo "<script>
            Swal.fire({
                title: 'Produto Adicionado!',
                text: 'O produto foi adicionado ao seu carrinho.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                background: '#111827',
                color: '#fff'
            }).then(() => {
                location.href='index.php?param=carrinho/index';
            });
        </script>";
    }

    /**
     * Exibe a view do carrinho
     */
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Se não está logado mas tem carrinho temp no localStorage, será mostrado via JS
        // Se está logado, mostrar carrinho da sessão normalmente
        
        require_once __DIR__ . '/../views/carrinho/index.php';
    }

    /**
     * Exibe o formulário de dados (nome e email) antes de finalizar
     */
    public function dados() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require_once __DIR__ . '/../views/carrinho/dados.php';
    }

    /**
     * Exibe a página de finalização com opções de pagamento (PIX, etc)
     */
    public function finalizar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require_once __DIR__ . '/../views/carrinho/finalizar.php';
    }

    /**
     * Limpa todos os itens do carrinho
     */
    public function limpar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION["carrinho"]);
        echo "<script>location.href='index.php?param=carrinho/index';</script>";
    }

    /**
     * Remove um produto específico do carrinho
     */
    public function remover($id = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!$id) {
            die("ID do produto não informado!");
        }

        // Remove o item do carrinho
        if (isset($_SESSION["carrinho"][$id])) {
            unset($_SESSION["carrinho"][$id]);
        }

        // Redireciona de volta ao carrinho
        echo "<script>location.href='index.php?param=carrinho/index';</script>";
    }

    /**
     * Atualiza a quantidade de um item no carrinho
     */
    public function atualizar($id = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!$id) {
            die("ID do produto não informado!");
        }

        $quantidade = $_POST['quantidade'] ?? null;

        if (!is_numeric($quantidade) || $quantidade < 1) {
            die("Quantidade inválida!");
        }

        if (isset($_SESSION["carrinho"][$id])) {
            $_SESSION["carrinho"][$id]["qtde"] = (int)$quantidade;
        }

        echo "<script>location.href='index.php?param=carrinho/index';</script>";
    }

    /**
     * Retorna o total do carrinho
     */
    public function getTotal() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $total = 0;
        $itens = $_SESSION["carrinho"] ?? [];

        foreach ($itens as $item) {
            $unit = $item['preco'] ?? $item['valor'] ?? 0;
            $total += $unit * $item['qtde'];
        }

        return $total;
    }

    /**
     * Retorna a quantidade de itens no carrinho
     */
    public function getQuantidade() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $quantidade = 0;
        $itens = $_SESSION["carrinho"] ?? [];

        foreach ($itens as $item) {
            $quantidade += $item['qtde'];
        }

        return $quantidade;
    }

    /**
     * Retorna o carrinho em formato JSON (para AJAX)
     */
    public function obter() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');
        
        $itens = $_SESSION["carrinho"] ?? [];
        $total = $this->getTotal();
        $quantidade = $this->getQuantidade();

        echo json_encode([
            'itens' => $itens,
            'total' => $total,
            'quantidade' => $quantidade,
            'vazio' => empty($itens)
        ]);
    }
    
}
?>
