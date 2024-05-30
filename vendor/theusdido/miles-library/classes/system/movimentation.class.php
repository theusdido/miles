<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://teia.tec.br
		
    * Classe Movimentation
    * Data de Criacao: 26/01/2024
    * Autor @theusdido

*/	
class Movimentation {
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

		Retorna as movimentações no formato JSON
		@params: ID
		@return: string
	*/
	public static function getJSON($_id){
		$_movimentacao 		= tdc::ru(MOVIMENTACAO,$_id);
		
		return json_encode(array(
            'id'            => $_movimentacao->id,
            'descricao'     => $_movimentacao->descricao,
            'classe'        => $_movimentacao->classe
        ));
	}
}