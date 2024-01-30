<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://teia.tec.br
		
    * Classe Query
    * Data de Criacao: 26/01/2024
    * Autor @theusdido

*/	
class Query {
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

		Retorna consulta no formato JSON
		@params: ID
		@return: string
	*/
	public static function getJSON($_id){
		$_consulta 		= tdc::d(CONSULTA,$_id);
	    $_filtros       = $_status = $_filtros_iniciais = $_colunas = array();

        $filters         = tdc::da(FILTROCONSULTA,tdc::f('consulta','=',$_consulta->id));
        foreach($filters as $filter){
            array_push($_filtros, FilterQuery::getJSON($filer->id));
        }

        $status         = tdc::da(STATUSCONSULTA,tdc::f('consulta','=',$_consulta->id));
        foreach($status as $s){
            array_push($_status, StatusQuery::getJSON($s->id));
        }

        $filters         = tdc::da(FILTROINICIALCONSULTA,tdc::f('consulta','=',$_consulta->id));
        foreach($filters as $f){
            array_push($_filtros_iniciais, InitialFilterQuery::getJSON($f->id));
        }
        
        $colunas         = tdc::da(COLUNACONSULTA,tdc::f('consulta','=',$_consulta->id));
        foreach($colunas as $c){
            array_push($_colunas, ColumnQuery::getJSON($c->id));
        }

		return json_encode(array(
            'id'                        => $_consulta->id,
            'projeto'                   => $_consulta->projeto,
            'empresa'                   => $_consulta->empresa,
            'entidade'                  => $_consulta->entidade,
            'movimentacao'              => $_consulta->movimentacao,
            'descricao'                 => $_consulta->descricao,
            'exibireditar'              => json_encode($_consulta->exibirbotaoeditar),
            'exibirexcluir'             => json_encode($_consulta->exibirbotaoexcluir),
            'exibiremmassa'             => json_encode($_consulta->exibirbotaoemmassa),
            'exibircolunaid'            => json_encode($_consulta->exibircolunaid),
            'filtros'                   => $_filtros,
            'status'                    => $_status,
            'filtros_iniciais'          => $_filtros_iniciais,
            'colunas'                   => $_colunas
        ));
	}
}