<?php
class CarrinhoController {
    public function adicionar($id = null) {
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!$id) {
        echo "<script>alert('ID do produto não informado!'); history.back();</script>";
        return;
        }
    }



        public function index() {
            require_once __DIR__ . '/../views/carrinho/index.php';
        }

    public function limpar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION["carrinho"]);
        echo "<script>location.href='index.php?pagina=carrinho';</script>";
     }

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
        echo "<script>location.href='index.php?pagina=carrinho';</script>";
        }
    
}
?>
