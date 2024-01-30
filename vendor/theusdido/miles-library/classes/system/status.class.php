<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://teia.tec.br
		
    * Classe Status
    * Data de Criacao: 26/01/2024
    * Autor @theusdido

*/	
class Status {
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

		Retorna os status no formato JSON
		@params: ID
		@return: string
	*/
	public static function getJSON($_id){
		$_status 		= tdc::d(STATUS,$_id);
		
		return json_encode(array(
            'id'            => $_status->id,
            'descricao'     => $_status->descricao,
            'classe'        => $_status->classe
        ));
	}
}