<?php
// ... includes ...
require_once "../models/Venda.php"; // NOVO MODEL DE VENDA
require_once "../config/Conexao.php"; 
require_once "../models/Item.php";
require_once "../models/Produto.php";
// ...

class CarrinhoController {
    // ... propriedades ...
    private $venda; // Novo Model Venda

    public function __construct() {
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }

        $conexao = new Conexao();
        $this->pdo = $conexao->conectar();

        $this->item = new Item($this->pdo);
        $this->produto = new Produto($this->pdo);
        $this->venda = new Venda($this->pdo); // Instancia o novo Model

        // AQUI SIM: Apenas recupera o ID da sessão, mas não tenta criar a venda
        $this->venda_id = $_SESSION['venda_id'] ?? null; 
    }

    // Método auxiliar para garantir que a venda exista
    private function garantirVendaAtiva() {
        if (!$this->venda_id) {
            $cliente_id = $_SESSION['user']['id'] ?? null;
            
            if (!$cliente_id) {
                // Tratar erro: Se não houver cliente logado, redirecionar ou permitir anônimo
                $_SESSION['erro'] = "É necessário estar logado para iniciar uma compra.";
                header("Location: ../login.php"); 
                exit;
            }

            // CHAMA O MODEL PARA CRIAR A VENDA
            $this->venda_id = $this->venda->criarVenda($cliente_id); 
            $_SESSION['venda_id'] = $this->venda_id;
        }
    }

    // ...

    // Adicionar item - A ação que inicia a venda
    public function adicionar() {
        // ... (Verificação de produto e qtde) ...

        // 1. GARANTE QUE HÁ UMA VENDA ATIVA ANTES DE ADICIONAR O ITEM
        $this->garantirVendaAtiva(); 
        
        // Agora você tem $this->venda_id garantido.
        // ... (Restante da lógica) ...
    }

    // ... outros métodos ...
}