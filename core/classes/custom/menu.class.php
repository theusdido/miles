<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online

    * Classe Menu
    * Data de Criacao: 30/04/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class Menu {

	/* 
		* Método filhos 
	    * Data de Criacao: 30/04/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* PARAMETROS
		*	1 - $pai: Id do Menu Pai
		* RETORNO
		*	[ array ] - Lista com os menus da primeira geração
	*/
	public static function filhos($pai = 0){
		global $conn;
		$retornomenu = array();
		$sqlMenu = "
			SELECT * 
			FROM " . MENU . " 
			WHERE pai = {$pai} 
			AND pai = 0
			ORDER BY pai,ordem;";
		$queryMenu = $conn->query($sqlMenu);
		While ($linhaMenu = $queryMenu->fetch()){
			array_push ($retornomenu,Menu::open($linhaMenu["id"]));
		}
		return $retornomenu;
	}
	
	public static function open($id){
		global $conn;
		$retorno = array();
		$sql 	= "SELECT * FROM " . MENU . " WHERE id = " . $id;
		$query 	= $conn->query($sql);
		if ($query->rowCount() > 0){
			$linha = $query->fetch();
			$retorno = array(
				"id"			=> $linha["id"],
				"descricao" 	=> $linha["descricao"],
				"link" 			=> $linha["link"],
				"pai" 			=> $linha["pai"],
				"entidade" 	=> $linha["entidade"],
				"target" 		=> empty($linha["target"])?"":$linha["target"],
				"tipomenu" 		=> $linha["tipomenu"],
				"filhos" 		=> Menu::filhos( $linha["id"] ),
				"path"			=> isset($linha["path"]) ? $linha["path"] : '',
				"icon"			=> isset($linha["icon"]) ? $linha["icon"] : ''
			);
		}
		return $retorno;
	}
}