<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online

    * Classe Attribute
    * Data de Criacao: 25/01/2024
    * Autor @theusdido

*/	
class Attribute {
    private $id                     = 0;
    public $conn;
    public $nome;
    public $descricao               = "";
    public $ncolunas                =1;
    public $exibirmenuadministracao = 0;
    public $exibircabecalho         = 1;
    public $campodescchave          = "";
    public $atributogeneralizacao   = 0;
    public $exibirlegenda           = 1;
    public $criarprojeto            = 0;
    public $criarempresa            = 0;
    public $criarauth               = 0;
    public $registrounico           = 0;
    public $carregarlibjavascript   = 1;
    public $criarinativo            = true;

}