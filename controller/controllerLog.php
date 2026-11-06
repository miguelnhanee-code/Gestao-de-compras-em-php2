<?php

include_once(__DIR__ . "/../model/LogModel.php");

$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");
function registarLog($idUsuario, $nome, $perfil, $accao)
{
    global $conexao;
    $idUsuario = (int) $idUsuario;
    $nome = mysqli_real_escape_string($conexao, $nome);
    $perfil = mysqli_real_escape_string($conexao, $perfil);
    $accao = mysqli_real_escape_string($conexao, $accao);
    $sql = "INSERT INTO log
            (idUsuario, nome, perfil, accao, dataAccao)
            VALUES
            ('$idUsuario', '$nome', '$perfil', '$accao', NOW())";
    $result = mysqli_query($conexao, $sql);
    if (!$result) {
        echo "Ocorreu um erro ao registar o log";
    }
}

function registarAccao($accao)
{
    global $conexao;
    $idUtilizador = $_SESSION['idUtilizador'] ?? null;
    if ($idUtilizador === null) {
        return;
    }
    $sql = "SELECT nome, perfil
            FROM utilizadores
            WHERE idUtilizador = " . (int) $idUtilizador;
    $resultado = mysqli_query($conexao, $sql);
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $utilizador = mysqli_fetch_assoc($resultado);
        registarLog($idUtilizador, $utilizador['nome'], $utilizador['perfil'], $accao);
    }
}

function listaDeLogs()
{
    global $conexao;
    $logs = array();
    $sql = "SELECT log.*, u.email FROM log LEFT JOIN utilizadores u
     ON u.idUtilizador = log.idUsuario
            ORDER BY log.dataAccao DESC";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $log = new Log($rs['idLog'], $rs['idUsuario'], $rs['nome'], $rs['perfil'], 
            $rs['accao'], $rs['dataAccao'], $rs['email']);
            array_push($logs, $log);
        }
    }
    return $logs;
}

function pesquisarLog($pesquisa)
{
    global $conexao;
    $logs = array();
    $pesquisa = mysqli_real_escape_string($conexao, $pesquisa);
    $sql = "SELECT log.*, u.email
            FROM log
            LEFT JOIN utilizadores u
            ON u.idUtilizador = log.idUsuario
            WHERE log.nome LIKE '%{$pesquisa}%'
               OR log.perfil LIKE '%{$pesquisa}%'
               OR log.accao LIKE '%{$pesquisa}%'
               OR u.email LIKE '%{$pesquisa}%'
               OR log.dataAccao LIKE '%{$pesquisa}%'
            ORDER BY log.dataAccao DESC";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $log = new Log($rs['idLog'], $rs['idUsuario'], $rs['nome'], $rs['perfil'], $rs['accao'], $rs['dataAccao'], $rs['email']);
            array_push($logs, $log);
        }
    }
    return $logs;
}
