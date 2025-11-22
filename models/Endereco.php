<?php

class Endereco {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Salvar endereço vinculado a uma venda
     * 
     * @param int $vendaId ID da venda
     * @param array $dados Dados do endereço (nome, email, cep, logradouro, numero, bairro, cidade, uf, complemento, telefone, observacoes)
     * @param int|null $usuarioId ID do usuário (opcional)
     * @return int|false ID do endereço criado ou false em caso de erro
     */
    public function salvarParaVenda($vendaId, $dados, $usuarioId = null) {
        try {
            // Log rápido se CEP estiver vazio (auxilia debug)
            if (empty($dados['cep'])) {
                error_log('[ENDERECO] CEP vazio ao salvar venda ' . $vendaId . ' - dados: ' . json_encode($dados));
            }
            $sql = "INSERT INTO endereco (
                usuario_id, venda_id, nome, email, cep, logradouro, numero,
                bairro, cidade, uf, complemento, telefone, observacoes
            ) VALUES (
                :usuario_id, :venda_id, :nome, :email, :cep, :logradouro, :numero,
                :bairro, :cidade, :uf, :complemento, :telefone, :observacoes
            )";

            $stmt = $this->pdo->prepare($sql);
            
            $params = [
                ':usuario_id' => $usuarioId,
                ':venda_id' => $vendaId,
                ':nome' => $dados['nome'] ?? '',
                ':email' => $dados['email'] ?? '',
                ':cep' => $dados['cep'] ?? '',
                ':logradouro' => $dados['endereco'] ?? $dados['logradouro'] ?? '',
                ':numero' => $dados['numero'] ?? '',
                ':bairro' => $dados['bairro'] ?? '',
                ':cidade' => $dados['cidade'] ?? '',
                ':uf' => $dados['estado'] ?? $dados['uf'] ?? '',
                ':complemento' => $dados['complemento'] ?? null,
                ':telefone' => $dados['telefone'] ?? null,
                ':observacoes' => $dados['observacoes'] ?? null
            ];

            $stmt->execute($params);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao salvar endereço: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Buscar endereços de uma venda
     * 
     * @param int $vendaId
     * @return array|null
     */
    public function buscarPorVenda($vendaId) {
        $sql = "SELECT * FROM endereco WHERE venda_id = :venda_id ORDER BY criado_em DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':venda_id' => $vendaId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar endereços de um usuário
     * 
     * @param int $usuarioId
     * @return array
     */
    public function buscarPorUsuario($usuarioId) {
        $sql = "SELECT * FROM endereco WHERE usuario_id = :usuario_id ORDER BY criado_em DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar último endereço usado por um usuário
     * 
     * @param int $usuarioId
     * @return array|null
     */
    public function buscarUltimoPorUsuario($usuarioId) {
        $sql = "SELECT * FROM endereco WHERE usuario_id = :usuario_id ORDER BY criado_em DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
