<?php
require 'conexao.php';
require 'funcoes.php';
$titulo = 'Detalhes do produto';

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
$st = $pdo->prepare("SELECT p.*, c.nome AS categoria FROM produtos p JOIN categorias c ON c.id = p.categoria_id WHERE p.id = ?");
$st->execute([$id ?: 0]);
$p = $st->fetch();
if (!$p) naoEncontrado('Produto não encontrado.', 'produtos.php');

$baixo = $p['estoque'] <= $p['estoque_minimo'];
$vencido = $p['validade'] !== null && $p['validade'] < date('Y-m-d');

require 'cabecalho.php';
?>
<h2><?= e($p['nome']) ?></h2>
<?php if ($baixo): ?><div class="msg aviso">Estoque baixo: igual ou abaixo do mínimo.</div><?php endif; ?>
<?php if ($vencido): ?><div class="msg erro">Produto vencido!</div><?php endif; ?>
<table>
  <tr><th>Código</th><td><?= e($p['codigo']) ?></td></tr>
  <tr><th>Nome</th><td><?= e($p['nome']) ?></td></tr>
  <tr><th>Categoria</th><td><?= e($p['categoria']) ?></td></tr>
  <tr><th>Descrição</th><td><?= e($p['descricao']) ?></td></tr>
  <tr><th>Preço</th><td><?= reais($p['preco']) ?></td></tr>
  <tr><th>Estoque</th><td><?= (int)$p['estoque'] ?></td></tr>
  <tr><th>Estoque mínimo</th><td><?= (int)$p['estoque_minimo'] ?></td></tr>
  <tr><th>Validade</th><td><?= $p['validade'] ? e(date('d/m/Y', strtotime($p['validade']))) : 'Não informada' ?></td></tr>
</table>
<p>
  <a class="btn" href="produtos.php">Voltar à lista</a>
  <a class="btn sec" href="produto_form.php?id=<?= (int)$p['id'] ?>">Editar</a>
</p>
<?php require 'rodape.php'; ?>