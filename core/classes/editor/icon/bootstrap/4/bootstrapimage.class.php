<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online

    * Classe Image do Bootstrap
    * Data de Criacao: 02/01/2019
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
Class BootstrapImage extends Elemento {	

	/*  
		* Método construct 
		* Data de Criacao: 02/01/2019
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Método Construtor
	*/
	public function __construct(){
		parent::__construct('div');
	}
	
	public static function icon(){
		$tdBtnIcon = tdClass::Criar("div");

		// Botão Título do Formulário
		$btn = tdClass::Criar("span");
		$btn->class = "btn btn-default editor-element img-fluid";
		$btn->data_tag = "img";		
		$btn->data_componente = "tdEditorIconBootstrapImage";
			$span = tdClass::Criar("span");
			$span->class = "fa fa-image fa-2x";
			$btn->add($span);

		// JS
		$js = tdClass::Criar("script");
		$js->add('
			
			function tdEditorIconBootstrapImage(){
				var objSel = pagina.objSelected;
				objSel.attr("src","system/images/icon/editor/addimage.png");
			}			
		');

		$tdBtnIcon->add($btn,$js);
		return $tdBtnIcon;
	}
}