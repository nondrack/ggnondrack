-- Inserts de exemplo para 5 placas-mãe
-- Compatível com o schema atual (colunas: nome, descricao, categoria_id, preco, estoque, imagem, ativo)
-- Ajuste `categoria_id` conforme sua base (por exemplo, 1 = Consoles, 2 = Jogos, 3 = Periféricos)

INSERT INTO `produto` (`nome`, `descricao`, `categoria_id`, `preco`, `estoque`, `imagem`, `ativo`) VALUES
('ASUS ROG Strix B550-F Gaming', 'Placa-mãe AM4, chipset B550, suporte a Ryzen 3ª/4ª geração, 4x DIMM DDR4, PCIe 4.0, Aura Sync RGB.', 7, 1299.90, 10, 'asus_rog_b550f.jpg', 'S'),
('MSI MPG Z690 Carbon WiFi', 'Placa-mãe LGA1700, chipset Z690, suporte Intel 12ª geração, DDR5, M.2 Gen4, Wi-Fi 6E e iluminação RGB.', 7, 2199.00, 8, 'msi_mpg_z690.jpg', 'S'),
('Gigabyte B760 AORUS Elite AX', 'Placa-mãe LGA1700, chipset B760, suporte a Intel 13ª geração, 4x DIMM DDR4, Dual M.2 e LAN 2.5Gb.', 7, 899.50, 15, 'gigabyte_b760_aorus.jpg', 'S'),
('ASRock X570 Phantom Gaming 4', 'Placa-mãe AM4, chipset X570, suporte PCIe 4.0, 2x M.2, 8 fases de alimentação e refrigeração aprimorada.', 7, 1149.90, 6, 'asrock_x570_phantom.jpg', 'S'),
('EVGA Z590 FTW WiFi', 'Placa-mãe LGA1200, chipset Z590, suporte a Intel 10ª/11ª geração, robusto VRM e conectividade Wi-Fi.', 7, 1699.00, 4, 'evga_z590_ftw.jpg', 'S');
