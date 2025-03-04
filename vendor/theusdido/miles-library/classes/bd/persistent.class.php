<?php
include_once PATH_BD . 'registro.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe de Persistencia com Base de Dados
    * Data de Criacao: 10/01/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class Persistent extends Registro {
	public $contexto;
	public $entidade;	
	/*  
		* Método construtor
	    * Data de Criacao: 19/01/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		@params Entidade ( Nome da tabela do banco de dados )
		@params Registro ( Id do registro que se deseja carregar )
	*/
	function __construct($entidade){
		if ($entidade == "") {
			echo 'Entidade nãoo especificada ou não existe.';
			return null;
		}
		if(!class_exists($entidade)){
			eval("class $entidade Extends Registro {}");	
		}
		if (func_num_args()>1){
			if (func_get_arg(1) != "" && func_get_arg(1) != null){
				$argumento = func_get_arg(1);
				$this->contexto =  tdClass::Criar($entidade,array($argumento));
			}
		}else{
			$this->contexto =  tdClass::Criar($entidade);
		}
	}
}