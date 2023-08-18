<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online

    * Carrinho Abandonados [ E-Commerce ]
    * Data de Criacao: 14/08/2020
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/

$documento		= tdc::o("html");
$head			= tdc::o("head");
$body			= tdc::o("body");

$empresa		= tdc::p("td_empresa",Session::Get()->empresa);
$enderecoempresa= tdc::p("td_endereco",@(int)getListaRegFilho(getEntidadeId("empresa"),getEntidadeId("endereco"),Session::Get()->empresa)[0]->regfilho);

$style		    = tdc::o("link");
$style->href    = URL_ECOMMERCE . "indicadores/indicadores.css";
$style->rel	    = "stylesheet";

// Div Topo
$topo		= new Dom("div" , array("class" => "topo"));

// Div da Logo
$divlogo 	= $topo->add("div" , array("propriedades" => array ("class" => "div-logo")));
$logo		= $topo->add("img",array(
    "propriedades" => array( "id" => "logo" , "src" => URL_CURRENT_LOGO_PADRAO) ,
    "elementopai" => $divlogo
));

// Div Titulo
$divtitulo	= $topo->add("div", array("propriedades" => array ("class" => "div-titulo")));
$titulo		= $topo->add("h1",array(
    "propriedades" => array("innerhtml" => tdc::utf8("CARRINHOS ABANDONADOS") , "id" => "titulo"),
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

// CABEÇALHO
$trHead		= $tabela->add("tr",array( "elementopai" => $thead));

$thCarrinho     = $tabela->add("th",array("propriedades" => array( "innerhtml" => "Carrinho" , "align" => "left" , "width" => "10%" ) , "elementopai" => $trHead));
$thCliente 	    = $tabela->add("th",array("propriedades" => array( "innerhtml" => "Cliente" , "align" => "left" , "width" => "60%" ) , "elementopai" => $trHead));
$thValorFrete   = $tabela->add("th",array("propriedades" => array( "innerhtml" => "Frete" , "align" => "right" , "width" => "10%" ) , "elementopai" => $trHead));
$thQuantidade   = $tabela->add("th",array("propriedades" => array( "innerhtml" => "Qtdade." , "align" => "center" , "width" => "10%" ) , "elementopai" => $trHead));
$thValorTotal   = $tabela->add("th",array("propriedades" => array( "innerhtml" => "Total" , "align" => "right" , "width" => "10%" ) , "elementopai" => $trHead));

$conn = Transacao::get();
$sqlTotalAcessos = "
    SELECT 
        (SUM(b.valor) * SUM(b.qtde)) valortotal,
        SUM(b.qtde) qtdetotalitens,
        a.id,
        a.cliente cliente,
        a.valorfrete valorfrete
    FROM td_ecommerce_carrinhocompras a 
    INNER JOIN td_ecommerce_carrinhoitem b ON a.id = b.carrinho
    WHERE (a.inativo = false OR a.inativo IS NULL)
    AND a.datahoracriacao < NOW() - INTERVAL 30 DAY
    GROUP BY a.id
";
$queryTotalAcessos = $conn->query($sqlTotalAcessos);
if ($queryTotalAcessos->rowCount() > 0){
    $itens = $queryTotalAcessos->fetchAll(PDO::FETCH_OBJ);
    foreach($itens as $item){
        $trBody		    = $tabela->add("tr",array("elementopai" => $tbody));

        $cliente        = $item->cliente == 0? utf8_encode("Não Logado") : $item->cliente . " - " . utf8charset(tdc::p("td_ecommerce_cliente",$item->cliente)->nome ,2);
        $frete          = $item->valorfrete == 0?" - ":"R$ " . moneyToFloat($item->valorfrete,true);
        $valor_total    = $item->qtdetotalitens <= 0 ? '0,00' : $item->valortotal;

        $tdCarrinho   	= $tabela->add("td",array("propriedades" => array( "innerhtml" => $item->id) , "elementopai" => $trBody));
        $tdCliente   	= $tabela->add("td",array("propriedades" => array( "innerhtml" => $cliente) , "elementopai" => $trBody));
        $tdValorFrete   = $tabela->add("td",array("propriedades" => array( "innerhtml" => $frete , "align" => "right") , "elementopai" => $trBody));
        $tdQtdade 	    = $tabela->add("td",array("propriedades" => array( "innerhtml" => $item->qtdetotalitens ,  "align" => "center" ) , "elementopai" => $trBody));
        $tdValorTotal   = $tabela->add("td",array("propriedades" => array( "innerhtml" => "R$ " . moneyToFloat($valor_total,true) , "align" => "right") , "elementopai" => $trBody));

    }
}else{
    $trBody = $tabela->add("tr", array("elementopai" => $tbody));
    $tdPedido = $tabela->add("td", array("propriedades" => array("innerhtml" => "Nenhum Registro Encontrado" , "colspan" => 5 , "align" => "center"), "elementopai" => $trBody));    
}
// RODAPÉ
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