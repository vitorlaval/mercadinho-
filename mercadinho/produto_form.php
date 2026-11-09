<?php
require 'conexao.php';
require 'funcoes.php';
$titulo = 'Produto';

$temId = isset($_GET['id']) || isset($_POST['id']);
$id = filter_var($_GET['id'] ?? $_POST['id'] ?? '', FILTER_VALIDATE_INT);
$p = ['codigo' => '', 'nome' => '', 'descricao' => '', 'categoria_id' => '', 'preco' => '',
      'estoque' => '0', 'estoque_minimo' => '0', 'validade' => ''];
$erros = [];

if ($temId) {
    $st = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $st->execute([$id ?: 0]);
    $atual = $st->fetch();
    if (!$atual) naoEncontrado('Produto não encontrado.', 'produtos.php');
    $p = $atual;
    $p['descricao'] = $p['descricao'] ?? '';
    $p['validade'] = $p['validade'] ?? '';
}

$cats = $pdo->query("SELECT id, nome FROM categorias ORDER BY nome")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['codigo','nome','descricao','categoria_id','preco','estoque','estoque_minimo','validade'] as $campo) {
        $p[$campo] = trim($_POST[$campo] ?? '');
    }

    if ($p['codigo'] === '') $erros[] = 'O código é obrigatório.';
    elseif (mb_strlen($p['codigo']) > 30) $erros[] = 'O código pode ter no máximo 30 caracteres.';

    if ($p['nome'] === '') $erros[] = 'O nome é obrigatório.';
    elseif (mb_strlen($p['nome']) > 120) $erros[] = 'O nome pode ter no máximo 120 caracteres.';

    $catId = filter_var($p['categoria_id'], FILTER_VALIDATE_INT);
    if (!$catId) {
        $erros[] = 'Selecione uma categoria.';
    } else {
        $st = $pdo->prepare("SELECT id FROM categorias WHERE id = ?");
        $st->execute([$catId]);
        if (!$st->fetch()) $erros[] = 'A categoria selecionada não existe.';
    }

    if (!preg_match('/^\d+([.,]\d{1,2})?$/', $p['preco']) || (float)str_replace(',', '.', $p['preco']) <= 0) {
        $erros[] = 'O preço deve ser um número maior que zero.';
    }

    $est = filter_var($p['estoque'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
    if ($est === false) $erros[] = 'O estoque deve ser um número inteiro maior ou igual a zero.';
    $min = filter_var($p['estoque_minimo'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
    if ($min === false) $erros[] = 'O estoque mínimo deve ser um número inteiro maior ou igual a zero.';

    if ($p['validade'] !== '' && !dataValida($p['validade'])) $erros[] = 'A data de validade é inválida.';

    if ($p['codigo'] !== '') {
        $st = $pdo->prepare("SELECT id FROM produtos WHERE codigo = ? AND id <> ?");
        $st->execute([$p['codigo'], $id ?: 0]);
        if ($st->fetch()) $erros[] = 'Já existe um produto com esse código.';
    }

    if (!$erros) {
        $preco = str_replace(',', '.', $p['preco']);
        $val = $p['validade'] === '' ? null : $p['validade'];
        $desc = $p['descricao'] === '' ? null : $p['descricao'];
        try {
            if ($id) {
                $st = $pdo->prepare("UPDATE produtos SET codigo=?, nome=?, descricao=?, categoria_id=?, preco=?, estoque=?, estoque_minimo=?, validade=? WHERE id=?");
                $st->execute([$p['codigo'], $p['nome'], $desc, $catId, $preco, $est, $min, $val, $id]);
                $texto = 'Produto atualizado com sucesso!';
            } else {
                $st = $pdo->prepare("INSERT INTO produtos (codigo, nome, descricao, categoria_id, preco, estoque, estoque_minimo, validade) VALUES (?,?,?,?,?,?,?,?)");
                $st->execute([$p['codigo'], $p['nome'], $desc, $catId, $preco, $est, $min, $val]);
                $texto = 'Produto cadastrado com sucesso!';
            }
            if ($val !== null && $val < date('Y-m-d')) {
                msg('aviso', $texto . ' Atenção: a validade informada já passou (produto vencido).');
            } else {
                msg('sucesso', $texto);
            }
            ir('produtos.php');
        } catch (PDOException $ex) {
            $erros[] = 'Não foi possível salvar o produto.';
        }
    }
}

require 'cabecalho.php';
?>
<h2><?= $id ? 'Editar produto' : 'Novo produto' ?></h2>

<?php if (!$cats): ?>
  <div class="msg aviso">Cadastre uma categoria antes de cadastrar produtos. <a href="categoria_form.php">Nova categoria</a></div>
<?php endif; ?>

<?php if ($erros): ?>
  <div class="msg erro"><ul><?php foreach ($erros as $er): ?><li><?= e($er) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="post" action="produto_form.php">
  <?php if ($id): ?><input type="hidden" name="id" value="<?= (int)$id ?>"><?php endif; ?>

  <label for="codigo">Código *</label>
  <input type="text" id="codigo" name="codigo" maxlength="30" required value="<?= e($p['codigo']) ?>">

  <label for="nome">Nome *</label>
  <input type="text" id="nome" name="nome" maxlength="120" required value="<?= e($p['nome']) ?>">

  <label for="categoria_id">Categoria *</label>
  <select id="categoria_id" name="categoria_id" required>
    <option value="">Selecione...</option>
    <?php foreach ($cats as $c): ?>
      <option value="<?= (int)$c['id'] ?>" <?= $p['categoria_id'] == $c['id'] ? 'selected' : '' ?>><?= e($c['nome']) ?></option>
    <?php endforeach; ?>
  </select>

  <label for="descricao">Descrição</label>
  <textarea id="descricao" name="descricao" rows="3"><?= e($p['descricao']) ?></textarea>

  <label for="preco">Preço (R$) *</label>
  <input type="text" id="preco" name="preco" required placeholder="Ex: 5,90" value="<?= e($p['preco']) ?>">

  <label for="estoque">Estoque *</label>
  <input type="number" id="estoque" name="estoque" min="0" step="1" required value="<?= e($p['estoque']) ?>">

  <label for="estoque_minimo">Estoque mínimo *</label>
  <input type="number" id="estoque_minimo" name="estoque_minimo" min="0" step="1" required value="<?= e($p['estoque_minimo']) ?>">

  <label for="validade">Validade</label>
  <input type="date" id="validade" name="validade" value="<?= e($p['validade']) ?>">

  <p>
    <button class="btn" type="submit">Salvar</button>
    <a class="btn sec" href="produtos.php">Cancelar</a>
  </p>
</form>
<?php require 'rodape.php'; ?>