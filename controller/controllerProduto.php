<?php

include_once(__DIR__ . "/../model/Produto.php");
include_once(__DIR__ . "/controllerLog.php");
$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");

function listarProduto()
{
    global $conexao;
    $produtos = array();
    $sql = "select p.*, f.nomeFornecedor from produto p left join fornecedor f on f.idFornecedor = p.idFornecedor";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $produto = new Produto($rs['idProduto'], $rs['produto'], $rs['precoUnitario'], $rs['dataValidade'], $rs['dataConfecao'],
             $rs['stock'], $rs['nomeFornecedor'], $rs['unidadeMedida']);
            array_push($produtos, $produto);
        }
    }
    return $produtos;
}

function criacaoProduto($produto)
{
    global $conexao;
    $sql = "select * from produto where produto='{$produto->getProduto()}'";
    $result = mysqli_query($conexao, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "O produto ja existe na agenda";
    } else {
        $idFornecedor = $produto->getIdFornecedor() ?: 'null';
        $sql = "insert into produto (idProduto, produto, precoUnitario, dataConfecao, dataValidade, idFornecedor, stock, unidadeMedida)
        values (null, '{$produto->getProduto()}', {$produto->getPrecoUnitario()}, '{$produto->getDataConfecao()}',
         '{$produto->getDataValidade()}', {$idFornecedor}, {$produto->getStock()}, '{$produto->getUnidadeMedida()}')";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            registarAccao("Cadastrou produto");
        }
        echo "Produto inserido";
        if ($result) {
            // header('location:../view/ListarProduto.php');
        } else
            echo "Ocorreu um erro ao gravar os dados";
    }
}

function remocaoProduto($idProduto)
{
    global $conexao;
    $sql = "delete from produto where idProduto=" . $idProduto;
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Removeu produto");
    }
    // header('location:../view/ListarProduto.php');
}

function edicaoProduto($produto)
{
    global $conexao;

    $idFornecedor = $produto->getIdFornecedor() ?: 'null';
    $sql = "update produto set produto='{$produto->getProduto()}', precoUnitario={$produto->getPrecoUnitario()},
    dataConfecao='{$produto->getDataConfecao()}', dataValidade='{$produto->getDataValidade()}',
    idFornecedor={$idFornecedor}, stock={$produto->getStock()}, unidadeMedida='{$produto->getUnidadeMedida()}'
    where idProduto={$produto->getIdproduto()}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Editou produto");
    }
    if ($result) {
        //  header('location:../view/ListarProduto.php');
    } else
        echo "Ocorreu um erro ao atualizar os dados";
}

function encontraProduto($id)
{
    global $conexao;
    $sql = "select * from produto where idProduto=" . $id;
    $result = mysqli_query($conexao, $sql);
    $rs = mysqli_fetch_assoc($result);
    $produto = new Produto($rs['idProduto'], $rs['produto'], $rs['precoUnitario'], $rs['dataValidade'], $rs['dataConfecao'],
     $rs['stock'], $rs['idFornecedor'], $rs['unidadeMedida']);
    return $produto;
}

function pesquisarProduto($pesquisa)
{
    global $conexao;
    $produtos = array();
    $sql = "select p.*, f.nomeFornecedor from produto p left join fornecedor f on f.idFornecedor = p.idFornecedor
    where p.idProduto like '%{$pesquisa}%'
    or p.produto like '%{$pesquisa}%'
    or p.stock like '%{$pesquisa}%'
    or p.unidadeMedida like '%{$pesquisa}%'
    or p.dataConfecao like '%{$pesquisa}%'
    or p.dataValidade like '%{$pesquisa}%'
    or p.precoUnitario like '%{$pesquisa}%'
    or f.nomeFornecedor like '%{$pesquisa}%'";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $produto = new Produto($rs['idProduto'], $rs['produto'], $rs['precoUnitario'], $rs['dataValidade'],
             $rs['dataConfecao'], $rs['stock'], $rs['nomeFornecedor'], $rs['unidadeMedida']);
            array_push($produtos, $produto);
        }
    }
    return $produtos;
}
    