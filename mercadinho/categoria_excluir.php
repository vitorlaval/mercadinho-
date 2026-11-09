<?php
require 'conexao.php';
require 'funcoes.php';
$titulo = 'Excluir categoria';

$id = filter_var($_GET['id'] ?? $_POST['id'] ?? '', FILTER_VALIDATE_INT);
$st = $pdo->prepare("SELECT c.*, (SELECT COUNT(*) FROM produtos p WHERE p.categoria_id = c.id) AS total
                     FROM categorias c WHERE c.id = ?");
$st->execute([$id ?: 0]);
$cat = $st->fetch();
if (!$cat) naoEncontrado('Categoria não encontrada.', 'categorias.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($cat['total'] > 0) {
        msg('erro', 'Não é possível excluir: a categoria possui produtos vinculados.');
    } else {
        try {
            $st = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
            $st->execute([$id]);
            msg('sucesso', 'Categoria excluída com sucesso!');
        } catch (PDOException $ex) {
            msg('erro', 'Não foi possível excluir a categoria.');
        }
    }
    ir('categorias.php');
}

require 'cabecalho.php';
?>
<h2>Excluir categoria</h2>
<?php if ($cat['total'] > 0): ?>
  <div class="msg erro">A categoria <strong><?= e($cat['nome']) ?></strong> possui <?= (int)$cat['total'] ?> produto(s) vinculado(s) e não pode ser excluída.</div>
  <a class="btn sec" href="categorias.php">Voltar</a>
<?php else: ?>
  <div class="msg aviso">Tem certeza que deseja excluir a categoria <strong><?= e($cat['nome']) ?></strong>?</div>
  <form method="post" action="categoria_excluir.php">
    <input type="hidden" name="id" value="<?= (int)$id ?>">
    <button class="btn perigo" type="submit">Sim, excluir</button>
    <a class="btn sec" href="categorias.php">Cancelar</a>
  </form>
<?php endif; ?>
<?php require 'rodape.php'; ?>