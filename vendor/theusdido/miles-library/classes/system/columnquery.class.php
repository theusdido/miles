<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://teia.tec.br
		
    * Classe ColumnQuery
    * Data de Criacao: 26/01/2024
    * Autor @theusdido

*/	
class ColumnQuery {
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

		Retorna coluinas da consulta no formato JSON
		@params: ID
		@return: string
	*/
	public static function getJSON($_id){
		$_coluna 		= tdc::d(COLUNACONSULTA,$_id);
		
		return json_encode(array(
            'atributo'      => $_coluna["atributo"],
            'consulta'      => $_coluna["consulta"],
            'alinhamento'   => $_coluna["alinhamento"],
            'exibirid'      => $_coluna["exibirid"]
        ));
	}
}
