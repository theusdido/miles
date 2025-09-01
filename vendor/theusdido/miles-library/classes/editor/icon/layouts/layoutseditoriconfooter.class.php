<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe Footer
    * Data de Criacao: 29/12/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
Class LayoutsEditorIconFooter extends Elemento {

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
		$footer = tdClass::Criar("imagem");
		$footer->src  = "system/images/icon/editor/footer.png";
		$footer->class = "btn btn-default editor-element";
		$footer->data_tag = "footer";
		return $footer;
	}
}