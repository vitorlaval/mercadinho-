<?php
require 'conexao.php';
require 'funcoes.php';
$titulo = 'Categoria';

$temId = isset($_GET['id']) || isset($_POST['id']);
$id = filter_var($_GET['id'] ?? $_POST['id'] ?? '', FILTER_VALIDATE_INT);
$cat = ['nome' => '', 'descricao' => ''];
$erros = [];

if ($temId) {
    $st = $pdo->prepare("SELECT * FROM categorias WHERE id = ?");
    $st->execute([$id ?: 0]);
    $atual = $st->fetch();
    if (!$atual) naoEncontrado('Categoria não encontrada.', 'categorias.php');
    $cat = $atual;
    $cat['descricao'] = $cat['descricao'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat['nome'] = trim($_POST['nome'] ?? '');
    $cat['descricao'] = trim($_POST['descricao'] ?? '');

    if ($cat['nome'] === '') $erros[] = 'O nome da categoria é obrigatório.';
    elseif (mb_strlen($cat['nome']) > 80) $erros[] = 'O nome pode ter no máximo 80 caracteres.';
    if (mb_strlen($cat['descricao']) > 255) $erros[] = 'A descrição pode ter no máximo 255 caracteres.';

    if (!$erros) {
        $st = $pdo->prepare("SELECT id FROM categorias WHERE nome = ? AND id <> ?");
        $st->execute([$cat['nome'], $id ?: 0]);
        if ($st->fetch()) $erros[] = 'Já existe uma categoria com esse nome.';
    }

    if (!$erros) {
        $desc = $cat['descricao'] === '' ? null : $cat['descricao'];
        try {
            if ($id) {
                $st = $pdo->prepare("UPDATE categorias SET nome = ?, descricao = ? WHERE id = ?");
                $st->execute([$cat['nome'], $desc, $id]);
                msg('sucesso', 'Categoria atualizada com sucesso!');
            } else {
                $st = $pdo->prepare("INSERT INTO categorias (nome, descricao) VALUES (?, ?)");
                $st->execute([$cat['nome'], $desc]);
                msg('sucesso', 'Categoria cadastrada com sucesso!');
            }
            ir('categorias.php');
        } catch (PDOException $ex) {
            $erros[] = 'Não foi possível salvar a categoria.';
        }
    }
}

require 'cabecalho.php';
?>
<h2><?= $id ? 'Editar categoria' : 'Nova categoria' ?></h2>

<?php if ($erros): ?>
  <div class="msg erro"><ul><?php foreach ($erros as $er): ?><li><?= e($er) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="post" action="categoria_form.php">
  <?php if ($id): ?><input type="hidden" name="id" value="<?= (int)$id ?>"><?php endif; ?>
  <label for="nome">Nome *</label>
  <input type="text" id="nome" name="nome" maxlength="80" required value="<?= e($cat['nome']) ?>">
  <label for="descricao">Descrição</label>
  <input type="text" id="descricao" name="descricao" maxlength="255" value="<?= e($cat['descricao']) ?>">
  <p>
    <button class="btn" type="submit">Salvar</button>
    <a class="btn sec" href="categorias.php">Cancelar</a>
  </p>
</form>
<?php require 'rodape.php'; ?>