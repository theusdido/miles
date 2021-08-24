<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB.
    * @link http://www.teia.tec.br

    * Classe Config
    * Data de Criacao: 19/06/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class Config {
	
	/*
		* Método currentProject
	    * Data de Criacao: 19/06/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna o ID do projeto atual
	*/	
	public static function currentProject() {
		if (defined("PROJETO_CONSUMIDOR")){			
			$currentProject = (int)PROJETO_CONSUMIDOR;
		}else if (defined("MILES_PROJECT")){
			$currentProject = (int)MILES_PROJECT;
		}else if (isset($_GET["currentproject"])){
			$currentProject = (int)$_GET["currentproject"];
		}else if (isset($_POST["currentproject"])){
			$currentProject = (int)$_POST["currentproject"];
		}else{
			if (isset($_SESSION["currentproject"])){
				$currentProject = $_SESSION["currentproject"];
			}else{
				$currentProject = 1;
			}
		}
		return $currentProject;
	}

	/*
		* Método isOnline
	    * Data de Criacao: 19/06/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna se está online/offline
	*/
	public static function isOnline(){
		switch(HTTP_HOST){
			case "localhost":
			case "127.0.0.1":
				$isonline 				= false;
			break;
			default:
				$isonline 				= true;
		}
		return $isonline;
	}
}