<?php
require_once "../config/Conexao.php";

class DashboardController {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function index() {
        // Verificar se usuário está logado
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION["user"])) {
            header("Location: login.php");
            exit;
        }

        // Verificar se o usuário é administrador
        if ($_SESSION["user"]["tipo"] !== "admin") {
            header("Location: index.php");
            exit;
        }

        $indicadores = $this->getIndicadores();
        $produtosMaisVendidos = $this->getProdutosMaisVendidos();
        $vendasRecentes = $this->getVendasRecentes();
        $produtosBaixoEstoque = $this->getProdutosBaixoEstoque();
        $vendasPorMes = $this->getVendasPorMes();
        $categoriasPopulares = $this->getCategoriasPopulares();

        require "../views/dashboard/index.php";
    }

    private function getIndicadores() {
        try {
            $sql = "SELECT * FROM vw_dashboard_indicadores";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            // Fallback para consultas manuais se a view não existir
            return $this->getIndicadoresManual();
        }
    }

    private function getIndicadoresManual() {
        $indicadores = new stdClass();
        
        // Total de produtos ativos
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM produto WHERE ativo = 'S'");
        $indicadores->total_produtos = $stmt->fetch(PDO::FETCH_OBJ)->count;
        
        // Total de usuários ativos
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM usuario WHERE ativo = 'S'");
        $indicadores->total_usuarios = $stmt->fetch(PDO::FETCH_OBJ)->count;
        
        // Total de vendas pagas
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM venda WHERE status = 'paga'");
        $indicadores->total_vendas = $stmt->fetch(PDO::FETCH_OBJ)->count;
        
        // Receita total
        $stmt = $this->pdo->query("
            SELECT COALESCE(SUM(iv.subtotal), 0) as total
            FROM item_venda iv 
            JOIN venda v ON iv.venda_id = v.id 
            WHERE v.status = 'paga'
        ");
        $indicadores->receita_total = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        // Produtos com baixo estoque
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM produto WHERE estoque < 10 AND ativo = 'S'");
        $indicadores->produtos_baixo_estoque = $stmt->fetch(PDO::FETCH_OBJ)->count;
        
        return $indicadores;
    }

    private function getProdutosMaisVendidos($limit = 5) {
        $sql = "
            SELECT 
                p.nome,
                p.preco,
                COALESCE(SUM(iv.quantidade), 0) as total_vendido,
                COALESCE(SUM(iv.subtotal), 0) as receita
            FROM produto p
            LEFT JOIN item_venda iv ON p.id = iv.produto_id
            LEFT JOIN venda v ON iv.venda_id = v.id AND v.status = 'paga'
            WHERE p.ativo = 'S'
            GROUP BY p.id, p.nome, p.preco
            HAVING total_vendido > 0
            ORDER BY total_vendido DESC
            LIMIT :limit
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    private function getVendasRecentes($limit = 10) {
        $sql = "
            SELECT 
                v.id,
                u.nome as cliente,
                v.data_criacao,
                v.status,
                COALESCE(SUM(iv.subtotal), 0) as valor_total
            FROM venda v
            LEFT JOIN usuario u ON v.usuario_id = u.id
            LEFT JOIN item_venda iv ON v.id = iv.venda_id
            GROUP BY v.id, u.nome, v.data_criacao, v.status
            ORDER BY v.data_criacao DESC
            LIMIT :limit
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    private function getProdutosBaixoEstoque($limit = 10) {
        $sql = "
            SELECT nome, estoque, preco
            FROM produto 
            WHERE estoque < 10 AND ativo = 'S'
            ORDER BY estoque ASC
            LIMIT :limit
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    private function getVendasPorMes() {
        $sql = "
            SELECT 
                DATE_FORMAT(v.data_criacao, '%Y-%m') as mes,
                COUNT(*) as total_vendas,
                COALESCE(SUM(iv.subtotal), 0) as receita
            FROM venda v
            LEFT JOIN item_venda iv ON v.id = iv.venda_id
            WHERE v.status = 'paga' 
            AND v.data_criacao >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(v.data_criacao, '%Y-%m')
            ORDER BY mes DESC
            LIMIT 12
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    private function getCategoriasPopulares() {
        $sql = "
            SELECT 
                c.nome,
                COUNT(DISTINCT p.id) as total_produtos,
                COALESCE(SUM(iv.quantidade), 0) as total_vendido
            FROM categoria c
            LEFT JOIN produto p ON c.id = p.categoria_id AND p.ativo = 'S'
            LEFT JOIN item_venda iv ON p.id = iv.produto_id
            LEFT JOIN venda v ON iv.venda_id = v.id AND v.status = 'paga'
            WHERE c.ativo = 'S'
            GROUP BY c.id, c.nome
            ORDER BY total_vendido DESC
            LIMIT 5
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Método para obter dados JSON para gráficos
    public function getChartData($type) {
        header('Content-Type: application/json');
        
        switch ($type) {
            case 'vendas_mes':
                echo json_encode($this->getVendasPorMes());
                break;
            case 'categorias':
                echo json_encode($this->getCategoriasPopulares());
                break;
            case 'produtos_vendidos':
                echo json_encode($this->getProdutosMaisVendidos(10));
                break;
            default:
                echo json_encode(['error' => 'Tipo de dados não encontrado']);
        }
        exit;
    }
}