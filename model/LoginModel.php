<?php
class Login {
    private $username;
    private $senha;

    public function __construct($username, $senha)
    {
        $this->username = $username;
        $this->senha = $senha;
    }

    public function getNome() {return $this->username; }
    public function setNome($username) {   $this->username = $username;}
    public function getSenha() {  return $this->senha; }
    public function setSenha($senha) {   $this->senha = $senha;}
}