<?php

include_once(__DIR__ . "/../controller/bairroController.php");
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
    remocaoBairro($_POST['id']);
    header('Location: listarBairro.php');
    exit;
}

if (isset($_POST['guardar']) && temPermissao('actualizar') && !empty($_POST['id']) && !empty($_POST['atualizarBairro']) && !empty($_POST['idLocalidade'])) {
    $bairro = encontraBairro($_POST['id']);
    if ($bairro) {
        $bairro->setNomeBairro($_POST['atualizarBairro']);
        $bairro->setIdLocalidade($_POST['idLocalidade']);
        edicaoBairro($bairro);
    }
    header('Location: listarBairro.php#');
    exit;
}

$bairros = listarBairro();
$localidades = listarLocalidades();
$pesquisa = isset($_POST['pesquisaCampo']) ? $_POST['pesquisaCampo'] : '';

if (temPermissao('pesquisar') && isset($_POST['pesquisarBt'])) {
    if (!empty($pesquisa)) {
        $bairros = pesquisarBairro($pesquisa);
    }
}
?>



<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>LISTAR BAIRRO</title>
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
                <a href="<?= $paginaDistrito ?>"><span class="icone"></span>DISTRITO</a>
                <a href="<?= $paginaPosto ?>"><span class="icone"></span>POSTO ADMINISTRATIVO</a>
                <a href="<?= $paginaLocalidade ?>"><span class="icone"></span>LOCALIDADE</a>
                <a class="on" href="<?= $paginaBairro ?>"><span class="icone"></span>BAIRRO</a>
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
                <?php if (temPermissao('criar')) { ?><a href="cadastrarBairro.php" class="aba">CADASTRO DE BAIRRO</a><?php } ?>
                <div class="aba abaAtiva">LISTA DE BAIRROS</div>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">

                <div class="topo">
                    <h1 class="titulo"><strong>Lista de bairros</strong></h1>
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
                                <th>Nome do bairro</th>
                                <th>Continente</th>
                                <th>País</th>
                                <th>Província</th>
                                <th>Distrito</th>
                                <th>Posto administrativo</th>
                                <th>Localidade</th>
                                <th>Acoes</th>
                            </tr>
                        </thead>

                        <?php if (count($bairros) == 0) { ?>
                            <tr>
                                <td colspan="9">SEM NENHUM BAIRRO REGISTADO</td>
                            </tr>
                        <?php } else { ?>

                            <?php foreach ($bairros as $bairro) {  ?>
                                <tr>
                                    <td><?= $bairro->getIdBairro() ?></td>
                                    <td><?= $bairro->getNomeBairro() ?></td>
                                    <td><?= $bairro->getNomeContinente() ?></td>
                                    <td><?= $bairro->getNomePais() ?></td>
                                    <td><?= $bairro->getNomeProvincia() ?></td>
                                    <td><?= $bairro->getNomeDistrito() ?></td>
                                    <td><?= $bairro->getNomePostoAdministrativo() ?></td>
                                    <td><?= $bairro->getNomeLocalidade() ?></td>

                                    <td class="acoes">

                                        <?php if (temPermissao('actualizar') || temPermissao('remover')) { ?>
                                            <?php if (temPermissao('actualizar')) { ?>
                                                <a href="#editar-<?= $bairro->getIdBairro() ?>" class="botaoAtualizar"> Editar </a>
                                            <?php } ?>
                                            <?php if (temPermissao('remover')) { ?>
                                                <a href="#remover-<?= $bairro->getIdBairro() ?>" class="botaoRemover"> REMOVER </a>
                                            <?php } ?>
                                        <?php } ?>

                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </table>

                    <?php foreach ($bairros as $bairro) { ?>
                        <!-- MODAL EDITAR-->
                        <div class="modalFundo" id="editar-<?= $bairro->getIdBairro() ?>">
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">Editar bairro</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>
                                <form method="post">
                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Nome do bairro</strong></label>
                                        </div>
                                        <input type="text" name="atualizarBairro" class="input" value="<?= $bairro->getNomeBairro() ?>">
                                    </div>

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Localidade</strong></label>
                                        </div>
                                        <select name="idLocalidade" class="input">
                                            <?php foreach ($localidades as $localidade) { ?>
                                                <option value="<?= $localidade['idLocalidade'] ?>"
                                                    <?= ($localidade['idLocalidade'] == $bairro->getIdLocalidade()) ? 'selected' : '' ?>>
                                                    <?= $localidade['nomeLocalidade'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <input type="hidden" name="id" value="<?= $bairro->getIdBairro() ?>">
                                    <div class="acao">
                                        <button name="guardar" type="submit" class="botao">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- fim da class modal -->
                    <?php } ?>


                    <?php foreach ($bairros as $bairro) { ?>
                        <!-- MODAL REMOVER-->
                        <div class="modalFundo" id="remover-<?= $bairro->getIdBairro() ?>">
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">REMOVER bairro</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>
                                <div class="campo">
                                    <div class="campoTipo">
                                        <label class="label"><strong>Nome do bairro</strong></label>
                                    </div>
                                    <form method="post">
                                        <input type="text" class="input" value="<?= $bairro->getNomeBairro() ?>" readonly>
                                        <input type="hidden" name="id" value="<?= $bairro->getIdBairro() ?>">
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