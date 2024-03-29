<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://teia.tec.br
		
    * Classe Relationship
    * Data de Criacao: 26/01/2024
    * Autor @theusdido

*/	
class Relationship {
	/* 
		* Método __construct
		* Data de Criacao: 26/01/20224
		* Autor @theusdido
	*/
	public function __construct(){

	}

	/*
		* Método getJSON
	    * Data de Criacao: 26/01/2024
	    * Autor @theusdido

		Retorna o relatório no formato JSON
		@params: ID do relatório
		@return: string
	*/
	public static function getJSON($_id){
		$_relacionamento 		= tdc::p(RELACIONAMENTO,$_id);
		
		return json_encode(array(
			'id' 					=> $_relacionamento->id,
			'pai' 					=> $_relacionamento->pai,
			'tipo' 					=> $_relacionamento->tipo,
			'filho' 			    => $_relacionamento->filho,
			'atributo' 				=> $_relacionamento->atributo,
			'descricao' 			=> $_relacionamento->descricao,
			'controller' 			=> $_relacionamento->controller,
			'cardinalidade' 		=> $_relacionamento->cardinalidade
		));
	}	
}