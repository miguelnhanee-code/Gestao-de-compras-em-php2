<?php

class PostoAdministrativo
{
    private $idPostoAdministrativo;
    private $nomePostoAdministrativo;
    private $idDistrito;
    private $nomeDistrito;

    public function __construct($idPostoAdministrativo, $nomePostoAdministrativo, $idDistrito, $nomeDistrito)
    {
        $this->idPostoAdministrativo = $idPostoAdministrativo;
        $this->nomePostoAdministrativo = $nomePostoAdministrativo;
        $this->idDistrito = $idDistrito;
        $this->nomeDistrito = $nomeDistrito;
    }
    public function getIdPostoAdministrativo() {return $this->idPostoAdministrativo;}
    public function setIdPostoAdministrativo($idPostoAdministrativo) { $this->idPostoAdministrativo = $idPostoAdministrativo;  return $this;  }
    public function getNomePostoAdministrativo()  { return $this->nomePostoAdministrativo;  }
    public function setNomePostoAdministrativo($nomePostoAdministrativo) {$this->nomePostoAdministrativo = $nomePostoAdministrativo;return $this;}
    public function getIdDistrito() { return $this->idDistrito; }
    public function setIdDistrito($idDistrito) { $this->idDistrito = $idDistrito; return $this;  }
    public function getNomeDistrito() { return $this->nomeDistrito; }
    public function setNomeDistrito($nomeDistrito) { $this->nomeDistrito = $nomeDistrito; return $this; }
}
