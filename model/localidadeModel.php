<?php

class localidade
{
    private $idLocalidade;
    private $nomeLocalidade;
    private $idPostoAdministrativo;
    private $postoAdministrativo;

    public function __construct($idLocalidade, $nomeLocalidade, $idPostoAdministrativo, $postoAdministrativo)
    {
        $this->idLocalidade = $idLocalidade;
        $this->nomeLocalidade = $nomeLocalidade;
        $this->idPostoAdministrativo = $idPostoAdministrativo;
        $this->postoAdministrativo = $postoAdministrativo;
    }

    public function getIdLocalidade() { return $this->idLocalidade; }
    public function setIdLocalidade($idLocalidade){    $this->idLocalidade = $idLocalidade;    return $this;}
    public function getNomeLocalidade() {   return $this->nomeLocalidade; }
    public function setNomeLocalidade($nomeLocalidade){ $this->nomeLocalidade = $nomeLocalidade; return $this;}
    public function getIdPostoAdministrativo() { return $this->idPostoAdministrativo;  }
    public function setIdPostoAdministrativo($idPostoAdministrativo)  {  $this->idPostoAdministrativo = $idPostoAdministrativo;return $this; }
    public function getPostoAdministrativo() {return $this->postoAdministrativo;  }
    public function setPostoAdministrativo($postoAdministrativo) { $this->postoAdministrativo = $postoAdministrativo; return $this; }
}
