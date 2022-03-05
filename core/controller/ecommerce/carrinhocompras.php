<?php

$carrinhoID = $_GET["id"];

// Dados do Carrinho
$carrinho = tdClass::Criar("persistent",array("td_ecommerce_carrinhocompras",$carrinhoID))->contexto;
$datahoracarrinho 	= explode(" ",$carrinho->datahoracriacao);
$datadocarrinho 	= '<div id="data-carrinho"><span class="fas fa-calendar-alt" aria-hidden="true"></span><span>'.dateToMysqlFormat($datahoracarrinho[0],true).'</span></div>';
$horadocarrinho 	= '<div id="hora-carrinho"><span class="fas fa-clock" aria-hidden="true"></span><span>'.(isset($datahoracarrinho[1])?$datahoracarrinho[1]:'').'</span></div>';

// Valor Total do Carrinho
$valorTotalCarrinho = moneyToFloat($carrinho->valortotal,true);
if ($valorTotalCarrinho == 0 || $valorTotalCarrinho == ''){
	$valorTotalCarrinho = "0,00";
}
$totalcarrinho 		= '<div id="total-carrinho"><small>Valor Total</small><h1>R$ '.$valorTotalCarrinho.'</h1></div>';

// Lista de Estado dos carrinhos
if ($conn = Transacao::Get()){
	$listaStatuscarrinho = "<select class='form-control' id='carrinho-listastatus'>";
	$listaStatuscarrinho .= "<option value='0'>Ativo</option>";
	$listaStatuscarrinho .= "<option value='1'>Inativo</option>";
	$listaStatuscarrinho .= "</select>";
}
$btnAlterarStatus = '<button class="btn btn-primary form-control" type="button" id="btn-carrinhohome-alterarstatus">Alterar Status</button>';

// Dados do Cliente
$clienteEntidadeID = getEntidadeId("ecommerce_cliente");
if ($carrinho->cliente > 0){
	$clienteID					= $carrinho->cliente;
	$cliente 					= tdClass::Criar("persistent",array("td_ecommerce_cliente",$clienteID))->contexto;
	$clienteNome				= strtoupper($cliente->nome);
	$cnpj 						= "<div class='dadoscliente'><label>CNPJ</label><p>".$cliente->cnpj."</p></div>";
	$nomefantasia				= "<div class='dadoscliente'><label>Nome Fantasia</label><p>".$cliente->nomefantasia."</p></div>";
	$email 						= "<div class='dadoscliente'><label>E-Mail</label><p>".$cliente->email."</p></div>";
	$telefone 					= "<div class='dadoscliente'><label>Telefone</label><p>".$cliente->telefone."</p></div>";
	$btnAlterarDadosCliente 	= '<button class="btn btn-success" type="button" id="carrinho-editar-cliente">Editar</button>';
	$dadosClientePanel			= $cnpj . $telefone . $nomefantasia . $email . $btnAlterarDadosCliente;
}else{
	$clienteID = 0;
	$clienteNome = $cnpj = $nomefantasia = $email = $telefone = $btnAlterarDadosCliente = "";
	$dadosClientePanel = '<div class="alert alert-warning text-center">Cliente ainda não efetuou o login.</div>';
}

//Estilo
$estilo = tdClass::Criar("style");
$estilo->type = "text/css";
$estilo->add('
	#dados-itens-carrinho .panel-heading b{
		text-align:right;
	}
	.dadoscliente{
		float:left;
		margin:10px;
		width:40%;		
	}
	.dadoscliente p,.dadoscliente label{
		float:left;
		clear:left;
		margin:5px;
	}
	#total-carrinho{
		text-align:right;
	}
	#total-carrinho h1 {
		margin:0px;
	}
	#data-carrinho,#hora-carrinho{
		float:left;
		margin-top:40px;
		margin-left:10px;		
	}
	#data-carrinho span,#hora-carrinho span {
		padding-left:5px;
	}
	#carrinho-listastatus{
		margin-bottom:5px;
	}
	#dados-carrinho , #dados-cliente-carrinho{
		height:265px;
	}
	#btn-imptimir-carrinho{
		float:right;
		margin-top:-7px;
	}	
');
$estilo->mostrar();

// Titulo
$titulo = tdClass::Criar("titulo");
$titulo->add("Carrinho de Compras");
$titulo->mostrar();

// Linha 1
$linha1 = tdClass::Criar("div");
$linha1->class = "row-fluid";
$colDadosCliente = tdClass::Criar("div");
$colDadosCliente->class = "col-md-8";
$colDadoscarrinho = tdClass::Criar("div");
$colDadoscarrinho->class = "col-md-4";

// Botão Imprimir Carrinho
$btnImprimirCarrinho = '<button id="btn-imptimir-carrinho" type="button" class="btn btn-default" aria-label="Imprimir Carrinho"><span class="fas fa-print" aria-hidden="true"></span></button>';

// Panel carrinho
$panelDadoscarrinho = tdClass::Criar("panel");
$panelDadoscarrinho->id = "dados-carrinho";
$panelDadoscarrinho->tipo = "default";
$panelDadoscarrinho->head("Dados do Carrinho" . $btnImprimirCarrinho);
$panelDadoscarrinho->body($totalcarrinho . $datadocarrinho . $horadocarrinho . $listaStatuscarrinho . $btnAlterarStatus);
$colDadoscarrinho->add($panelDadoscarrinho);
$linha1->add($colDadoscarrinho);


// Panel Cliente
$panelDadosCliente = tdClass::Criar("panel");
$panelDadosCliente->id = "dados-cliente-carrinho";
$panelDadosCliente->tipo = "success";
$panelDadosCliente->head("<b>".($clienteNome==''?'Cliente':$clienteNome)."</b>");
$panelDadosCliente->body($dadosClientePanel);
$colDadosCliente->add($panelDadosCliente);
$linha1->add($colDadosCliente);

$linha1->mostrar();

// Linha 2
$linha2 = tdClass::Criar("div");
$linha2->class = "row-fluid";

$colItenscarrinho = tdClass::Criar("div");
$colItenscarrinho->class = "col-md-12";

// Itens do carrinho
if ($conn = Transacao::Get()){
	$tItens = tdClass::Criar("tabela");
	$tItens->class = "table table-hover";
	$tItensHead = tdClass::Criar("thead");;
	$tItensTR = tdClass::Criar("tabelalinha");

	// ID Itens
	$tdItensId = tdClass::Criar("tabelahead");
	$tdItensId->add('ID');
	$tItensTR->add($tdItensId);

	// Descrição
	$tdItensDescricao = tdClass::Criar("tabelahead");
	$tdItensDescricao->add('Descrição');
	$tItensTR->add($tdItensDescricao);

	// Qtde
	$tdItensQtde = tdClass::Criar("tabelahead");
	$tdItensQtde->add('Qtde');
	$tdItensQtde->class = "text-center";
	$tItensTR->add($tdItensQtde);

	// Valor
	$tdItensValor = tdClass::Criar("tabelahead");
	$tdItensValor->add('Valor');
	$tdItensValor->class = "text-center";
	$tItensTR->add($tdItensValor);

	// Total
	$tdItensTotal = tdClass::Criar("tabelahead");
	$tdItensTotal->add('Total');
	$tdItensTotal->class = "text-center";

	$tItensTR->add($tdItensTotal);

	$tBody = tdClass::Criar("tbody");
	
	$totalquantidade = $totalvalorunitario = $totalgeral = 0;
	$sqlItens = "SELECT id,qtde,valor,descricao FROM td_ecommerce_carrinhoitem WHERE carrinho = " .$carrinhoID;
	$queryItens = $conn->query($sqlItens);
	while ($linhaItens = $queryItens->fetch()){
		$tr = tdClass::Criar("tabelalinha");

		$td = tdClass::Criar("tabelacelula");
		$td->add($linhaItens["id"]);
		$tr->add($td);

		$td = tdClass::Criar("tabelacelula");
		$td->add($linhaItens["descricao"]);		
		$tr->add($td);
		
		$qtde 				= $linhaItens["qtde"];
		$valorunitario 		= $linhaItens["valor"];
		$valortotal			= $qtde * $valorunitario;
		
		$td = tdClass::Criar("tabelacelula");
		$td->add($qtde);
		$td->class = "text-center";
		$tr->add($td);

		$td = tdClass::Criar("tabelacelula");
		$td->add("R$ " . moneyToFloat($valorunitario,true));
		$td->class = "text-center";
		$tr->add($td);

		$td = tdClass::Criar("tabelacelula");
		$td->add("R$ " . moneyToFloat($valortotal,true));
		$td->class = "text-center";
		$tr->add($td);

		$tBody->add($tr);
		
		$totalquantidade += $qtde;
		$totalvalorunitario += $valorunitario;
		$totalgeral += $valortotal;
	}
	
	if ($queryItens->rowCount() <= 0){

		$td = tdClass::Criar("tabelacelula");
		$td->add("Nenhum item adicionado ao carrinho.");
		$td->colspan = "5";
		$td->class = "text-center";

		$tr = tdClass::Criar("tabelalinha");
		$tr->class = "warning";		
		$tr->add($td);
		$tBody->add($tr);
		
		$tFoot = null;
	}else{

		$tFoot = tdClass::Criar("tfoot");
		$trFoot = tdClass::Criar("tabelalinha");
		$trFoot->class = "success";

		$td = tdClass::Criar("tabelahead");
		$td->add("<b>TOTAL</b>");
		$td->class = "text-left";
		$td->colspan = "2";
		$trFoot->add($td);
		
		$td = tdClass::Criar("tabelahead");
		$td->add($totalquantidade);
		$td->class = "text-center";
		$trFoot->add($td);
		
		$td = tdClass::Criar("tabelahead");
		$td->add("R$ " . moneyToFloat($totalvalorunitario,true));
		$td->class = "text-center";
		$trFoot->add($td);
		
		$td = tdClass::Criar("tabelahead");
		$td->add("R$ " . moneyToFloat($totalgeral,true));
		$td->class = "text-center";
		$trFoot->add($td);
		
		$tFoot->add($trFoot);
	}
	
	$tItensHead->add($tItensTR);
	$tItens->add($tItensHead,$tBody,$tFoot);
}
// Panel Cliente
$panelItenscarrinho = tdClass::Criar("panel");
$panelItenscarrinho->id = "dados-itens-carrinho";
$panelItenscarrinho->tipo = "info";
$panelItenscarrinho->head("Itens do carrinho ( Produtos ) ");
$panelItenscarrinho->body($tItens);
$colItenscarrinho->add($panelItenscarrinho);
$linha2->add($colItenscarrinho);

$linha2->mostrar();

$jscarrinho = tdClass::Criar("script");
$jscarrinho->add('
	$(document).ready(function(){
		$("#carrinho-listastatus").val("'.($carrinho->inativo==''?0:$carrinho->inativo).'");
	});
	$("#carrinho-editar-cliente").click(function(){
		editarTDFormulario('.$clienteEntidadeID.','.$clienteID.');
	});
	$("#btn-carrinhohome-alterarstatus").click(function(){
		$.ajax({
			url:"index.php",
			data:{
				controller:"ecommerce/alterarstatuscarrinho",
				carrinho:'.$carrinhoID.',
				status:$("#carrinho-listastatus").val()
			},
			complete:function(ret){
				if (parseInt(ret.responseText) == 1){
					bootbox.alert("Status do carrinho Alterado com Sucesso.");
				}
			}
		});
	});
	$("#btn-imptimir-carrinho").click(function(){
		window.open(getURLProject("index.php?controller=ecommerce/carrinhoimpressao/carrinhoimpressao&registro='.$carrinho->id.'"),"_blank");
	});	
');
$jscarrinho->mostrar();