<?php
include_once(__DIR__ . "/../controller/localidadeController.php");
include_once(__DIR__ . "/../controller/postoAdministrativoController.php");
include_once(__DIR__ . "/../controller/controllerFornecedor.php");

include_once __DIR__ . '/../controller/controllerLogin.php';
if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$perfil = $_SESSION['perfil'];
exigirPermissao('criar');

// listas completas
$continentes = listarContinentesFornecedor();
$paises      = listarPaisesFornecedor();
$provincias  = listarProvinciasFornecedor();
$distritos   = listarDistritosFornecedor();
$postos      = listarPostosFornecedor();

// valores escolhidos
$idContinente          = $_POST['idContinente'] ?? '';
$idPais                = $_POST['idPais'] ?? '';
$idProvincia           = $_POST['idProvincia'] ?? '';
$idDistrito            = $_POST['idDistrito'] ?? '';
$idPostoAdministrativo = $_POST['idPostoAdministrativo'] ?? '';


if (isset($_POST['gravar'])) {
    if (!empty($_POST['nomeLocalidade']) && !empty($_POST['idPostoAdministrativo'])) {
        $localidade = new Localidade(null, $_POST['nomeLocalidade'], $_POST['idPostoAdministrativo'], null);
        criacaoLocalidade($localidade);
    }
}

?>

<!DOCTYPE html>
<html lang='pt'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>CADASTRAR LOCALIDADE</title>
    <link rel='stylesheet' href='../css/cadastrarCaixa.css'>
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
                <a class="on" href="<?= $paginaLocalidade ?>"><span class="icone"></span>LOCALIDADE</a>
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
                <div class="aba abaAtiva">CADASTRO DE LOCALIDADE</div>
                <?php if (temPermissao('listar')) { ?><a href="listarLocalidade.php" class="aba">LISTA DE LOCALIDADES</a><?php } ?>

            </div>
        </div>

        <div class="dalheee">
            <div class="card">
                <form method="post">

                    <div class="topo">
                        <h1 class="titulo"><strong>Nova Localidade</strong></h1>
                    </div>

                    <div class="campo campoFlex">
                        <label class="label"><strong>Continente</strong></label>
                        <select name="idContinente" class="input">
                            <option value="">Selecione</option>
                            <?php foreach ($continentes as $c) { ?>
                                <option value="<?= $c['idContinente'] ?>"
                                    <?= $c['idContinente'] == $idContinente ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nomeContinente']) ?>
                                </option>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botao" formnovalidate>Filtrar países</button>
                    </div>

                    <div class="campo campoFlex">
                        <label class="label"><strong>País</strong></label>
                        <select name="idPais" class="input">
                            <option value="">Selecione</option>
                            <?php foreach ($paises as $pa) { ?>
                                <?php if ($pa['idContinente'] == $idContinente) { ?>
                                    <option value="<?= $pa['idPais'] ?>"
                                        <?= $pa['idPais'] == $idPais ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($pa['nomePais']) ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botao" formnovalidate>Filtrar províncias</button>
                    </div>

                    <div class="campo campoFlex">
                        <label class="label"><strong>Província</strong></label>
                        <select name="idProvincia" class="input">
                            <option value="">Selecione</option>
                            <?php foreach ($provincias as $p) { ?>
                                <?php if ($p['idPais'] == $idPais) { ?>
                                    <option value="<?= $p['idProvincia'] ?>"
                                        <?= $p['idProvincia'] == $idProvincia ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['nomeProvincia']) ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botao" formnovalidate>Filtrar distritos</button>
                    </div>

                    <div class="campo campoFlex">
                        <label class="label"><strong>Distrito</strong></label>
                        <select name="idDistrito" class="input">
                            <option value="">Selecione</option>
                            <?php foreach ($distritos as $d) { ?>
                                <?php if ($d['idProvincia'] == $idProvincia) { ?>
                                    <option value="<?= $d['idDistrito'] ?>"
                                        <?= $d['idDistrito'] == $idDistrito ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($d['nomeDistrito']) ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botao" formnovalidate>Filtrar postos administrativos</button>
                    </div>

                    <div class="campo">
                        <div class="campoTipo">
                            <label class="label"><strong>Posto Administrativo</strong></label>
                        </div>
                        <select name="idPostoAdministrativo" class="input">
                            <option value="">Selecione o posto administrativo</option>

                            <?php foreach ($postos as $posto) { ?>
                                <?php if ($posto['idDistrito'] == $idDistrito) { ?>
                                    <option value="<?= $posto['idPostoAdministrativo'] ?>"
                                        <?= $posto['idPostoAdministrativo'] == $idPostoAdministrativo ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($posto['nomePostoAdministrativo']) ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="campo">
                        <div class="campoTipo">
                            <label class="label"><strong>Nome da localidade</strong></label>
                        </div>
                        <input type="text" name="nomeLocalidade" class="input">
                    </div>

                    <div class="acao">
                        <button type="submit" name="gravar" class="botao" >Cadastrar localidade</button>
                    </div>

                </form>
            </div>
        </div>

    </div>

</body>

</html>