-- Inserts de exemplo para produtos da categoria Periféricos
-- Compatível com o schema atual (colunas: nome, descricao, categoria_id, preco, estoque, imagem, ativo)
-- Assumindo que categoria_id = 3 para Periféricos. Ajuste se necessário.

INSERT INTO `produto` (`nome`, `descricao`, `categoria_id`, `preco`, `estoque`, `imagem`, `ativo`) VALUES
('Mouse Gamer Logitech G502 HERO', 'Mouse gamer com sensor HERO 25K, 11 botões programáveis e peso ajustável.', 3, 349.90, 50, 'mouse_logitech_g502.jpg', 'S'),
('Teclado Mecânico Redragon Kumara RGB', 'Teclado mecânico compacto 87-keys, switches Outemu Red, retroiluminação RGB e estrutura em metal.', 3, 239.90, 40, 'teclado_redragon_kumara.jpg', 'S'),
('Headset Gamer HyperX Cloud II', 'Headset com som surround 7.1 virtual, microfone removível e almofadas confortáveis.', 3, 399.00, 30, 'headset_hyperx_cloud2.jpg', 'S'),
('Webcam Full HD 1080p Logitech C920', 'Webcam Full HD 1080p com auto foco, microfone stereo e correção automática de luz.', 3, 299.00, 25, 'webcam_logitech_c920.jpg', 'S'),
('Mousepad Speed XL 900x400mm', 'Mousepad grande para jogos, superfície speed têxtil e base anti-deslizante em borracha.', 3, 79.90, 100, 'mousepad_xl.jpg', 'S'),
('Hub USB 3.0 4 portas com alimentação', 'Hub USB 3.0 de 4 portas com entrada de energia opcional para conexões estáveis.', 3, 119.90, 60, 'hub_usb_4p.jpg', 'S'),
('Teclado Membrana Gamer Redragon Kumara K552', 'Teclado compacto com iluminação LED, teclas anti-ghosting e estrutura resistente.', 3, 149.90, 45, 'teclado_k552.jpg', 'S'),
('Headset Wireless Bluetooth JBL Quantum 800', 'Headset sem fio com som imersivo, cancelamento de ruído e microfone com mute.', 3, 599.90, 20, 'headset_jbl_quantum800.jpg', 'S'),
('Mouse Vertical Ergonômico USB', 'Mouse vertical ergonômico projetado para reduzir tensão no pulso, conexão USB.', 3, 129.90, 70, 'mouse_vertical.jpg', 'S'),
('Webcam 4K Ultra HD com microfone', 'Webcam 4K com campo de visão amplo e microfone integrado com redução de ruído.', 3, 599.00, 15, 'webcam_4k.jpg', 'S');
