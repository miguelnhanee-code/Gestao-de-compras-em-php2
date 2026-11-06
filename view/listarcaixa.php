<?php

include_once(__DIR__ . "/../controller/caixaController.php");
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
    global $conexao;
    remocaoCaixa($_POST['id']);
    header('Location: listarcaixa.php');
    exit;
}

if (isset($_POST['guardar']) && temPermissao('actualizar') && !empty($_POST['id']) && !empty($_POST['atualizarCaixa'])) {
    $caixa = encontraCaixa($_POST['id']);
    if ($caixa) {
        $caixa->setNomeCaixa($_POST['atualizarCaixa']);
        edicaoCaixa($caixa);
    }
    header('Location: listarcaixa.php#');
    exit;
}

// $caixas = listarCaixa();

$caixas = listarCaixa();
$pesquisa = $_POST['pesquisaCampo'] ?? '';

if (temPermissao('pesquisar') && isset($_POST['pesquisarBt'])) {
    if (!empty($pesquisa)) {
        $caixas = pesquisarCaixa($pesquisa);
    }
}
?>



<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>LISTAR CLIENTE</title>
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
                <a href="<?= $paginaBairro ?>"><span class="icone"></span>BAIRRO</a>
                <a href="<?= $paginaProduto ?>"><span class="icone"></span>PRODUTO</a>
                <a href="<?= $paginaFornecedor ?>"><span class="icone"></span>FORNECEDOR</a>
                <a href="<?= $paginaCompra ?>"><span class="icone"></span>COMPRA</a>
                <a class="on" href="<?= $paginaCaixa ?>"><span class="icone"></span>CAIXA</a>
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
                <?php if (temPermissao('criar')) { ?><a href="cadastrarCaixa.php" class="aba">CADASTRO DE CAIXAS</a><?php } ?>
                <div class="aba abaAtiva">LISTA DE CAIXAS</div>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">

                <div class="topo">
                    <h1 class="titulo"><strong>Lista de caixas</strong></h1>
                </div>

                <?php if (temPermissao('pesquisar')) { ?>
                    <form method="post">
                        <div class="linha">
                            <div class="campo campoBusca">
                                <input type="text" name="pesquisaCampo" class="input" placeholder="Pesquisar " value="<?= htmlspecialchars($pesquisa) ?>">
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
                                <th>Nome do caixa</th>
                                <th>Acoes</th>
                            </tr>
                        </thead>

                        <?php if (count($caixas) == 0) { ?>
                            <tr>
                                <td>SEM NENHUM CAIXA REGISTADO</td>
                            </tr>
                        <?php } else { ?>

                            <?php foreach ($caixas as $caixa) {  ?>
                                <tr>
                                    <td><?= $caixa->getIdCaixa() ?></td>
                                    <td><?= $caixa->getNomeCaixa() ?></td>

                                    <td class="acoes">


                                        <?php if (temPermissao('actualizar') || temPermissao('remover')) { ?>
                                            <?php if (temPermissao('actualizar')) {
                                                $idCaixa = $caixa->getIdCaixa();  ?>
                                                <a href="#editar-<?= $idCaixa ?>" class="botaoAtualizar"> Editar </a>
                                            <?php } ?>
                                            <?php if (temPermissao('remover')) {
                                                $idCaixa = $caixa->getIdCaixa();   ?>
                                                <a href="#remover-<?= $idCaixa ?>" class="botaoRemover"> REMOVER </a> <?php } ?>

                                        <?php } ?>

                                        <form method="post">
                                            <input type="hidden" name="id" value="<?= $caixa->getIdCaixa() ?>">
                                            <!-- <button type="submit" name="remover">Remover</button> -->
                                        </form>

                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </table>

                    <?php for ($i = 0; $i < count($caixas); $i++) { ?>
                        <!-- MODAL EDITAR-->
                        <div class="modalFundo" id="editar-<?= $caixas[$i]->getIdCaixa() ?>">
                            <!-- <a href="#" class="modalOverlay" aria-label="Fechar"></a> -->
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">Editar caixa</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>
                                <div class="campo">
                                    <div class="campoTipo">
                                        <label class="label"><strong>Nome do Caixa</strong></label>
                                    </div>
                                    <form method="post">
                                        <input type="text" name="atualizarCaixa" class="input" value="<?= $caixas[$i]->getNomeCaixa() ?>">
                                        <input type="hidden" name="id" value="<?= $caixas[$i]->getIdCaixa() ?>">
                                        <div class="acao">
                                            <button name="guardar" type="submit" class="botao">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- fim da class modal -->
                    <?php } ?>


                    <?php for ($i = 0; $i < count($caixas); $i++) { ?>
                        <!-- MODAL REMOVER-->
                        <div class="modalFundo" id="remover-<?= $caixas[$i]->getIdCaixa() ?>">
                            <!-- <a href="#" class="modalOverlay" aria-label="Fechar"></a> -->
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">REMOVER caixa</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>
                                <div class="campo">
                                    <div class="campoTipo">
                                        <label class="label"><strong>Nome do Caixa</strong></label>
                                    </div>
                                    <form method="post">
                                        <input type="text" name="atualizarCaixa" class="input" value="<?= $caixas[$i]->getNomeCaixa() ?>">
                                        <input type="hidden" name="id" value="<?= $caixas[$i]->getIdCaixa() ?>">
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

    </div>

    </div>




</body>

</html>