<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB.
    * @link https://www.teia.tec.br

    * Classe de Idioma
    * Data de Criacao: 10/01/2024
    * Author @theusdido
*/

class Idioma
{
    public static function Traduzir($entidade,$campo,$registro,$lingua = 0){

        $atributo   = getAtributoId($entidade,$campo);

        $criterio   = tdc::f();
        $criterio->addFiltro('atributo','=',$atributo);
        $criterio->addFiltro('registro','=',$registro);
        if ($lingua > 0) $criterio->addFiltro('lingua','=',$lingua);

        return tdc::rs('td_website_idioma_traducao',$criterio);
        
    }
}