<?php
include_once(__DIR__ . "/../controller/controllerLog.php");
include_once(__DIR__ . "/../controller/controllerLogin.php");

if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$perfil = $_SESSION['perfil'];
exigirPermissao('gerir_log');
// if (isset($_POST['remover'])) {
//     remocao($_POST['remover']);
// }

$logs = listaDeLogs();
$pesquisa = $_POST['pesquisaCampo'] ?? '';
if (isset($_POST['pesquisarBt'])) {
    if (!empty($pesquisa)) {
        $logs = pesquisarLog($pesquisa);
    }
}

?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>LISTAR LOGS</title>
    <link rel='stylesheet' href='../css/Log.css'>
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
                <a href="<?= $paginaCliente ?>"><span class="icone"></span>CLIENTE</a>

            <?php } ?>

            <?php if (temPermissao('gerir_utilizador')) { ?>
                <a href="cadastarUtilizadores.php"><span class="icone"></span>UTILIZADORES</a>
                <a href="Perfil.php"><span class="icone"></span>Configurar Perfil</a>
            <?php } ?>

            <?php if (temPermissao('gerir_log')) { ?>
                <a class="on" href="ListarLog.php"><span class="icone"></span>Log</a>
            <?php } ?>
        </nav>

        <div style="margin: 15px;">
            <p>Olá, <?= isset($_SESSION['nome']) ? $_SESSION['nome'] : ''; ?>!</p>
            <p>Perfil: <?= isset($_SESSION['perfil']) ? $_SESSION['perfil'] : ''; ?></p>
        </div>
        <a href="alterarSenha.php" class="sair">Alterar Senha</a>
        <a href="logout.php" class="sair"><span class="icone"></span>Log out</a>
    </div>

    <div class="mae">

        <div class="cabecalho">
            <div class="abas">
                <div class="aba abaAtiva">LISTA DE LOGS</div>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">

                <div class="topo">
                    <h1 class="titulo"><strong>Lista de Logs</strong></h1>
                </div>

                <form method="post">
                    <div class="linha">
                        <div class="campo campoBusca">
                            <input type="text" name="pesquisaCampo" class="input" placeholder="Pesquisar ">
                        </div>
                        <div class="campo">
                            <button type="submit" name="pesquisarBt" class="botao">Pesquisar</button>
                        </div>
                    </div>
                </form>

                <div class="tabela">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Nome</th>
                                <th>Perfil</th>
                                <th>Acao</th>
                                <th>Data da Acao</th>
                            </tr>
                        </thead>
                        <?php if (count($logs) == 0) { ?>
                            <tr>
                                <td>SEM NENHUM LOG REGISTADO</td>
                            </tr>
                        <?php } else { ?>
                            <?php foreach ($logs as $log) { ?>
                                <tr>
                                    <td><?= $log->getIdLog() ?></td>
                                    <td><?= $log->getEmail() ?></td>
                                    <td><?= $log->getNome() ?></td>
                                    <td><?= $log->getPerfil() ?></td>
                                    <td><?= $log->getAccao() ?></td>
                                    <td><?= $log->getDataAccao() ?></td>
                                </tr>
                        <?php }
                        } ?>

                    </table>
                </div>

            </div>
        </div>

    </div>

</body>

</html>