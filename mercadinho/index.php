<?php
require 'conexao.php';
require 'funcoes.php';
$titulo = 'Início';
require 'cabecalho.php';
mostrarMsg();
?>
<h2>Bem-vindo ao Mercado Boa Compra</h2>
<p>Painel do administrador. Escolha uma opção:</p>
<div class="atalhos">
  <a class="card" href="produtos.php">📦 Produtos</a>
  <a class="card" href="categorias.php">🏷️ Categorias</a>
  <a class="card" href="produto_form.php">➕ Novo produto</a>
  <a class="card" href="categoria_form.php">➕ Nova categoria</a>
</div>
<?php require 'rodape.php'; ?>