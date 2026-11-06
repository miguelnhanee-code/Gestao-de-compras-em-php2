<?php
include_once(__DIR__ . "/../controller/controllerProvincia.php");
include_once(__DIR__ . "/../controller/controllerPais.php");
include_once(__DIR__ . '/../controller/controllerLogin.php');

if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$perfil = $_SESSION['perfil'];
exigirPermissao('listar');

$provincias = listarProvincia();
$paises = listarPais();

/* REMOVER PROVÍNCIA */
if (isset($_POST['remover']) && temPermissao('remover')) {
    remocaoProvincia($_POST['id']);
    header('Location: listarProvincias.php');
    exit;
}

/* EDITAR PROVÍNCIA */
if (
    isset($_POST['guardar']) &&
    temPermissao('actualizar') &&
    !empty($_POST['id']) &&
    !empty($_POST['nome_provincia']) &&
    !empty($_POST['idPais'])
) {
    $provincia = encontraProvincia($_POST['id']);

    if ($provincia) {
        $provincia->setNomeProvincia($_POST['nome_provincia']);
        $provincia->setIdPais($_POST['idPais']);

        edicaoProvincia($provincia);
    }

    header('Location: listarProvincias.php');
    exit;
}

/* PESQUISAR PROVÍNCIA */
$pesquisa = $_POST['pesquisaCampo'] ?? '';

if (isset($_POST['pesquisarBt']) && temPermissao('pesquisar')) {
    if (!empty($pesquisa)) {
        $provincias = pesquisarProvincia($pesquisa);
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LISTAR PROVÍNCIAS</title>
    <link rel="stylesheet" href="../css/ListarProduto.css">
</head>

<body>

    <div class="navbar">
        <div class="marca">
            <img src="img/logo.png" alt="Logo" class="marca-logo">
            <div class="titulo-marca">
                SISTEMA DE<br />GESTAO DE COMPRAS
            </div>
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
                <a class="on" href="<?= $paginaProvincia ?>"><span class="icone"></span>PROVÍNCIA</a>
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
            <p>
                Olá, <?= isset($_SESSION['nome']) ? $_SESSION['nome'] : ''; ?>!
            </p>
            <p>
                Perfil: <?= isset($_SESSION['perfil']) ? $_SESSION['perfil'] : ''; ?>
            </p>
        </div>

        <a href="alterarSenha.php" class="sair">Alterar Senha</a>
        <a href="logout.php" class="sair">Log out</a>
    </div>
    <div class="mae">
        <div class="cabecalho">
            <div class="abas">

                <?php if (temPermissao('criar')) { ?>
                    <a href="cadastrarProvincia.php" class="aba">
                        CADASTRO DE PROVÍNCIAS
                    </a>
                <?php } ?>

                <div class="aba abaAtiva">
                    LISTA DE PROVÍNCIAS
                </div>

            </div>
        </div>

        <div class="dalheee">
            <div class="card">

                <div class="topo">
                    <h1 class="titulo">
                        <strong>Lista de Províncias</strong>
                    </h1>
                </div>

                <?php if (temPermissao('pesquisar')) { ?>
                    <form method="post" action="listarProvincias.php">
                        <div class="linha">
                            <div class="campo campoBusca">
                                <input type="text" name="pesquisaCampo" class="input" placeholder="Pesquisar província" value="">
                            </div>

                            <div class="campo">
                                <button type="submit" name="pesquisarBt" class="botao">
                                    Pesquisar
                                </button>
                            </div>

                        </div>
                    </form>
                <?php } ?>

                <div class="tabela">

                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome da Província</th>
                                <th>Nome do País</th>
                                <th>Ação</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (count($provincias) == 0) { ?>

                                <tr>
                                    <td colspan="4">
                                        SEM NENHUMA PROVÍNCIA REGISTADA
                                    </td>
                                </tr>

                            <?php } else { ?>

                                <?php foreach ($provincias as $provincia) { ?>

                                    <tr>
                                        <td> <?= $provincia->getIdProvincia() ?></td>
                                        <td><?= $provincia->getNomeProvincia() ?></td>
                                        <td> <?= $provincia->getNomePais() ?> </td>
                                        <td>
                                            <div class="acoes">
                                                <?php if (temPermissao('actualizar')) { ?>
                                                    <a href="#editar-<?= $provincia->getIdProvincia() ?>" class="botaoAtualizar"> Editar
                                                    </a>
                                                <?php } ?>

                                                <?php if (temPermissao('remover')) { ?>
                                                    <a href="#remover-<?= $provincia->getIdProvincia() ?>" class="botaoRemover">REMOVER
                                                    </a>
                                                <?php } ?>

                                            </div>
                                        </td>
                                    </tr>

                                <?php } ?>

                            <?php } ?>

                        </tbody>
                    </table>

                    <?php foreach ($provincias as $provincia) { ?>

                        <!-- MODAL EDITAR -->
                        <div class="modalFundo" id="editar-<?= $provincia->getIdProvincia() ?>">
                            <div class="modalCaixa">

                                <div class="modalTopo">
                                    <h2 class="titulo">Editar Província</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="">
                                    <input type="hidden" name="id" value="<?= $provincia->getIdProvincia() ?>">
                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label">
                                                <strong>Nome da província</strong>
                                            </label>
                                        </div>
                                        <input type="text" name="nome_provincia" class="input" value="<?= $provincia->getNomeProvincia() ?>" required>
                                    </div>

                                    <div class="campo">
                                        <label class="label">
                                            <strong>Nome do país</strong>
                                        </label>
                                        <select name="idPais" class="input" required>
                                            <option value="">Selecione um país </option>

                                            <?php foreach ($paises as $pais) { ?>
                                                <option value="<?= $pais->getIdPais() ?>" <?= $pais->getIdPais() == $provincia->getIdPais() ?
                                                                                                'selected' : '' ?>> <?= $pais->getNomePais() ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="acao">
                                        <button type="submit" name="guardar" class="botao"> Guardar </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <!-- FIM MODAL EDITAR -->

                    <?php } ?>

                    <?php foreach ($provincias as $provincia) { ?>

                        <!-- MODAL REMOVER -->
                        <div
                            class="modalFundo"
                            id="remover-<?= $provincia->getIdProvincia() ?>">
                            <div class="modalCaixa">

                                <div class="modalTopo">
                                    <h2 class="titulo">Remover Província</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="">
                                    <input type="hidden" name="id" value="<?= $provincia->getIdProvincia() ?>">
                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label">
                                                <strong>Nome da província</strong>
                                            </label>
                                        </div>
                                        <input type="text" class="input" value="<?= $provincia->getNomeProvincia() ?>" readonly>
                                    </div>

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label">
                                                <strong>Nome do país</strong>
                                            </label>
                                        </div>

                                        <input type="text" class="input" value="<?= $provincia->getNomePais() ?>" readonly>
                                    </div>
                                    <div class="acao">
                                        <button type="submit" name="remover" class="botao"> REMOVER</button>
                                    </div>

                                </form>

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