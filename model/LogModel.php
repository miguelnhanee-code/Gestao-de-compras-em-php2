<?php

class Log
{
    private $idLog;
    private $idUsuario;
    private $nome;
    private $perfil;
    private $accao;
    private $dataAccao;
    private $email;

    public function __construct($idLog, $idUsuario, $nome, $perfil, $accao, $dataAccao, $email)
    {
        $this->idLog = $idLog;
        $this->idUsuario = $idUsuario;
        $this->nome = $nome;
        $this->perfil = $perfil;
        $this->accao = $accao;
        $this->dataAccao = $dataAccao;
        $this->email = $email;
    }

    public function getIdLog()
    {
        return $this->idLog;
    }

    public function setIdLog($idLog)
    {
        $this->idLog = $idLog;
        return $this;
    }

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getPerfil()
    {
        return $this->perfil;
    }

    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;
        return $this;
    }

    public function getAccao()
    {
        return $this->accao;
    }

    public function setAccao($accao)
    {
        $this->accao = $accao;
        return $this;
    }

    public function getDataAccao()
    {
        return $this->dataAccao;
    }

    public function setDataAccao($dataAccao)
    {
        $this->dataAccao = $dataAccao;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
}
