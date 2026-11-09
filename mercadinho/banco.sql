CREATE DATABASE IF NOT EXISTS supermercado CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE supermercado;

DROP TABLE IF EXISTS produtos;
DROP TABLE IF EXISTS categorias;

CREATE TABLE categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(80) NOT NULL UNIQUE,
  descricao VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(30) NOT NULL UNIQUE,
  nome VARCHAR(120) NOT NULL,
  descricao TEXT NULL,
  categoria_id INT NOT NULL,
  preco DECIMAL(10,2) NOT NULL,
  estoque INT UNSIGNED NOT NULL DEFAULT 0,
  estoque_minimo INT UNSIGNED NOT NULL DEFAULT 0,
  validade DATE NULL,
  CONSTRAINT fk_produto_categoria FOREIGN KEY (categoria_id)
    REFERENCES categorias(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO categorias (nome, descricao) VALUES
('Mercearia','Arroz, feijão, massas e enlatados'),
('Bebidas','Sucos, refrigerantes e água'),
('Laticínios','Leite, queijo e iogurte'),
('Limpeza','Produtos de limpeza da casa'),
('Hortifruti','Frutas e verduras');

INSERT INTO produtos (codigo,nome,descricao,categoria_id,preco,estoque,estoque_minimo,validade) VALUES
('0012345','Arroz 5kg','Arroz branco tipo 1',1,24.90,40,10,'2027-06-30'),
('7891000','Feijão Preto 1kg','Feijão preto selecionado',1,8.50,25,10,'2027-03-15'),
('7891001','Macarrão Espaguete','Pacote 500g',1,4.20,5,10,'2027-01-20'),
('7892000','Suco de Laranja 1L','Suco integral',2,9.99,0,8,'2026-12-10'),
('7892001','Refrigerante Cola 2L','Garrafa PET',2,8.90,30,12,'2027-02-01'),
('7893000','Leite Integral 1L','Caixa longa vida',3,5.49,60,20,'2027-01-05'),
('7893001','Queijo Mussarela 500g','Fatiado',3,22.00,12,5,'2026-10-01'),
('7894000','Detergente 500ml','Neutro',4,2.99,45,15,'2028-01-01'),
('7894001','Água Sanitária 2L','Uso geral',4,7.80,18,6,NULL),
('7895000','Banana Prata kg','Banana fresca',5,6.90,22,8,'2026-11-20'),
('7895001','Tomate kg','Tomate vermelho',5,7.50,3,6,'2026-11-18');