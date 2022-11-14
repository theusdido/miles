<?php
    include_once PATH_TDC . 'elemento.class.php';
    /*
	    * Framework MILES
	    * @license : Teia Tecnologia WEB
	    * @link https://teia.tec.br
        
        * Classe TR
        * Data de Criacao: 07/11/2021
        * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
    */
    class TR Extends Elemento {
        /*
            * Método construct
            * Data de Criacao: 07/11/2021
            * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

            Tag <tr>
        */
        public function __construct(){
            parent::__construct('tr');
        }
    }