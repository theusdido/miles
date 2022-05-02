<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online

    * Classe que implementa a geração de impressão de pedido
    * Data de Criacao: 14/08/2020
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/

$documento		= tdc::o("html");
$head			= tdc::o("head");
$body			= tdc::o("body");

$registro 		= tdc::r("registro");
$pedido			= tdc::p("td_ecommerce_carrinhocompras",$registro);
$empresa		= tdc::p("td_empresa",Session::Get()->empresa);
$cliente 		= tdc::p("td_ecommerce_cliente",$pedido->cliente);
$enderecliente 	= tdc::p("td_ecommerce_endereco",@(int)getListaRegFilho(getEntidadeId("ecommerce_cliente"),getEntidadeId("ecommerce_endereco"),$cliente->id)[0]->regfilho);
$enderecoempresa= tdc::p("td_endereco",@(int)getListaRegFilho(getEntidadeId("empresa"),getEntidadeId("endereco"),Session::Get()->empresa)[0]->regfilho);

$style			= tdc::o("link");
$style->href	= Session::Get('URL_ECOMMERCE') . "pedidoimpressao/pedidoimpressao.css";
$style->rel		= "stylesheet";

// Div Topo
$topo		= new Dom("div" , array("class" => "topo"));

// Div da Logo
$divlogo 	= $topo->add("div" , array("propriedades" => array ("class" => "div-logo")));
$logo		= $topo->add("img",array(
	"propriedades" => array( "id" => "logo" , "src" => Session::get("URL_CURRENT_LOGO_PADRAO")) ,
	"elementopai" => $divlogo
));

// Div Titulo
$divtitulo	= $topo->add("div", array("propriedades" => array ("class" => "div-titulo")));
$titulo		= $topo->add("h1",array(
	"propriedades" => array("innerhtml" => "CARRINHO DE COMPRAS" , "id" => "titulo"),
	"elementopai" => $divtitulo
));

// Lista Dados da Empresa
$listadadosempresa 	= $topo->node("ul");
$liNomeEmpresa 		= $topo->add("li", array("propriedades" => array("innerhtml" => utf8charset($empresa->razaosocial,5)) , "elementopai" => $listadadosempresa )); 
$liCNPJEmpresa		= $topo->add("li", array("propriedades" => array("innerhtml" => "CNPJ: " . $empresa->cnpj) , "elementopai" => $listadadosempresa )); 
$liTelefoneEmpresa	= $topo->add("li", array("propriedades" => array("innerhtml" => "TELEFONE: ".$empresa->telefone) , "elementopai" => $listadadosempresa )); 
$liEMailEmpresa		= $topo->add("li", array("propriedades" => array("innerhtml" => "E-MAIL: ". $empresa->email) , "elementopai" => $listadadosempresa )); 

// Div dados da empresa
$divdadosempresa 	= $topo->add("div", array("propriedades" => array("innerhtml" => $listadadosempresa , "class" => "div-dados-empresa")));

// Div Número do Pedido
$divnumeropedido 	= $topo->add("div", array("propriedades" => array("innerhtml" => "NÚMERO: " . $pedido->id , "class" => "div-numeropedido" )));

// Div Data e Hora do Pedido
$divdatahora	 	= $topo->add("div", array("propriedades" => array("innerhtml" => "DATA/HORA: " . datetimeToMysqlFormat($pedido->datahoracriacao,true) , "class" => "div-datahorapedido")));

// Div Nome Cliente
$divnomecliente 	= $topo->add("div", array("propriedades" => array("innerhtml" => "Razão Social: " . $cliente->id . " - " . strtoupper($cliente->nome) , "class" => "div-cliente-razaosocial")));

// Div Dados de Contato do Cliente
$divdadoscliente 	= $topo->add("div", array("propriedades" => array("class" => "div-dados-cliente" ,"innerhtml" => array(	
	$topo->node("div", array("innerhtml" => "CNPJ: " . $cliente->cnpj , "class" => "div-cliente-cnpj")) ,
	$topo->node("div", array("innerhtml" => "Telefone: " . $cliente->telefone , "class" => "div-cliente-telefone")) ,
	$topo->node("div", array("innerhtml" => "E-Mail: " . $cliente->email , "class" => "div-cliente-email"))	
))));

// Div Dados do Endereço do Cliente
$divdadosenderecocliente	= $topo->add("div", array("propriedades" => array( "class" => "div-dados-endereco" , "innerhtml" => array(
	$topo->node("div", array("innerhtml" => "Logradouro: " . utf8charset($enderecliente->logradouro,5) , "class" => "div-enderecocliente-logradouro" )) ,
	$topo->node("div", array("innerhtml" => "Complemento: " . utf8charset($enderecliente->complemento,5) , "class" => "div-enderecocliente-complemento")) ,
	$topo->node("div", array("innerhtml" => "Bairro: " . utf8charset($enderecliente->bairro,5) , "class" => "div-enderecocliente-bairro"))
))));

$divdadosenderecocliente2	= $topo->add("div", array("propriedades" => array( "class" => "div-dados-endereco" , "innerhtml" => array(
	$topo->node("div", array("innerhtml" => "Cidade: " . utf8charset($enderecliente->cidade,5) , "class" => "div-enderecocliente-cidade")),
	$topo->node("div", array("innerhtml" => "CEP: " . $enderecliente->cep , "class" => "div-enderecocliente-cep"))	
))));

// Div Cabeçalho do Pedido
$divcabecalhopedido = $topo->add("div", array("propriedades" => array( "class" => "div-cabecalho-pedido" , "innerhtml" => array(
	$topo->node("div", array("innerhtml" => " ")),
    $topo->node("div", array("innerhtml" => "Valor Frete: R$ " . moneyToFloat($pedido->valorfrete,true) , "class" => "div-cabecalho-pedido-valorfrete")),
	$topo->node("div", array("innerhtml" => "Valor Total: R$ " . moneyToFloat($pedido->valortotal,true) , "class" => "div-cabecalho-pedido-valortotal"))
))));

$tabela  	= new Dom("table" , array("class" => "tabela-itenspedido" , "cellpadding" => "0" , "cellspacing" => "0"));
$thead	 	= $tabela->add("thead");
$tbody		= $tabela->add("tbody");
$tfoot		= $tabela->add("tfoot");

// CABEÇALHO
$trHead		= $tabela->add("tr",array( "elementopai" => $thead));

$thID   	= $tabela->add("th",array("propriedades" => array( "innerhtml" => "ID" , "align" => "left" , "width" => "5%" ) , "elementopai" => $trHead));
$thProduto 	= $tabela->add("th",array("propriedades" => array( "innerhtml" => "Produto" , "align" => "left" , "width" => "40%" ) , "elementopai" => $trHead));
$thTamanho 	= $tabela->add("th",array("propriedades" => array( "innerhtml" => "Tamanho" , "align" => "left" , "width" => "20%" ) , "elementopai" => $trHead));
$thQtdade 	= $tabela->add("th",array("propriedades" => array( "innerhtml" => "Qtdade" , "align" => "center" , "width" => "10%" ) , "elementopai" => $trHead));
$thValor 	= $tabela->add("th",array("propriedades" => array( "innerhtml" => "Valor" , "align" => "right" , "width" => "10%" ) , "elementopai" => $trHead));
$thTotal 	= $tabela->add("th",array("propriedades" => array( "innerhtml" => "Total" , "align" => "right" , "width" => "15%" ) , "elementopai" => $trHead));


$ft = tdc::f("carrinho","=",$pedido->id);
$ft->setPropriedade("order","descricao");
$itens 		= tdc::d("td_ecommerce_carrinhoitem",$ft);


foreach($itens as $item){
	$trBody		= $tabela->add("tr",array("elementopai" => $tbody));
	$tamanhoproduto = tdc::p("td_ecommerce_tamanhoproduto",$item->produto);
	$produto		= tdc::p("td_ecommerce_produto",$tamanhoproduto->produto);
    $referencia     = $produto->referencia!="" ? " - Ref.: " . $produto->referencia : '';

	$tdID   	= $tabela->add("td",array("propriedades" => array( "innerhtml" => $produto->id ) , "elementopai" => $trBody));
	$tdProduto 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => utf8charset($produto->nome . $referencia,5) ) , "elementopai" => $trBody));
	$tdTamanho 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => utf8charset($tamanhoproduto->descricao,5) ) , "elementopai" => $trBody));
	$tdQtdade 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => $item->qtde  , "align" => "center") , "elementopai" => $trBody));
	$tdValor 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => "R$ " . moneyToFloat($item->valor,true)  , "align" => "right") , "elementopai" => $trBody));
	$tdTotal 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => "R$ " . moneyToFloat($item->valortotal,true)  , "align" => "right") , "elementopai" => $trBody));
}

// TOTAIS
$trBody			= $tabela->add("tr" ,array ("propriedades" => array ("class" => "tr-totais-pedido") , "elementopai" => $tbody));

$tdTotalLabel	= $tabela->add("td",array("propriedades" => array( "innerhtml" => "TOTAL" , "colspan" => "3") , "elementopai" => $trBody));
$tdTotalQtdade 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => $pedido->qtdetotaldeitens ,"align" => "center") , "elementopai" => $trBody));
$tdTotalValor 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => "" ) , "elementopai" => $trBody));
$tdTotalTotal 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => "R$ " .moneyToFloat($pedido->valortotal,true) , "align" => "right") , "elementopai" => $trBody));

//$enderecoempresa->td_cidade
$cidadeempresa	= tdc::p("td_cidade",tdc::p("td_bairro",$enderecoempresa->bairro)->cidade);

// RODAPÉ
$trFoot				= $tabela->add("tr");
$tdFoot				= $tabela->add("td",array("propriedades" => array("colspan" => "6") , "elementopai" => $trFoot));
$divRodape			= $tabela->add("div", array( "propriedades" => array("class" => "div-rodape") , "elementopai" => $tdFoot));
$emitidopor			= $tabela->add("div", array( "propriedades" => array("innerhtml" => "Impresso por " . Session::Get()->username , "class" => "div-emitidopor") , "elementopai" =>  $divRodape));
$datahoraemissao	= $tabela->add("div", array( "propriedades" => array("innerhtml" => "Impresso em " . date("d/m/Y H:i:s") , "class" => "div-datahora-emissao") , "elementopai" =>  $divRodape));
#$divEndereco1Rodape	= $tabela->add("div", array( "propriedades" => array("innerhtml" => $enderecoempresa->logradouro ."," . $enderecoempresa->numero . " - " . $enderecoempresa->complemento , "class" => "div-endereco-empresa1") , "elementopai" =>  $divRodape));
#$divEndereco2Rodape	= $tabela->add("div", array( "propriedades" => array("innerhtml" => "Cep:" . $enderecoempresa->cep . " - " . tdc::p("td_bairro",$enderecoempresa->bairro)->nome . " - " . $cidadeempresa->nome . " / " . tdc::p("td_uf",$cidadeempresa->uf)->sigla , "class" => "div-endereco-empresa2") , "elementopai" =>  $divRodape));

$head->add($style);
$body->add($topo->getHTML());
$body->add($tabela->getHTML());

// Exibe o documento
$documento->add($head,$body);
$documento->mostrar();