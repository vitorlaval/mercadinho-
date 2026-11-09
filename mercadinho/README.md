# Mercado Boa Compra - Sistema de Supermercado em PHP

## Integrantes
- Nome 1
- Nome 2
- Nome 3

## Pré-requisitos
- XAMPP (Apache + MySQL/MariaDB)
- Navegador e VS Code

## Como executar
1. Abra o XAMPP e clique em Start em Apache e MySQL.
2. Copie a pasta supermercado para C:\xampp\htdocs.
3. Acesse http://localhost/phpmyadmin, vá em Importar e escolha o arquivo banco.sql.
4. Acesse http://localhost/supermercado.

## Configuração da conexão
Arquivo conexao.php: host localhost, banco supermercado, usuário root, senha vazia (padrão do XAMPP).

## Arquivos
- index.php: página inicial
- categorias.php, categoria_form.php, categoria_excluir.php: CRUD de categorias
- produtos.php, produto_form.php, produto_detalhes.php, produto_excluir.php: CRUD de produtos
- conexao.php, funcoes.php, cabecalho.php, rodape.php: arquivos compartilhados
- estilo.css: visual
- banco.sql: criação e dados de exemplo

## Testes
Veja testes.md.