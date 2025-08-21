<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe Icon Editor Bar
    * Data de Criacao: 26/09/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	
	Carrega os ícones da barra de ferramentas do editor visual
*/
class IconEditorBar {
	
	/*  
		* td
	    * Data de Criacao: 26/09/2018
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Carrega os icones personalizados TD
	*/
	public static function td(){
		$formulario = tdClass::Criar("editor.icon.td.tdeditoriconformulario");
		$titulopagina = tdClass::Criar("editor.icon.td.tdeditortitulopagina");
		$inpurgroupaddon = tdClass::Criar("editor.icon.td.tdeditoriconinputgroupaddon");
		$filtropesquisa = tdClass::Criar("editor.icon.td.tdeditoriconfiltro");
		
		
		$div = tdClass::Criar("div");
		$div->class = "editor-bar-componentes-td";
		$div->add($formulario::icon());
		$div->add($titulopagina::icon());
		$div->add($inpurgroupaddon::icon());
		$div->add($filtropesquisa::icon());
		return $div;
	}
	
	/*  
		* bootstrap 
	    * Data de Criacao: 27/09/2018
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Carrega os icones para Bootstrap
	*/
	public static function bootstrap(){
		$div = tdClass::Criar("div");
		$div->class = "editor-bar-componentes-bootstrap";

		$label = tdClass::Criar("editor.icon.bootstrap.3.bootstrappanel");
		$div->add($label::icon());

		$image = tdClass::Criar("editor.icon.bootstrap.4.bootstrapimage");
		$div->add($image::icon());

		return $div;
	}
	/*  
		* layouts
	    * Data de Criacao: 29/12/2018
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Carrega os icones para os Layots
	*/
	public static function layouts(){
		// Barra de Layout ( LAYOUTS )
		$layoutBar 		= tdClass::Criar("div");
	
		// Botão Grid
		$btn_grid = tdClass::Criar("imagem");
		$btn_grid->src  = "system/images/icon/editor/grid.png";
		$btn_grid->class = "btn btn-default";
		$btn_grid->id = "btn-grid";
		$layoutBar->add($btn_grid);
		
		// Botão Div
		$LayoutsEditorIconDiv = tdClass::Criar("editor.icon.layouts.layoutseditoricondiv");
		$layoutBar->add($LayoutsEditorIconDiv::icon());
		
		// Botão Main
		$LayoutsEditorIconMain = tdClass::Criar("editor.icon.layouts.layoutseditoriconmain");
		$layoutBar->add($LayoutsEditorIconMain::icon());
		
		// Botão Header
		$LayoutsEditorIconHeader = tdClass::Criar("editor.icon.layouts.layoutseditoriconheader");
		$layoutBar->add($LayoutsEditorIconHeader::icon());

		// Botão Article
		$LayoutsEditorIconArticle = tdClass::Criar("editor.icon.layouts.layoutseditoriconarticle");
		$layoutBar->add($LayoutsEditorIconArticle::icon());

		// Botão Footer
		$LayoutsEditorIconFooter = tdClass::Criar("editor.icon.layouts.layoutseditoriconfooter");
		$layoutBar->add($LayoutsEditorIconFooter::icon());

		return $layoutBar;
	}
}