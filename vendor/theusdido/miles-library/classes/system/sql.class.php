 <?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://www.teia.tec.br

    * Classe Entidade
    * Data de Criacao: 20/10/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/

class SQL {

	/*
		* Método definition
	    * Data de Criacao: 19/06/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna a formatação da definição do atributo
		@params: $atributo ( td_atributo object )
	*/
    public static function definition($attribute){
        if ($attribute->tipo == "varchar" || $attribute->tipo == "char" ){
            if ($attribute->tamanho == '' || $attribute->tamanho == 0){
                $tamanhoSQL = "(0)";
            }else{
                $tamanhoSQL = "({$attribute->tamanho})";
            }
        }else{
            $tamanhoSQL = '';
        }
        $nuloSQL = ((int)$attribute->nulo==0)?'NOT NULL':'NULL';
        return strtoupper($attribute->tipo) . $tamanhoSQL . ' ' . $nuloSQL;
    }

	/*
		* Método entity_exists
	    * Data de Criacao: 19/06/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna se uma entidade existe na tabela de entidade
		@params: $entidade ( string )
	*/
    public static function entity_exists($entity){
        if (sizeof(tdc::da(ENTIDADE,tdc::f('nome','=',$entity))) > 0){
            return true;
        }else{
            return false;
        }
    }
}