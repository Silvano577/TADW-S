-- -----------------------------------------------------
-- Banco: pizzaria (script refeito)
-- Charset: utf8mb4
-- -----------------------------------------------------

DROP DATABASE IF EXISTS `pizzaria`;
CREATE DATABASE IF NOT EXISTS `pizzaria`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;
USE `pizzaria`;

-- -----------------------------------------------------
-- Tabela usuario
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuario` (
  `idusuario` INT NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(80) NOT NULL,
  `email` VARCHAR(90) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `tipo` ENUM('cliente','adm') NOT NULL DEFAULT 'cliente',
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `uq_usuario` (`usuario`),
  UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela cliente (vinculada a usuario via idusuario)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cliente` (
  `idcliente` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `data_ani` DATE NOT NULL,
  `telefone` VARCHAR(45) NOT NULL,
  `foto` VARCHAR(255) NULL DEFAULT NULL,
  `idusuario` INT NOT NULL,
  PRIMARY KEY (`idcliente`),
  INDEX `fk_cliente_usuario_idx` (`idusuario`),
  CONSTRAINT `fk_cliente_usuario`
    FOREIGN KEY (`idusuario`)
    REFERENCES `usuario` (`idusuario`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Se preferir NÃO excluir cliente quando usuario for excluído, substitua ON DELETE CASCADE por ON DELETE NO ACTION.
-- -----------------------------------------------------
-- Tabela endentrega (endereços de entrega)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `endentrega` (
  `idendentrega` INT NOT NULL AUTO_INCREMENT,
  `rua` VARCHAR(150) NOT NULL,
  `numero` VARCHAR(20) NOT NULL,
  `complemento` VARCHAR(50) NULL DEFAULT NULL,
  `bairro` VARCHAR(100) NOT NULL,
  `cliente` INT NOT NULL,
  PRIMARY KEY (`idendentrega`),
  INDEX `fk_endentrega_cliente_idx` (`cliente`),
  CONSTRAINT `fk_endentrega_cliente`
    FOREIGN KEY (`cliente`)
    REFERENCES `cliente` (`idcliente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela pagamento
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pagamento` (
  `idpagamento` INT NOT NULL AUTO_INCREMENT,
  `metodo_pagamento` ENUM('pix','cartao_debito','cartao_credito','dinheiro') NOT NULL,
  `valor` DECIMAL(10,2) NOT NULL,
  `status_pagamento` ENUM('pendente','concluido','falhado','cancelado') NOT NULL,
  `data_pagamento` DATETIME NOT NULL,
  PRIMARY KEY (`idpagamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela pedido
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pedido` (
  `idpedido` INT NOT NULL AUTO_INCREMENT,
  `cliente` INT NOT NULL,
  `idfeedback` INT NULL DEFAULT NULL,
  `valortotal` DECIMAL(10,2) NOT NULL,
  `endentrega` INT NOT NULL,
  `status` ENUM('pendente','preparando','pronto','cancelado') NOT NULL DEFAULT 'pendente',
  `idpagamento` INT NOT NULL,
  PRIMARY KEY (`idpedido`),
  INDEX `fk_pedido_cliente_idx` (`cliente`),
  INDEX `fk_pedido_endentrega_idx` (`endentrega`),
  INDEX `fk_pedido_pagamento_idx` (`idpagamento`),
  CONSTRAINT `fk_pedido_cliente`
    FOREIGN KEY (`cliente`)
    REFERENCES `cliente` (`idcliente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_pedido_endentrega`
    FOREIGN KEY (`endentrega`)
    REFERENCES `endentrega` (`idendentrega`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_pedido_pagamento`
    FOREIGN KEY (`idpagamento`)
    REFERENCES `pagamento` (`idpagamento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela feedback
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `feedback` (
  `idfeedback` INT NOT NULL AUTO_INCREMENT,
  `assunto` VARCHAR(100) NOT NULL,
  `comentario` VARCHAR(2000) NOT NULL,
  `nota` TINYINT NULL DEFAULT NULL,
  `pedido_id` INT NULL DEFAULT NULL,
  `cliente_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`idfeedback`),
  INDEX `fk_feedback_pedido_idx` (`pedido_id`),
  INDEX `fk_feedback_cliente_idx` (`cliente_id`),
  CONSTRAINT `fk_feedback_pedido`
    FOREIGN KEY (`pedido_id`)
    REFERENCES `pedido` (`idpedido`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_feedback_cliente`
    FOREIGN KEY (`cliente_id`)
    REFERENCES `cliente` (`idcliente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela produto (pizzas, bebidas, promoções)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `produto` (
  `idproduto` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NOT NULL,
  `tipo` ENUM('pizza','bebida','promocao') NOT NULL,
  `tamanho` VARCHAR(45) NULL DEFAULT NULL,
  `preco` DECIMAL(10,2) NOT NULL,
  `foto` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`idproduto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela pedido_produto (itens do pedido)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pedido_produto` (
  `idpedido` INT NOT NULL,
  `idproduto` INT NOT NULL,
  `quantidade` INT NOT NULL,
  PRIMARY KEY (`idpedido`, `idproduto`),
  INDEX `fk_pedido_produto_produto_idx` (`idproduto`),
  CONSTRAINT `fk_pedido_produto_pedido`
    FOREIGN KEY (`idpedido`)
    REFERENCES `pedido` (`idpedido`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_pedido_produto_produto`
    FOREIGN KEY (`idproduto`)
    REFERENCES `produto` (`idproduto`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela delivery
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `delivery` (
  `iddelivery` INT NOT NULL AUTO_INCREMENT,
  `pedido_id` INT NOT NULL,
  `status` ENUM('atribuido','a_caminho','entregue','falha') NOT NULL DEFAULT 'atribuido',
  `iniciado_em` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `entregue_em` DATETIME NULL DEFAULT NULL,
  UNIQUE KEY `uq_delivery_pedido` (`pedido_id`),
  PRIMARY KEY (`iddelivery`),
  CONSTRAINT `fk_delivery_pedido`
    FOREIGN KEY (`pedido_id`)
    REFERENCES `pedido` (`idpedido`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Triggers de consistência
-- -----------------------------------------------------
DELIMITER $$

-- Quando o delivery for marcado como 'entregue', setar pedido.status = 'pronto'
CREATE TRIGGER trg_delivery_entregue
AFTER UPDATE ON delivery
FOR EACH ROW
BEGIN
    IF NEW.status = 'entregue' AND OLD.status <> 'entregue' THEN
        UPDATE pedido
        SET status = 'pronto'
        WHERE idpedido = NEW.pedido_id;
    END IF;
END$$

-- Quando o pedido for cancelado, o delivery passa para 'falha'
CREATE TRIGGER trg_pedido_cancelado
AFTER UPDATE ON pedido
FOR EACH ROW
BEGIN
    IF NEW.status = 'cancelado' AND OLD.status <> 'cancelado' THEN
        UPDATE delivery
        SET status = 'falha'
        WHERE pedido_id = NEW.idpedido;
    END IF;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- Dados de exemplo (usuário admin)
-- -----------------------------------------------------
INSERT INTO `usuario` (`usuario`, `email`, `senha`, `tipo`)
VALUES ('Administrador', 'admin@pizzaria.com', '$2y$10$iEmilq/3TWnh.vKZHJ9jG.NdwVSI9CE5mi0EaeFuSnfiPBcd0JPbS', 'adm');

-- Opcional: exemplo de usuário cliente + cliente + endereço (descomente se quiser inserir exemplos)
-- INSERT INTO `usuario` (`usuario`,`email`,`senha`,`tipo`)
-- VALUES ('silvano','silvano@example.com','SENHA_HASH_AQUI','cliente');
-- SET @last_user = LAST_INSERT_ID();
-- INSERT INTO `cliente` (`nome`,`data_ani`,`telefone`,`foto`,`idusuario`)
-- VALUES ('Silvano','2000-01-01','11999999999',NULL,@last_user);
-- SET @last_cliente = LAST_INSERT_ID();
-- INSERT INTO `endentrega` (`rua`,`numero`,`complemento`,`bairro`,`cliente`)
-- VALUES ('Rua Exemplo','123','Apto 1','Bairro Exemplo', @last_cliente);

-- -----------------------------------------------------
-- Fim do script
-- -----------------------------------------------------

