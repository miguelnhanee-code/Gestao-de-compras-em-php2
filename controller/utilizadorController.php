<?php

include_once(__DIR__ . "/../model/utilizadoresModel.php");
include_once(__DIR__ . "/../controller/controllerLog.php");

$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");

function listarUtilizador()
{
    global $conexao;
    $utilizadores = array();
    $sql = "select * from utilizadores";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $utilizador = new utilizadores($rs['idUtilizador'], $rs['nome'], $rs['apelido'], $rs['email'], 
            $rs['contacto'], $rs['username'], $rs['senha'], $rs['perfil'], $rs['bi'], $rs['estadoCivil'], $rs['sexo']);
            array_push($utilizadores, $utilizador);
        }
    }
    return $utilizadores;
}

function criacaoUtilizador($utilizador)
{
    global $conexao;
    $sql = "select * from utilizadores where nome='{$utilizador->getNome()}' and apelido='{$utilizador->getApelido()}'
     and email='{$utilizador->getEmail()}' and contacto='{$utilizador->getContacto()}' and username='{$utilizador->getUsername()}' 
     and perfil='{$utilizador->getPerfil()}'";
    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "O utilizador ja existe";
    } else {
        $senhaEncriptada = password_hash($utilizador->getSenha(), PASSWORD_DEFAULT);
        $sql = "insert into utilizadores values (null,'{$utilizador->getNome()}','{$utilizador->getApelido()}',
        '{$utilizador->getEmail()}','{$utilizador->getContacto()}','{$utilizador->getUsername()}','{$senhaEncriptada}',
        '{$utilizador->getPerfil()}','{$utilizador->getBi()}','{$utilizador->getEstadoCivil()}','{$utilizador->getSexo()}')";
        $result = mysqli_query($conexao, $sql);
        echo "utilizador inserido";

        if ($result) {
            registarAccao("Cadastrou Utilizador");
        }

        if ($result) {
            // header('location:index.php');
        } else
            echo "Ocorreu um erro ao gravar os dados";
    }
}

function remocaoUtilizador($idUtilizador)
{
    global $conexao;
    $sql = "delete from utilizadores where idUtilizador=" . $idUtilizador;
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Removeu Utilizador");
    }
    // header('location:index.php');
}

function edicaoUtilizador($utilizador)
{
    global $conexao;
    $senhaEncriptada = password_hash($utilizador->getSenha(), PASSWORD_DEFAULT);

    $sql = "update utilizadores  set nome='{$utilizador->getNome()}', apelido='{$utilizador->getApelido()}',
     email='{$utilizador->getEmail()}', contacto='{$utilizador->getContacto()}', username='{$utilizador->getUsername()}',
      senha='{$senhaEncriptada}', perfil='{$utilizador->getPerfil()}', bi='{$utilizador->getBi()}', 
      estadoCivil='{$utilizador->getEstadoCivil()}', sexo='{$utilizador->getSexo()}' where idUtilizador={$utilizador->getIdUtilizador()}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        registarAccao("Editou utilizador");
    }
    if ($result) {
        //  header('location:index.php');
    } else
        echo "Ocorreu um erro ao atualizar os dados";
}


function encontraUtilizador($id)
{
    global $conexao;
    $sql = "select * from utilizadores where idUtilizador={$id}";
    $result = mysqli_query($conexao, $sql);
    $rs = mysqli_fetch_assoc($result);
    return new utilizadores($rs['idUtilizador'], $rs['nome'], $rs['apelido'], $rs['email'], $rs['contacto'],
     $rs['username'], $rs['senha'], $rs['perfil'], $rs['bi'], $rs['estadoCivil'], $rs['sexo']);
}

function alterarSenha($email, $senha)
{
    global $conexao;
    $senhaEncriptada = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "update utilizadores set senha='{$senhaEncriptada}' where email='{$email}'";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        //  header('location:index.php');
    } else
        echo "Ocorreu um erro ao atualizar a senha";
}


function resetarSenha($id)
{
    global $conexao;
    $senhaPadrao = password_hash("12345678", PASSWORD_DEFAULT);
    $sql = "update utilizadores set senha='{$senhaPadrao}' where idUtilizador={$id}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        echo "Senha resetada com sucesso";
        if ($result) {
            registarAccao("Resetou senha");
        }
    } else
        echo "Ocorreu um erro ao resetar a senha";
}

function verificarSenhaAntiga($email, $senha)
{
    global $conexao;
    $sql = "select * from utilizadores where email='{$email}'";
    $result = mysqli_query($conexao, $sql);
    if (mysqli_num_rows($result) > 0) {
        $rs = mysqli_fetch_assoc($result);
        return password_verify($senha, $rs['senha']);
    } else
        return false;
}
function verificarEmail($email)
{
    global $conexao;
    $sql = "select * from utilizadores where email='{$email}'";
    $result = mysqli_query($conexao, $sql);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else
        return false;
}


function pesquisarUtilizador($pesquisa)
{
    global $conexao;
    $utilizadores = array();
    $pesquisa = mysqli_real_escape_string($conexao, $pesquisa);
    $sql = "select * from utilizadores where nome like '%{$pesquisa}%' or apelido like '%{$pesquisa}%' or
     email like '%{$pesquisa}%' or contacto like '%{$pesquisa}%' or username like '%{$pesquisa}%' or perfil like '%{$pesquisa}%'";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $utilizador = new utilizadores($rs['idUtilizador'], $rs['nome'], $rs['apelido'], $rs['email'],
             $rs['contacto'], $rs['username'], $rs['senha'], $rs['perfil'], $rs['bi'], $rs['estadoCivil'], $rs['sexo']);
            array_push($utilizadores, $utilizador);
        }
    }
    return $utilizadores;
}

function listarPerfis()
{
    global $conexao;
    $perfis = array();
    $sql = "select * from perfil";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            array_push($perfis, $rs);
        }
    }
    return $perfis;
}
