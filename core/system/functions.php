<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB.
    * @link https://teia.tec.br

    * Arquivo com as funções estáticas ( Não tem vinculo direto com o sistema )
    * Data de Criacao: 20/12/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/

/*
	* removeBarraRoot
	* Data de Criacao: 20/12/2021
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* Retira a barra inicial do path
	* PARAMETROS
	*	@params: String page:"Path"
	* RETORNO
	*	@return: String
*/
function removeBarraRoot($path){
	if (substr($path,0,1) == '/'){
		return substr($path,1,strlen($path));
	}else{
		return $path;
	}
}