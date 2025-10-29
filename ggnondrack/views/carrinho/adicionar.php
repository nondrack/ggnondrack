<?php
// Inicializa sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui o controller
require_once "../controllers/CarrinhoController.php";

// Instancia o controller
$carrinho = new CarrinhoController();

// Chama o método adicionar
$carrinho->adicionar();
