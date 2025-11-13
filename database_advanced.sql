-- ============================================================
-- BANCO DE DADOS AVANÇADO - MELHORIAS PARA ATENDER RUBRICA
-- ============================================================

-- 1. CRIAÇÃO DA TABELA DE AUDITORIA
CREATE TABLE IF NOT EXISTS auditoria_produto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    campo_alterado VARCHAR(50) NOT NULL,
    valor_antigo TEXT,
    valor_novo TEXT,
    usuario_id INT DEFAULT NULL,
    data_alteracao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_produto_id (produto_id),
    INDEX idx_data_alteracao (data_alteracao)
);

-- 2. TRIGGER PARA AUDITORIA DE ALTERAÇÃO DE PREÇOS
DELIMITER $$
CREATE TRIGGER audit_produto_preco 
BEFORE UPDATE ON produto 
FOR EACH ROW 
BEGIN
    -- Auditar alteração de preço
    IF OLD.preco != NEW.preco THEN
        INSERT INTO auditoria_produto (produto_id, campo_alterado, valor_antigo, valor_novo, usuario_id)
        VALUES (NEW.id, 'preco', OLD.preco, NEW.preco, @current_user_id);
    END IF;
    
    -- Auditar alteração de estoque
    IF OLD.estoque != NEW.estoque THEN
        INSERT INTO auditoria_produto (produto_id, campo_alterado, valor_antigo, valor_novo, usuario_id)
        VALUES (NEW.id, 'estoque', OLD.estoque, NEW.estoque, @current_user_id);
    END IF;
    
    -- Auditar alteração de status
    IF OLD.ativo != NEW.ativo THEN
        INSERT INTO auditoria_produto (produto_id, campo_alterado, valor_antigo, valor_novo, usuario_id)
        VALUES (NEW.id, 'ativo', OLD.ativo, NEW.ativo, @current_user_id);
    END IF;
END$$
DELIMITER ;

-- 3. PROCEDURE PARA INSERÇÃO MASSIVA DE PRODUTOS
DELIMITER $$
CREATE PROCEDURE inserir_produtos_lote(
    IN p_categoria_id INT,
    IN p_nome_base VARCHAR(100),
    IN p_preco_base DECIMAL(10,2),
    IN p_quantidade INT
)
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE nome_produto VARCHAR(150);
    DECLARE preco_produto DECIMAL(10,2);
    DECLARE estoque_produto INT;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    WHILE i <= p_quantidade DO
        SET nome_produto = CONCAT(p_nome_base, ' - Modelo ', LPAD(i, 3, '0'));
        SET preco_produto = p_preco_base + (RAND() * p_preco_base * 0.3); -- Variação de até 30%
        SET estoque_produto = FLOOR(10 + (RAND() * 50)); -- Estoque entre 10 e 60
        
        INSERT INTO produto (nome, descricao, categoria_id, preco, estoque, imagem, ativo, data_cadastro)
        VALUES (
            nome_produto,
            CONCAT('Descrição automática para ', nome_produto, '. Produto de alta qualidade com excelente custo-benefício.'),
            p_categoria_id,
            ROUND(preco_produto, 2),
            estoque_produto,
            CONCAT('produto_', LOWER(REPLACE(p_nome_base, ' ', '_')), '_', i, '.jpg'),
            'S',
            NOW()
        );
        
        SET i = i + 1;
    END WHILE;
    
    COMMIT;
    
    SELECT CONCAT('Inseridos ', p_quantidade, ' produtos com sucesso!') AS resultado;
END$$
DELIMITER ;

-- 4. FUNÇÃO PARA VERIFICAR DISPONIBILIDADE DE ESTOQUE
DELIMITER $$
CREATE FUNCTION verificar_estoque(p_produto_id INT, p_quantidade INT)
RETURNS VARCHAR(100)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE estoque_atual INT;
    DECLARE produto_ativo CHAR(1);
    DECLARE resultado VARCHAR(100);
    
    -- Buscar estoque e status do produto
    SELECT estoque, ativo INTO estoque_atual, produto_ativo
    FROM produto 
    WHERE id = p_produto_id;
    
    -- Verificações
    IF produto_ativo IS NULL THEN
        SET resultado = 'ERRO: Produto não encontrado';
    ELSEIF produto_ativo = 'N' THEN
        SET resultado = 'ERRO: Produto inativo';
    ELSEIF estoque_atual < p_quantidade THEN
        SET resultado = CONCAT('INSUFICIENTE: Disponível apenas ', estoque_atual, ' unidades');
    ELSE
        SET resultado = 'DISPONIVEL: Estoque suficiente';
    END IF;
    
    RETURN resultado;
END$$
DELIMITER ;

-- 5. ÍNDICES PARA OTIMIZAÇÃO DE CONSULTAS
-- Índice composto para busca por categoria ativa
CREATE INDEX idx_produto_categoria_ativo ON produto(categoria_id, ativo);

-- Índice para ordenação por preço
CREATE INDEX idx_produto_preco ON produto(preco);

-- Índice para busca por nome (FULLTEXT para busca textual)
ALTER TABLE produto ADD FULLTEXT(nome, descricao);

-- Índice para consultas de vendas por usuário
CREATE INDEX idx_venda_usuario_data ON venda(usuario_id, data_criacao);

-- Índice para itens de venda
CREATE INDEX idx_item_venda_produto ON item_venda(produto_id);

-- Índice para auditoria por data
CREATE INDEX idx_auditoria_data_produto ON auditoria_produto(data_alteracao, produto_id);

-- 6. VIEW PARA RELATÓRIOS AVANÇADOS
CREATE OR REPLACE VIEW vw_produtos_vendas AS
SELECT 
    p.id,
    p.nome,
    p.preco,
    p.estoque,
    COALESCE(SUM(iv.quantidade), 0) as total_vendido,
    COALESCE(SUM(iv.subtotal), 0) as receita_total,
    COUNT(DISTINCT v.id) as numero_vendas
FROM produto p
LEFT JOIN item_venda iv ON p.id = iv.produto_id
LEFT JOIN venda v ON iv.venda_id = v.id AND v.status = 'paga'
WHERE p.ativo = 'S'
GROUP BY p.id, p.nome, p.preco, p.estoque;

-- View para indicadores do dashboard
CREATE OR REPLACE VIEW vw_dashboard_indicadores AS
SELECT 
    (SELECT COUNT(*) FROM produto WHERE ativo = 'S') as total_produtos,
    (SELECT COUNT(*) FROM usuario WHERE ativo = 'S') as total_usuarios,
    (SELECT COUNT(*) FROM venda WHERE status = 'paga') as total_vendas,
    (SELECT COALESCE(SUM(iv.subtotal), 0) FROM item_venda iv 
     JOIN venda v ON iv.venda_id = v.id WHERE v.status = 'paga') as receita_total,
    (SELECT COUNT(*) FROM produto WHERE estoque < 10 AND ativo = 'S') as produtos_baixo_estoque;

-- 7. PROCEDURE PARA ATUALIZAR ESTOQUE APÓS VENDA
DELIMITER $$
CREATE PROCEDURE atualizar_estoque_venda(IN p_venda_id INT)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_produto_id INT;
    DECLARE v_quantidade INT;
    
    DECLARE cur_itens CURSOR FOR 
        SELECT produto_id, quantidade 
        FROM item_venda 
        WHERE venda_id = p_venda_id;
        
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    OPEN cur_itens;
    
    read_loop: LOOP
        FETCH cur_itens INTO v_produto_id, v_quantidade;
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        -- Atualizar estoque
        UPDATE produto 
        SET estoque = estoque - v_quantidade,
            data_atualizacao = NOW()
        WHERE id = v_produto_id AND estoque >= v_quantidade;
        
        -- Verificar se a atualização foi bem-sucedida
        IF ROW_COUNT() = 0 THEN
            SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = CONCAT('Estoque insuficiente para produto ID: ', v_produto_id);
        END IF;
        
    END LOOP;
    
    CLOSE cur_itens;
    COMMIT;
    
END$$
DELIMITER ;

-- 8. FUNCTION PARA CALCULAR DESCONTO BASEADO EM VOLUME
DELIMITER $$
CREATE FUNCTION calcular_desconto(p_quantidade INT, p_preco DECIMAL(10,2))
RETURNS DECIMAL(10,2)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE desconto_percentual DECIMAL(5,2) DEFAULT 0;
    
    -- Regras de desconto por volume
    IF p_quantidade >= 10 THEN
        SET desconto_percentual = 10.00; -- 10% para 10+ itens
    ELSEIF p_quantidade >= 5 THEN
        SET desconto_percentual = 5.00;  -- 5% para 5+ itens
    ELSEIF p_quantidade >= 3 THEN
        SET desconto_percentual = 2.50;  -- 2.5% para 3+ itens
    END IF;
    
    RETURN ROUND((p_preco * p_quantidade * desconto_percentual / 100), 2);
END$$
DELIMITER ;

-- 9. EXEMPLOS DE USO DAS NOVAS FUNCIONALIDADES

-- Inserir produtos em lote
-- CALL inserir_produtos_lote(3, 'Mouse Gamer', 150.00, 5);

-- Verificar estoque antes da venda
-- SELECT verificar_estoque(1, 2) as status_estoque;

-- Consultar produtos com baixo estoque
-- SELECT * FROM produto WHERE estoque < 10 AND ativo = 'S';

-- Ver auditoria de um produto
-- SELECT * FROM auditoria_produto WHERE produto_id = 1 ORDER BY data_alteracao DESC;

-- Calcular desconto para uma compra
-- SELECT calcular_desconto(5, 100.00) as desconto_aplicado;

-- Ver relatório de vendas por produto
-- SELECT * FROM vw_produtos_vendas ORDER BY total_vendido DESC LIMIT 10;

-- Ver indicadores do dashboard
-- SELECT * FROM vw_dashboard_indicadores;

COMMIT;