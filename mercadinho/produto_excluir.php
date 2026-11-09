<?php
require 'conexao.php';
require 'funcoes.php';
$titulo = 'Excluir produto';

$id = filter_var($_GET['id'] ?? $_POST['id'] ?? '', FILTER_VALIDATE_INT);
$st = $pdo->prepare("SELECT id, codigo, nome FROM produtos WHERE id = ?");
$st->execute([$id ?: 0]);
$p = $st->fetch();
if (!$p) naoEncontrado('Produto não encontrado.', 'produtos.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $st = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
        $st->execute([$id]);
        msg('sucesso', 'Produto excluído com sucesso!');
    } catch (PDOException $ex) {
        msg('erro', 'Não foi possível excluir o produto.');
    }
    ir('produtos.php');
}

require 'cabecalho.php';
?>
<h2>Excluir produto</h2>
<div class="msg aviso">Tem certeza que deseja excluir o produto <strong><?= e($p['nome']) ?></strong> (código <?= e($p['codigo']) ?>)?</div>
<form method="post" action="produto_excluir.php">
  <input type="hidden" name="id" value="<?= (int)$id ?>">
  <button class="btn perigo" type="submit">Sim, excluir</button>
  <a class="btn sec" href="produtos.php">Cancelar</a>
</form>
<?php require 'rodape.php'; ?>