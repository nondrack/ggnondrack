-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 22/09/2025 às 14:52
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `shop`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `ativo` enum('S','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoria`
--

INSERT INTO `categoria` (`id`, `nome`, `ativo`) VALUES
(1, 'Consoles', 'S'),
(2, 'Jogos', 'S'),
(3, 'Periféricos', 'S');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `ativo` enum('S','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `item`
--

CREATE TABLE `item` (
  `venda_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `qtde` int(11) NOT NULL,
  `valor` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `valor` double NOT NULL,
  `imagem` varchar(100) NOT NULL,
  `ativo` enum('S','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`id`, `nome`, `categoria_id`, `descricao`, `valor`, `imagem`, `ativo`) VALUES
(1, 'Sony PS5 - Mídia Física', 1, '<ul class=\"a-unordered-list a-vertical a-spacing-mini\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px;\"><li class=\"a-spacing-mini\" style=\"list-style: disc; overflow-wrap: break-word; margin: 0px;\"><span class=\"a-list-item\" style=\"\">PlayStation 5 Aproveite o carregamento extremamente rápido com o SSD de altíssima velocidade, uma imersão mais profunda com suporte a resposta tátil, gatilhos adaptáveis e áudio em 3D2, além de uma geração inédita de jogos incríveis para PlayStation. O PS5 Edição Digital é uma versão totalmente digital do console PS5 sem unidade de disco. Entre em sua conta da PlayStationNetwork e acesse a PlayStationStore para comprar e baixar jogos.</span></li><li class=\"a-spacing-mini\" style=\"list-style: disc; overflow-wrap: break-word; margin: 0px;\"><span class=\"a-list-item\">Na velocidade da luz domine o poder de uma CPU e GPU personalizadas e o SSD com E/S integradas que redefinem as regras do que o console PlayStation pode fazer.</span></li><li class=\"a-spacing-mini\" style=\"list-style: disc; overflow-wrap: break-word; margin: 0px;\"><span class=\"a-list-item\">Jogos impressionantes maravilhe-se com os gráficos incríveis e experimente os recursos do novo PS5. Jogue o catálogo de jogos compatíveis com o PS4.</span></li><li class=\"a-spacing-mini\" style=\"list-style: disc; overflow-wrap: break-word; margin: 0px;\"><span class=\"a-list-item\">Imersão de tirar o fôlego Descubra uma experiência de jogos mais profunda com compatibilidade com feedback tátil, gatilhos adaptáveis e tecnologia de áudio em 3D2.</span></li><li class=\"a-spacing-mini\" style=\"list-style: disc; overflow-wrap: break-word; margin: 0px;\"><span class=\"a-list-item\">1TB de armazenamento Tenha seus jogos favoritos prontos e esperando para você começar a jogar com 1TB de armazenamento SSD integrado.3</span></li><li class=\"a-spacing-mini\" style=\"list-style: disc; overflow-wrap: break-word; margin: 0px;\"><span class=\"a-list-item\">SSD extremamente rápido Aproveite mais horas jogando com uma inicialização quase instantânea dos jogos de PS5 instalados.</span></li><li class=\"a-spacing-mini\" style=\"list-style: disc; overflow-wrap: break-word; margin: 0px;\"><span class=\"a-list-item\">Inclui ASTRO’S Playroom Explore quatro mundos, cada um apresentando uma gameplay inovadora usando os recursos versáteis do controle sem fio DualSense, neste jogo incluído para todos os usuários do console PS5.</span></li><li class=\"a-spacing-mini\" style=\"list-style: disc; overflow-wrap: break-word; margin: 0px;\"><span class=\"a-list-item\" style=\"\">Retrocompatibilidade e Game Boost O console PS5 pode reproduzir mais de 4.000 jogos do PS4. Com o recurso Game Boost, você pode aproveitar as taxas de quadros mais rápidas e suaves em alguns dos melhores jogos do console PS4.</span></li></ul>', 4900.9, 'f3e01591bd53223b1c32ea222955796dps5.jpg', 'S');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `ativo` enum('S','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`, `ativo`) VALUES
(1, 'Bill Gates', 'bill@gmail.com', '$2y$10$ipBqnE9hYVLW7wrEPCoF7.ugIgKx5Fj0HTMLCciu0NEAqxmf0Qtrm', 'S'),
(2, 'Anderson Burnes', 'burnes@professorburnes.com', '$2y$10$HjGY5bRtWNU1WP4bS4VFgOPvqTga5plj/rfCiXK4yKBkXKOJUtywG', 'S');

-- --------------------------------------------------------

--
-- Estrutura para tabela `venda`
--

CREATE TABLE `venda` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`venda_id`,`produto_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `venda`
--
ALTER TABLE `venda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `venda`
--
ALTER TABLE `venda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id`),
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`venda_id`) REFERENCES `venda` (`id`);

--
-- Restrições para tabelas `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`);

--
-- Restrições para tabelas `venda`
--
ALTER TABLE `venda`
  ADD CONSTRAINT `venda_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
