<?php

include_once(__DIR__ . "/../model/distritoModel.php");
include_once(__DIR__ . "/controllerLog.php");
$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");

function listarDistrito()
{
    global $conexao;
    $distritos = array();
    $sql = "select d.idDistrito, d.nomeDistrito, p.idProvincia, p.nomeProvincia, pa.idPais, pa.nomePais, c.idContinente, c.nomeContinente
    from distrito d inner join provincia p on d.idProvincia = p.idProvincia inner join pais pa on p.idPais = pa.idPais inner join
     continente c on pa.idContinente = c.idContinente";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $distrito = new Distrito($rs['idDistrito'], $rs['nomeDistrito'], $rs['idProvincia'], $rs['nomeProvincia'], $rs['idPais'],
             $rs['nomePais'], $rs['idContinente'], $rs['nomeContinente']);
            array_push($distritos, $distrito);
        }
    }
    return $distritos;
}

function criacaoDistrito($distrito)
{
    global $conexao;
    $sql = "select * from distrito where nomeDistrito='{$distrito->getNomeDistrito()}' and idProvincia={$distrito->getIdProvincia()}";
    $result = mysqli_query($conexao, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "O distrito ja existe";
    } else {
        $sql = "insert into distrito values (null,'{$distrito->getNomeDistrito()}',{$distrito->getIdProvincia()})";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            registarAccao("Cadastrou distrito");
            echo "Distrito inserido";
        } else
            echo "Ocorreu um erro ao gravar os dados";
    }
}

function remocaoDistrito($idDistrito)
{
    global $conexao;
    $sql = "delete from distrito where idDistrito=" . $idDistrito;
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Removeu distrito");
    }
}

function edicaoDistrito($distrito)
{
    global $conexao;

    $sql = "update distrito set nomeDistrito='{$distrito->getNomeDistrito()}', idProvincia={$distrito->getIdProvincia()} where idDistrito={$distrito->getIdDistrito()}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Editou distrito");
    } else
        echo "Ocorreu um erro ao atualizar os dados";
}

function encontraDistrito($id)
{
    global $conexao;
    $sql = "select d.idDistrito, d.nomeDistrito, p.idProvincia, p.nomeProvincia, pa.idPais, pa.nomePais, c.idContinente, c.nomeContinente
    from distrito d
    inner join provincia p on d.idProvincia = p.idProvincia
    inner join pais pa on p.idPais = pa.idPais
    inner join continente c on pa.idContinente = c.idContinente
    where d.idDistrito=" . $id;
    $result = mysqli_query($conexao, $sql);
    $rs = mysqli_fetch_assoc($result);
    $distrito = new Distrito($rs['idDistrito'], $rs['nomeDistrito'], $rs['idProvincia'], $rs['nomeProvincia'], $rs['idPais'], $rs['nomePais'],
     $rs['idContinente'], $rs['nomeContinente']);
    return $distrito;
}

function pesquisarDistrito($pesquisa)
{
    global $conexao;
    $distritos = array();
    $sql = "select d.idDistrito, d.nomeDistrito, p.idProvincia, p.nomeProvincia, pa.idPais, pa.nomePais, c.idContinente, c.nomeContinente
    from distrito d inner join provincia p on d.idProvincia = p.idProvincia inner join pais pa on p.idPais = pa.idPais inner join continente c
     on pa.idContinente = c.idContinente
    where d.nomeDistrito like '%{$pesquisa}%' or p.nomeProvincia like '%{$pesquisa}%' or pa.nomePais like '%{$pesquisa}%'
     or c.nomeContinente like '%{$pesquisa}%'";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $distrito = new Distrito($rs['idDistrito'], $rs['nomeDistrito'], $rs['idProvincia'], $rs['nomeProvincia'], $rs['idPais'], $rs['nomePais'],
             $rs['idContinente'], $rs['nomeContinente']);
            array_push($distritos, $distrito);
        }
    }
    return $distritos;
}

function listarContinentesDistrito()
{
    global $conexao;
    $continentes = array();
    $sql = "select * from continente order by nomeContinente";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            array_push($continentes, $rs);
        }
    }
    return $continentes;
}

function listarPaisesDistrito()
{
    global $conexao;
    $paises = array();
    $sql = "select * from pais order by nomePais";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            array_push($paises, $rs);
        }
    }
    return $paises;
}

function listarProvinciasDistrito()
{
    global $conexao;
    $provincias = array();
    $sql = "select * from provincia order by nomeProvincia";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            array_push($provincias, $rs);
        }
    }
    return $provincias;
}

function listarPaisesPorContinenteDistrito($idContinente)
{
    global $conexao;
    $paises = array();
    $sql = "select * from pais where idContinente=" . $idContinente . " order by nomePais";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            array_push($paises, $rs);
        }
    }
    return $paises;
}

function listarProvinciasPorPaisDistrito($idPais)
{
    global $conexao;
    $provincias = array();
    $sql = "select * from provincia where idPais=" . $idPais . " order by nomeProvincia";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            array_push($provincias, $rs);
        }
    }
    return $provincias;
}

// }