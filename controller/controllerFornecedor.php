<?php

include_once(__DIR__ . "/../model/fornecedorModel.php");
include_once(__DIR__ . "/controllerLog.php");

$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");

function listarFornecedor()
{
    global $conexao;
    $fornecedores = array();
    $sql = "SELECT f.idFornecedor, f.nomeFornecedor, f.telefone, f.email, pr.idProvincia, pr.nomeProvincia, d.idDistrito, d.nomeDistrito,
        pa.idPostoAdministrativo, pa.nomePostoAdministrativo, b.idBairro, b.nomeBairro, f.quarteirao, f.nrEmpresa,
        ps.nomePais, ct.nomeContinente
        FROM fornecedor f
        LEFT JOIN provincia pr ON f.idProvincia = pr.idProvincia
        LEFT JOIN distrito d ON f.idDistrito = d.idDistrito
        LEFT JOIN posto_administrativo pa ON f.idPostoAdministrativo = pa.idPostoAdministrativo
        LEFT JOIN bairro b ON f.idBairro = b.idBairro
        LEFT JOIN pais ps ON pr.idPais = ps.idPais
        LEFT JOIN continente ct ON ps.idContinente = ct.idContinente";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $fornecedor = new Fornecedor($rs['idFornecedor'], $rs['nomeFornecedor'], $rs['telefone'], $rs['email'], $rs['idProvincia'],
             $rs['nomeProvincia'], $rs['idDistrito'], $rs['nomeDistrito'], $rs['idPostoAdministrativo'], $rs['nomePostoAdministrativo'],
              $rs['idBairro'], $rs['nomeBairro'], $rs['quarteirao'], $rs['nrEmpresa'], $rs['nomePais'], $rs['nomeContinente']);
            array_push($fornecedores, $fornecedor);
        }
    }
    return $fornecedores;
}

function criacaoFornecedor($fornecedor)
{
    global $conexao;
    $sql = "SELECT * FROM fornecedor
            WHERE nomeFornecedor = '{$fornecedor->getNomeFornecedor()}'
            AND telefone = '{$fornecedor->getTelefone()}'
            AND email = '{$fornecedor->getEmail()}'
            AND idPostoAdministrativo = {$fornecedor->getIdPostoAdministrativo()}
            AND idBairro = {$fornecedor->getIdBairro()}
            AND quarteirao = '{$fornecedor->getQuarteirao()}'
            AND nrEmpresa = '{$fornecedor->getNrEmpresa()}'";
    $result = mysqli_query($conexao, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        echo "O fornecedor ja existe";
    } else {
        $sql = "insert into fornecedor (nomeFornecedor, telefone, email, idProvincia, idDistrito, idPostoAdministrativo, idBairro, quarteirao, nrEmpresa)
                values ('{$fornecedor->getNomeFornecedor()}', '{$fornecedor->getTelefone()}', '{$fornecedor->getEmail()}', {$fornecedor->getIdProvincia()},
                {$fornecedor->getIdDistrito()}, {$fornecedor->getIdPostoAdministrativo()}, {$fornecedor->getIdBairro()},
                 '{$fornecedor->getQuarteirao()}', '{$fornecedor->getNrEmpresa()}')";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            echo "Fornecedor inserido";
            registarAccao("Fornecedor Cadastrado");
        } else {
            echo "Ocorreu um erro ao gravar os dados: " . mysqli_error($conexao);
        }
    }
}

function remocaoFornecedor($id)
{
    global $conexao;
    $sql = "delete from fornecedor where idFornecedor=" . $id;
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Fornecedor Removido");
    }
}


function edicaoFornecedor($fornecedor)
{
    global $conexao;
    $sql = "UPDATE fornecedor SET nomeFornecedor='{$fornecedor->getNomeFornecedor()}', telefone='{$fornecedor->getTelefone()}',
            email='{$fornecedor->getEmail()}', idProvincia={$fornecedor->getIdProvincia()}, idDistrito={$fornecedor->getIdDistrito()},
            idPostoAdministrativo={$fornecedor->getIdPostoAdministrativo()}, idBairro={$fornecedor->getIdBairro()}, quarteirao='{$fornecedor->getQuarteirao()}',
            nrEmpresa='{$fornecedor->getNrEmpresa()}' WHERE idFornecedor={$fornecedor->getIdFornecedor()}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Fornecedor Editado");
    } else {
        echo "Ocorreu um erro ao atualizar os dados: " . mysqli_error($conexao);
    }
}


function encontraFornecedor($id)
{
    global $conexao;
    $sql = "SELECT f.idFornecedor, f.nomeFornecedor, f.telefone, f.email, pr.idProvincia, pr.nomeProvincia, d.idDistrito, d.nomeDistrito,
        pa.idPostoAdministrativo, pa.nomePostoAdministrativo, b.idBairro, b.nomeBairro, f.quarteirao, f.nrEmpresa
        FROM fornecedor f
        LEFT JOIN provincia pr ON f.idProvincia = pr.idProvincia
        LEFT JOIN distrito d ON f.idDistrito = d.idDistrito
        LEFT JOIN posto_administrativo pa ON f.idPostoAdministrativo = pa.idPostoAdministrativo
        LEFT JOIN bairro b ON f.idBairro = b.idBairro
        WHERE f.idFornecedor=" . $id;
    $result = mysqli_query($conexao, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $rs = mysqli_fetch_assoc($result);
        return new Fornecedor($rs['idFornecedor'], $rs['nomeFornecedor'], $rs['telefone'], $rs['email'], $rs['idProvincia'], $rs['nomeProvincia'],
         $rs['idDistrito'], $rs['nomeDistrito'], $rs['idPostoAdministrativo'], $rs['nomePostoAdministrativo'], $rs['idBairro'], $rs['nomeBairro'],
          $rs['quarteirao'], $rs['nrEmpresa']);
    }

    return null;
}


function pesquisarFornecedor($pesquisa)
{
    global $conexao;
    $fornecedores = array();
    $pesquisa = mysqli_real_escape_string($conexao, $pesquisa);
    $sql = "SELECT f.idFornecedor, f.nomeFornecedor, f.telefone, f.email, pr.idProvincia, pr.nomeProvincia, d.idDistrito, d.nomeDistrito,
        pa.idPostoAdministrativo, pa.nomePostoAdministrativo, b.idBairro, b.nomeBairro, f.quarteirao, f.nrEmpresa,
        ps.nomePais, ct.nomeContinente
        FROM fornecedor f
        LEFT JOIN provincia pr ON f.idProvincia = pr.idProvincia
        LEFT JOIN distrito d ON f.idDistrito = d.idDistrito
        LEFT JOIN posto_administrativo pa ON f.idPostoAdministrativo = pa.idPostoAdministrativo
        LEFT JOIN bairro b ON f.idBairro = b.idBairro
        LEFT JOIN pais ps ON pr.idPais = ps.idPais
        LEFT JOIN continente ct ON ps.idContinente = ct.idContinente
        WHERE f.idFornecedor like '%{$pesquisa}%' or f.nomeFornecedor like '%{$pesquisa}%' or f.telefone like '%{$pesquisa}%'
        or f.email like '%{$pesquisa}%' or ct.nomeContinente like '%{$pesquisa}%' or ps.nomePais like '%{$pesquisa}%' or pr.nomeProvincia like '%{$pesquisa}%'
        or d.nomeDistrito like '%{$pesquisa}%' or pa.nomePostoAdministrativo like '%{$pesquisa}%' or b.nomeBairro like '%{$pesquisa}%'
        or f.quarteirao like '%{$pesquisa}%' or f.nrEmpresa like '%{$pesquisa}%'";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $fornecedor = new Fornecedor($rs['idFornecedor'], $rs['nomeFornecedor'], $rs['telefone'], $rs['email'], $rs['idProvincia'], $rs['nomeProvincia'],
             $rs['idDistrito'], $rs['nomeDistrito'], $rs['idPostoAdministrativo'], $rs['nomePostoAdministrativo'], $rs['idBairro'], $rs['nomeBairro'],
              $rs['quarteirao'], $rs['nrEmpresa'], $rs['nomePais'], $rs['nomeContinente']);
            array_push($fornecedores, $fornecedor);
        }
    }
    return $fornecedores;
}


function listarContinentesFornecedor()
{
    global $conexao;
    $continentes = array();
    $sql = "SELECT idContinente, nomeContinente FROM continente";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $continentes[] = $rs;
        }
    }
    return $continentes;
}
function listarPaisesFornecedor()
{
    global $conexao;
    $paises = array();
    $sql = "SELECT idPais, nomePais, idContinente FROM pais";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $paises[] = $rs;
        }
    }
    return $paises;
}
function listarProvinciasFornecedor()
{
    global $conexao;
    $provincias = array();
    $sql = "SELECT idProvincia, nomeProvincia, idPais FROM provincia";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $provincias[] = $rs;
        }
    }
    return $provincias;
}


function listarDistritosFornecedor()
{
    global $conexao;
    $distritos = array();
    $sql = "SELECT idDistrito, nomeDistrito, idProvincia FROM distrito";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $distritos[] = $rs;
        }
    }
    return $distritos;
}
function listarPostosFornecedor()
{
    global $conexao;
    $postos = array();
    $sql = "SELECT idPostoAdministrativo, nomePostoAdministrativo, idDistrito FROM posto_administrativo";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $postos[] = $rs;
        }
    }
    return $postos;
}
