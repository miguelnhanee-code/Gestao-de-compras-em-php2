<?php
include_once __DIR__ . '/../controller/controllerProduto.php';
include_once __DIR__ . '/../controller/controllerFornecedor.php';
include_once __DIR__ . '/../controller/controllerLogin.php';

if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$perfil = $_SESSION['perfil'];
exigirPermissao('criar');

if (isset($_POST['gravar'])) {
    if (!empty($_POST['produto']) && !empty($_POST['precoUnitario']) && !empty($_POST['dataConfeccao']) && !empty($_POST['dataValidade'])) {
        $produto = new Produto(null, $_POST['produto'], $_POST['precoUnitario'], $_POST['dataValidade'], $_POST['dataConfeccao'], $_POST['stock'], $_POST['idFornecedor'], $_POST['unidadeMedida']);
        criacaoProduto($produto);
    }
}
$fornecedores = listarFornecedor();
?>


<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>CADASTRAR PRODUTO</title>
    <link rel='stylesheet' href='../css/CadstrarProduto.css'>
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
                <a class="on" href="<?= $paginaProduto ?>"><span class="icone"></span>PRODUTO</a>
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
                <div class="aba abaAtiva">CADASTRO DE PRODUTO</div>
                <?php if (temPermissao('listar')) { ?><a href="ListarProduto.php" class="aba">LISTA DE PRODUTOS</a><?php } ?>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">
                <form method="post">

                    <div class="topo">
                        <h1 class="titulo">
                            <strong>Novo produto</strong>
                        </h1>
                    </div>

                    <div class="campo">
                        <div class="campoTipo">
                            <label class="label">
                                <strong>Nome do produto</strong>
                            </label>
                        </div>

                        <input type="text" name="produto" class="input" required>
                    </div>

                    <div class="linha">

                        <div class="campo campoFlex">
                            <div class="campoTipo">
                                <label class="label">
                                    <strong>Preço unitário</strong>
                                </label>
                            </div>

                            <input type="number" name="precoUnitario" class="input" placeholder="0.00" required>
                        </div>


                        <div class="campo campoFlex">
                            <div class="campoTipo">
                                <label class="label">
                                    <strong>Unidade Medida</strong>
                                </label>
                            </div>
                            <input type="text" name="unidadeMedida" class="input" required>
                        </div>

                        <div class="campo campoFlex">
                            <div class="campoTipo">
                                <label class="label">
                                    <strong>Stock</strong>
                                </label>
                            </div>

                            <input type="number" name="stock" class="input" placeholder="0" required>
                        </div>

                    </div>
                    <div class="campo campoFlex">
                        <div class="campoTipo">
                            <label class="label">
                                <strong>Data de Confecao</strong>
                            </label>
                        </div>

                        <input type="date" name="dataConfeccao" class="input" required>
                    </div>

                    <div class="linha">

                        <div class="campo campoFlex">
                            <div class="campoTipo">
                                <label class="label">
                                    <strong>Data de validade</strong>
                                </label>
                            </div>

                            <input type="date" name="dataValidade" class="input" required>
                        </div>



                        <div class="campo campoFlex">
                            <div class="campoTipo">
                                <label class="label">
                                    <strong>Fornecedor</strong>
                                </label>
                            </div>

                            <select name="idFornecedor" class="input" required>
                                <option value="">Seleccione o fornecedor</option>

                                <?php foreach ($fornecedores as $fornecedor) { ?>
                                    <option value="<?= $fornecedor->getIdFornecedor() ?>">
                                        <?= $fornecedor->getNomeFornecedor() ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                    </div>
                    <div class="acao">
                        <button type="submit" name="gravar" class="botao"> Cadastrar produto </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

</body>

</html>