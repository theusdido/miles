<?php 
    include_once PATH_TDC . 'elemento.class.php';
    /*
        * Framework MILES
        * @license : Teia Tecnologia WEB
        * @link https://teia.tec.br
            
        * Classe Canvas
        * Data de Criacao: 23/02/2023
        * Autor @theusdido
    */
    class Canvas Extends Elemento {
        /*
            * Método construct
            * Data de Criacao: 23/02/2023
            * Autor @theusdido
            
            Tag canvas
        */
        public function __construct(){
            parent::__construct('canvas');
        }
    }