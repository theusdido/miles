<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://teia.tec.br
		
    * Classe Permission
    * Data de Criacao: 26/01/2024
    * Autor @theusdido

*/	
class Permission {
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

		Retorna a permisssão no formato JSON
		@params: ID da permissão
		@return: string
	*/
	public static function getJSON($_id){
		$permissoes 		= tdc::ru(PERMISSOES,$_id);
		
		return json_encode(array(
            'id'            => $permissoes->id,
            'projeto'       => $permissoes->projeto,
            'empresa'       => $permissoes->empresa,
            'entidade'      => $permissoes->entidade,
            'usuario'       => $permissoes->usuario,
            'inserir'       => $permissoes->inserir,
            'excluir'       => $permissoes->excluir,
            'editar'        => $permissoes->editar,
            'visualizar'    => $permissoes->visualizar,
            'atributos'     => []
        ));
	}
}