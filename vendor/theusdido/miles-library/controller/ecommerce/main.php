<?php
   
    include PATH_CLASS_WEBSITE      . 'configuracoes.class.php';
    include PATH_CLASS_ECOMMERCE    . 'configuracoes.class.php';
    include PATH_CLASS_ECOMMERCE    . 'carrinho.class.php';
    include PATH_CLASS_ECOMMERCE    . 'checkout.class.php';
    include PATH_CLASS_ECOMMERCE    . 'endereco.class.php';
    include PATH_CLASS_ECOMMERCE    . 'estoque.class.php';
    include PATH_CLASS_ECOMMERCE    . 'pedido.class.php';
    include PATH_CLASS_ECOMMERCE    . 'produto.class.php';
    include PATH_CLASS_ECOMMERCE    . 'transportadora.class.php';

    // Entidades ID
    $_entidade_unidademedida_id     = getEntidadeId("ecommerce_unidademedida");
	$_entidade_categoria_id			= tdc::r('categoria');
	$_entidade_subcategoria_id		= tdc::r('subcategoria');
    $_entidade_cliente_id           = getEntidadeId("ecommerce_cliente");
    $_entidade_endereco_id          = getEntidadeId("ecommerce_endereco");
    
    // Website Configurações
    $website_geral_configuracoes    = tdc::pj('td_website_geral_configuracoes',1);
    // Ecommerce - Configurações
    $ecommerce_configuracoes        = new EcommerceConfiguracoes();	

    // Carrinho de Compras
    $ecommerce_carrinho                 = new CarrinhoCompras();        
    $carrinho_id                        = $ecommerce_carrinho->getId();

    // Cliente
    $cliente_id                         = $ecommerce_carrinho->getClient();

    // Endereço do Cliente
    $endereco_cliente                       = new Endereco();
	$endereco_cliente->cliente				= $cliente_id;
	$endereco_cliente->entidadecliente 	    = $_entidade_cliente_id;
	$endereco_cliente->entidadeendereco 	= $_entidade_endereco_id;

	$controller 		= tdc::r('controller');
	$paginacontroller 	= tdc::r('pagina');

	if ($controller == "websitepage" || $paginacontroller != ''){
		$pc = array($paginacontroller , "custom");
		#include 'template.php';
	}else{
		if ($controller == ''){	
			$pc = array();
			#include 'template.php';
		}else{
			$pc = array($controller);
			#include $controller . ".php";
			#include 'importarjs.php';
		}
	}

class EcommerceMain
{
    public static function Loja()
    {
        return tdc::ru('td_ecommerce_loja');
    }

    public static function EnderecoLoja()
    {
        return getListaRegFilhoObject(
            getEntidadeId('td_ecommerce_loja'),
            getEntidadeId('td_ecommerce_endereco'),
            1
        )[0];
    }

    public static function QuemSomos()
    {
        return tdc::ru('td_website_geral_quemsomos');
    }
    public static function Blog()
    {
        $criterio = tdc::f();
        $criterio->setPropriedade('limit',3);
        $criterio->setPropriedade('order','id DESC');

        $posts = [];
        foreach(tdc::da('td_website_geral_blog',$criterio) as $d){
            $post               = new stdClass;
            $post->id           = $d['id'];
            $post->titulo       = $d['titulo'];
            $post->href         = formatURLAmigavel('blog/' . $d['titulo'] . '/' . $d['id']);
            $post->imagem       = $d['arquivo_src'];
            $post->datahora     = dateToMysqlFormat($d['datahora'],true);
            array_push($posts,$post);
        }
        return $posts;
    }

    public static function HoraAtendimento()
    {
        $horarios = [];
        foreach(tdc::da('td_ecommerce_horaatendimento') as $ha){

            $horario                = new stdClass;
            $horario->dia_semana    = tdc::p('td_geral_diasemana',$ha['diasemana'])->nome;
            $horario->hora_inicial  = date('H:i',strtotime($ha['horainicial']));
            $horario->hora_final    = date('H:i',strtotime($ha['horafinal']));

            if ($ha['isfechado']){
                $horario->hora_inicial   = '';
                $horario->hora_final     = 'Fechado';
            }
            array_push($horarios,$horario);
        }
        return $horarios;
    }

    public static function GoogleMaps()
    {
        $iframe             = preg_match('/src="(.*)" /i',tdc::ru('td_website_geral_googlemaps')->iframe,$match);
        $google_maps        = new stdClass;
        $google_maps->src   = isset($match[1]) ? $match[1] : '';

        return $google_maps;
    }
}