-- ========================================
-- DATABASE: ggnondrack_shop
-- Estrutura otimizada e organizada
-- Data: 12 de novembro de 2025
-- ========================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Criar banco de dados
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `ggnondrack_shop` 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `ggnondrack_shop`;

-- --------------------------------------------------------
-- Tabela: usuario
-- Usuários do sistema (clientes e administradores)
-- --------------------------------------------------------

CREATE TABLE `usuario` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `tipo` ENUM('cliente', 'admin') NOT NULL DEFAULT 'cliente',
  `ativo` ENUM('S', 'N') NOT NULL DEFAULT 'S',
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_ativo` (`ativo`),
  KEY `idx_tipo` (`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabela: categoria
-- Categorias de produtos
-- --------------------------------------------------------

CREATE TABLE `categoria` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `descricao` TEXT NULL,
  `ativo` ENUM('S', 'N') NOT NULL DEFAULT 'S',
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ativo` (`ativo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabela: produto
-- Produtos do e-commerce
-- --------------------------------------------------------

CREATE TABLE `produto` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `descricao` TEXT NULL,
  `categoria_id` INT(11) NOT NULL,
  `preco` DECIMAL(10, 2) NOT NULL,
  `estoque` INT(11) NOT NULL DEFAULT 0,
  `imagem` VARCHAR(255) NULL,
  `ativo` ENUM('S', 'N') NOT NULL DEFAULT 'S',
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_produto_categoria` (`categoria_id`),
  KEY `idx_ativo` (`ativo`),
  CONSTRAINT `fk_produto_categoria` FOREIGN KEY (`categoria_id`) 
    REFERENCES `categoria` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabela: venda
-- Vendas/Pedidos realizados
-- --------------------------------------------------------

CREATE TABLE `venda` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` INT(11) NOT NULL,
  `status` ENUM('aberta', 'aguardando_pagamento', 'paga', 'enviada', 'entregue', 'cancelada') NOT NULL DEFAULT 'aberta',
  `metodo_pagamento` VARCHAR(50) NULL COMMENT 'pix, mercadopago, cartao, boleto',
  `valor_total` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `txid` VARCHAR(100) NULL COMMENT 'ID da transação de pagamento',
  `data_criacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_pagamento` DATETIME NULL,
  `data_envio` DATETIME NULL,
  `data_entrega` DATETIME NULL,
  `data_cancelamento` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `fk_venda_usuario` (`usuario_id`),
  KEY `idx_status` (`status`),
  KEY `idx_data_criacao` (`data_criacao`),
  CONSTRAINT `fk_venda_usuario` FOREIGN KEY (`usuario_id`) 
    REFERENCES `usuario` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabela: item_venda
-- Itens de cada venda
-- --------------------------------------------------------

CREATE TABLE `item_venda` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `venda_id` INT(11) NOT NULL,
  `produto_id` INT(11) NOT NULL,
  `quantidade` INT(11) NOT NULL,
  `preco_unitario` DECIMAL(10, 2) NOT NULL,
  `subtotal` DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_item_venda` (`venda_id`),
  KEY `fk_item_produto` (`produto_id`),
  CONSTRAINT `fk_item_venda` FOREIGN KEY (`venda_id`) 
    REFERENCES `venda` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_item_produto` FOREIGN KEY (`produto_id`) 
    REFERENCES `produto` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
  -- RESTRICT impede exclusão de produtos em vendas (correto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dados iniciais: Usuários
-- --------------------------------------------------------

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`, `tipo`, `ativo`) VALUES
(1, 'Administrador', 'admin@ggnondrack.com', '$2y$10$ipBqnE9hYVLW7wrEPCoF7.ugIgKx5Fj0HTMLCciu0NEAqxmf0Qtrm', 'admin', 'S'),
(2, 'Bill Gates', 'bill@gmail.com', '$2y$10$ipBqnE9hYVLW7wrEPCoF7.ugIgKx5Fj0HTMLCciu0NEAqxmf0Qtrm', 'cliente', 'S'),
(3, 'Anderson Burnes', 'burnes@professorburnes.com', '$2y$10$HjGY5bRtWNU1WP4bS4VFgOPvqTga5plj/rfCiXK4yKBkXKOJUtywG', 'cliente', 'S');

-- --------------------------------------------------------
-- Dados iniciais: Categorias
-- --------------------------------------------------------

INSERT INTO `categoria` (`id`, `nome`, `descricao`, `ativo`) VALUES
(1, 'Eletrônicos', 'Produtos eletrônicos e tecnologia', 'S'),
(2, 'Informática', 'Computadores, notebooks e acessórios', 'S'),
(3, 'Games', 'Consoles, jogos e acessórios', 'S'),
(4, 'Smartphones', 'Celulares e tablets', 'S'),
(5, 'Periféricos', 'Mouse, teclado, headset e mais', 'S');

-- --------------------------------------------------------
-- Dados iniciais: Produtos (exemplo)
-- --------------------------------------------------------

INSERT INTO `produto` (`id`, `nome`, `descricao`, `categoria_id`, `preco`, `estoque`, `ativo`) VALUES
(1, 'Notebook Dell Inspiron', 'Notebook Dell Core i5, 8GB RAM, 256GB SSD', 2, 2999.90, 10, 'S'),
(2, 'Mouse Gamer Logitech', 'Mouse Gamer RGB 12000 DPI', 5, 199.90, 25, 'S'),
(3, 'Teclado Mecânico RGB', 'Teclado Mecânico Switch Blue RGB', 5, 349.90, 15, 'S'),
(4, 'PlayStation 5', 'Console PlayStation 5 + 1 Controle', 3, 4499.00, 5, 'S'),
(5, 'iPhone 15 Pro', 'Apple iPhone 15 Pro 256GB', 4, 7999.00, 8, 'S');

-- --------------------------------------------------------
-- Configurações AUTO_INCREMENT
-- --------------------------------------------------------

ALTER TABLE `usuario` AUTO_INCREMENT = 4;
ALTER TABLE `categoria` AUTO_INCREMENT = 6;
ALTER TABLE `produto` AUTO_INCREMENT = 6;
ALTER TABLE `venda` AUTO_INCREMENT = 1;
ALTER TABLE `item_venda` AUTO_INCREMENT = 1;

COMMIT;

-- ========================================
-- Melhorias implementadas:
-- ========================================
-- 1. Unificação: Eliminada duplicação usuario/cliente
-- 2. Campo 'tipo': Diferencia clientes de administradores
-- 3. Timestamps: data_cadastro, data_atualizacao automáticas
-- 4. Status expandido: Mais controle sobre vendas
-- 5. item_venda: Tabela renomeada de 'item' para maior clareza
-- 6. Índices: Adicionados para melhor performance
-- 7. Constraints: FKs com nomes descritivos
-- 8. Decimal: Uso correto para valores monetários
-- 9. Comentários: Documentação inline
-- 10. Charset: utf8mb4_unicode_ci para suporte completo a Unicode
-- ========================================
