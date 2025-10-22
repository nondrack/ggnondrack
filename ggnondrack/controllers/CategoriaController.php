<?php
    require "../config/Conexao.php";
    require "../models/Categoria.php";

    class CategoriaController {

        private $categoria;

        public function __construct()
        {
            $conexao = new Conexao();
            $pdo = $conexao->conectar();
            $this->categoria = new Categoria($pdo);
        }

        public function index($id) {
            //editar e adicionar uma categoria
            require "../views/categoria/index.php";
        }

        public function listar() {
            //listagem de categorias
            require "../views/categoria/listar.php";

        }

        public function excluir($id) {
            //excluir um registro
            $dados = $this->categoria->excluir($id);

            if ($dados == 1) {
                echo "<script>mensagem('Registro excluído','ok','categoria/listar')</script>";
                exit;
            } else {
                echo "<script>mensagem('Erro ao excluir','error','')</script>";
                exit;
            }
        }

        public function salvar() {
            //salvar um registro
            $nome = trim($_POST["nome"] ?? NULL);
            $ativo = trim($_POST["ativo"] ?? NULL);

            if (empty($nome)) {
                echo "<script>mensagem('Preencha o campo nome','error','');</script>";
                exit;
            } else if (empty($ativo)) {
                echo "<script>mensagem('Selecione o ativo','error','');</script>";
                exit;
            }

            $this->categoria->salvar($_POST);
        }
    }