<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://teia.tec.br
		
    * Classe FilterAttribute
    * Data de Criacao: 26/01/2024
    * Autor @theusdido

*/	
class FilterAttribute {
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

		Retorna filtro para os atributros no formato JSON
		@params: ID da Filtro
		@return: string
	*/
	public static function getJSON($_id){
		$_filtro 		= tdc::d(FILTROATRIBUTO,$_id);
		
		return json_encode(array(            
            'id'            => $_filtro->id,
            'atributo'      => $_filtro->atributo,
            'campo'         => $_filtro->campo,
            'operador'      => $_filtro->operador,
            'valor'         => $_filtro->valor
        ));
	}
}