<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://teia.tec.br

    * Classe Empresa
    * Data de Criacao: 14/11/2023
    * Author @theusdido

*/
class Empresa
{
    /*  
		* Método filtro
	    * Data de Criacao: 14/11/2023
	    * Author @theusdido

		Retorna um filtro para pesquisar Empresa
	*/
    public static function filtro(
        $elemento_retorno = '#coluna-pesquisa-empresa'
    )
    {
        $script = tdc::html('script');
        $script->add('
            $(document).ready(function(){
                $("#modal-content-pesquisar-empresa").load(session.urlmiles + "?controller=page&page=pesquisar/farein");
            });

            function showPesquisarEmpresa(){
                $("#modal-pesquisar-empresa").modal("show");
            }
    
            $(document).on("click","#btn-pesquisar-empresa",function(e){
                e.preventDefault();
                e.stopPropagation();
                showPesquisarEmpresa();
            });            
        ');

        $inpuit_hidden_empresa 			= tdc::html('input');
        $inpuit_hidden_empresa->type	= "hidden";
        $inpuit_hidden_empresa->id 		= "retorno_empresa";
        $inpuit_hidden_empresa->name    = "retorno_empresa";

        $modal          = tdc::html('span');
        $modal->id      = "modal-content-pesquisar-empresa";

        $container      = tdc::html('span');
        $container->add( Campos::filter("empresa","Empresa") );
        $container->add( $inpuit_hidden_empresa );
        $container->add( $modal );
        $container->add( $script );
        return $container;
    }
}