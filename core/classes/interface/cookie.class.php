<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link http://www.teia.tec.br

    * Classe que implementa a interface para manipulação de cookie
    * Data de Criacao: 19/01/2022
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/

class TDCookie {
	/*
		* Método clear
	    * Data de Criacao: 19/11/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Apaga os dados de um determinado cookie
	*/
    public static function clear($cookie){
        unset($_COOKIE[$cookie]);
        @setcookie($cookie, null, -1, PATH_ROOT);
        @setcookie($cookie, '', time()-3600 , PATH_ROOT);
    }

	/*
		* Método add
	    * Data de Criacao: 19/11/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Cria um cookie e adiciona um valor
	*/
    public static function add($cookiename,$valor){
        @setCookie($cookiename,$valor,self::getTimeExpiration(),PATH_ROOT);
    }

	/*
		* Método append
	    * Data de Criacao: 19/11/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Incrementa um valor ao cookie
	*/
    public static function append($cookiename,$valor){
        @setCookie($cookiename,(self::existsCookie($cookiename)?$_COOKIE[$cookiename]:'') . $valor,self::getTimeExpiration(),PATH_ROOT);
    }

	/*
		* Método get
	    * Data de Criacao: 19/11/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna o valor de um cookie
	*/
    public static function get($cookiename){
        if (isset($_COOKIE[$cookiename])){
            if ($_COOKIE[$cookiename] == null){
                return '';
            }else{
                return $_COOKIE[$cookiename];
            }
        }else{
            return '';
        }
    }

	/*
		* Método exists
	    * Data de Criacao: 19/11/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Verifica se um cookie existe
	*/
    public static function exists($cookiename){
        if (isset($_COOKIE[$cookiename])){
            if ($_COOKIE[$cookiename] == null){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }

	/*
		* Método expiration
	    * Data de Criacao: 19/11/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna o tempo de experação do cookie ( Default: 30 dias )
	*/
    private function getTimeExpiration(){
        return time()+60*60*24*30; // 30 Dias
    }
}