-- Inserts de exemplo para processadores
-- Compatível com o schema atual (colunas: nome, descricao, categoria_id, preco, estoque, imagem, ativo)
-- Categoria usada: 9 (Processadores)

INSERT INTO `produto` (`nome`, `descricao`, `categoria_id`, `preco`, `estoque`, `imagem`, `ativo`) VALUES
('AMD Ryzen 5 5600X', 'Processador 6-core/12-threads, arquitetura Zen 3, ótimo desempenho para jogos e produtividade.', 9, 899.00, 20, 'ryzen_5_5600x.jpg', 'S'),
('AMD Ryzen 7 5800X', 'Processador 8-core/16-threads, Zen 3, excelente balanceamento entre multi-thread e single-thread.', 9, 1499.00, 12, 'ryzen_7_5800x.jpg', 'S'),
('AMD Ryzen 9 5900X', 'Processador 12-core/24-threads, alto desempenho para criação de conteúdo e jogos pesados.', 9, 2799.00, 6, 'ryzen_9_5900x.jpg', 'S'),
('Intel Core i5-12400F', 'Processador 6-core (6P)/12-threads, arquitetura Alder Lake, bom custo-benefício para gaming.', 9, 799.00, 18, 'i5_12400f.jpg', 'S'),
('Intel Core i7-12700K', 'Processador híbrido com P-cores e E-cores, alto desempenho single-thread e multi-thread.', 9, 1799.00, 10, 'i7_12700k.jpg', 'S'),
('Intel Core i9-12900K', 'Top de linha Alder Lake, excelente desempenho para jogos e produção de conteúdo.', 9, 3299.00, 4, 'i9_12900k.jpg', 'S');
