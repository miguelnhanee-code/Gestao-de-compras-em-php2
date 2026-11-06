<?php

include_once(__DIR__ . "/../model/caixaModel.php");
include_once(__DIR__ . "/controllerLog.php");

$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");

function listarCaixa()
{
    global $conexao;
    $caixas = array();
    $sql = "select * from caixa";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $caixa = new Caixa($rs['idCaixa'], $rs['nomeCaixa']);
            array_push($caixas, $caixa);
        }
    }
    return $caixas;
}

function criacaoCaixa($caixa)
{
    global $conexao;
    $sql = "select * from caixa where nomeCaixa='{$caixa->getNomeCaixa()}'";
    $result = mysqli_query($conexao, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        echo "O caixa ja existe";
    } else {
        $sql = "insert into caixa (nomeCaixa) values ('{$caixa->getNomeCaixa()}')";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            echo "Caixa inserida";
            registarAccao("Cadastrou caixa");
        } else {
            echo "Ocorreu um erro ao gravar os dados: " . mysqli_error($conexao);
        }
    }
}

function remocaoCaixa($idCaixa)
{
    global $conexao;
    $sql = "delete from caixa where idCaixa=" . $idCaixa;
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Removeu caixa");
    }
}

function edicaoCaixa($caixa)
{
    global $conexao;
    $sql = "update caixa set nomeCaixa='{$caixa->getNomeCaixa()}' where idCaixa={$caixa->getIdCaixa()}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Editou caixa");
    } else {
        echo "Ocorreu um erro ao atualizar os dados: " . mysqli_error($conexao);
    }
}

function encontraCaixa($id)
{
    global $conexao;
    $sql = "select * from caixa where idCaixa=" . $id;
    $result = mysqli_query($conexao, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $rs = mysqli_fetch_assoc($result);
        return new Caixa($rs['idCaixa'], $rs['nomeCaixa']);
    }

    return null;
}

function pesquisarCaixa($pesquisa)
{
    global $conexao;
    $caixas = array();
    $sql = "select * from caixa where idCaixa like '%{$pesquisa}%' or nomeCaixa like '%{$pesquisa}%'";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $caixa = new Caixa($rs['idCaixa'], $rs['nomeCaixa']);
            array_push($caixas, $caixa);
        }
    }
    return $caixas;
}
