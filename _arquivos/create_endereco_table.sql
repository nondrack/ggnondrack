-- Criar tabela de endereços
-- Relaciona com usuario e/ou venda (modelo híbrido)
-- Permite armazenar histórico de entrega por venda e endereços reutilizáveis por usuário

CREATE TABLE endereco (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT(11) NULL,
  venda_id INT(11) NULL,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL,
  cep VARCHAR(9) NOT NULL,
  logradouro VARCHAR(200) NOT NULL,
  numero VARCHAR(20) NOT NULL,
  bairro VARCHAR(120) NOT NULL,
  cidade VARCHAR(120) NOT NULL,
  uf CHAR(2) NOT NULL,
  complemento VARCHAR(150) NULL,
  telefone VARCHAR(20) NULL,
  observacoes TEXT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  
  -- Índices para melhor performance
  INDEX idx_usuario (usuario_id),
  INDEX idx_venda (venda_id),
  INDEX idx_cep (cep),
  INDEX idx_uf (uf),
  
  -- Foreign keys com tipo correto (INT, não UNSIGNED)
  CONSTRAINT fk_endereco_usuario 
    FOREIGN KEY (usuario_id) 
    REFERENCES usuario(id) 
    ON DELETE SET NULL 
    ON UPDATE CASCADE,
    
  CONSTRAINT fk_endereco_venda 
    FOREIGN KEY (venda_id) 
    REFERENCES venda(id) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Exemplo de inserção após criar venda:
-- INSERT INTO endereco (usuario_id, venda_id, nome, email, cep, logradouro, numero, bairro, cidade, uf, complemento, telefone, observacoes)
-- VALUES (4, 1, 'João Silva', 'joao@example.com', '01001-000', 'Praça da Sé', '100', 'Sé', 'São Paulo', 'SP', 'Apto 12', '(11)98888-7777', 'Entregar após 18h');
