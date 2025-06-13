USE pizzaria;

INSERT INTO cliente (nome, data_ani, endereco, telefone) VALUES
('João Silva', '1990-05-10', 'Rua A, 123', '11999999999'),
('Maria Oliveira', '1985-08-20', 'Av. B, 456', '11988888888'),
('Pedro Santos', '1992-12-05', 'Rua C, 789', '11977777777'),
('Ana Paula', '1988-03-15', 'Av. D, 321', '11966666666'),
('Lucas Costa', '1995-11-30', 'Rua E, 654', '11955555555');



INSERT INTO delivery (endereco_entrega, tempo_entrega) VALUES
('Rua A, 123', '30 minutos'),
('Av. B, 456', '45 minutos'),
('Rua C, 789', '25 minutos'),
('Av. D, 321', '40 minutos'),
('Rua E, 654', '35 minutos');

INSERT INTO feedback (assunto, comentario) VALUES
('Qualidade', 'A pizza estava ótima e chegou rápido.'),
('Entrega', 'Entrega demorou um pouco, mas tudo certo.'),
('Atendimento', 'Funcionários muito educados.'),
('Promoção', 'Gostei muito da promoção de combo.'),
('Sabor', 'O sabor da pizza é maravilhoso!');

INSERT INTO promocao (descricao, preco) VALUES
('Combo Família', '59.90'),
('Pizza Média + Refrigerante', '39.90'),
('Pizza Grande', '49.90'),
('Desconto 10%', '45.00'),
('Pizza Pequena + Suco', '29.90');

INSERT INTO pagamento (metodo_pagamento, valor, status_pagamento, data_pagamento) VALUES
('Cartão de Crédito', 59.90, 'Pago', '2025-06-10'),
('Dinheiro', 39.90, 'Pago', '2025-06-10'),
('Pix', 49.90, 'Pendente', '2025-06-09'),
('Cartão de Débito', 45.00, 'Pago', '2025-06-08'),
('Dinheiro', 29.90, 'Pago', '2025-06-07');

INSERT INTO pedido (delivery, cliente, promocao, idfeedback1, idpagamento1) VALUES
(1, 1, 1, 1, 1),
(2, 2, 2, 2, 2),
(3, 3, 3, 3, 3),
(4, 4, 4, 4, 4),
(5, 5, 5, 5, 5);

INSERT INTO bebidas (marca, preco, quantidade, idpedido) VALUES
('Coca-Cola', 6.50, 2, 1),
('Guaraná', 5.00, 1, 2),
('Fanta', 5.50, 3, 3),
('Pepsi', 6.00, 2, 4),
('Água Mineral', 3.00, 1, 5);

INSERT INTO pizza (variedade, tamanho, preco, quantidade, idpedido) VALUES
('Calabresa', 'Grande', 29.90, 1, 1),
('Mussarela', 'Média', 25.00, 2, 2),
('Frango com Catupiry', 'Pequena', 20.00, 1, 3),
('Portuguesa', 'Grande', 30.00, 1, 4),
('Marguerita', 'Média', 27.50, 2, 5);

