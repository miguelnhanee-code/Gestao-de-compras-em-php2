<?php
include_once __DIR__ . '/../controller/controllerPais.php';
include_once __DIR__ . '/../controller/controllerContinente.php';
include_once __DIR__ . '/../controller/controllerLogin.php';
if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
$perfil = $_SESSION['perfil'] ?? '';
exigirPermissao('listar');
$paises = listarPais();
$continentes = listarContinentes();

if (isset($_POST['remover']) && temPermissao('remover')) {
    remocaoPais($_POST['id']);
    header('Location: listarPais.php');
    exit;
}
if (
    isset($_POST['guardar']) && temPermissao('actualizar') && !empty($_POST['id']) &&
    !empty($_POST['atualizarPais']) && !empty($_POST['idContinente'])
) {
    $pais = encontraPais($_POST['id']);

    if ($pais) {
        $pais->setNomePais($_POST['atualizarPais']);
        $pais->setIdContinente($_POST['idContinente']);
        edicaoPais($pais);
    }
    header('Location: listarPais.php#');
    exit;
}
$pesquisa = $_POST['pesquisaCampo'] ?? '';
if (isset($_POST['pesquisarBt']) && temPermissao('pesquisar')) {
    if (!empty(trim($pesquisa))) {
        $paises = pesquisarPais(trim($pesquisa));
    }
}

?>

<!DOCTYPE html>
<html lang='pt'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>LISTAR PAÍSES</title>
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
                <a class="on" href="<?= $paginaPais ?>"><span class="icone"></span>PAÍS</a>
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
                <?php if (temPermissao('criar')) { ?><a href="cadastrarPais.php" class="aba">CADASTRO DE PAÍS</a><?php } ?>
                <div class="aba abaAtiva">LISTA DE PAÍSES</div>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">

                <div class="topo">
                    <h1 class="titulo"><strong>Lista de países</strong></h1>
                </div>

                <?php if (temPermissao('pesquisar')) { ?>
                    <form method="post">
                        <div class="linha">
                            <div class="campo campoBusca">
                                <input type="text" name="pesquisaCampo" class="input" placeholder="Pesquisar país ou continente" value="<?= $pesquisa ?>">
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
                                <th>Nome do país</th>
                                <th>Continente</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <?php if (count($paises) == 0) { ?>
                            <tr>
                                <td colspan="4">Nenhum país encontrado.</td>
                            </tr>
                        <?php } else { ?>
                            <?php foreach ($paises as $pais) { ?>

                                <tr>
                                    <td><?= $pais->getIdPais() ?></td>
                                    <td><?= $pais->getNomePais() ?></td>
                                    <td><?= $pais->getNomeContinente() ?></td>

                                    <td>
                                        <div class="acoes">

                                            <?php
                                            if (temPermissao('actualizar') || temPermissao('remover')) { ?>

                                                <?php
                                                if (temPermissao('actualizar')) {
                                                ?>
                                                    <a href="#editar-<?= $pais->getIdPais() ?>" class="botaoAtualizar">
                                                        Editar
                                                    </a>
                                                <?php } ?>

                                                <?php
                                                if (temPermissao('remover')) {
                                                ?>
                                                    <a href="#remover-<?= $pais->getIdPais() ?>" class="botaoRemover">
                                                        Remover
                                                    </a>
                                                <?php } ?>

                                            <?php } ?>

                                        </div>
                                    </td>
                                </tr>
                        <?php }
                        } ?>

                    </table>

                    <?php if (temPermissao('actualizar')) { ?>
                        <?php foreach ($paises as $pais) { ?>
                            <!-- MODAL-->
                            <div class="modalFundo" id="editar-<?= $pais->getIdPais() ?>">
                                <div class="modalCaixa">
                                    <div class="modalTopo">
                                        <h2 class="titulo">Editar país</h2>
                                        <a href="#" class="modalFechar">x</a>
                                    </div>

                                    <form method="post" action="">
                                        <input type="hidden" name="id" value="<?= $pais->getIdPais() ?>">

                                        <div class="linha">
                                            <div class="campo campoFlex">
                                                <div class="campoTipo">
                                                    <label class="label"><strong>Continente</strong></label>
                                                </div>

                                                <select name="idContinente" class="input" required>
                                                    <option value="">Selecione um continente</option>

                                                    <?php foreach ($continentes as $continente) { ?>

                                                        <option value="<?= $continente->getIdContinente() ?>" <?= $continente->getIdContinente() == $pais->getIdContinente() ? 'selected' : '' ?>>
                                                            <?= $continente->getNomeContinente() ?>
                                                        </option>

                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label" for="atualizarPais<?= $pais->getIdPais() ?>"><strong>Nome do país</strong></label>
                                            </div>
                                            <input type="text" id="atualizarPais<?= $pais->getIdPais() ?>" name="atualizarPais" class="input" value="<?= $pais->getNomePais() ?>" required>
                                        </div>

                                        <div class="acao">
                                            <button type="submit" name="guardar" class="botao">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- fim da modal -->
                        <?php } ?>
                    <?php } ?>

                    <?php if (temPermissao('remover')) { ?>
                        <?php foreach ($paises as $pais) { ?>
                            <!-- MODAL-->
                            <div class="modalFundo" id="remover-<?= $pais->getIdPais() ?>">
                                <div class="modalCaixa">
                                    <div class="modalTopo">
                                        <h2 class="titulo">Remover país</h2>
                                        <a href="#" class="modalFechar">x</a>
                                    </div>

                                    <form method="post" action="">
                                        <input type="hidden" name="id" value="<?= $pais->getIdPais() ?>">

                                        <p>Tem a certeza de que deseja remover este país?</p>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Nome do país</strong></label>
                                            </div>
                                            <input type="text" class="input" value="<?= $pais->getNomePais() ?>" readonly>
                                        </div>

                                        <div class="acao">
                                            <button type="submit" name="remover" class="botao">Confirmar remoção</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- fim da modal -->
                        <?php } ?>
                    <?php } ?>

                </div>

            </div>
        </div>

    </div>

</body>

</html>