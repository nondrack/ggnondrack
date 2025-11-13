-- Inserts de exemplo para consoles
-- Compatível com o schema atual (colunas: nome, descricao, categoria_id, preco, estoque, imagem, ativo)
-- Categoria usada: 8 (Consoles)

INSERT INTO `produto` (`nome`, `descricao`, `categoria_id`, `preco`, `estoque`, `imagem`, `ativo`) VALUES
('PlayStation 5 (Edição com Leitor)', 'Console PlayStation 5 com unidade de disco Blu-ray, 825 GB SSD, GPU customizada e suporte a ray tracing.', 8, 4299.00, 5, 'ps5_disc.jpg', 'S'),
('PlayStation 5 Digital Edition', 'Console PS5 sem leitor de disco, mesma performance do modelo com leitor, ideal para jogos digitais.', 8, 3999.00, 7, 'ps5_digital.jpg', 'S'),
('Xbox Series X', 'Console Xbox Series X, CPU e GPU poderosas, SSD NVMe e retrocompatibilidade com milhares de jogos.', 8, 4199.00, 4, 'xbox_series_x.jpg', 'S'),
('Xbox Series S', 'Console Xbox Series S, versão digital e compacta com resolução alvo mais baixa, ótimo custo-benefício.', 8, 2199.00, 10, 'xbox_series_s.jpg', 'S'),
('Nintendo Switch OLED', 'Nintendo Switch com tela OLED de 7", dock com LAN e maior armazenamento interno; portátil e doméstico.', 8, 2499.00, 8, 'switch_oled.jpg', 'S'),
('Nintendo Switch (modelo padrão)', 'Versão clássica do Nintendo Switch, híbrido portátil/console doméstico, grande biblioteca de jogos.', 8, 1999.00, 12, 'switch_standard.jpg', 'S');
