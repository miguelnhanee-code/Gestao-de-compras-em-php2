<?php
include_once __DIR__ . '/../controller/controllerCompra.php';
include_once __DIR__ . '/../controller/controllerProduto.php';
include_once __DIR__ . '/../controller/clienteController.php';

include_once __DIR__ . '/../controller/controllerLogin.php';
if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
$perfil = $_SESSION['perfil'];
exigirPermissao('listar');

$compras = listarCompra();
$produtos = listarProduto();
$cliente = listarCliente();

if (isset($_POST['remover']) && temPermissao('remover')) {
    global $conexao;
    remocaoCompra($_POST['id']);
    header('Location: ListarCompra.php');
}

if (
    isset($_POST['guardar']) && temPermissao('actualizar') && !empty($_POST['id']) &&
    !empty($_POST['idproduto']) && !empty($_POST['idcliente'])
    && !empty($_POST['quantidade']) && !empty($_POST['dataCompra'])
) {
    $compra = encontraCompra($_POST['id']);

    if ($compra) {
        $compra->setIdproduto($_POST['idproduto']);
        $compra->setIdcliente($_POST['idcliente']);
        $compra->setQuantidade($_POST['quantidade']);
        $compra->setDataCompra($_POST['dataCompra']);
        edicaoCompra($compra);
    }
    header('Location: ListarCompra.php#');
}

$compras = listarCompra();
$pesquisa = $_POST['pesquisaCampo'] ?? '';
if (isset($_POST['pesquisarBt']) && temPermissao('pesquisar')) {
    if (!empty($pesquisa)) {
        $compras = pesquisarCompra($pesquisa);
    }
}

?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>LISTAR COMPRA</title>
    <link rel='stylesheet' href='../css/ListarCompra.css'>
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
                <a class="on" href="<?= $paginaCompra ?>"><span class="icone"></span>COMPRA</a>
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
                <?php if (temPermissao('criar')) { ?><a href="CadastrarCompra.php" class="aba">CADASTRO DE COMPRA</a><?php } ?>
                <div class="aba abaAtiva">LISTA DE COMPRAS</div>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">

                <div class="topo">
                    <h1 class="titulo"><strong>Lista de Compras</strong></h1>
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
                                <th>Nome do Produto</th>
                                <th>Nome do Cliente</th>
                                <th>Nome do Caixa</th>
                                <th>Preco Unitario</th>
                                <th>Quantidade</th>
                                <th>Unidade de Medida</th>
                                <th>Data de Compra</th>
                                <th>Preco Total</th>
                                <th>Acao</th>
                            </tr>
                        </thead>

                        <?php if (count($compras) == 0) { ?>
                            <tr>
                                <td>SEM NENHUMA COMPRA REGISTADA</td>
                            </tr>
                        <?php } else { ?>
                            <?php foreach ($compras as $compra) { ?>

                                <tr>
                                    <td><?= $compra->getIdcompra() ?></td>
                                    <td><?= $compra->getNomeProduto() ?></td>
                                    <td><?= $compra->getNomeCliente() ?></td>
                                    <td><?= $compra->getNomeCaixa() ?></td>
                                    <td><?= $compra->getPrecoProduto() ?></td>
                                    <td><?= $compra->getQuantidade() ?></td>
                                    <td><?= $compra->getUnidadeMedida() ?></td>
                                    <td><?= $compra->getDataCompra() ?></td>
                                    <td><?= (float) $compra->getQuantidade() * (float) $compra->getPrecoProduto() ?></td>

                                    <td>
                                        <div class="acoes">


                                            <form method="post">

                                                <?php
                                                if (temPermissao('actualizar') || temPermissao('remover')) { ?>
                                                    <input type="hidden" name="id" value="<?= $compra->getIdcompra() ?>">

                                                    <?php
                                                    if (temPermissao('actualizar')) {
                                                    ?>
                                                        <a href="#editar-<?= $compra->getIdcompra() ?>" class="botaoAtualizar">
                                                            Editar
                                                        </a>
                                                    <?php } ?>

                                                    <?php
                                                    if (temPermissao('remover')) {
                                                    ?>
                                                        <a href="#remover-<?= $compra->getIdcompra() ?>" class="botaoRemover">
                                                            Remover
                                                        </a>
                                                    <?php } ?>

                                                <?php } ?>

                                            </form>
                                        </div>
                                    </td>
                                </tr>
                        <?php }
                        } ?>

                    </table>

                    <?php foreach ($compras as $compra) { ?>
                        <!-- MODAL-->
                        <div class="modalFundo" id="editar-<?= $compra->getIdcompra() ?>">
                            <!-- <a href="#" class="modalOverlay" aria-label="Fechar"></a> -->
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">Editar Compra</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="">
                                    <input type="hidden" name="id" value="<?= $compra->getIdcompra() ?>">

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Nome do produto</strong></label>
                                        </div>
                                        <select name="idproduto" class="input">
                                            <?php foreach ($produtos as $produto) { ?>
                                                <option value="<?= $produto->getIdproduto() ?>"
                                                    <?= $produto->getIdproduto() == $compra->getIdproduto() ? 'selected' : '' ?>>
                                                    <?= $produto->getProduto() ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Nome do Cliente</strong></label>
                                            </div>
                                            <select name="idcliente" class="input">
                                                <?php foreach ($cliente as $clientes) { ?>
                                                    <option value="<?= $clientes->getIdcliente() ?>"
                                                        <?= $clientes->getIdcliente() == $compra->getIdcliente() ? 'selected' : '' ?>>
                                                        <?= $clientes->getNomeCliente() ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Quantidade</strong></label>
                                            </div>
                                            <input type="text" name="quantidade" class="input" value="<?= $compra->getQuantidade() ?>">
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Data da compra</strong></label>
                                            </div>
                                            <input type="date" name="dataCompra" class="input" value="<?= $compra->getDataCompra() ?>">
                                        </div>
                                    </div>

                                    <div class="acao">
                                        <button type="submit" name="guardar" class="botao">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- fim da modal -->
                    <?php } ?>

                    <?php foreach ($compras as $compra) { ?>
                        <!-- MODAL-->
                        <div class="modalFundo" id="remover-<?= $compra->getIdcompra() ?>">
                            <!-- <a href="#" class="modalOverlay" aria-label="Fechar"></a> -->
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">Remover Compra</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="">
                                    <input type="hidden" name="id" value="<?= $compra->getIdcompra() ?>">

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Nome do produto</strong></label>
                                        </div>
                                        <select name="idproduto" class="input">
                                            <?php foreach ($produtos as $produto) { ?>
                                                <option value="<?= $produto->getIdproduto() ?>"
                                                    <?= $produto->getIdproduto() == $compra->getIdproduto() ? 'selected' : '' ?>>
                                                    <?= $produto->getProduto() ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="linha">
                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Nome do Cliente</strong></label>
                                            </div>
                                            <select name="idcliente" class="input">
                                                <?php foreach ($cliente as $clientes) { ?>
                                                    <option value="<?= $clientes->getIdcliente() ?>"
                                                        <?= $clientes->getIdcliente() == $compra->getIdcliente() ? 'selected' : '' ?>>
                                                        <?= $clientes->getNomeCliente() ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Quantidade</strong></label>
                                            </div>
                                            <input type="text" name="quantidade" class="input" value="<?= $compra->getQuantidade() ?>">
                                        </div>

                                        <div class="campo campoFlex">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Data da compra</strong></label>
                                            </div>
                                            <input type="date" name="dataCompra" class="input" value="<?= $compra->getDataCompra() ?>">
                                        </div>
                                    </div>

                                    <div class="acao">
                                        <button type="submit" name="remover" class="botao">Remover</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- fim da modal -->
                    <?php } ?>

                </div>

            </div>
        </div>

    </div>

</body>

</html>