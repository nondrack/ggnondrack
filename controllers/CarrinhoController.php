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

        // Verificar estoque disponível
        $estoqueDisponivel = (int)$produto->estoque;
        
        // Verificar se o produto já está no carrinho
        if (isset($_SESSION["carrinho"][$id])) {
            // Verificar se pode aumentar quantidade
            $qtdeAtual = $_SESSION["carrinho"][$id]["qtde"];
            
            if ($qtdeAtual >= $estoqueDisponivel) {
                echo "<script>
                    Swal.fire({
                        title: 'Estoque Insuficiente!',
                        text: 'Você já tem a quantidade máxima disponível deste produto no carrinho (" . $estoqueDisponivel . " unidades).',
                        icon: 'warning',
                        confirmButtonColor: '#ffc107',
                        background: '#111827',
                        color: '#fff'
                    }).then(() => {
                        history.back();
                    });
                </script>";
                return;
            }
            
            // Aumentar quantidade
            $_SESSION["carrinho"][$id]["qtde"]++;
            $_SESSION["carrinho"][$id]["estoque"] = $estoqueDisponivel;
        } else {
            // Verificar se tem estoque disponível
            if ($estoqueDisponivel < 1) {
                echo "<script>
                    Swal.fire({
                        title: 'Produto Indisponível!',
                        text: 'Este produto está sem estoque no momento.',
                        icon: 'error',
                        confirmButtonColor: '#dc3545',
                        background: '#111827',
                        color: '#fff'
                    }).then(() => {
                        history.back();
                    });
                </script>";
                return;
            }
            
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
                "categoria"=> $produto->categoria_id,
                "estoque"  => $estoqueDisponivel
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
            // Buscar estoque atual do produto
            $produto = $this->produto->buscarPorId($id);
            $estoqueDisponivel = (int)($produto->estoque ?? 0);
            
            // Verificar se a quantidade solicitada está disponível
            if ($quantidade > $estoqueDisponivel) {
                echo "<script>
                    Swal.fire({
                        title: 'Estoque Insuficiente!',
                        text: 'Disponível apenas " . $estoqueDisponivel . " unidade(s) deste produto.',
                        icon: 'warning',
                        confirmButtonColor: '#ffc107',
                        background: '#111827',
                        color: '#fff'
                    }).then(() => {
                        location.href='index.php?param=carrinho/index';
                    });
                </script>";
                return;
            }
            
            $_SESSION["carrinho"][$id]["qtde"] = (int)$quantidade;
            $_SESSION["carrinho"][$id]["estoque"] = $estoqueDisponivel;
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
