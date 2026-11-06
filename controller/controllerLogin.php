<?php
include_once(__DIR__ . "/../model/utilizadoresModel.php");
include_once(__DIR__ . "/controllerLog.php");
$conexao = mysqli_connect("localhost", "root", "", "gestaocompra");

class Sessao
{
    private $logado = false;
    public $idUtilizador;
    public $email;

    function __construct()
    {
        session_start();
        $this->verificar_login();
    }

    private function verificar_login()
    {
        if (
            isset($_SESSION['idUtilizador']) &&isset($_SESSION['email'])
        ) {

            $this->idUtilizador = $_SESSION['idUtilizador'];
            $this->email = $_SESSION['email'];
            $this->logado = true;
        } else {

            unset($this->idUtilizador);
            unset($this->email);
            $this->logado = false;
        }
    }

    public function esta_logado()  { return $this->logado; }
    public function login($usuario)
    {
        if ($usuario) {
            $_SESSION['idUtilizador'] = $usuario['idUtilizador'];
            $this->idUtilizador = $_SESSION['idUtilizador'];
            $_SESSION['email'] = $usuario['email'];
            $this->email = $_SESSION['email'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['perfil'] = $usuario['perfil'];
            $this->logado = true;
        }
    }

    public function logout()
    {
        unset($_SESSION['idUtilizador']);
        unset($this->idUtilizador);
        unset($_SESSION['email']);
        unset($this->email);
        unset($_SESSION['nome']);
        unset($_SESSION['perfil']);

        $this->logado = false;
    }
}
$sessao = new Sessao();

function login($email, $senha)
{
    global $conexao, $sessao;
    $sql = "select * from utilizadores where email='{$email}'";
    $result = mysqli_query($conexao, $sql);
    if (mysqli_num_rows($result) > 0) {
        $utilizador = mysqli_fetch_assoc($result);
        if (password_verify($senha, $utilizador['senha'])) {
            $sessao->login($utilizador);
            header("location:../view/Dashboard.php");
            if ($result) {
                registarAccao("Log in");
            }
            exit();
        } else {
            echo "Senha incorrecta";
        }
    } else {
        echo "Email não encontrado";
    }
}

// ---------- PERMISSOES DO PERFIL ----------

// Devolve true se o perfil do utilizador logado tem a permissao (ex.: 'criar').
// A consulta e feita a cada pedido, por isso qualquer alteracao feita
// na tela "Configurar perfil" aplica-se logo, sem precisar de novo login.
function temPermissao($chave)
{
    global $conexao;
    static $chaves = null;

    if ($chaves === null) {
        $chaves = array();
        $perfil = isset($_SESSION['perfil']) ? $_SESSION['perfil'] : '';
        $sql = "select p.chave from perfil_permissao pp
                join permissao p on p.codigoPermissao = pp.codigoPermissao
                join perfil f on f.idPerfil = pp.idPerfil
                where f.nomePerfil='" . mysqli_real_escape_string($conexao, $perfil) . "'
                   or f.idPerfil=" . (int) $perfil;
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            while ($rs = mysqli_fetch_assoc($result)) {
                array_push($chaves, $rs['chave']);
            }
        }
    }
    return in_array($chave, $chaves);
}

// Use no topo de uma pagina: sem a permissao, volta para o Dashboard.
function exigirPermissao($chave)
{
    if (!temPermissao($chave)) {
        header("Location: Dashboard.php");
        exit();
    }
}
function usaSenhaPadrao($email, $senha)
{
    global $conexao;
    if ($senha !== '12345678') {return false;  }
    $result = mysqli_query($conexao, "select senha from utilizadores where email='{$email}'");
    if ($result && mysqli_num_rows($result) > 0) {
        $rs = mysqli_fetch_assoc($result);
        return password_verify($senha, $rs['senha']);
    }
    return false;
}