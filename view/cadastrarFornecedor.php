<?php
include_once(__DIR__ . "/../controller/controllerFornecedor.php");
include_once(__DIR__ . "/../controller/bairroController.php");

include_once __DIR__ . '/../controller/controllerLogin.php';
if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$perfil = $_SESSION['perfil'] ?? '';
exigirPermissao('criar');

// listas completas
$continentes = listarContinentesFornecedor();
$paises      = listarPaisesFornecedor();
$provincias  = listarProvinciasFornecedor();
$distritos   = listarDistritosFornecedor();
$postos      = listarPostosFornecedor();
$localidades = listarLocalidades();
$bairros     = listarBairro();

// valores escolhidos
$idContinente = $_POST['idContinente'] ?? '';
$idPais       = $_POST['idPais'] ?? '';
$idProvincia  = $_POST['idProvincia'] ?? '';
$idDistrito   = $_POST['idDistrito'] ?? '';
$idPosto      = $_POST['idPostoAdministrativo'] ?? '';
$idLocalidade = $_POST['idLocalidade'] ?? '';
$idBairro     = $_POST['idBairro'] ?? '';

if (isset($_POST['gravar'])) {
    if (
        !empty($_POST['nomeFornecedor']) &&
        !empty($_POST['contacto']) &&
        !empty($_POST['email']) &&
        !empty($idProvincia) &&
        !empty($idDistrito) &&
        !empty($idPosto) &&
        !empty($idBairro) &&
        !empty($_POST['quarteirao']) &&
        !empty($_POST['nrEmpresa'])
    ) {
        $fornecedor = new Fornecedor(
            null,
            trim($_POST['nomeFornecedor']),
            $_POST['contacto'],
            trim($_POST['email']),
            $idProvincia,
            null,
            $idDistrito,
            null,
            $idPosto,
            null,
            $idBairro,
            null,
            trim($_POST['quarteirao']),
            trim($_POST['nrEmpresa'])
        );
        criacaoFornecedor($fornecedor);
    } else {
        echo "Preencha todos os campos, incluindo a localização até ao bairro.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CADASTRAR FORNECEDOR</title>
    <link rel="stylesheet" href="../css/CadstrarProduto.css">
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
                <a class="on" href="<?= $paginaFornecedor ?>"><span class="icone"></span>FORNECEDOR</a>
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
                <div class="aba abaAtiva">CADASTRO DE FORNECEDORES</div>
                <?php if (temPermissao('listar')) { ?><a href="listarFornecedores.php" class="aba">LISTA DE FORNECEDORES</a><?php } ?>

            </div>
        </div>

        <div class="dalheee">
            <div class="card">
                <form method="post">

                    <!-- Botão por omissão (Enter): grava em vez de filtrar -->
                    <button type="submit" name="gravar" style="display:none" tabindex="-1" aria-hidden="true"></button>

                    <div class="topo">
                        <h1 class="titulo"><strong>Novo fornecedor</strong></h1>
                    </div>

                    <div class="campo">
                        <div class="campoTipo">
                            <label class="label"><strong>Nome do fornecedor</strong></label>
                        </div>
                        <input type="text" name="nomeFornecedor" class="input" value="<?= $_POST['nomeFornecedor'] ?? '' ?>" required>
                    </div>

                    <div class="campo">
                        <div class="campoTipo">
                            <label class="label"><strong>Contacto</strong></label>
                        </div>
                        <input type="number" name="contacto" class="input" value="<?= $_POST['contacto'] ?? '' ?>" required>
                    </div>

                    <div class="campo">
                        <div class="campoTipo">
                            <label class="label"><strong>Email</strong></label>
                        </div>
                        <input type="email" name="email" class="input" value="<?= $_POST['email'] ?? '' ?>" required>
                    </div>

                    <div class="campo campoFlex">
                        <label class="label"><strong>Continente</strong></label>
                        <select name="idContinente" class="input">
                            <option value="">Selecione</option>
                            <?php foreach ($continentes as $continente) { ?>
                                <option value="<?= $continente['idContinente'] ?>"
                                    <?= $continente['idContinente'] == $idContinente ? 'selected' : '' ?>>
                                    <?= $continente['nomeContinente'] ?>
                                </option>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botao-atualizar" formnovalidate>Filtrar países</button>
                    </div>

                    <div class="campo campoFlex">
                        <label class="label"><strong>País</strong></label>
                        <select name="idPais" class="input">
                            <option value="">Selecione</option>
                            <?php foreach ($paises as $pais) { ?>
                                <?php if ($pais['idContinente'] == $idContinente) { ?>
                                    <option value="<?= $pais['idPais'] ?>"
                                        <?= $pais['idPais'] == $idPais ? 'selected' : '' ?>>
                                        <?= $pais['nomePais'] ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botao-atualizar" formnovalidate>Filtrar províncias</button>
                    </div>

                    <div class="campo campoFlex">
                        <label class="label"><strong>Província</strong></label>
                        <select name="idProvincia" class="input" required>
                            <option value="">Selecione</option>
                            <?php foreach ($provincias as $provincia) { ?>
                                <?php if ($provincia['idPais'] == $idPais) { ?>
                                    <option value="<?= $provincia['idProvincia'] ?>"
                                        <?= $provincia['idProvincia'] == $idProvincia ? 'selected' : '' ?>>
                                        <?= $provincia['nomeProvincia'] ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botao-atualizar" formnovalidate>Filtrar distritos</button>
                    </div>

                    <div class="campo campoFlex">
                        <label class="label"><strong>Distrito</strong></label>
                        <select name="idDistrito" class="input" required>
                            <option value="">Selecione</option>
                            <?php foreach ($distritos as $distrito) { ?>
                                <?php if ($distrito['idProvincia'] == $idProvincia) { ?>
                                    <option value="<?= $distrito['idDistrito'] ?>"
                                        <?= $distrito['idDistrito'] == $idDistrito ? 'selected' : '' ?>>
                                        <?= $distrito['nomeDistrito'] ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botao-atualizar" formnovalidate>Filtrar postos</button>
                    </div>

                    <div class="campo campoFlex">
                        <label class="label"><strong>Posto Administrativo</strong></label>
                        <select name="idPostoAdministrativo" class="input" required>
                            <option value="">Selecione</option>
                            <?php foreach ($postos as $posto) { ?>
                                <?php if ($posto['idDistrito'] == $idDistrito) { ?>
                                    <option value="<?= $posto['idPostoAdministrativo'] ?>"
                                        <?= $posto['idPostoAdministrativo'] == $idPosto ? 'selected' : '' ?>>
                                        <?= $posto['nomePostoAdministrativo'] ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botao-atualizar" formnovalidate>Filtrar localidades</button>
                    </div>

                    <div class="campo campoFlex">
                        <label class="label"><strong>Localidade</strong></label>
                        <select name="idLocalidade" class="input">
                            <option value="">Selecione</option>
                            <?php foreach ($localidades as $l) { ?>
                                <?php if ($l['idPostoAdministrativo'] == $idPosto) { ?>
                                    <option value="<?= $l['idLocalidade'] ?>"
                                        <?= $l['idLocalidade'] == $idLocalidade ? 'selected' : '' ?>>
                                        <?= $l['nomeLocalidade'] ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <button type="submit" name="filtrar" class="botao-atualizar" formnovalidate>Filtrar bairros</button>
                    </div>

                    <div class="campo">
                        <label class="label"><strong>Bairro</strong></label>
                        <select name="idBairro" class="input" required>
                            <option value="">Selecione</option>
                            <?php foreach ($bairros as $bairro) { ?>
                                <?php if ($bairro->getIdLocalidade() == $idLocalidade) { ?>
                                    <option value="<?= $bairro->getIdBairro() ?>"
                                        <?= $bairro->getIdBairro() == $idBairro ? 'selected' : '' ?>>
                                        <?= $bairro->getNomeBairro() ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="campo">
                        <div class="campoTipo">
                            <label class="label"><strong>Quarteirão</strong></label>
                        </div>
                        <input type="text" name="quarteirao" class="input" value="<?= $_POST['quarteirao'] ?? '' ?>" required>
                    </div>

                    <div class="campo">
                        <div class="campoTipo">
                            <label class="label"><strong>Nº da Empresa</strong></label>
                        </div>
                        <input type="text" name="nrEmpresa" class="input" value="<?= $_POST['nrEmpresa'] ?? '' ?>" required>
                    </div>

                    <div class="acao">
                        <button type="submit" name="gravar" class="botao">Cadastrar fornecedor</button>
                    </div>

                </form>
            </div>
        </div>

    </div>

</body>

</html>