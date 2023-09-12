<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Nav
    * Data de Criacao: 19/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
	Tag Button utilizada no BootStrap
*/	
class Button Extends Elemento {

    public static $class   = 'btn';
    public static $type    = 'btn-default';
    public static $icon;

	/*
		* Método construct
	    * Data de Criacao: 19/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag button
	*/
	public function __construct(){
		parent::__construct('button');
	}

	public static function icon($_icon){
		$_btn 			= tdc::o('button');
		$_btn->class 	= self::$class . ' ' . self::$type;

		if (gettype($_icon) == 'string'){
			$icon_class     = $_icon;
			$_icon 			= tdc::html('i');
			$_icon->class 	= $icon_class;
		}
		$_btn->add($_icon);
		return $_btn;
	}	
}