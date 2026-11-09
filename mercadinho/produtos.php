<?php
require 'conexao.php';
require 'funcoes.php';
$titulo = 'Produtos';

$busca = trim($_GET['busca'] ?? '');
$catFiltro = filter_var($_GET['categoria'] ?? '', FILTER_VALIDATE_INT);

$sql = "SELECT p.*, c.nome AS categoria FROM produtos p JOIN categorias c ON c.id = p.categoria_id WHERE 1=1";
$par = [];
if ($busca !== '') {
    $sql .= " AND (p.nome LIKE ? OR p.codigo LIKE ?)";
    $par[] = "%$busca%";
    $par[] = "%$busca%";
}
if ($catFiltro) {
    $sql .= " AND p.categoria_id = ?";
    $par[] = $catFiltro;
}
$sql .= " ORDER BY p.nome";
$st = $pdo->prepare($sql);
$st->execute($par);
$produtos = $st->fetchAll();

$cats = $pdo->query("SELECT id, nome FROM categorias ORDER BY nome")->fetchAll();
$hoje = date('Y-m-d');
$filtrando = ($busca !== '' || $catFiltro);

require 'cabecalho.php';
mostrarMsg();
?>
<h2>Produtos</h2>
<p><a class="btn" href="produto_form.php">+ Novo produto</a></p>

<form method="get" action="produtos.php" class="filtros">
  <div>
    <label for="busca">Buscar por nome ou código</label>
    <input type="text" id="busca" name="busca" value="<?= e($busca) ?>">
  </div>
  <div>
    <label for="categoria">Categoria</label>
    <select id="categoria" name="categoria">
      <option value="">Todas</option>
      <?php foreach ($cats as $c): ?>
        <option value="<?= (int)$c['id'] ?>" <?= $catFiltro == $c['id'] ? 'selected' : '' ?>><?= e($c['nome']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <button class="btn" type="submit">Filtrar</button>
    <a class="btn sec" href="produtos.php">Limpar filtros</a>
  </div>
</form>

<?php if (!$produtos): ?>
  <div class="msg aviso"><?= $filtrando ? 'Nenhum produto encontrado para os filtros informados.' : 'Nenhum produto cadastrado.' ?></div>
<?php else: ?>
<p><small>Linhas amarelas: estoque menor ou igual ao estoque mínimo.</small></p>
<div class="tabela-wrap">
<table>
  <tr><th>Código</th><th>Nome</th><th>Categoria</th><th>Preço</th><th>Estoque</th><th>Ações</th></tr>
  <?php foreach ($produtos as $p):
      $baixo = $p['estoque'] <= $p['estoque_minimo'];
      $vencido = $p['validade'] !== null && $p['validade'] < $hoje; ?>
  <tr class="<?= $baixo ? 'alerta' : '' ?>">
    <td><?= e($p['codigo']) ?></td>
    <td><?= e($p['nome']) ?> <?= $vencido ? '<span class="tag vencido">Vencido</span>' : '' ?></td>
    <td><?= e($p['categoria']) ?></td>
    <td><?= reais($p['preco']) ?></td>
    <td><?= (int)$p['estoque'] ?> <?= $baixo ? '<span class="tag baixo">Baixo</span>' : '' ?></td>
    <td class="acoes">
      <a class="btn peq" href="produto_detalhes.php?id=<?= (int)$p['id'] ?>">Detalhes</a>
      <a class="btn peq sec" href="produto_form.php?id=<?= (int)$p['id'] ?>">Editar</a>
      <a class="btn peq perigo" href="produto_excluir.php?id=<?= (int)$p['id'] ?>">Excluir</a>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
</div>
<?php endif; ?>
<?php require 'rodape.php'; ?>