 <?php

        class Provincia
        {
            private $idProvincia;
            private $nomeProvincia;
            private $idPais;
            private $nomePais;

            public function __construct($idProvincia, $nomeProvincia, $idPais, $nomePais)
            {
                $this->idProvincia = $idProvincia;
                $this->nomeProvincia = $nomeProvincia;
                $this->idPais = $idPais;
                $this->nomePais = $nomePais;
            }

            public function getIdProvincia() { return $this->idProvincia; }
            public function setIdProvincia($idProvincia)  {   $this->idProvincia = $idProvincia;   return $this;  }
            public function getNomeProvincia()  {   return $this->nomeProvincia;  }
            public function setNomeProvincia($nomeProvincia) {   $this->nomeProvincia = $nomeProvincia;   return $this;    }
            public function getIdPais() {    return $this->idPais; }
            public function setIdPais($idPais)  {      $this->idPais = $idPais;      return $this;  }
            public function getNomePais()  {  return $this->nomePais;   }
            public function setNomePais($nomePais)  {  $this->nomePais = $nomePais;  return $this;  }
        }
