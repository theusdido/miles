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
Class LayoutsEditorIconArticle extends Elemento {

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
		$article = tdClass::Criar("imagem");
		$article->src  = "system/images/icon/editor/article.png";
		$article->class = "btn btn-default editor-element";
		$article->data_tag = "article";
		return $article;
	}
}