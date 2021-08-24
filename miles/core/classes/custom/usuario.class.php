<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online

    * Classe Usuário
    * Data de Criacao: 04/05/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class Usuario {
	/* 
		* Método id
	    * Data de Criacao: 04/05/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* PARAMETROS
		*	
		* RETORNO
		*	[ int ] - Código do usuário atual
	*/
	public static function id(){
		if (isset(Session::Get()->userid)){
			return Session::Get()->userid;
		}else if (tdc::r("userid") != ''){
			return tdc::r('userid');
		}else{
			return 0;
		}
	}
}