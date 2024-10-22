<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link http://teia.tec.br

    * Classe tdLista
    * Data de Criacao: 26/04/2024
    * Author: @theusdido
*/
class tdLista {

    /* 
		* Método add
		* Data de Criacao: 26/04/2024
		* Author: @theusdido

       Adiciona um registro na tabela td_lista
	*/
	public static function add($entidade_pai,$entidade_filho,$registro_pai,$registro_filho){
		if (!exists_lista($entidade_pai,$entidade_filho,$registro_pai,$registro_filho)){
			$_lista 					= tdc::p('td_lista');
			$_lista->entidadepai 		= $entidade_pai;
			$_lista->entidadefilho		= $entidade_filho;
			$_lista->regpai				= $registro_pai;
			$_lista->regfilho			= $registro_filho;
			$_lista->armazenar();
		}
	}

    /* 
		* Método del
		* Data de Criacao: 21/10/2024
		* Author: @theusdido

       Delete registro na tabela td_lista
	*/
	public static function del($entidade_pai,$entidade_filho,$registro_pai,$registro_filho = 0){

		$criterio = tdc::f();
		$criterio->addFiltro('entidadepai'		,"=",$entidade_pai);
		$criterio->addFiltro('entidadefilho'	,"=",$entidade_filho);
		$criterio->addFiltro('regpai'			,"=",$registro_pai);

		if ($registro_filho != 0){
			$criterio->addFiltro('regfilho'			,"=",$registro_filho);
		}

		tdc::de(LISTA,$criterio);
	}

    /* 
		* Método sync
		* Data de Criacao: 21/10/2024
		* Author: @theusdido

       Sincroniza registro na tabela td_lista
	*/
	public static function sync($entidade_pai,$entidade_filho,$registro_pai,$registro_filho){
		if (exists_lista($entidade_pai,$entidade_filho,$registro_pai,$registro_filho)){
			self::del($entidade_pai,$entidade_filho,$registro_pai,$registro_filho);
		}
		self::add($entidade_pai,$entidade_filho,$registro_pai,$registro_filho);	
	}	
}	