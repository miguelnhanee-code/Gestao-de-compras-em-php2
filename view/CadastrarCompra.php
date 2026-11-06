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
exigirPermissao('criar');


$produtos = listarProduto();
$cliente = listarCliente();
$mensagemStock = '';

if (isset($_POST['gravar'])) {

    if (!empty($_POST['idproduto']) && !empty($_POST['idcliente']) && !empty($_POST['quantidade']) && !empty($_POST['dataCompra'])) {
        $compra = new Compra(null, $_POST['quantidade'], $_POST['dataCompra'], $_POST['idproduto'], $_POST['idcliente'], null, null, null, null, null);
        $resultado = criacaoCompra($compra);
        if ($resultado === "Nao ha Stock suficiente para realizar a compra") {
            $mensagemStock = $resultado;
        }
    }
}
?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>CADASTRAR COMPRA</title>
    <link rel='stylesheet' href='../css/CadastrarCompra.css'>
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
                <div class="aba abaAtiva">CADASTRO DE COMPRA</div>
                <?php if (temPermissao('listar')) { ?><a href="ListarCompra.php" class="aba">LISTA DE COMPRAS</a><?php } ?>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">
                <form method="post">

                    <div class="topo">
                        <h1 class="titulo"><strong>Nova compra</strong></h1>
                    </div>

                    <?php if ($mensagemStock !== '') { ?>
                        <div style="margin: 10px 0; padding: 12px 15px; border-radius: 6px; font-weight: bold; background:#fdecea; color:#c62828; border:1px solid #ef9a9a;">
                            <?= htmlspecialchars($mensagemStock) ?>
                        </div>
                    <?php } ?>

                    <div class="campo">
                        <div class="campoTipo">
                            <label class="label"><strong>Produto</strong></label>
                        </div>
                        <select name="idproduto" class="input">
                            <option value="">Selecione um produto</option>
                            <?php foreach ($produtos as $produto) { ?>
                                <option value="<?= $produto->getIdproduto() ?>"><?= $produto->getProduto() ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="linha">
                        <div class="campo campoFlex">

                            <div class="campoTipo">
                                <label class="label"><strong>Nome do Cliente</strong></label>
                            </div>
                            <select name="idcliente" class="input">
                                <option value="">Selecione um Cliente</option>
                                <?php foreach ($cliente as $cliente) { ?>
                                    <option value="<?= $cliente->getIdcliente() ?>"><?= $cliente->getNomeCliente() ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="campo campoFlex">
                            <div class="campoTipo">
                                <label class="label"><strong>Quantidade da compra</strong></label>
                            </div>
                            <input type="text" name="quantidade" class="input">
                        </div>

                        <div class="campo campoFlex">
                            <div class="campoTipo">
                                <label class="label"><strong>Data da compra</strong></label>
                            </div>
                            <input type="date" name="dataCompra" class="input">
                        </div>

                    </div>

                    <div class="acao">
                        <button type="submit" name="gravar" class="botao">Cadastrar compra</button>

                    </div>

                </form>
            </div>
        </div>

    </div>

</body>

</html>