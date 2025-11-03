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

        public function index($id = null) {
    $dadosCategoria = null;

    if ($id) {
        $dadosCategoria = $this->categoria->getDados($id);
    }

    require "../views/categoria/index.php"; // formulário
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
        public function editar($id) {
    $dadosCategoria = $this->categoria->getDados($id); // pega dados pelo ID
    require "../views/categoria/index.php"; // carrega o formulário preenchido
}
public function alterar($id, $acao) {
        if (!isset($_SESSION['carrinho'][$id])) {
            header("Location: ../carrinho/index");
            exit;
        }

        if ($acao === 'aumentar') {
            $_SESSION['carrinho'][$id]['quantidade']++;
        } elseif ($acao === 'diminuir') {
            $_SESSION['carrinho'][$id]['quantidade']--;
            if ($_SESSION['carrinho'][$id]['quantidade'] <= 0) {
                unset($_SESSION['carrinho'][$id]);
            }
        }

        header("Location: ../carrinho/index");
    }


}