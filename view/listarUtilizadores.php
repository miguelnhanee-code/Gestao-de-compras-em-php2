<?php
// include_once("clienteController.php");
include_once __DIR__ . '/../controller/utilizadorController.php';

include_once __DIR__ . '/../controller/controllerLogin.php';

if (!$sessao->esta_logado()) {
    header("Location: Login.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$perfil = $_SESSION['perfil'];

exigirPermissao('gerir_utilizador');

$perfis = listarPerfis();
$nomesPerfis = array_column($perfis, 'nomePerfil', 'idPerfil');

$utilizadores = listarUtilizador();

$pesquisa = $_POST['pesquisaCampo'] ?? '';

if (isset($_POST['pesquisarBt']) && temPermissao('pesquisar')) {
    if (!empty($pesquisa)) {
        $utilizadores = pesquisarUtilizador($pesquisa);
    }
}

if (isset($_POST['guardar']) && !empty($_POST['id'])) {
    $atual = encontraUtilizador((int) $_POST['id']);

    if ($atual) {
        $novoContacto = !empty($_POST['contacto'])
            ? $_POST['contacto']
            : $atual->getContacto();

        $novoPerfil = !empty($_POST['perfil'])
            ? $_POST['perfil']
            : $atual->getPerfil();

        $utilizador = new utilizadores(
            $atual->getIdUtilizador(),
            $atual->getNome(),
            $atual->getApelido(),
            $atual->getEmail(),
            $novoContacto,
            $atual->getUsername(),
            $atual->getSenha(),
            $novoPerfil,
            $atual->getBi(),
            $atual->getEstadoCivil(),
            $atual->getSexo()
        );

        edicaoUtilizador($utilizador);
    }

    header('Location: listarUtilizadores.php');
    exit;
}

if (isset($_POST['remover']) && !empty($_POST['id'])) {
    remocaoUtilizador((int) $_POST['id']);

    header('Location: listarUtilizadores.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LISTAR UTILIZADORES</title>
    <link rel="stylesheet" href="../css/listarUtilizadores.css">
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
                <a class="on" href="cadastarUtilizadores.php"><span class="icone"></span>UTILIZADORES</a>
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
                <a href="cadastarUtilizadores.php" class="aba">CADASTRO DE UTILIZADORES</a>
                <div class="aba abaAtiva">LISTA DE UTILIZADORES</div>
            </div>
        </div>

        <div class="dalheee">
            <div class="card">

                <div class="topo">
                    <h1 class="titulo"><strong>Lista de Utilizadores</strong></h1>
                </div>

                <?php if (temPermissao('pesquisar')) { ?>
                    <form method="post" action="listarUtilizadores.php">
                        <div class="linha">
                            <div class="campo campoBusca">
                                <input
                                    type="text"
                                    name="pesquisaCampo"
                                    class="input"
                                    placeholder="Pesquisar utilizador"
                                    value="<?= htmlspecialchars($pesquisa, ENT_QUOTES, 'UTF-8') ?>">
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
                                <th>Nome</th>
                                <th>Apelido</th>
                                <th>Contacto</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Perfil</th>
                                <th>Genero</th>
                                <th>Estado Civil</th>
                                <th>Numero de BI</th>
                                <th>Acoes</th>
                            </tr>
                        </thead>

                        <?php if (count($utilizadores) == 0) { ?>
                            <tr>
                                <td colspan="11">SEM NENHUM UTILIZADOR REGISTADO</td>
                            </tr>
                        <?php } else { ?>

                            <?php foreach ($utilizadores as $utilizador) { ?>
                                <tr>
                                    <td><?= $utilizador->getIdUtilizador() ?></td>
                                    <td><?= $utilizador->getNome() ?></td>
                                    <td><?= $utilizador->getApelido() ?></td>
                                    <td><?= $utilizador->getContacto() ?></td>
                                    <td><?= $utilizador->getEmail() ?></td>
                                    <td><?= $utilizador->getUsername() ?></td>
                                    <td><?= $nomesPerfis[$utilizador->getPerfil()] ?? $utilizador->getPerfil() ?></td>
                                    <td><?= $utilizador->getSexo() ?></td>
                                    <td><?= $utilizador->getEstadoCivil() ?></td>
                                    <td><?= $utilizador->getBi() ?></td>
                                    <td>
                                        <div class="acoes">

                                            <?php if (temPermissao('actualizar')) { ?>
                                                <a href="#editar-<?= $utilizador->getIdUtilizador() ?>" class="botaoAtualizar">Editar</a>
                                            <?php } ?>

                                            <?php if (temPermissao('remover')) { ?>
                                                <a href="#removerModal-<?= $utilizador->getIdUtilizador() ?>" class="botaoRemover">Remover</a>
                                            <?php } ?>

                                            <a href="#resetarModal-<?= $utilizador->getIdUtilizador() ?>" class="botaoRemover">Resetar Senha</a>

                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>

                        <?php } ?>
                    </table>

                    <?php for ($i = 0; $i < count($utilizadores); $i++) { ?>

                        <!-- MODAL EDITAR -->
                        <div class="modalFundo" id="editar-<?= $utilizadores[$i]->getIdUtilizador() ?>">
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">Editar utilizador</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="listarUtilizadores.php">
                                    <input type="hidden" name="id" value="<?= $utilizadores[$i]->getIdUtilizador() ?>">

                                    <div class="container-campos">
                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Nome</strong></label>
                                            </div>
                                            <input type="text" name="nome" class="input" value="<?= $utilizadores[$i]->getNome() ?>" required readonly>
                                        </div>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Apelido</strong></label>
                                            </div>
                                            <input type="text" name="apelido" class="input" value="<?= $utilizadores[$i]->getApelido() ?>" required readonly>
                                        </div>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Email</strong></label>
                                            </div>
                                            <input type="email" name="email" class="input" value="<?= $utilizadores[$i]->getEmail() ?>" required readonly>
                                        </div>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Contacto</strong></label>
                                            </div>
                                            <input type="text" name="contacto" class="input" value="<?= $utilizadores[$i]->getContacto() ?>" required>
                                        </div>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Username</strong></label>
                                            </div>
                                            <input type="text" name="username" class="input" value="<?= $utilizadores[$i]->getUsername() ?>" required readonly>
                                        </div>

                                        <div class="campo">
                                            <label class="label"><strong>Perfil</strong></label>
                                            <select name="perfil" class="input">
                                                <?php foreach ($perfis as $p) { ?>
                                                    <option
                                                        value="<?= $p['nomePerfil'] ?>"
                                                        <?= $utilizadores[$i]->getPerfil() == $p['idPerfil'] ? 'selected' : '' ?>>
                                                        <?= $p['nomePerfil'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Genero</strong></label>
                                            </div>
                                            <input type="text" class="input" value="<?= $utilizadores[$i]->getSexo() ?>" readonly>
                                        </div>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Estado Civil</strong></label>
                                            </div>
                                            <input type="text" class="input" value="<?= $utilizadores[$i]->getEstadoCivil() ?>" readonly>
                                        </div>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Numero de BI</strong></label>
                                            </div>
                                            <input type="text" class="input" value="<?= $utilizadores[$i]->getBi() ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="acao">
                                        <button type="submit" name="guardar" class="botao">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    <?php } ?>

                    <?php for ($i = 0; $i < count($utilizadores); $i++) { ?>

                        <!-- MODAL REMOVER -->
                        <div class="modalFundo" id="removerModal-<?= $utilizadores[$i]->getIdUtilizador() ?>">
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">Remover utilizador</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="">
                                    <input type="hidden" name="id" value="<?= $utilizadores[$i]->getIdUtilizador() ?>">

                                    <div class="container-campos">
                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Email</strong></label>
                                            </div>
                                            <input type="text" class="input" value="<?= $utilizadores[$i]->getEmail() ?>" readonly>
                                        </div>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Apelido do Utilizador</strong></label>
                                            </div>
                                            <input type="text" class="input" value="<?= $utilizadores[$i]->getApelido() ?>" readonly>
                                        </div>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Contacto</strong></label>
                                            </div>
                                            <input type="text" class="input" value="<?= $utilizadores[$i]->getContacto() ?>" readonly>
                                        </div>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Username</strong></label>
                                            </div>
                                            <input type="text" class="input" value="<?= $utilizadores[$i]->getUsername() ?>" readonly>
                                        </div>

                                        <div class="campo">
                                            <label class="label"><strong>Perfil</strong></label>
                                            <input type="text" class="input" value="<?= $nomesPerfis[$utilizadores[$i]->getPerfil()] ?? $utilizadores[$i]->getPerfil() ?>" readonly>
                                        </div>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Nome</strong></label>
                                            </div>
                                            <input type="text" class="input" value="<?= $utilizadores[$i]->getNome() ?>" readonly>
                                        </div>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Genero</strong></label>
                                            </div>
                                            <input type="text" class="input" value="<?= $utilizadores[$i]->getSexo() ?>" readonly>
                                        </div>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Estado Civil</strong></label>
                                            </div>
                                            <input type="text" class="input" value="<?= $utilizadores[$i]->getEstadoCivil() ?>" readonly>
                                        </div>

                                        <div class="campo">
                                            <div class="campoTipo">
                                                <label class="label"><strong>Numero de BI</strong></label>
                                            </div>
                                            <input type="text" class="input" value="<?= $utilizadores[$i]->getBi() ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="acao">
                                        <button type="submit" name="remover" class="botao">Remover</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    <?php } ?>

                    <?php for ($i = 0; $i < count($utilizadores); $i++) { ?>

                        <!-- MODAL RESETAR SENHA -->
                        <div class="modalFundo" id="resetarModal-<?= $utilizadores[$i]->getIdUtilizador() ?>">
                            <div class="modalCaixa">
                                <div class="modalTopo">
                                    <h2 class="titulo">Resetar senha</h2>
                                    <a href="#" class="modalFechar">x</a>
                                </div>

                                <form method="post" action="">
                                    <input type="hidden" name="id" value="<?= $utilizadores[$i]->getIdUtilizador() ?>">

                                    <p>
                                        Tem certeza que deseja resetar a senha de<br>
                                        <strong><?= $utilizadores[$i]->getNome() ?> <?= $utilizadores[$i]->getApelido() ?></strong>
                                        (<?= $utilizadores[$i]->getUsername() ?>)?
                                    </p>

                                    <div class="acao">
                                        <button type="submit" name="resetar" class="botao">Sim, resetar</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    <?php } ?>

                </div>

            </div>
        </div>

    </div>

</body>

</html>