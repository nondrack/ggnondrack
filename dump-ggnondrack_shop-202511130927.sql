-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: ggnondrack_shop
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auditoria_produto`
--

DROP TABLE IF EXISTS `auditoria_produto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auditoria_produto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produto_id` int(11) NOT NULL,
  `campo_alterado` varchar(50) NOT NULL,
  `valor_antigo` text DEFAULT NULL,
  `valor_novo` text DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `data_alteracao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_produto_id` (`produto_id`),
  KEY `idx_data_alteracao` (`data_alteracao`),
  KEY `idx_auditoria_data_produto` (`data_alteracao`,`produto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria_produto`
--

LOCK TABLES `auditoria_produto` WRITE;
/*!40000 ALTER TABLE `auditoria_produto` DISABLE KEYS */;
INSERT INTO `auditoria_produto` VALUES (1,21,'preco','1699.00','1599.00',NULL,'2025-11-13 11:53:22');
/*!40000 ALTER TABLE `auditoria_produto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_ativo` (`ativo`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (3,'Periféricos',NULL,'S','2025-11-13 00:08:13'),(7,'Placa mãe',NULL,'S','2025-11-13 00:24:40'),(8,'Consoles',NULL,'S','2025-11-13 00:30:03'),(9,'Processadores',NULL,'S','2025-11-13 00:37:01');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_venda`
--

DROP TABLE IF EXISTS `item_venda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_venda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venda_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_item_venda` (`venda_id`),
  KEY `fk_item_produto` (`produto_id`),
  KEY `idx_item_venda_produto` (`produto_id`),
  CONSTRAINT `fk_item_produto` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_item_venda` FOREIGN KEY (`venda_id`) REFERENCES `venda` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_venda`
--

LOCK TABLES `item_venda` WRITE;
/*!40000 ALTER TABLE `item_venda` DISABLE KEYS */;
INSERT INTO `item_venda` VALUES (4,4,33,1,3299.00,3299.00),(5,5,32,1,1799.00,1799.00);
/*!40000 ALTER TABLE `item_venda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produto`
--

DROP TABLE IF EXISTS `produto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `categoria_id` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `estoque` int(11) NOT NULL DEFAULT 0,
  `imagem` varchar(255) DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_produto_categoria` (`categoria_id`),
  KEY `idx_ativo` (`ativo`),
  KEY `idx_produto_categoria_ativo` (`categoria_id`,`ativo`),
  KEY `idx_produto_preco` (`preco`),
  FULLTEXT KEY `nome` (`nome`,`descricao`),
  CONSTRAINT `fk_produto_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produto`
--

LOCK TABLES `produto` WRITE;
/*!40000 ALTER TABLE `produto` DISABLE KEYS */;
INSERT INTO `produto` VALUES (7,'Mouse Gamer Logitech G502 HERO','Mouse gamer com sensor HERO 25K, 11 botões programáveis e peso ajustável.',3,349.90,50,'https://m.media-amazon.com/images/I/61mpMH5TzkL._AC_SY300_SX300_QL70_ML2_.jpg','S','2025-11-13 00:15:16','2025-11-13 00:20:00'),(8,'Teclado Mecânico Redragon Kumara RGB','Teclado mecânico compacto 87-keys, switches Outemu Red, retroiluminação RGB e estrutura em metal.',3,239.90,40,'https://images0.kabum.com.br/produtos/fotos/93160/93160_1523969683_index_gg.jpg','S','2025-11-13 00:15:16','2025-11-13 00:19:39'),(9,'Headset Gamer HyperX Cloud II','Headset com som surround 7.1 virtual, microfone removível e almofadas confortáveis.',3,399.00,30,'https://m.media-amazon.com/images/I/71pz2njkNRL._AC_SY300_SX300_QL70_ML2_.jpg','S','2025-11-13 00:15:16','2025-11-13 00:19:20'),(10,'Webcam Full HD 1080p Logitech C920','Webcam Full HD 1080p com auto foco, microfone stereo e correção automática de luz.',3,299.00,25,'https://m.media-amazon.com/images/I/61SCZyiMSNL._AC_SY300_SX300_QL70_ML2_.jpg','S','2025-11-13 00:15:16','2025-11-13 00:19:00'),(11,'Mousepad Speed XL 900x400mm','Mousepad grande para jogos, superfície speed têxtil e base anti-deslizante em borracha.',3,79.90,100,'https://images3.kabum.com.br/produtos/fotos/416903/mousepad-gamer-fortrek-speed-extra-grande-900x400mm-azul-mpg-104_1673440654_gg.jpg','S','2025-11-13 00:15:16','2025-11-13 00:18:42'),(12,'Hub USB 3.0 4 portas com alimentação','Hub USB 3.0 de 4 portas com entrada de energia opcional para conexões estáveis.',3,119.90,60,'https://http2.mlstatic.com/D_NQ_NP_2X_723583-MLA95979237024_102025-F.webp','S','2025-11-13 00:15:16','2025-11-13 00:18:03'),(13,'Teclado Membrana Gamer Redragon Kumara K552','Teclado compacto com iluminação LED, teclas anti-ghosting e estrutura resistente.',3,149.90,45,'https://images0.kabum.com.br/produtos/fotos/93160/93160_1523969683_index_gg.jpg','S','2025-11-13 00:15:16','2025-11-13 00:17:43'),(14,'Headset Wireless Bluetooth JBL Quantum 800','Headset sem fio com som imersivo, cancelamento de ruído e microfone com mute.',3,599.90,20,'https://m.media-amazon.com/images/I/61NIN9D9wjL._AC_SY300_SX300_QL70_ML2_.jpg','S','2025-11-13 00:15:16','2025-11-13 00:17:13'),(15,'Mouse Vertical Ergonômico USB','Mouse vertical ergonômico projetado para reduzir tensão no pulso, conexão USB.',3,129.90,70,'https://cdn.shoppub.io/cdn-cgi/image/w=1000,h=1000,q=80,f=auto/oficinadosbits/media/uploads/produtos/foto/mduhiego/file.png','S','2025-11-13 00:15:16','2025-11-13 00:16:49'),(16,'Webcam 4K Ultra HD com microfone','Webcam 4K com campo de visão amplo e microfone integrado com redução de ruído.',3,599.00,15,'https://m.media-amazon.com/images/I/61SCZyiMSNL._AC_SY300_SX300_QL70_ML2_.jpg','S','2025-11-13 00:15:16','2025-11-13 00:16:29'),(17,'ASUS ROG Strix B550-F Gaming','Placa-mãe AM4, chipset B550, suporte a Ryzen 3ª/4ª geração, 4x DIMM DDR4, PCIe 4.0, Aura Sync RGB.',7,1299.90,10,'https://images9.kabum.com.br/produtos/fotos/264899/placa-mae-asus-rog-strix-b550-f-gaming-ii-am4-atx-ddr4-wifi-aura-sync-rgb-90mb14s0-m0eay0_1637756349_gg.jpg','S','2025-11-13 00:26:37','2025-11-13 00:28:56'),(18,'MSI MPG Z690 Carbon WiFi','Placa-mãe LGA1700, chipset Z690, suporte Intel 12ª geração, DDR5, M.2 Gen4, Wi-Fi 6E e iluminação RGB.',7,2199.00,8,'https://m.media-amazon.com/images/I/81pfrWx5gGL._AC_SY300_SX300_QL70_ML2_.jpg','S','2025-11-13 00:26:37','2025-11-13 00:28:37'),(19,'Gigabyte B760 AORUS Elite AX','Placa-mãe LGA1700, chipset B760, suporte a Intel 13ª geração, 4x DIMM DDR4, Dual M.2 e LAN 2.5Gb.',7,899.50,15,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/b/7/b760m-aorus-elite3.jpg','S','2025-11-13 00:26:37','2025-11-13 00:28:13'),(20,'ASRock X570 Phantom Gaming 4','Placa-mãe AM4, chipset X570, suporte PCIe 4.0, 2x M.2, 8 fases de alimentação e refrigeração aprimorada.',7,1149.90,6,'https://images6.kabum.com.br/produtos/fotos/sync_mirakl/321976/Placa-M-e-AsRock-X570-Phantom-Gaming-4S-AMD-AM4_1728001675_gg.jpg','S','2025-11-13 00:26:37','2025-11-13 00:27:37'),(21,'EVGA Z590 FTW WiFi','Placa-mãe LGA1200, chipset Z590, suporte a Intel 10ª/11ª geração, robusto VRM e conectividade Wi-Fi.',7,1599.00,4,'https://www.adrenaline.com.br/wp-content/uploads/2021/01/evga-placa-z590-dark.jpg','S','2025-11-13 00:26:37','2025-11-13 08:53:22'),(22,'PlayStation 5 (Edição com Leitor)','Console PlayStation 5 com unidade de disco Blu-ray, 825 GB SSD, GPU customizada e suporte a ray tracing.',8,4299.00,5,'https://m.media-amazon.com/images/I/71PeCknZMRL._AC_SX679_.jpg','S','2025-11-13 00:34:08','2025-11-13 00:36:05'),(23,'PlayStation 5 Digital Edition','Console PS5 sem leitor de disco, mesma performance do modelo com leitor, ideal para jogos digitais.',8,3999.00,7,'https://m.magazineluiza.com.br/a-static/420x420/playstation-5-edicao-digital-825gb-1-controle-branco-sony/magazineluiza/240604800/14378c66c45177f117b6efa3ba964d8a.jpg','S','2025-11-13 00:34:08','2025-11-13 00:35:42'),(24,'Xbox Series X','Console Xbox Series X, CPU e GPU poderosas, SSD NVMe e retrocompatibilidade com milhares de jogos.',8,4199.00,4,'https://m.media-amazon.com/images/I/516pVDAQMnL._AC_SX342_SY445_QL70_ML2_.jpg','S','2025-11-13 00:34:08','2025-11-13 00:35:27'),(25,'Xbox Series S','Console Xbox Series S, versão digital e compacta com resolução alvo mais baixa, ótimo custo-benefício.',8,2199.00,10,'https://m.media-amazon.com/images/I/61WjhFLFDmL._AC_SX342_SY445_QL70_ML2_.jpg','S','2025-11-13 00:34:08','2025-11-13 00:35:10'),(26,'Nintendo Switch OLED','Nintendo Switch com tela OLED de 7\", dock com LAN e maior armazenamento interno; portátil e doméstico.',8,2499.00,8,'https://m.media-amazon.com/images/I/61XXC0azO-L._AC_SX342_SY445_QL70_ML2_.jpg','S','2025-11-13 00:34:08','2025-11-13 00:34:52'),(27,'Nintendo Switch (modelo padrão)','Versão clássica do Nintendo Switch, híbrido portátil/console doméstico, grande biblioteca de jogos.',8,1999.00,12,'https://images1.kabum.com.br/produtos/fotos/385191/console-nintendo-switch-joy-con-neon-mario-kart-8-deluxe-3-meses-de-assinatura-nintendo-switch-online-azul-e-vermelho-hbdskabl2_1710513348_gg.jpg','S','2025-11-13 00:34:08','2025-11-13 00:34:33'),(28,'AMD Ryzen 5 5600X','Processador 6-core/12-threads, arquitetura Zen 3, ótimo desempenho para jogos e produtividade.',9,899.00,20,'https://m.media-amazon.com/images/I/51ld6RR8IrL._AC_SY741_.jpg','S','2025-11-13 00:37:59','2025-11-13 00:40:36'),(29,'AMD Ryzen 7 5800X','Processador 8-core/16-threads, Zen 3, excelente balanceamento entre multi-thread e single-thread.',9,1499.00,12,'https://m.media-amazon.com/images/I/51gRv8z+K6L._AC_SY300_SX300_QL70_ML2_.jpg','S','2025-11-13 00:37:59','2025-11-13 00:40:16'),(30,'AMD Ryzen 9 5900X','Processador 12-core/24-threads, alto desempenho para criação de conteúdo e jogos pesados.',9,2799.00,6,'https://images3.kabum.com.br/produtos/fotos/609953/amd-ryzen-9-5900xt-16-core_1722432051_gg.jpg','S','2025-11-13 00:37:59','2025-11-13 00:39:55'),(31,'Intel Core i5-12400F','Processador 6-core (6P)/12-threads, arquitetura Alder Lake, bom custo-benefício para gaming.',9,799.00,18,'https://images8.kabum.com.br/produtos/fotos/283718/processador-intel-core-i5-12400f-cache-xmb-xghz-xghz-max-turbo-lga-1700-bx8071512400f_1640094446_gg.jpg','S','2025-11-13 00:37:59','2025-11-13 00:39:39'),(32,'Intel Core i7-12700K','Processador híbrido com P-cores e E-cores, alto desempenho single-thread e multi-thread.',9,1799.00,10,'https://images8.kabum.com.br/produtos/fotos/241048/processador-intel-core-i7-12700k-cache-25mb-3-6ghz-5-0ghz-max-turbo-lga-1700-bx8071512700k_1634830258_gg.jpg','S','2025-11-13 00:37:59','2025-11-13 00:38:53'),(33,'Intel Core i9-12900K','Top de linha Alder Lake, excelente desempenho para jogos e produção de conteúdo.',9,3299.00,4,'https://images6.kabum.com.br/produtos/fotos/315286/processador-intel-core-i9-12900ks-cache-30mb-3-4ghz-5-5ghz-max-turbo-lga-1700-bx8071512900ks_1649103567_gg.jpg','S','2025-11-13 00:37:59','2025-11-13 00:38:22');
/*!40000 ALTER TABLE `produto` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER audit_produto_preco 
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
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('cliente','admin') NOT NULL DEFAULT 'cliente',
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_ativo` (`ativo`),
  KEY `idx_tipo` (`tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (4,'lucas fernando','luckasfernando500@gmail.com','$2y$10$6O0fxiVO9kxQlVhZGOVDgeMW96cn8M7wi85U8JKx6f8jpA3Sdq08e','cliente','S','2025-11-13 00:05:05',NULL);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venda`
--

DROP TABLE IF EXISTS `venda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `venda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `status` enum('aberta','aguardando_pagamento','paga','enviada','entregue','cancelada') NOT NULL DEFAULT 'aberta',
  `metodo_pagamento` varchar(50) DEFAULT NULL COMMENT 'pix, mercadopago, cartao, boleto',
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `txid` varchar(100) DEFAULT NULL COMMENT 'ID da transação de pagamento',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `data_pagamento` datetime DEFAULT NULL,
  `data_envio` datetime DEFAULT NULL,
  `data_entrega` datetime DEFAULT NULL,
  `data_cancelamento` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_venda_usuario` (`usuario_id`),
  KEY `idx_status` (`status`),
  KEY `idx_data_criacao` (`data_criacao`),
  KEY `idx_venda_usuario_data` (`usuario_id`,`data_criacao`),
  CONSTRAINT `fk_venda_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venda`
--

LOCK TABLES `venda` WRITE;
/*!40000 ALTER TABLE `venda` DISABLE KEYS */;
INSERT INTO `venda` VALUES (4,4,'aguardando_pagamento','pix',0.00,'V4','2025-11-13 08:51:04',NULL,NULL,NULL,NULL),(5,4,'aguardando_pagamento','pix',0.00,'V5','2025-11-13 08:55:58',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `venda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vw_dashboard_indicadores`
--

DROP TABLE IF EXISTS `vw_dashboard_indicadores`;
/*!50001 DROP VIEW IF EXISTS `vw_dashboard_indicadores`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_dashboard_indicadores` AS SELECT 
 1 AS `total_produtos`,
 1 AS `total_usuarios`,
 1 AS `total_vendas`,
 1 AS `receita_total`,
 1 AS `produtos_baixo_estoque`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_vendas`
--

DROP TABLE IF EXISTS `vw_produtos_vendas`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_vendas`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_vendas` AS SELECT 
 1 AS `id`,
 1 AS `nome`,
 1 AS `preco`,
 1 AS `estoque`,
 1 AS `total_vendido`,
 1 AS `receita_total`,
 1 AS `numero_vendas`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'ggnondrack_shop'
--
/*!50003 DROP FUNCTION IF EXISTS `calcular_desconto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `calcular_desconto`(p_quantidade INT, p_preco DECIMAL(10,2)) RETURNS decimal(10,2)
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
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `verificar_estoque` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `verificar_estoque`(p_produto_id INT, p_quantidade INT) RETURNS varchar(100) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci
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
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `inserir_produtos_lote` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `inserir_produtos_lote`(
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
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `vw_dashboard_indicadores`
--

/*!50001 DROP VIEW IF EXISTS `vw_dashboard_indicadores`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_dashboard_indicadores` AS select (select count(0) from `produto` where `produto`.`ativo` = 'S') AS `total_produtos`,(select count(0) from `usuario` where `usuario`.`ativo` = 'S') AS `total_usuarios`,(select count(0) from `venda` where `venda`.`status` = 'paga') AS `total_vendas`,(select coalesce(sum(`iv`.`subtotal`),0) from (`item_venda` `iv` join `venda` `v` on(`iv`.`venda_id` = `v`.`id`)) where `v`.`status` = 'paga') AS `receita_total`,(select count(0) from `produto` where `produto`.`estoque` < 10 and `produto`.`ativo` = 'S') AS `produtos_baixo_estoque` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_produtos_vendas`
--

/*!50001 DROP VIEW IF EXISTS `vw_produtos_vendas`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_produtos_vendas` AS select `p`.`id` AS `id`,`p`.`nome` AS `nome`,`p`.`preco` AS `preco`,`p`.`estoque` AS `estoque`,coalesce(sum(`iv`.`quantidade`),0) AS `total_vendido`,coalesce(sum(`iv`.`subtotal`),0) AS `receita_total`,count(distinct `v`.`id`) AS `numero_vendas` from ((`produto` `p` left join `item_venda` `iv` on(`p`.`id` = `iv`.`produto_id`)) left join `venda` `v` on(`iv`.`venda_id` = `v`.`id` and `v`.`status` = 'paga')) where `p`.`ativo` = 'S' group by `p`.`id`,`p`.`nome`,`p`.`preco`,`p`.`estoque` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-13  9:27:30
