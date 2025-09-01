<?php
    include_once PATH_TDC . 'elemento.class.php';
    /*
        * Framework MILES
        * @license : Teia Tecnologia WEB.
        * @link https://teia.tec.br
        * Classe I
        * Data de Criacao: 06/11/2021
        * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
    */
    class A Extends Elemento {
        /*
            * Método construct
            * Data de Criacao: 06/11/2021
            * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

            Tag <a>
        */
        public function __construct(){
            parent::__construct('a');
        }
    }