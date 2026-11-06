<?php

include_once(__DIR__ . "/../model/continenteModel.php");
include_once(__DIR__ . "/controllerLog.php");
$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");


function listarContinentes()
{
    global $conexao;
    $continentes = array();
    $sql = "SELECT * FROM continente";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $continente = new Continente($rs['idContinente'], $rs['nomeContinente']);
            array_push($continentes, $continente);
        }
    }
    return $continentes;
}

function criacaoContinente($continente)
{
    global $conexao;
    $sql = "select * from continente where nomeContinente='{$continente->getNomeContinente()}'";
    $result = mysqli_query($conexao, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "O continente ja existe";
    } else {
        $sql = "insert into continente values (null,'{$continente->getNomeContinente()}')";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            registarAccao("Cadastrou continente");
            echo "Continente inserido";
        } else
            echo "Ocorreu um erro ao gravar os dados";
    }
}

function remocaoContinente($idContinente)
{
    global $conexao;
    $sql = "delete from continente where idContinente=" . $idContinente;
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Removeu continente");
    }
}

function edicaoContinente($continente)
{
    global $conexao;

    $sql = "update continente set nomeContinente='{$continente->getNomeContinente()}' where idContinente={$continente->getIdContinente()}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Editou continente");
    } else
        echo "Ocorreu um erro ao atualizar os dados";
}

function encontraContinente($id)
{
    global $conexao;
    $sql = "select * from continente where idContinente=" . $id;
    $result = mysqli_query($conexao, $sql);
    $rs = mysqli_fetch_assoc($result);
    $continente = new Continente($rs['idContinente'], $rs['nomeContinente']);
    return $continente;
}

function pesquisarContinente($pesquisa)
{
    global $conexao;
    $continentes = array();
    $sql = "SELECT * FROM continente WHERE nomeContinente LIKE '%{$pesquisa}%'";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $continente = new Continente($rs['idContinente'], $rs['nomeContinente']);
            array_push($continentes, $continente);
        }
    }
    return $continentes;
}

