<?php
    require "../config/Conexao.php";
    require "../models/Produto.php";
    require "../models/Categoria.php";

    class ProdutoController {
        private $produto;
        private $categoria;

        public function __construct()
        {
            $conexao = new Conexao();
            $pdo = $conexao->conectar();

            $this->categoria = new Categoria($pdo);
            $this->produto = new Produto($pdo);
        }

        public function index($id) {
            //formulario de cadastro
            require "../views/produto/index.php";
        }

        public function listar() {
            require "../views/produto/listar.php";
        }

        public function salvar() {

        }

        public function excluir($id) {
            
        }
    }