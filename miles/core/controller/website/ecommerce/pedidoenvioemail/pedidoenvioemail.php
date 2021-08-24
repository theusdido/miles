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

$cliente 			= tdc::p("td_ecommerce_cliente",$pedido->td_cliente);
$enderecliente 		= tdc::p("td_ecommerce_endereco",@(int)getListaRegFilho(getEntidadeId("ecommerce_cliente"),getEntidadeId("ecommerce_endereco"),$cliente->id)[0]->regfilho);
$enderecoempresa	= tdc::p("td_endereco",@(int)getListaRegFilho(getEntidadeId("empresa"),getEntidadeId("endereco"),Session::Get()->empresa)[0]->regfilho);
$configuracoes		= tdc::p("td_ecommerce_configuracoes",1);

// Div Topo
$topo		= new Dom("div" , array("class" => "topo"));

// Div da Logo
$divlogo 	= $topo->add("div" , array("propriedades" => array ("class" => "div-logo")));
$logo		= $topo->add("img",array(
	"propriedades" => array( "id" => "logo" , "src" => Session::get("URL_CURRENT_LOGO_PADRAO") ) ,
	"elementopai" => $divlogo
));

// Div Titulo
$divtitulo	= $topo->add("div", array("propriedades" => array ("class" => "div-titulo")));
$titulo		= $topo->add("h1",array(
	"propriedades" => array("innerhtml" => "PEDIDO DE VENDA" , "id" => "titulo"),
	"elementopai" => $divtitulo
));

// Lista Dados da Empresa
$listadadosempresa 	= $topo->node("ul");
$liNomeEmpresa 		= $topo->add("li", array("propriedades" => array("innerhtml" => $empresa->razaosocial) , "elementopai" => $listadadosempresa )); 
$liCNPJEmpresa		= $topo->add("li", array("propriedades" => array("innerhtml" => "CNPJ: " . $empresa->cnpj) , "elementopai" => $listadadosempresa )); 
$liTelefoneEmpresa	= $topo->add("li", array("propriedades" => array("innerhtml" => "TELEFONE: ".$empresa->telefone) , "elementopai" => $listadadosempresa )); 
$liEMailEmpresa		= $topo->add("li", array("propriedades" => array("innerhtml" => "E-MAIL: ". $empresa->email) , "elementopai" => $listadadosempresa )); 

// Div dados da empresa
$divdadosempresa 	= $topo->add("div", array("propriedades" => array("innerhtml" => $listadadosempresa , "class" => "div-dados-empresa")));

// Div Número do Pedido
$divnumeropedido 	= $topo->add("div", array("propriedades" => array("innerhtml" => "NÚMERO: " . $pedido->id , "class" => "div-numeropedido" )));

// Div Data e Hora do Pedido
$divdatahora	 	= $topo->add("div", array("propriedades" => array("innerhtml" => "DATA/HORA: " . datetimeToMysqlFormat($pedido->datahoraretorno,true) , "class" => "div-datahorapedido")));

// Div Nome Cliente
$divnomecliente 	= $topo->add("div", array("propriedades" => array("innerhtml" => "CLIENTE: " . $cliente->id . " - " . strtoupper($cliente->nome) , "class" => "div-cliente-razaosocial")));

// Div Dados de Contato do Cliente
$documento			= $cliente->cpf != '' ? 'CPF: ' . $cliente->cpf : 'CNPJ: ' . $cliente->cnpj;
$divdadoscliente 	= $topo->add("div", array("propriedades" => array("class" => "div-dados-cliente" ,"innerhtml" => array(	
	$topo->node("div", array("innerhtml" => $documento, "class" => "div-cliente-cnpj")) ,
	$topo->node("div", array("innerhtml" => "Telefone: " . $cliente->telefone , "class" => "div-cliente-telefone")) ,
	$topo->node("div", array("innerhtml" => "E-Mail: " . $cliente->email , "class" => "div-cliente-email"))	
))));

// Div Dados do Endereço do Cliente
$divdadosenderecocliente	= $topo->add("div", array("propriedades" => array( "class" => "div-dados-endereco" , "innerhtml" => array(
	$topo->node("div", array("innerhtml" => "Logradouro: " . $enderecliente->logradouro , "class" => "div-enderecocliente-logradouro" )) ,
	$topo->node("div", array("innerhtml" => "Complemento: " . $enderecliente->complemento , "class" => "div-enderecocliente-complemento")) ,
	$topo->node("div", array("innerhtml" => "Bairro: " . $enderecliente->bairro , "class" => "div-enderecocliente-bairro"))
))));

$divdadosenderecocliente2	= $topo->add("div", array("propriedades" => array( "class" => "div-dados-endereco" , "innerhtml" => array(
	$topo->node("div", array("innerhtml" => "Cidade: " . $enderecliente->cidade , "class" => "div-enderecocliente-cidade")),
	$topo->node("div", array("innerhtml" => "CEP: " . $enderecliente->cep , "class" => "div-enderecocliente-cep"))	
))));

// Div Cabeçalho do Pedido
$divcabecalhopedido = $topo->add("div", array("propriedades" => array( "class" => "div-cabecalho-pedido" , "innerhtml" => array(
	$topo->node("div", array("innerhtml" => "Status: " . tdc::p("td_ecommerce_statuspedido",$pedido->td_status)->descricao , "class" => "div-cabecalho-pedido-status")),
    $topo->node("div", array("innerhtml" => "Valor Frete: " . ($pedido->valorfrete != ""?'R$ ' . moneyToFloat($pedido->valorfrete,true):'-') , "class" => "div-cabecalho-pedido-valorfrete")),
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
if ($configuracoes->usarvariacaoproduto){
	$thTamanho 	= $tabela->add("th",array("propriedades" => array( "innerhtml" => "Tamanho" , "align" => "left" , "width" => "20%" ) , "elementopai" => $trHead));
}	
$thQtdade 	= $tabela->add("th",array("propriedades" => array( "innerhtml" => "Qtdade" , "align" => "center" , "width" => "10%" ) , "elementopai" => $trHead));
$thValor 	= $tabela->add("th",array("propriedades" => array( "innerhtml" => "Valor" , "align" => "right" , "width" => "10%" ) , "elementopai" => $trHead));
$thTotal 	= $tabela->add("th",array("propriedades" => array( "innerhtml" => "Total" , "align" => "right" , "width" => "15%" ) , "elementopai" => $trHead));

$itens 		= tdc::d(getEntidadeEcommercePedidoItem(),tdc::f("td_pedido","=",$pedido->id));
foreach($itens as $item){
	$trBody		= $tabela->add("tr",array("elementopai" => $tbody));
	$tamanhoproduto = tdc::p("td_ecommerce_tamanhoproduto",$item->td_produto);
	$produto		= tdc::p("td_ecommerce_produto",$tamanhoproduto->td_produto);
    $referencia     = $produto->referencia!="" ? " - Ref.: " . $produto->referencia : '';

	$tdID   	= $tabela->add("td",array("propriedades" => array( "innerhtml" => $produto->id ) , "elementopai" => $trBody));
	$tdProduto 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => $produto->nome . $referencia ) , "elementopai" => $trBody));
	if ($configuracoes->usarvariacaoproduto){
		$tdTamanho 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => $tamanhoproduto->descricao ) , "elementopai" => $trBody));
	}		
	$tdQtdade 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => $item->qtde  , "align" => "center") , "elementopai" => $trBody));
	$tdValor 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => "R$ " . moneyToFloat($item->valor,true)  , "align" => "right") , "elementopai" => $trBody));
	$tdTotal 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => "R$ " . moneyToFloat($item->valortotal,true)  , "align" => "right") , "elementopai" => $trBody));
}

// TOTAIS
$trBody			= $tabela->add("tr" ,array ("propriedades" => array ("class" => "tr-totais-pedido") , "elementopai" => $tbody));

$tdTotalLabel	= $tabela->add("td",array("propriedades" => array( "innerhtml" => "TOTAL" , "colspan" => ($configuracoes->usarvariacaoproduto?"3":"2")) , "elementopai" => $trBody));
$tdTotalQtdade 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => $pedido->qtdetotaldeitens ,"align" => "center") , "elementopai" => $trBody));
$tdTotalValor 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => "" ) , "elementopai" => $trBody));
$tdTotalTotal 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => "R$ " .moneyToFloat($pedido->valortotal,true) , "align" => "right") , "elementopai" => $trBody));

//$enderecoempresa->td_cidade
$cidadeempresa	= tdc::p("td_cidade",tdc::p("td_bairro",$enderecoempresa->td_bairro)->td_cidade);

// RODAPÉ
$trFoot				= $tabela->add("tr");
$tdFoot				= $tabela->add("td",array("propriedades" => array("colspan" => "6") , "elementopai" => $trFoot));
$divRodape			= $tabela->add("div", array( "propriedades" => array("class" => "div-rodape") , "elementopai" => $tdFoot));
$emitidopor			= $tabela->add("div", array( "propriedades" => array("innerhtml" => "Enviado pelo E-Commerce.", "class" => "div-emitidopor") , "elementopai" =>  $divRodape));
$datahoraemissao	= $tabela->add("div", array( "propriedades" => array("innerhtml" => "Enviado em " . date("d/m/Y H:i:s") , "class" => "div-datahora-emissao") , "elementopai" =>  $divRodape));

$style = tdc::o("style");
$style->add( file_get_contents(PATH_CURRENT_FILES_ECOMMERCE . "pedidoenvioemail/pedidoenvioemail.css") );

$documento		= tdc::o("div");

// Exibe o documento
$documento->add($style,$topo->getHTML(),$tabela->getHTML());
$documento->mostrar();