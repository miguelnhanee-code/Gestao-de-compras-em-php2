<?php
include_once(__DIR__ . "/../controller/clienteController.php");

include_once __DIR__ . '/../controller/controllerLogin.php';
if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
$perfil = $_SESSION['perfil'];
exigirPermissao('listar');

$clientes = listarCliente();
$caixas = listarCaixas();

if (isset($_POST['remover']) && temPermissao('remover')) {
    global $conexao;
    remocaoCliente($_POST['id']);
    header('Location: listarClientes.php');
    exit;
}

if (isset($_POST['guardar']) && temPermissao('actualizar') && !empty($_POST['id']) && !empty($_POST['nome_cliente']) && !empty($_POST['apelido_cliente']) && !empty($_POST['idCaixa'])) {
    $cliente = encontraCliente($_POST['id']);
    if ($cliente) {
        $cliente->setNomecliente($_POST['nome_cliente']);
        $cliente->setApelidoCliente($_POST['apelido_cliente']);
        $cliente->setIdCaixa($_POST['idCaixa']);
        edicaoCliente($cliente);
    }
    header('Location: listarClientes.php#');
    exit;
}

$clientes = listarCliente();
$pesquisa = $_POST['pesquisaCampo'] ?? '';

if (temPermissao('pesquisar') && isset($_POST['pesquisarBt'])) {
    if (!empty($pesquisa)) {
        $clientes = pesquisarCliente($pesquisa);
    }
}

?>



<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>LISTAR CLIENTES</title>
    <link rel='stylesheet' href='../css/ListarProduto.css'>
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
                <a href="<?= $paginaCaixa ?>"><span class="icone"></span>CAIXA</a>
                <a class="on" href="<?= $paginaCliente ?>"><span class="icone"></span>CLIENTE</a>

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
                <?php if (temPermissao('criar')) { ?><a href="../view/cadastarCliente.php" class="aba">CADASTRO DE CLIENTES</a><?php } ?>
                <div class="aba abaAtiva">LISTA DE CLIENTES</div>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">

                <div class="topo">
                    <h1 class="titulo"><strong>Lista de Clientes</strong></h1>
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
                                <th>Nome do Cliente</th>
                                <th>Apelido do Cliente</th>
                                <th>Nome do Caixa</th>
                                <th>Acao</th>
                            </tr>
                        </thead>

                        <?php if (count($clientes) == 0) { ?>
                            <tr>
                                <td>SEM NENHUM CAIXA REGISTADO</td>
                            </tr>
                        <?php } else { ?>
                            <?php foreach ($clientes as $cliente) { ?>

                                <tr>
                                    <td><?= $cliente->getIdCliente() ?></td>
                                    <td><?= $cliente->getNomeCliente() ?></td>
                                    <td><?= $cliente->getApelidoCliente() ?></td>
                                    <td><?= $cliente->getNomeCaixa() ?></td>

                                    <td>
                                        <div class="acoes">

                                            <?php if (temPermissao('actualizar') || temPermissao('remover')) { ?>
                                                <?php if (temPermissao('actualizar')) {
                                                    $idCliente = $cliente->getIdCliente(); ?>
                                                    <a href="#editar-<?= $idCliente ?>" class="botaoAtualizar"> Editar </a> <?php } ?>
                                                <?php
                                                if (temPermissao('remover')) {
                                                    $idCliente = $cliente->getIdCliente();  ?>
                                                    <a href="#remover-<?= $idCliente ?>" class="botaoRemover"> REMOVER </a>
                                                <?php } ?>
                                            <?php } ?>
                                            <form method="post">
                                                <input type="hidden" name="id" value="<?= $cliente->getIdCliente() ?>">
                                                <!-- <button type="submit" class="botaoRemover" name="remover"> Remover </button> -->

                                            </form>
                                        </div>
                                    </td>
                                </tr>
                        <?php }
                        } ?>

                    </table>

                    <?php foreach ($clientes as $cliente) {   ?>
                        <!-- MODAL EDITAR-->
                        <div class="modalFundo" id="editar-<?= $cliente->getIdCliente() ?>">
                            <!-- <a href="#" class="modalOverlay" aria-label="Fechar"></a> -->
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">Editar Cliente</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="">
                                    <input type="hidden" name="id" value="<?= $cliente->getIdCliente() ?>">

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Nome do cliente</strong></label>
                                        </div>
                                        <input type="text" name="nome_cliente" class="input" value="<?= $cliente->getNomeCliente() ?>">
                                    </div>

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Apelido do Cliente</strong></label>
                                        </div>
                                        <input type="text" name="apelido_cliente" class="input" value="<?= $cliente->getApelidoCliente() ?>">
                                    </div>

                                    <div class="campo">
                                        <label class="label"> <strong>Nome do caixa</strong></label>

                                        <select name="idCaixa" class="input">
                                            <option value=""> Selecione um caixa</option>

                                            <?php foreach ($caixas as $caixa) { ?>

                                                <option value="<?= $caixa['idCaixa'] ?>"
                                                    <?= $caixa['idCaixa'] == $cliente->getIdCaixa() ? 'selected' : '' ?>>
                                                    <?= $caixa['nomeCaixa'] ?>
                                                </option>

                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="acao">
                                        <button type="submit" name="guardar" class="botao">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- fim da modal -->
                    <?php } ?>

                    <?php foreach ($clientes as $cliente) {   ?>
                        <!-- MODAL REMOVER-->
                        <div class="modalFundo" id="remover-<?= $cliente->getIdCliente() ?>">
                            <!-- <a href="#" class="modalOverlay" aria-label="Fechar"></a> -->
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">RENOVER Cliente</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="">
                                    <input type="hidden" name="id" value="<?= $cliente->getIdCliente() ?>">

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Nome do cliente</strong></label>
                                        </div>
                                        <input type="text" name="nome_cliente" class="input" value="<?= $cliente->getNomeCliente() ?>">
                                    </div>

                                    <div class="campo">
                                        <div class="campoTipo">
                                            <label class="label"><strong>Apelido do Cliente</strong></label>
                                        </div>
                                        <input type="text" name="apelido_cliente" class="input" value="<?= $cliente->getApelidoCliente() ?>">
                                    </div>

                                    <div class="campo">
                                        <label class="label"> <strong>Nome do caixa</strong></label>

                                        <select name="idCaixa" class="input">
                                            <option value=""> Selecione um caixa</option>

                                            <?php foreach ($caixas as $caixa) { ?>

                                                <option value="<?= $caixa['idCaixa'] ?>"
                                                    <?= $caixa['idCaixa'] == $cliente->getIdCaixa() ? 'selected' : '' ?>>
                                                    <?= $caixa['nomeCaixa'] ?>
                                                </option>

                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="acao">
                                        <button type="submit" name="remover" class="botao">REMOVER</button>
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