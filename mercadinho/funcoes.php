<?php
session_start();

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function msg($tipo, $texto) { $_SESSION['msg'] = [$tipo, $texto]; }

function mostrarMsg() {
    if (!empty($_SESSION['msg'])) {
        [$tipo, $texto] = $_SESSION['msg'];
        unset($_SESSION['msg']);
        echo '<div class="msg ' . e($tipo) . '">' . e($texto) . '</div>';
    }
}

function reais($v) { return 'R$ ' . number_format((float)$v, 2, ',', '.'); }

function dataValida($d) {
    $o = DateTime::createFromFormat('Y-m-d', $d);
    return $o && $o->format('Y-m-d') === $d;
}

function ir($url) { header('Location: ' . $url); exit; }

function naoEncontrado($texto, $voltar) {
    global $titulo;
    require 'cabecalho.php';
    echo '<div class="msg erro">' . e($texto) . '</div>';
    echo '<p><a class="btn" href="' . e($voltar) . '">Voltar</a></p>';
    require 'rodape.php';
    exit;
}