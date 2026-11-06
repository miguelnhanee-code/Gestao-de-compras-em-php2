<?php
include_once __DIR__ . '/../controller/controllerDashboard.php';
include_once __DIR__ . '/../controller/controllerLogin.php';
if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$perfil = $_SESSION['perfil'];

$produtos = dashboardProdutos();
$compras  = dashboardCompras();
$caixas   = dashboardCaixas();
$clientes = dashboardClientes();
$fornecedores = dashboardFornecedores();

$totalProdutos   = count($produtos);
$totalFornecedor = count($fornecedores);
$totalCompras    = count($compras);
$totalCaixas     = count($caixas);
$totalClientes   = count($clientes);

$valorPorProduto = dashboardValorPorProduto($compras);
$ultimaCompra    = dashboardUltimaCompra($compras);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>INICIO</title>
    <link rel="stylesheet" href="../css/Dashboard.css">
</head>

<body>

    <div class="navbar">
        <div class="marca">
            <img src="img/logo.png" alt="Logo" class="marca-logo">
            <div class="titulo-marca">SISTEMA DE GESTÃO DE COMPRAS</div>
        </div>

        <nav class="navlinks">
            <a class="on" href="Dashboard.php"><span class="icone"></span>Inicio</a>

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
                <a href="Perfil.php"><span class="icone"></span>Configurar Perfil</a>
            <?php } ?>

            <?php if (temPermissao('gerir_log')) { ?>
                <a href="ListarLog.php"><span class="icone"></span>Log</a>
            <?php } ?>
        </nav>

        <a href="alterarSenha.php" class="sair">Alterar Senha</a>
        <a href="logout.php" class="sair">Log out</a>

    </div>

    <div class="mae">

        <div class="topo">
            <h1>Olá, <?= isset($_SESSION['nome']) ? $_SESSION['nome'] : ''; ?>!</h1>
            <p>Perfil: <?= isset($_SESSION['perfil']) ? $_SESSION['perfil'] : ''; ?></p>

            <?php if (temPermissao('criar')) { ?><a href="CadastrarCompra.php" class="botao">Nova compra</a><?php } ?>
        </div>

        <div class="dashboard">

            <div class="quadrado quadrado1">
                <div class="icone">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2l9 5v10l-9 5-9-5V7z" />
                        <path d="M3 7l9 5 9-5M12 12v10" />
                    </svg>
                </div>
                <p class="legenda">Produtos</p>
                <p class="numero"><?= $totalProdutos ?></p>
            </div>

            <div class="quadrado quadrado2">
                <div class="icone">
                    <svg viewBox="0 0 24 24">
                        <path d="M2 6h12v10H2zM14 9h4l3 3v4h-7" />

                    </svg>
                </div>
                <p class="legenda">Fornecedor</p>
                <p class="numero"><?= $totalFornecedor ?></p>
            </div>

            <div class="quadrado quadrado3">
                <div class="icone">
                    <svg viewBox="0 0 24 24">
                        <path d="M5 8h14l-1 13H6z" />
                        <path d="M9 8V6a3 3 0 0 1 6 0v2" />
                    </svg>
                </div>
                <p class="legenda">Compra</p>
                <p class="numero"><?= $totalCompras ?></p>
            </div>

            <div class="quadrado quadrado4">
                <div class="icone">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 7v10M9.5 9.5h4a1.5 1.5 0 0 1 0 3h-3a1.5 1.5 0 0 0 0 3h4" />
                    </svg>
                </div>
                <p class="legenda">Caixa</p>
                <p class="numero"><?= $totalCaixas ?></p>
            </div>


        </div>

</body>

</html>