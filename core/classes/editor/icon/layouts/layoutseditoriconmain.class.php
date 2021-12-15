<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe Main
    * Data de Criacao: 29/12/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
Class LayoutsEditorIconMain extends Elemento {	

	/*  
		* Método construct 
		* Data de Criacao: 29/12/2018
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Método Construtor
	*/		
	public function __construct(){
		parent::__construct('div');
	}
	
	public static function icon(){
		$main = tdClass::Criar("imagem");
		$main->src  = "system/images/icon/editor/main.png";
		$main->class = "btn btn-default editor-element";
		$main->data_tag = "main";
		return $main;
	}
}