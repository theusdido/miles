<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://teia.tec.br
		
    * Classe InitialFilterQuery
    * Data de Criacao: 26/01/2024
    * Autor @theusdido

*/	
class InitialFilterQuery {
	/* 
		* Método __construct
		* Data de Criacao: 26/01/20224
		* Autor @theusdido
	*/
	public function __construct(){}

	/*
		* Método getJSON
	    * Data de Criacao: 26/01/2024
	    * Autor @theusdido

		Retorna filtros iniciais da consulta no formato JSON
		@params: ID
		@return: string
	*/
	public static function getJSON($_id){
		$_filter 		= tdc::d(FILTROINICIALCONSULTA,$_id);
		
		return json_encode(array(
            'atributo'  => tdc::a($_filter["atributo"])->nome,
            'operador'  => $_filter["operador"],
            'valor'     => $_filter["valor"]
        ));
	}
}