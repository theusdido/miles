<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://teia.tec.br
		
    * Classe FilterQuery
    * Data de Criacao: 26/01/2024
    * Autor @theusdido

*/	
class FilterQuery {
	/* 
		* Método __construct
		* Data de Criacao: 26/01/20224
		* Autor @theusdido
	*/
	public function __construct($nome,$descricao){}

	/*
		* Método getJSON
	    * Data de Criacao: 26/01/2024
	    * Autor @theusdido

		Retorna os filtros da consulta no formato JSON
		@params: ID
		@return: string
	*/
	public static function getJSON($_id){
		$_filter 		= tdc::rua(FILTROCONSULTA,$_id);
		
		return json_encode(array(
            $_filter['id'] => array(
                'atributo' => $_filter["atributo"],
                'operador' => $_filter["operador"]
            )
        ));
	}
}