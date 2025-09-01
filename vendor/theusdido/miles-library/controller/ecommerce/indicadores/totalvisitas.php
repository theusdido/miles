<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online

    * Total de Visitas [ E-Commerce ]
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
    "propriedades" => array("innerhtml" => tdc::utf8("TOTAL DE VISITAS") , "id" => "titulo"),
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

$thCliente  = $tabela->add("th",array("propriedades" => array( "innerhtml" => "Cliente" , "align" => "left" , "width" => "40%" ) , "elementopai" => $trHead));
$thData 	    = $tabela->add("th",array("propriedades" => array( "innerhtml" => "Data" , "align" => "left" , "width" => "20%" ) , "elementopai" => $trHead));
$thHora 	    = $tabela->add("th",array("propriedades" => array( "innerhtml" => "Hora" , "align" => "left" , "width" => "10%" ) , "elementopai" => $trHead));
$thIP 	        = $tabela->add("th",array("propriedades" => array( "innerhtml" => "IP" , "align" => "left" , "width" => "10%" ) , "elementopai" => $trHead));
$thNavegador 	= $tabela->add("th",array("propriedades" => array( "innerhtml" => "Navegador" , "align" => "left" , "width" => "15%" ) , "elementopai" => $trHead));
$thSessao 	    = $tabela->add("th",array("propriedades" => array( "innerhtml" => tdc::utf8("Sessão") , "align" => "left" , "width" => "15%" ) , "elementopai" => $trHead));

$conn = Transacao::get();
$sqlTotalAcessos = "SELECT * FROM td_ecommerce_visitantes GROUP BY sessao,ip,cliente ORDER BY data DESC,hora DESC;";
$queryTotalAcessos = $conn->query($sqlTotalAcessos);
if ($queryTotalAcessos->rowCount() > 0 ){
    $itens = $queryTotalAcessos->fetchAll(PDO::FETCH_OBJ);
    foreach($itens as $item){
        $trBody		    = $tabela->add("tr",array("elementopai" => $tbody));

        if ($item->cliente == 0){
            $cliente = tdc::utf8("Não Logado");
        }else{
            $cliente = $item->cliente . " - " . tdc::p("td_ecommerce_cliente",$item->cliente)->nome;
        }

        $tdCliente   	= $tabela->add("td",array("propriedades" => array( "innerhtml" => $cliente) , "elementopai" => $trBody));
        $tdData 	    = $tabela->add("td",array("propriedades" => array( "innerhtml" => $item->data ) , "elementopai" => $trBody));
        $tdHora 	    = $tabela->add("td",array("propriedades" => array( "innerhtml" => $item->hora ) , "elementopai" => $trBody));
        $tdIP 	        = $tabela->add("td",array("propriedades" => array( "innerhtml" => $item->ip  ) , "elementopai" => $trBody));
        $tdNavegador 	= $tabela->add("td",array("propriedades" => array( "innerhtml" => getNomeNavegador($item->navegador) ) , "elementopai" => $trBody));
        $tdSessao 	    = $tabela->add("td",array("propriedades" => array( "innerhtml" => $item->sessao  ) , "elementopai" => $trBody));

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