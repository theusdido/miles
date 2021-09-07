<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Debug
    * Data de Criacao: 26/03/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Debug Extends Elemento {
	/*  
		* M�todo construct 
	    * Data de Criacao: 01/06/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria um arquivo para monitorar atividades do PHP
	*/	
	public static function log($msg=null){
		if($msg!=null){
			$ref = fopen(PATH_DEBUG . "debug.txt","a");
			fwrite ($ref,date("d/m/Y H:i:s") . ": $msg\r\n");
			fclose($ref);
		}
	}
	public static function append($valor){		
		$memcache = new Memcache;
		$memcache->set("DEBUG:" . session_id(), $memcache->get("DEBUG:".session_id()) . "<br />" .  $valor);
	}
	public static function dump(){
		$memcache = new Memcache;
		echo "DEBUG:".session_id() . "<br />";
		var_dump($memcache->get("DEBUG:".session_id()));
	}
	public static function set($valor){
		$memcache = new Memcache;		
		$memcache->set("DEBUG:" . session_id(), $valor);
	}
	public static function clear(){
		$memcache = new Memcache;
		$memcache->set("DEBUG:" . session_id(), "");
	}
    /*
        * M�todo put
        * Data de Criacao: 01/06/2015
        * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

        Cria um arquivo e adiciona uma mensagem para monitorar atividades do PHP
    */
    public static function put($msg){
		if (!file_exists(PATH_DEBUG)){
			try{
				mkdir(PATH_DEBUG);
			}catch(Exception $e){
				echo 'N�o foi poss�vel criar diret�rio debug. Motivo => ' . $e->getMessage();
				return false;
			}
		}
		$sessionpath = PATH_DEBUG . "session/";
		if (!file_exists($sessionpath)){
			try{
				mkdir($sessionpath);
			}catch(Exception $e){
				echo 'N�o foi poss�vel criar diret�rio debug/session. Motivo => ' . $e->getMessage();
				return false;
			}
		}
		$ref = fopen(PATH_DEBUG . "session/".session_id().".txt","w");
		fwrite ($ref,date("d/m/Y H:i:s") . ": $msg\r\n");
		fclose($ref);
	}
}