<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://teia.tec.br
		
    * Classe Reporty
    * Data de Criacao: 05/03/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

*/	
class Reporty {
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

		Retorna o relatório no formato JSON
		@params: ID
		@return: string
	*/
	public static function getJSON($_relatorio_id){
		$_relatorio 		= tdc::d(RELATORIO,$_relatorio_id);
		$_filtros			= $_status = array();

        $filters         = tdc::da(FILTRORELATORIO,tdc::f('relatorio','=',$_relatorio->id));
        foreach($filters as $filter){
            array_push($_filtros, FilterReporty::getJSON($filer->id));
        }

        $status         = tdc::da(STATUSRELATORIO,tdc::f('relatorio','=',$_relatorio->id));
        foreach($status as $s){
            array_push($_status, StatusReporty::getJSON($s->id));
        }		
		
		return json_encode(array(
			'id'					=> $_relatorio->id,
			'projeto'				=> $_relatorio->projeto,
			'empresa'				=> $_relatorio->empresa,
			'entidade'				=> $_relatorio->entidade,
			'descricao'				=> $_relatorio->descricao,
			'urlpersonalizada'		=> $_relatorio->urlpersonalizada,
			'filtros'               => $_filtros,
			'status'				=> $_status
		));
	}	
}