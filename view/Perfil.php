<?php

include_once(__DIR__ . "/../controller/controllerPerfil.php");
include_once __DIR__ . '/../controller/controllerLogin.php';
if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit();
}
$perfil = $_SESSION['perfil'];
exigirPermissao('gerir_utilizador');

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$nome = '';
if (isset($_SESSION['nome'])) {
    $nome = $_SESSION['nome'];
}

$mensagem = '';
$permissoes = listarPermissoes();

// Criar um perfil novo
if (isset($_POST['guardar'])) {
    if (!empty($_POST['nomePerfil'])) {
        $mensagem = criacaoPerfil(new Perfil(null, $_POST['nomePerfil']));
        if ($mensagem == "Perfil criado") {
            $novoPerfil = encontraPerfil($_POST['nomePerfil']);
            $marcadas = isset($_POST['permissoes']) ? $_POST['permissoes'] : array();
            gravarPermissoesPerfil($novoPerfil->getIdPerfil(), $marcadas);
        }
    } else {
        $mensagem = "Escreva o nome do perfil";
    }
}

// Editar um perfil (nome + permissoes)
if (isset($_POST['actualizarPerfil'])) {
    $marcadas = isset($_POST['permissoes']) ? $_POST['permissoes'] : array();
    $editado = encontraPerfilPorId($_POST['idPerfil']);
    $nomeNovo = trim($_POST['nomePerfil']);

    if (!$editado) {
        $mensagem = "Perfil nao encontrado";
    } else if ($nomeNovo == '') {
        $mensagem = "Escreva o nome do perfil";
    } else {
        $eProprio = ($editado->getNomePerfil() == $perfil || $editado->getIdPerfil() == $perfil);

        // O proprio perfil nao pode perder 'gerir_utilizador' (ficava sem acesso a esta tela)
        $retiraAcesso = false;
        foreach ($permissoes as $permissao) {
            if ($permissao['chave'] == 'gerir_utilizador' && !in_array($permissao['codigoPermissao'], $marcadas) && $eProprio) {
                $retiraAcesso = true;
            }
        }

        if ($retiraAcesso) {
            $mensagem = "Nao pode retirar a permissao Gerir utilizador ao seu proprio perfil";
        } else {
            $mensagem = editarPerfil(new Perfil($editado->getIdPerfil(), $nomeNovo));
            if ($mensagem == "Perfil actualizado") {
                gravarPermissoesPerfil($editado->getIdPerfil(), $marcadas);
                if ($eProprio && $editado->getNomePerfil() == $perfil) {
                    $_SESSION['perfil'] = $nomeNovo;
                    $perfil = $nomeNovo;
                }
            }
        }
    }
}

// Remover um perfil
if (isset($_POST['removerPerfil'])) {
    $removido = encontraPerfilPorId($_POST['idPerfil']);
    if (!$removido) {
        $mensagem = "Perfil nao encontrado";
    } else if ($removido->getNomePerfil() == $perfil || $removido->getIdPerfil() == $perfil) {
        $mensagem = "Nao pode remover o seu proprio perfil";
    } else {
        $mensagem = removerPerfil($removido->getIdPerfil());
    }
}

$perfis = listarPerfil();

// Nome da permissao como aparece na tela: gerir_log -> Gerir log
function nomePermissao($chave)
{
    return ucfirst(str_replace('_', ' ', $chave));
}
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>CONFIGURAR PERFIL</title>
    <link rel='stylesheet' href='../css/Perfil.css'>
</head>

<body>

    <div class="navbar">
        <div class="marca">
            <img src="img/logo.png" alt="Logo" class="marca-logo">
            <div class="titulo-marca">SISTEMA DE<br />GESTAO DE COMPRAS</div>
        </div>

        <nav class="navlinks">
            <a href="Dashboard.php"><span class="icone"></span>Inicio</a>

            <?php
            if (temPermissao('criar') || temPermissao('listar')) {

                if (temPermissao('criar')) {
                    $paginaContinente = 'cadastrarContinente.php';
                    $paginaPais = 'cadastrarPais.php';
                    $paginaProvincia = 'cadastrarProvincia.php';
                    $paginaDistrito = 'cadastrarDistrito.php';
                    $paginaPosto = 'cadastrarPostoAdministrativo.php';
                    $paginaLocalidade = 'cadastrarLocalidade.php';
                    $paginaBairro = 'cadastrarBairro.php';
                    $paginaProduto = 'CadastrarProduto.php';
                    $paginaFornecedor = 'cadastrarFornecedor.php';
                    $paginaCompra = 'CadastrarCompra.php';
                    $paginaCaixa = 'cadastrarCaixa.php';
                    $paginaCliente = 'cadastarCliente.php';
                } else {
                    $paginaContinente = 'listarContinente.php';
                    $paginaPais = 'listarPais.php';
                    $paginaProvincia = 'listarProvincias.php';
                    $paginaDistrito = 'listarDistrito.php';
                    $paginaPosto = 'listarPostoAdministrativo.php';
                    $paginaLocalidade = 'listarLocalidade.php';
                    $paginaBairro = 'listarBairro.php';
                    $paginaProduto = 'ListarProduto.php';
                    $paginaFornecedor = 'listarFornecedores.php';
                    $paginaCompra = 'ListarCompra.php';
                    $paginaCaixa = 'listarcaixa.php';
                    $paginaCliente = 'listarClientes.php';
                }
            ?>
                <a href="<?= $paginaContinente ?>"><span class="icone"></span>CONTINENTE</a>
                <a href="<?= $paginaPais ?>"><span class="icone"></span>PAÍS</a>
                <a href="<?= $paginaProvincia ?>"><span class="icone"></span>PROVÍNCIA</a>
                <a href="<?= $paginaDistrito ?>"><span class="icone"></span>DISTRITO</a>
                <a href="<?= $paginaPosto ?>"><span class="icone"></span>POSTO ADMINISTRATIVO</a>
                <a href="<?= $paginaLocalidade ?>"><span class="icone"></span>LOCALIDADE</a>
                <a href="<?= $paginaBairro ?>"><span class="icone"></span>BAIRRO</a>
                <a href="<?= $paginaProduto ?>"><span class="icone"></span>PRODUTO</a>
                <a href="<?= $paginaFornecedor ?>"><span class="icone"></span>FORNECEDOR</a>
                <a href="<?= $paginaCompra ?>"><span class="icone"></span>COMPRA</a>
                <a href="<?= $paginaCaixa ?>"><span class="icone"></span>CAIXA</a>
                <a href="<?= $paginaCliente ?>"><span class="icone"></span>CLIENTE</a>

            <?php } ?>

            <?php if (temPermissao('gerir_utilizador')) { ?>
                <a href="cadastarUtilizadores.php"><span class="icone"></span>UTILIZADORES</a>
                <a class="on" href="Perfil.php"><span class="icone"></span>Configurar Perfil</a>
            <?php } ?>

            <?php if (temPermissao('gerir_log')) { ?>
                <a href="ListarLog.php"><span class="icone"></span>Log</a>
            <?php } ?>
        </nav>


        <div class="infoUtilizador">
            <p>Olá, <?= $nome ?>!</p>
            <p>Perfil: <?= $perfil ?></p>
        </div>
        <a href="alterarSenha.php" class="sair">Alterar Senha</a>
        <a href="logout.php" class="sair">Log out</a>
    </div>

    <div class="mae">

        <div class="cabecalho">
            <div class="abas">
                <div class="aba abaAtiva">CONFIGURACAO DE PERFIL</div>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">
                <div class="topo">
                    <h1 class="titulo"><strong>Novo perfil</strong></h1>
                </div>

             

                <form method="post">
                    <div class="campo">
                        <label class="label"><strong>Nome do perfil</strong></label>
                        <input type="text" name="nomePerfil" class="input" placeholder="Ex.: OPERADOR">
                    </div>

                    <div class="campo">
                        <label class="label">O que este perfil pode fazer</label>
                        <div class="permissoes">
                            <?php foreach ($permissoes as $permissao) { ?>
                                <label class="opcao"><input type="checkbox" name="permissoes[]" value="<?= $permissao['codigoPermissao'] ?>"> <?= nomePermissao($permissao['chave']) ?></label>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="acao">
                        <button type="submit" name="guardar" class="botao">Guardar perfil</button>
                    </div>
                </form>

                <div class="topo">
                    <h1 class="titulo"><strong>Perfis existentes</strong></h1>
                </div>

                <div class="tabela">
                    <table>
                        <thead>
                            <tr>
                                <th>Perfil</th>
                                <?php foreach ($permissoes as $permissao) { ?>
                                    <th><?= nomePermissao($permissao['chave']) ?></th>
                                <?php } ?>
                                <th>Accao</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($perfis as $p) {
                                $marcadas = permissoesDoPerfil($p->getIdPerfil()); ?>
                                <tr>
                                    <td><?= $p->getNomePerfil() ?></td>
                                    <?php foreach ($permissoes as $permissao) { ?>
                                        <td><?= in_array($permissao['codigoPermissao'], $marcadas) ? 'Sim' : 'Nao' ?></td>
                                    <?php } ?>
                                    <td>
                                        <div class="acoes">
                                            <a href="#editar-<?= $p->getIdPerfil() ?>" class="botaoAtualizar">Editar</a>
                                            <a href="#remover-<?= $p->getIdPerfil() ?>" class="botaoRemover">Remover</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php foreach ($perfis as $p) {
            $marcadas = permissoesDoPerfil($p->getIdPerfil()); ?>

            <!-- Modal Editar -->
            <div class="modalFundo" id="editar-<?= $p->getIdPerfil() ?>">
                <a href="#" class="modalOverlay"></a>
                <div class="modalCaixa">
                    <div class="modalTopo">
                        <h1 class="titulo"><strong>Editar Perfil</strong></h1>
                        <a href="#" class="modalFechar">x</a>
                    </div>
                    <form method="post">
                        <input type="hidden" name="idPerfil" value="<?= $p->getIdPerfil() ?>">
                        <div class="campo">
                            <label class="label"><strong>Nome do perfil</strong></label>
                            <input type="text" name="nomePerfil" class="input" value="<?= $p->getNomePerfil() ?>">
                        </div>
                        <div class="campo">
                            <label class="label">Editar perfil</label>
                            <div class="permissoes">
                                <?php foreach ($permissoes as $permissao) { ?>
                                    <label class="opcao"><input type="checkbox" name="permissoes[]" value="<?= $permissao['codigoPermissao'] ?>" <?= in_array($permissao['codigoPermissao'], $marcadas) ? 'checked' : '' ?>> <?= nomePermissao($permissao['chave']) ?></label>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="acao">
                            <button type="submit" name="actualizarPerfil" class="botao">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Remover -->
            <div class="modalFundo" id="remover-<?= $p->getIdPerfil() ?>">
                <!-- <a href="#" class="modalOverlay"></a> -->
                <div class="modalCaixa">
                    <div class="modalTopo">
                        <h1 class="titulo"><strong>Remover Perfil</strong></h1>
                        <a href="#" class="modalFechar">x</a>
                    </div>
                    <form method="post">
                        <input type="hidden" name="idPerfil" value="<?= $p->getIdPerfil() ?>">
                        <p class="label">Tem a certeza que deseja remover o perfil <strong><?= $p->getNomePerfil() ?></strong>?</p>
                        <div class="acao modalBotoes">
                            <a href="#" class="botao">Cancelar</a>
                            <button type="submit" name="removerPerfil" class="botao botaoVermelho">REMOVER</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>

    </div>

</body>

</html>