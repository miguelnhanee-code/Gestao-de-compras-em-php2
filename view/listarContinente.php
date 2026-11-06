<?php

include_once(__DIR__ . "/../controller/controllerContinente.php");
include_once __DIR__ . '/../controller/controllerLogin.php';

if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
$perfil = $_SESSION['perfil'];
exigirPermissao('listar');
if (isset($_POST['remover']) && temPermissao('remover')) {
    remocaoContinente($_POST['id']);
    header('Location: listarContinente.php');
    exit;
}
if (isset($_POST['guardar']) && temPermissao('actualizar') && !empty($_POST['id']) && !empty($_POST['atualizarContinente'])) {
    $continente = encontraContinente($_POST['id']);
    if ($continente) {
        $continente->setNomeContinente($_POST['atualizarContinente']);
        edicaoContinente($continente);
    }
    header('Location: listarContinente.php#');
    exit;
}
$pesquisa = $_POST['pesquisaCampo'] ?? '';
$continentes = listarContinentes();
if (isset($_POST['pesquisarBt']) && temPermissao('pesquisar')) {
    if (!empty($pesquisa)) {
        $continentes = pesquisarContinente($pesquisa);
    }
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>LISTAR CONTINENTES</title>
    <link rel='stylesheet' href='../css/ListarProduto.css'>
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
                <a class="on" href="<?= $paginaContinente ?>"><span class="icone"></span>CONTINENTE</a>
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
                <a href="Perfil.php"><span class="icone"></span>Configurar Perfil</a>
            <?php } ?>

            <?php if (temPermissao('gerir_log')) { ?>
                <a href="ListarLog.php"><span class="icone"></span>Log</a>
            <?php } ?>
        </nav>

        <div style="margin: 15px;">
            <p>Olá, <?= isset($_SESSION['nome']) ? $_SESSION['nome'] : ''; ?>!</p>
            <p>Perfil: <?= isset($_SESSION['perfil']) ? $_SESSION['perfil'] : ''; ?></p>
        </div>

        <a href="alterarSenha.php" class="sair">Alterar Senha</a>
        <a href="logout.php" class="sair">Log out</a>
    </div>

    <div class="mae">

        <div class="cabecalho">
            <div class="abas">
                <?php if (temPermissao('criar')) { ?>
                    <a href="cadastrarContinente.php" class="aba">CADASTRO DE CONTINENTES</a>
                <?php } ?>

                <div class="aba abaAtiva">LISTA DE CONTINENTES</div>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">

                <div class="topo">
                    <h1 class="titulo"><strong>Lista de continentes</strong></h1>
                </div>

                <?php if (temPermissao('pesquisar')) { ?>
                    <form method="POST">
                        <div class="linha">
                            <div class="campo campoBusca">
                                <input type="text" name="pesquisaCampo" class="input" placeholder="Pesquisar" value="<?= $pesquisa ?>">
                            </div>

                            <div class="campo">
                                <button type="submit" name="pesquisarBt" class="botao">Pesquisar</button>
                            </div>
                        </div>
                    </form>
                <?php } ?>

                <div class="tabela">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome do continente</th>
                                <th>Acoes</th>
                            </tr>
                        </thead>

                        <?php if (count($continentes) == 0) { ?>
                            <tr>
                                <td colspan="3">SEM NENHUM CONTINENTE REGISTADO</td>
                            </tr>
                        <?php } else { ?>

                            <?php foreach ($continentes as $continente) { ?>
                                <tr>
                                    <td><?= $continente->getIdContinente() ?></td>
                                    <td><?= $continente->getNomeContinente() ?></td>

                                    <td class="acoes">

                                        <?php if (temPermissao('actualizar') || temPermissao('remover')) { ?>

                                            <?php if (temPermissao('actualizar')) {
                                                $idContinente = $continente->getIdContinente(); ?>
                                                <a href="#editar-<?= $idContinente ?>" class="botaoAtualizar">Editar</a>
                                            <?php } ?>

                                            <?php if (temPermissao('remover')) {
                                                $idContinente = $continente->getIdContinente(); ?>
                                                <a href="#remover-<?= $idContinente ?>" class="botaoRemover">REMOVER</a>
                                            <?php } ?>

                                        <?php } ?>

                                        <form method="post">
                                            <input type="hidden" name="id" value="<?= $continente->getIdContinente() ?>">
                                        </form>

                                    </td>
                                </tr>
                            <?php } ?>

                        <?php } ?>
                    </table>

                    <?php for ($i = 0; $i < count($continentes); $i++) { ?>

                        <!-- MODAL EDITAR -->
                        <div class="modalFundo" id="editar-<?= $continentes[$i]->getIdContinente() ?>">
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">Editar continente</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <div class="campo">
                                    <div class="campoTipo">
                                        <label class="label"><strong>Nome do continente</strong></label>
                                    </div>

                                    <form method="post">
                                        <input type="text" name="atualizarContinente" class="input" value="<?= $continentes[$i]->getNomeContinente() ?>">
                                        <input type="hidden" name="id" value="<?= $continentes[$i]->getIdContinente() ?>">

                                        <div class="acao">
                                            <button name="guardar" type="submit" class="botao">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- FIM MODAL EDITAR -->

                    <?php } ?>

                    <?php for ($i = 0; $i < count($continentes); $i++) { ?>

                        <!-- MODAL REMOVER -->
                        <div class="modalFundo" id="remover-<?= $continentes[$i]->getIdContinente() ?>">
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">REMOVER CONTINENTE</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <div class="campo">
                                    <div class="campoTipo">
                                        <label class="label"><strong>Nome do continente</strong></label>
                                    </div>

                                    <form method="post">
                                        <input type="text" name="atualizarContinente" class="input" value="<?= $continentes[$i]->getNomeContinente() ?>" readonly>
                                        <input type="hidden" name="id" value="<?= $continentes[$i]->getIdContinente() ?>">

                                        <div class="acao">
                                            <button name="remover" type="submit" class="botao">REMOVER</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- FIM MODAL REMOVER -->

                    <?php } ?>

                </div>

            </div>
        </div>

    </div>

</body>

</html>