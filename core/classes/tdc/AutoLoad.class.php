<?php
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe AutoLoad
    * Data de Criacao: 24/07/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)		
*/	
class AutoLoad
{
	protected $class_instanciadas = array();
	function load($className)
	{
		if (isset($this->class_instanciadas[$className])) return false;

		$pacote = array(
			PATH_CLASS,
			PATH_ADO,
			PATH_BD,
			PATH_TDC,
			PATH_WIDGETS,
			PATH_MVC_MODEL,
			PATH_MVC_VIEW,
			PATH_MVC_CONTROLLER,
			PATH_BOOTSTRAP,
			PATH_CURRENT_CLASS_PROJECT,
			PATH_CLASS_CUSTOM,
			PATH_CLASS_INTERFACE,
			PATH_CLASS_SYSTEM,
			PATH_CLASS_INSTALL,
			PATH_CLASS_ECOMMERCE
		);
		foreach ($pacote as &$p){
			$className = strtolower($className);
			$pacoteclassfile = "{$p}{$className}.class.php";
			if (file_exists($pacoteclassfile) && !class_exists($className)){
				include_once $pacoteclassfile;
				$this->class_instanciadas[$className] = null;
			}
		}
	}
}