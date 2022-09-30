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
		$userid 	= Usuario::id();
		$where 		= "WHERE (EXISTS(SELECT 1 FROM td_entidadepermissoes b WHERE b.entidade = a.entidade AND b.usuario = ".$userid." AND b.visualizar = 1)";
		$where 	   .= " OR EXISTS(SELECT 1 FROM td_menupermissoes c WHERE c.menu = a.id AND c.usuario = ".$userid." AND c.permissao = 1)) AND a.descricao <> '' ";
		$retornomenu = array();
		$sqlMenu = "
			SELECT * 
			FROM " . MENU . " a
			".$where."
			AND a.pai = {$pai}
			AND a.descricao <> ''
			ORDER BY a.pai,a.ordem;
		";
		$queryMenu = $conn->query($sqlMenu);
		While ($linhaMenu = $queryMenu->fetch()){
			array_push ($retornomenu,Menu::open($linhaMenu["id"]));
		}
		return $retornomenu;
	}

	public static function open($id,$load_filhos = true){
		global $conn;
		$retorno = array();
		$sql 	= "SELECT * FROM " . MENU . " WHERE id = " . $id;
		$query 	= $conn->query($sql);
		if ($query->rowCount() > 0){
			$linha = $query->fetch();
			$retorno = array(
				"id"			=> $linha["id"],
				"descricao" 	=> isutf8($linha["descricao"]) ? $linha["descricao"] : utf8encode($linha["descricao"]),
				"link" 			=> $linha["link"],
				"pai" 			=> $linha["pai"],
				"entidade" 		=> $linha["entidade"],
				"target" 		=> empty($linha["target"])?"":$linha["target"],
				"tipomenu" 		=> $linha["tipomenu"],
				"filhos" 		=> $load_filhos ? Menu::filhos( $id ) : [],
				"path"			=> isset($linha["path"]) ? $linha["path"] : '',
				"icon"			=> isset($linha["icon"]) ? $linha["icon"] : '',
				"coluna" 		=> isset($linha["coluna"]) ? $linha["coluna"] : 0
			);
		}
		return $retorno;
	}
}