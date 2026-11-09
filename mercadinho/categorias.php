<?php
require 'conexao.php';
require 'funcoes.php';
$titulo = 'Categorias';

$cats = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM produtos p WHERE p.categoria_id = c.id) AS total
                     FROM categorias c ORDER BY c.nome")->fetchAll();

require 'cabecalho.php';
mostrarMsg();
?>
<h2>Categorias</h2>
<p><a class="btn" href="categoria_form.php">+ Nova categoria</a></p>

<?php if (!$cats): ?>
  <div class="msg aviso">Nenhuma categoria cadastrada.</div>
<?php else: ?>
<div class="tabela-wrap">
<table>
  <tr><th>Nome</th><th>Descrição</th><th>Produtos</th><th>Ações</th></tr>
  <?php foreach ($cats as $c): ?>
  <tr>
    <td><?= e($c['nome']) ?></td>
    <td><?= e($c['descricao']) ?></td>
    <td><?= (int)$c['total'] ?></td>
    <td class="acoes">
      <a class="btn peq" href="produtos.php?categoria=<?= (int)$c['id'] ?>">Ver produtos</a>
      <a class="btn peq sec" href="categoria_form.php?id=<?= (int)$c['id'] ?>">Editar</a>
      <a class="btn peq perigo" href="categoria_excluir.php?id=<?= (int)$c['id'] ?>">Excluir</a>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
</div>
<?php endif; ?>
<?php require 'rodape.php'; ?>