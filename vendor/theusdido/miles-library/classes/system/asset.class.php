<?php
    /*
        * Framework MILES
        * @license : Teia Tecnologia WEB.
        * @link http://www.teia.tec.br

        * Classe Asset
        * Data de Criacao: 19/06/2021
        * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
    */
    class Asset {
        /*
            * path
            * Data de Criacao: 30/12/2021
            * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
            * PARAMETROS
            *	@params: string file:"Nome da constante com o nome do arquivo"
            * RETORNO
            *	@return: PATH de um arquivo
        */
        public static function path($file){
            return  constant( str_replace('FILE_','PATH_',$file) ) . constant($file);
        }

        /*
            * url
            * Data de Criacao: 30/12/2021
            * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
            * PARAMETROS
            *	@params: string file:"Nome da constante com o nome do arquivo"
            * RETORNO
            *	@return: URL de um arquivo
        */
        public static function url($file){
            return  constant( str_replace('FILE_','URL_',$file) ) . constant($file);
        }        
    }