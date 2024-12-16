<?php
    /*
        * Framework MILES
        * @license : Teia Tecnologia WEB.
        * @link http://www.teia.tec.br

        * Classe Console
        * Data de Criacao: 17/01/2023
        * Autor: @theusdido
    */
    class Console {
        /*
            * log
            * Data de Criacao: 17/01/2023
            * Autor: @theusdido
            * PARAMETROS
            *	@params: string mensagem:"Mensagem a ser exibida no console do navegador"
            * RETORNO
            *	@return: void
        */
        public static function log($mensagem){
            consoleJS($mensagem);
        }        
    }