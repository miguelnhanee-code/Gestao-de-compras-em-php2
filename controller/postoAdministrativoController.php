<?php
include_once(__DIR__ . "/../model/postoAdministrativoModel.php");
include_once(__DIR__ . "/controllerLog.php");
$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");

function listarPostoAdministrativo()
{
    global $conexao;
    $postosAdministrativos = array();
    $sql = "SELECT
                pa.idPostoAdministrativo,
                pa.nomePostoAdministrativo,
                d.idDistrito,
                d.nomeDistrito
            FROM posto_administrativo pa
            INNER JOIN distrito d
                ON pa.idDistrito = d.idDistrito";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $postoAdministrativo = new PostoAdministrativo($rs['idPostoAdministrativo'], $rs['nomePostoAdministrativo'], $rs['idDistrito'], $rs['nomeDistrito']);
            array_push($postosAdministrativos, $postoAdministrativo);
        }
    }
    return $postosAdministrativos;
}

function criacaoPostoAdministrativo($postoAdministrativo)
{
    global $conexao;
    $sql = "select * from posto_administrativo where nomePostoAdministrativo='{$postoAdministrativo->getNomePostoAdministrativo()}' 
    and idDistrito={$postoAdministrativo->getIdDistrito()}";
    $result = mysqli_query($conexao, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "O posto administrativo já existe";
    } else {
        $sql = "insert into posto_administrativo values (null,'{$postoAdministrativo->getNomePostoAdministrativo()}'
        ,{$postoAdministrativo->getIdDistrito()})";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            registarAccao("Cadastrou posto administrativo");
            echo "Posto administrativo inserido";
        } else
            echo "Ocorreu um erro ao gravar os dados";
    }
}

function remocaoPostoAdministrativo($idPostoAdministrativo)
{
    global $conexao;
    $sql = "delete from posto_administrativo where idPostoAdministrativo=" . $idPostoAdministrativo;
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Removeu posto administrativo");
    }
}

function edicaoPostoAdministrativo($postoAdministrativo)
{
    global $conexao;

    $sql = "update posto_administrativo set nomePostoAdministrativo='{$postoAdministrativo->getNomePostoAdministrativo()}', 
    idDistrito={$postoAdministrativo->getIdDistrito()} where idPostoAdministrativo={$postoAdministrativo->getIdPostoAdministrativo()}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Editou posto administrativo");
    } else
        echo "Ocorreu um erro ao atualizar os dados";
}

function encontraPostoAdministrativo($id)
{
    global $conexao;
    $sql = "select * from posto_administrativo where idPostoAdministrativo=" . $id;
    $result = mysqli_query($conexao, $sql);
    $rs = mysqli_fetch_assoc($result);
    $postoAdministrativo = new PostoAdministrativo($rs['idPostoAdministrativo'], $rs['nomePostoAdministrativo'], $rs['idDistrito'], '');
    return $postoAdministrativo;
}

function pesquisarPostoAdministrativo($pesquisa)
{
    global $conexao;
    $postosAdministrativos = array();
    $sql = "SELECT
                pa.idPostoAdministrativo,
                pa.nomePostoAdministrativo,
                d.idDistrito,
                d.nomeDistrito
            FROM posto_administrativo pa
            INNER JOIN distrito d
                ON pa.idDistrito = d.idDistrito
            WHERE pa.nomePostoAdministrativo LIKE '%{$pesquisa}%'
               OR d.nomeDistrito LIKE '%{$pesquisa}%'
            ORDER BY pa.idPostoAdministrativo ASC";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $postoAdministrativo = new PostoAdministrativo($rs['idPostoAdministrativo'], $rs['nomePostoAdministrativo'],
             $rs['idDistrito'], $rs['nomeDistrito']);
            array_push($postosAdministrativos, $postoAdministrativo);
        }
    }
    return $postosAdministrativos;
}

function listarDistritos()
{
    global $conexao;
    $distritos = array();
    $sql = "select idDistrito, nomeDistrito from distrito order by nomeDistrito asc";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $distritos[] = $rs;
        }
    }
    return $distritos;
}
