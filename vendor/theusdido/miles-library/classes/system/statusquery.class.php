<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://teia.tec.br
		
    * Classe StatusQuery
    * Data de Criacao: 26/01/2024
    * Autor @theusdido

*/	
class StatusQuery {
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

		Retorna os status da consulta no formato JSON
		@params: ID
		@return: string
	*/
	public static function getJSON($_id){
		$_filter 		= tdc::d(STATUSCONSULTA,$_id);
		
		return json_encode(array(
            $_filter->id => array(
                'atributo'  => $_filter["atributo"],
                'operador'  => $_filter["operador"],
                'valor'     => $_filter["valor"],
                'status'    => $_filter["status"]
            )
        ));
	}
}