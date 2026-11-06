<?php

include_once(__DIR__ . "/../controller/controllerDistrito.php");
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
    remocaoDistrito($_POST['id']);
    header('Location: listarDistrito.php');
    exit;
}

if (isset($_POST['guardar']) && temPermissao('actualizar') && !empty($_POST['id']) && !empty($_POST['atualizarDistrito']) && !empty($_POST['idProvincia'])) {
    $distrito = encontraDistrito($_POST['id']);
    if ($distrito) {
        $distrito->setNomeDistrito($_POST['atualizarDistrito']);
        $distrito->setIdProvincia($_POST['idProvincia']);
        edicaoDistrito($distrito);
    }
    header('Location: listarDistrito.php#');
    exit;
}

$distritos = listarDistrito();
$continentes = listarContinentesDistrito();
$paises = listarPaisesDistrito();
$provincias = listarProvinciasDistrito();
$pesquisa = isset($_POST['pesquisaCampo']) ? $_POST['pesquisaCampo'] : '';

if (temPermissao('pesquisar') && isset($_POST['pesquisarBt'])) {
    if (!empty($pesquisa)) {
        $distritos = pesquisarDistrito($pesquisa);
    }
}
?>



<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>LISTAR DISTRITO</title>
    <link rel='stylesheet' href='../css/listarCaixa.css'>
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
                <a class="on" href="<?= $paginaDistrito ?>"><span class="icone"></span>DISTRITO</a>
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
                <?php if (temPermissao('criar')) { ?><a href="cadastrarDistrito.php" class="aba">CADASTRO DE DISTRITO</a><?php } ?>
                <div class="aba abaAtiva">LISTA DE DISTRITOS</div>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">

                <div class="topo">
                    <h1 class="titulo"><strong>Lista de distritos</strong></h1>
                </div>

                <?php if (temPermissao('pesquisar')) { ?>
                    <form method="post">
                        <div class="linha">
                            <div class="campo campoBusca">
                                <input type="text" name="pesquisaCampo" class="input" placeholder="Pesquisar " value="<?= $pesquisa ?>">
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
                                <th>Nome do distrito</th>
                                <th>Continente</th>
                                <th>País</th>
                                <th>Província</th>
                                <th>Acoes</th>
                            </tr>
                        </thead>

                        <?php if (count($distritos) == 0) { ?>
                            <tr>
                                <td colspan="6">SEM NENHUM DISTRITO REGISTADO</td>
                            </tr>
                        <?php } else { ?>

                            <?php foreach ($distritos as $distrito) {  ?>
                                <tr>
                                    <td><?= $distrito->getIdDistrito() ?></td>
                                    <td><?= $distrito->getNomeDistrito() ?></td>
                                    <td><?= $distrito->getNomeContinente() ?></td>
                                    <td><?= $distrito->getNomePais() ?></td>
                                    <td><?= $distrito->getNomeProvincia() ?></td>

                                    <td class="acoes">

                                        <?php if (temPermissao('actualizar') || temPermissao('remover')) { ?>
                                            <?php if (temPermissao('actualizar')) { ?>
                                                <a href="#editar-<?= $distrito->getIdDistrito() ?>" class="botaoAtualizar"> Editar </a>
                                            <?php } ?>
                                            <?php if (temPermissao('remover')) { ?>
                                                <a href="#remover-<?= $distrito->getIdDistrito() ?>" class="botaoRemover"> REMOVER </a>
                                            <?php } ?>
                                        <?php } ?>

                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </table>

                    <?php foreach ($distritos as $distrito) {
                        $idDistrito = $distrito->getIdDistrito();

                        // Valores actuais do distrito
                        $continenteSelecionado = $distrito->getIdContinente();
                        $paisSelecionado = $distrito->getIdPais();
                        $provinciaSelecionada = $distrito->getIdProvincia();

                        // Se o utilizador carregou num botão "Filtrar" deste modal, usa o que foi seleccionado
                        $filtrouEsteDistrito = isset($_POST['filtrarEditar'], $_POST['id']) && $_POST['id'] == $idDistrito;
                        if ($filtrouEsteDistrito) {
                            $continenteSelecionado = $_POST['idContinente'] ?? '';
                            $paisSelecionado = $_POST['idPais'] ?? '';
                            $provinciaSelecionada = $_POST['idProvincia'] ?? '';
                        }
                        $nomeEditar = $filtrouEsteDistrito ? ($_POST['atualizarDistrito'] ?? '') : $distrito->getNomeDistrito();
                    ?>
                        <!-- MODAL EDITAR-->
                        <div class="modalFundo" id="editar-<?= $idDistrito ?>">
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">Editar distrito</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>
                                <form method="post" action="listarDistrito.php#editar-<?= $idDistrito ?>">
                                    <input type="hidden" name="id" value="<?= $idDistrito ?>">

                                    <!-- Botão por omissão (Enter): grava em vez de filtrar -->
                                    <button type="submit" name="guardar" style="display:none" tabindex="-1" aria-hidden="true"></button>

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Nome do distrito</strong></label>
                                        </div>
                                        <input type="text" name="atualizarDistrito" class="input" value="<?= $nomeEditar ?>">
                                    </div>

                                    <div class="campo campoFlex">
                                        <label class="label"><strong>Continente</strong></label>
                                        <select name="idContinente" class="input">
                                            <option value="">Selecione</option>
                                            <?php foreach ($continentes as $continente) { ?>
                                                <option value="<?= $continente['idContinente'] ?>"
                                                    <?= $continente['idContinente'] == $continenteSelecionado ? 'selected' : '' ?>>
                                                    <?= $continente['nomeContinente'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <button type="submit" name="filtrarEditar" class="botaoAtualizar" formnovalidate>Filtrar países</button>
                                    </div>

                                    <div class="campo campoFlex">
                                        <label class="label"><strong>País</strong></label>
                                        <select name="idPais" class="input">
                                            <option value="">Selecione</option>
                                            <?php foreach ($paises as $pais) { ?>
                                                <?php if ($pais['idContinente'] == $continenteSelecionado) { ?>
                                                    <option value="<?= $pais['idPais'] ?>"
                                                        <?= $pais['idPais'] == $paisSelecionado ? 'selected' : '' ?>>
                                                        <?= $pais['nomePais'] ?>
                                                    </option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <button type="submit" name="filtrarEditar" class="botaoAtualizar" formnovalidate>Filtrar províncias</button>
                                    </div>

                                    <div class="campo">
                                        <label class="label"><strong>Província</strong></label>
                                        <select name="idProvincia" class="input" required>
                                            <option value="">Selecione</option>
                                            <?php foreach ($provincias as $provincia) { ?>
                                                <?php if ($provincia['idPais'] == $paisSelecionado) { ?>
                                                    <option value="<?= $provincia['idProvincia'] ?>"
                                                        <?= $provincia['idProvincia'] == $provinciaSelecionada ? 'selected' : '' ?>>
                                                        <?= $provincia['nomeProvincia'] ?>
                                                    </option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="acao">
                                        <button name="guardar" type="submit" class="botao">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- fim da class modal -->
                    <?php } ?>


                    <?php foreach ($distritos as $distrito) { ?>
                        <!-- MODAL REMOVER-->
                        <div class="modalFundo" id="remover-<?= $distrito->getIdDistrito() ?>">
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">REMOVER distrito</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>
                                <div class="campo">
                                    <div class="campoTipo">
                                        <label class="label"><strong>Nome do distrito</strong></label>
                                    </div>
                                    <form method="post">
                                        <input type="text" class="input" value="<?= $distrito->getNomeDistrito() ?>" readonly>
                                        <input type="hidden" name="id" value="<?= $distrito->getIdDistrito() ?>">
                                        <div class="acao">
                                            <button name="remover" type="submit" class="botao">REMOVER</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- fim da class modal -->
                    <?php } ?>

                </div>

            </div>
        </div>

    </div>

</body>

</html>