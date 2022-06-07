<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online

    * Classe que implementa a gera��o de impress�o de pedido
    * Data de Criacao: 14/08/2020
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/

$documento		= tdc::o("html");
$head			= tdc::o("head");
$body			= tdc::o("body");

$empresa		= tdc::p("td_empresa",Session::Get()->empresa);
$enderecoempresa= tdc::p("td_endereco",@(int)getListaRegFilho(getEntidadeId("empresa"),getEntidadeId("endereco"),Session::Get()->empresa)[0]->regfilho);

$style		= tdc::o("link");
$style->href= PATH_CURRENT_FILES_ECOMMERCE . "indicadores/indicadores.css";
$style->rel	= "stylesheet";

// Div Topo
$topo		= new Dom("div" , array("class" => "topo"));

// Div da Logo
$divlogo 	= $topo->add("div" , array("propriedades" => array ("class" => "div-logo")));
$logo		= $topo->add("img",array(
    "propriedades" => array( "id" => "logo" , "src" => Session::get("PATH_CURRENT_LOGO_PADRAO")) ,
    "elementopai" => $divlogo
));

// Div Titulo
$divtitulo	= $topo->add("div", array("propriedades" => array ("class" => "div-titulo")));
$titulo		= $topo->add("h1",array(
    "propriedades" => array("innerhtml" => utf8_encode("PRODUTOS ESGOTADOS") , "id" => "titulo"),
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

$tabela  	= new Dom("table" , array("class" => "tabela-itenspedido" , "cellpadding" => "0" , "cellspacing" => "0"));
$thead	 	= $tabela->add("thead");
$tbody		= $tabela->add("tbody");
$tfoot		= $tabela->add("tfoot");

// CABE�ALHO
$trHead		= $tabela->add("tr",array( "elementopai" => $thead));

$thPedido       = $tabela->add("th",array("propriedades" => array( "innerhtml" => "ID" , "align" => "left" , "width" => "20%" ) , "elementopai" => $trHead));
$thCliente 	    = $tabela->add("th",array("propriedades" => array( "innerhtml" => "Nome" , "align" => "left" , "width" => "40%" ) , "elementopai" => $trHead));
$thValorFrete   = $tabela->add("th",array("propriedades" => array( "innerhtml" => "Tamanho" , "align" => "left" , "width" => "40%" ) , "elementopai" => $trHead));


$conn               = Transacao::get();
$sqlSaldo           = "
	SELECT * FROM td_ecommerce_posicaogeralestoque 
	WHERE saldo <= 0 
	ORDER BY datahora DESC
	LIMIT 1;
";
$querySaldo         = $conn->query($sqlSaldo);
$itens              = $querySaldo->fetchAll(PDO::FETCH_OBJ);

if (sizeof($itens) > 0) {
    foreach ($itens as $item) {
        $trBody     = $tabela->add("tr", array("elementopai" => $tbody));

        $tamanho    = tdc::p("td_ecommerce_tamanhoproduto",$item->produto);
        $produto    = tdc::p("td_ecommerce_produto",$tamanho->produto);

        $tdPedido       = $tabela->add("td", array("propriedades" => array("innerhtml" => $produto->id), "elementopai" => $trBody));
        $tdCliente      = $tabela->add("td", array("propriedades" => array("innerhtml" => $produto->nome), "elementopai" => $trBody));
        $tdValorFrete   = $tabela->add("td", array("propriedades" => array("innerhtml" => $tamanho->descricao, "align" => "left"), "elementopai" => $trBody));
    }
}else{
    $trBody = $tabela->add("tr", array("elementopai" => $tbody));
    $tdPedido = $tabela->add("td", array("propriedades" => array("innerhtml" => "Nenhum Registro Encontrado" , "colspan" => 3 , "align" => "center"), "elementopai" => $trBody));
}

// RODAP�
$trFoot				= $tabela->add("tr");
$tdFoot				= $tabela->add("td",array("propriedades" => array("colspan" => "6") , "elementopai" => $trFoot));
$divRodape			= $tabela->add("div", array( "propriedades" => array("class" => "div-rodape") , "elementopai" => $tdFoot));
$emitidopor			= $tabela->add("div", array( "propriedades" => array("innerhtml" => "Impresso por " . Session::Get()->username , "class" => "div-emitidopor") , "elementopai" =>  $divRodape));
$datahoraemissao	= $tabela->add("div", array( "propriedades" => array("innerhtml" => "Impresso em " . date("d/m/Y H:i:s") , "class" => "div-datahora-emissao") , "elementopai" =>  $divRodape));


$head->add($style);
$body->add($topo->getHTML());
$body->add($tabela->getHTML());

// Exibe o documento
$documento->add($head,$body);
$documento->mostrar();