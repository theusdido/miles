<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe que implementa a instação dos pacotes
    * Data de Criacao: 06/11/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class Package {
    public $name;
    private $modules = array();
	/*  
		* Método __construct
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Inicializada a classe
		@name: String
	*/
    public function __construct($name = null){
        if ($name != null){
            $this->setName($name);
            $this->setModule();
        }
    }
	/*  
		* Método setName
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Adiciona o nome do pacote
		@name: String
	*/
    public function setName($name){
        $this->name = $name;
    }

	/*  
		* Método getName
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna o nome do pacote
		@return: String
	*/
    public function getName(){
        return $this->name;
    }

	/*  
		* Método getModule
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna uma lista de módulos
		@return: Vetor ( Array )
	*/
    public function getModule(){
        return $this->modules;
    }
	/*  
		* Método setModule
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona uma lista de módulos
		@return: Void
	*/
    public function setModule(){
        $piece = explode('-',$this->getName());
        include PATH_INSTALL . 'package/' . $piece[0] . '/modulos/' . $piece[1] . '.php';

        foreach($modules as $m){
            $module = tdc::o('module');
            $module->setName($m['name']);
            $module->setTitle($m['title']);
            $module->setComponent($m['components']);
            array_push($this->modules,$module);
        }
    }
}