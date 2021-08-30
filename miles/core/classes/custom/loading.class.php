<?php
/*
    * Framework MILES
    * @license : Teia - Tecnologia WEB
    * @link http://www.teia.tec.br

    * Classe Loading
    * Data de Criacao: 28/08/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

*/
class Loading {

	/*
		* Método __construct
	    * Data de Criacao: 28/08/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* PARAMETROS
		*	[ none ]
		* RETORNO
		*	[ void ]
		* FUNÇÃO
		* Inicializa a classe
	*/
	public function __construct(){
  
	}
	/*
		* Método pagina
	    * Data de Criacao: 28/08/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* PARAMETROS
		*	[ none ]
		* RETORNO
		*	[ void ]
		* FUNÇÃO
		* Retorna elemento HTML exibindo uma imagem de carregando
	*/
    public static function pagina(){
        $loader = tdc::o('div');
        $loader->class = "loadergeral";

        $lcenter = tdClass::Criar("center");
	
        $limagem = tdClass::Criar("imagem");
        $limagem->align = "middle";
        $limagem->src = Session::Get('URL_LOADING');
        
        $lp = tdClass::Criar("p");
        $lp->class = "text-muted">
        $lp->add("Aguarde");

        $lcenter->add($limagem,$lp);
        $loader->add($lcenter);
        return $loader;
    }

    public static function requisicao(){
        $imagem = tdClass::Criar("imagem");
        $imagem->style = "display:none;";
        $imagem->class = "td-loading2";
        $imagem->src = Session::Get('URL_LOADING2');
        return $imagem;
    }
}