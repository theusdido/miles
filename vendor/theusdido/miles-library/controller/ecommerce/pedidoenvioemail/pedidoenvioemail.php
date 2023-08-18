<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.tec.br

    * Classe que implementa a geração de pedido para ser enviado por e-mail
    * Data de Criacao: 01/11/2020
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/

$registro 			= tdc::r("registro");
$pedido				= tdc::p("td_ecommerce_pedido",$registro);
$empresa			= tdc::p("td_empresa",isset($_SESSION["empresa"])?Session::Get()->empresa:1);

$cliente 			= tdc::p("td_ecommerce_cliente",$pedido->cliente);
$enderecliente 		= tdc::p("td_ecommerce_endereco",@(int)getListaRegFilho(getEntidadeId("ecommerce_cliente"),getEntidadeId("ecommerce_endereco"),$cliente->id)[0]->regfilho);
$enderecoempresa	= tdc::p("td_endereco",@(int)getListaRegFilho(getEntidadeId("empresa"),getEntidadeId("endereco"),Session::Get()->empresa)[0]->regfilho);
$configuracoes		= tdc::p("td_ecommerce_configuracoes",1);

// Div Topo
$topo		= new Dom("div" , array("style" => "width:100%;float:left;"));

// Div da Logo
$divlogo 	= $topo->add("div" , array("propriedades" => array ("style" => "width:100%;float:left;height:75px;")));
$logo		= $topo->add("img",array(
	"propriedades" => array( "id" => "logo" , "src" => URL_CURRENT_LOGO_PADRAO , "style" => "height:50px;" ) ,
	"elementopai" => $divlogo
));

// Div Titulo
$divtitulo	= $topo->add("div", array("propriedades" => array ("style" => "width:100%;float:left;height:75px;")));
$titulo		= $topo->add("h1",array(
	"propriedades" => array("innerhtml" => "PEDIDO DE VENDA" , "id" => "titulo"),
	"elementopai" => $divtitulo
));

// Div Número do Pedido
$divnumeropedido 	= $topo->add("div", array("propriedades" => array("innerhtml" => "NÚMERO: " . $pedido->id , "style" => "width:50%;float:left;font-size:18px;font-weight:bold;border-bottom:1px solid #000;height:25px;line-height:25px;")));

// Div Data e Hora do Pedido
$divdatahora	 	= $topo->add("div", array("propriedades" => array("innerhtml" => "DATA/HORA: " . datetimeToMysqlFormat($pedido->datahoraretorno,true) , "style" => "width:50%;float:right;font-size:10px;text-align:right;border-bottom:1px solid #000;height:25px;line-height:25px;")));

// Div Nome Cliente
$divnomecliente 	= $topo->add("div", array("propriedades" => array("innerhtml" => "CLIENTE: " . $cliente->id . " - " . strtoupper($cliente->nome) , "style" => "width:100%;float:left;font-weight:bold;text-transform: uppercase;height:25px;line-height:25px;border-bottom:1px solid #000;")));

// Div Dados de Contato do Cliente
$documento			= $cliente->cpf != '' ? 'CPF: ' . $cliente->cpf : 'CNPJ: ' . $cliente->cnpj;
$divdadoscliente 	= $topo->add("div", array("propriedades" => array("style" => "width:50%;height:80px;float:left;font-size:12px;border-bottom:1px solid #000;padding-top:10px;" ,"innerhtml" => array(
	$topo->node("div", array("innerhtml" => $documento, 							'style' => 'width:100%;float:left;')) ,
	$topo->node("div", array("innerhtml" => "Telefone: " . $cliente->telefone , 	'style' => 'width:100%;float:left;')),
	$topo->node("div", array("innerhtml" => "E-Mail: " . $cliente->email , 			'style' => 'width:100%;float:left;'))
))));

// Div Dados do Endereço do Cliente
$divdadosenderecocliente	= $topo->add("div", array("propriedades" => array( "style" => "width:50%;height:80px;float:left;font-size:12px;border-bottom:1px solid #000;padding-top:10px;" , "innerhtml" => array(
	$topo->node("div", array("innerhtml" => "Logradouro: " . $enderecliente->logradouro , 			'style' => 'width:100%;float:left;')) ,
	$topo->node("div", array("innerhtml" => "Complemento: " . $enderecliente->complemento , 		'style' => 'width:100%;float:left;')) ,
	$topo->node("div", array("innerhtml" => "Bairro: " . tdc::utf8($enderecliente->bairro_nome) , 	'style' => 'width:100%;float:left;')),
	$topo->node("div", array("innerhtml" => "Cidade: " . tdc::utf8($enderecliente->cidade_nome) ,	'style' => 'width:70%;float:left;')),
	$topo->node("div", array("innerhtml" => "CEP: " . $enderecliente->cep ,							'style' => 'width:30%;float:left;'))
))));

// Div Cabeçalho do Pedido
$divcabecalhopedido = $topo->add("div", array("propriedades" => array("style" => "width:100%;float:left;margin:15px 0;", "innerhtml" => array(
	$topo->node("div", array("innerhtml" => "Status............: " . tdc::p("td_ecommerce_pagseguro_statuspedido",$pedido->status)->descricao,				"style" => "width:100%;font-weight: bold;float:left;margin-top:10px;")),
    $topo->node("div", array("innerhtml" => "Valor Frete......: " . ($pedido->valorfrete != ""?'R$ ' . moneyToFloat($pedido->valorfrete,true):'-'),	"style" => "width:100%;font-weight: bold;float:left;margin-top:10px;")),
	$topo->node("div", array("innerhtml" => "Valor Total......: R$ " . moneyToFloat($pedido->valortotal,true),											"style" => "width:100%;font-weight: bold;float:left;margin-top:10px;"))
))));

// Pedido
$_pedido	= new Pedido($pedido->id);

$tabela  	= new Dom("table" , array("style" => "float:left;width:100%;clear:left;", "cellpadding" => "0" , "cellspacing" => "0"));
$thead	 	= $tabela->add("thead");
$tbody		= $tabela->add("tbody");
$tfoot		= $tabela->add("tfoot");

// CABEÇALHO
$trHead			= $tabela->add("tr",array( "elementopai" => $thead));
$style_class_th = "background-color:#EEE;border-bottom:3px solid #000;";

$thID   	= $tabela->add("th",array("propriedades" => array("style" => $style_class_th, "innerhtml" => "ID" , "align" => "left" , "width" => "5%" ) , "elementopai" => $trHead));
$thProduto 	= $tabela->add("th",array("propriedades" => array("style" => $style_class_th, "innerhtml" => "Produto" , "align" => "left" , "width" => "40%" ) , "elementopai" => $trHead));
if ($configuracoes->usarvariacaoproduto){
	$thTamanho 	= $tabela->add("th",array("propriedades" => array( "innerhtml" => "Tamanho" , "align" => "left" , "width" => "20%" ) , "elementopai" => $trHead));
}	
$thQtdade 	= $tabela->add("th",array("propriedades" => array("style" => $style_class_th, "innerhtml" => "Qtdade" , "align" => "center" , "width" => "10%" ) , "elementopai" => $trHead));
$thValor 	= $tabela->add("th",array("propriedades" => array("style" => $style_class_th, "innerhtml" => "Valor" , "align" => "right" , "width" => "10%" ) , "elementopai" => $trHead));
$thTotal 	= $tabela->add("th",array("propriedades" => array("style" => $style_class_th, "innerhtml" => "Total" , "align" => "right" , "width" => "15%" ) , "elementopai" => $trHead));

$style_class_td = "height:25px;line-height:25px;border-bottom:1px dashed #666;font-size:12px;";

foreach($_pedido->getItens() as $item){
	$trBody			= $tabela->add("tr",array("elementopai" => $tbody));
	#$tamanhoproduto = tdc::p("td_ecommerce_tamanhoproduto",$item->produto);
	#$produto		= tdc::p("td_ecommerce_produto",$tamanhoproduto->produto);
    $referencia     = $item['referencia']!="" ? " - Ref.: " . tdc::utf8($item['referencia']) : '';
	
	$tdID   		= $tabela->add("td",array("propriedades" => array("style" => $style_class_td, "innerhtml" => completaString($item['id'],3) ) , "elementopai" => $trBody));
	$tdProduto 		= $tabela->add("td",array("propriedades" => array("style" => $style_class_td, "innerhtml" => tdc::utf8($item['produtonome'])) , "elementopai" => $trBody));
	if ($configuracoes->usarvariacaoproduto){
	 	$tdTamanho 	= $tabela->add("td",array("propriedades" => array("style" => $style_class_td, "innerhtml" => $tamanhoproduto->descricao ) , "elementopai" => $trBody));
	}		
	$tdQtdade 	= $tabela->add("td",array("propriedades" => array("style" => $style_class_td, "innerhtml" => completaString($item['quantidade'],3,'0') , "align" => "center") , "elementopai" => $trBody));
	$tdValor 	= $tabela->add("td",array("propriedades" => array("style" => $style_class_td, "innerhtml" => "R$ " . moneyToFloat($item['valor'],true)  , "align" => "right") , "elementopai" => $trBody));
	$tdTotal 	= $tabela->add("td",array("propriedades" => array("style" => $style_class_td, "innerhtml" => "R$ " . moneyToFloat($item['total'],true)  , "align" => "right") , "elementopai" => $trBody));
}

$style_class_td = "background-color:#EEE;font-weight:bold;font-size:14px;";

// TOTAIS
$trBody			= $tabela->add("tr" ,array ("elementopai" => $tbody));

$tdTotalLabel	= $tabela->add("td",array("propriedades" => array("style" => $style_class_td, "innerhtml" => "TOTAL" , "colspan" => ($configuracoes->usarvariacaoproduto?"3":"2")) , "elementopai" => $trBody));
$tdTotalQtdade 	= $tabela->add("td",array("propriedades" => array("style" => $style_class_td, "innerhtml" => $pedido->qtdetotaldeitens ,"align" => "center") , "elementopai" => $trBody));
$tdTotalValor 	= $tabela->add("td",array("propriedades" => array("style" => $style_class_td, "innerhtml" => "" ) , "elementopai" => $trBody));
$tdTotalTotal 	= $tabela->add("td",array("propriedades" => array("style" => $style_class_td, "innerhtml" => "R$ " .moneyToFloat($pedido->valortotal,true) , "align" => "right") , "elementopai" => $trBody));

$cidadeempresa	= tdc::p("td_cidade",tdc::p("td_bairro",$enderecoempresa->bairro)->cidade);

// RODAPÉ
$trFoot				= $tabela->add("tr");
$tdFoot				= $tabela->add("td",array("propriedades" => array("colspan" => "6") , "elementopai" => $trFoot));
$divRodape			= $tabela->add("div", array( "propriedades" => array("style" => "float:left;width:100%;margin-top:50px;border-top:3px solid #000;") , "elementopai" => $tdFoot));
$emitidopor			= $tabela->add("div", array( "propriedades" => array("innerhtml" => "Enviado pelo E-Commerce.","style" => "width:100%;float:left;") , "elementopai" =>  $divRodape));
$datahoraemissao	= $tabela->add("div", array( "propriedades" => array("innerhtml" => "Enviado em " . date("d/m/Y H:i:s"),"style" => "float:left;width:100%;text-align:left;") , "elementopai" =>  $divRodape));

$documento		= tdc::o("div");
// Exibe o documento
$documento->add($topo->getHTML(),$tabela->getHTML());
$documento->mostrar();