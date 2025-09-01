<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe DIV
    * Data de Criacao: 29/12/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
Class LayoutsEditorIconDiv extends Elemento {

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
		$btn_div = tdClass::Criar("imagem");
		$btn_div->src  = "system/images/icon/editor/div.png";
		$btn_div->class = "btn btn-default editor-element";
		$btn_div->data_tag = "div";
		return $btn_div;
	}
}