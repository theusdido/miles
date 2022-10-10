<?php

// Dados do Pedido
$pedidoID 		= $_GET["pedido"];
$pedido 		= tdClass::Criar("persistent",array("td_ecommerce_pedido",$pedidoID))->contexto;

$pedidoClass	= new Pedido($pedidoID);
$datahorapedido = explode(" ",$pedido->datahoraretorno);
$data_pedido	= isset($datahorapedido[0]) ? dateToMysqlFormat($datahorapedido[0],true) : '';
$hora_pedido	= isset($datahorapedido[1]) ? $datahorapedido[1] : '';
$datadopedido 	= '<div id="data-pedido"><span class="fas fa-calendar-alt" aria-hidden="true"></span><span>'.$data_pedido.'</span></div>';
$horadopedido 	= '<div id="hora-pedido"><span class="fas fa-clock" aria-hidden="true"></span><span>'.$hora_pedido.'</span></div>';
$totalpedido 	= '<div id="total-pedido"><small>Valor Total</small><h1>R$ '.moneyToFloat($pedidoClass->getValorTotal(),true).'</h1><p>Valor Frete: R$ '.moneyToFloat($pedido->valorfrete,true).'</p></div>';

// Lista de Estado dos pedidos	
if ($conn = Transacao::Get()){
	$listaStatusPedido 	= "<select class='form-control' id='pedido-listastatus'>";
	$sqlStatusPedido 	= "SELECT id,descricao FROM td_ecommerce_statuspedido";
	$queryStatusPedido 	= $conn->query($sqlStatusPedido);
	while ($linhaStatusPedido = $queryStatusPedido->fetch()){
		$listaStatusPedido .= "<option value='".$linhaStatusPedido["id"]."'>".$linhaStatusPedido["descricao"]."</option>";
	}
	$listaStatusPedido .= "</select>";
}
$btnAlterarStatus = '<button class="btn btn-primary form-control" type="button" id="btn-pedidohome-alterarstatus">Alterar Status</button>';

// Dados do Cliente
$cliente 					= tdClass::Criar("persistent",array("td_ecommerce_cliente",$pedido->cliente))->contexto;
if ($cliente->tipopessoa == 1){
	$numero_documento		= "<div class='dadoscliente'><label>CPF</label><p>".$cliente->cpf."</p></div>";
	$apelido				= "<div class='dadoscliente'><label>Nome</label><p>".$cliente->nome."</p></div>";
}else{
	$numero_documento 		= "<div class='dadoscliente'><label>CNPJ</label><p>".$cliente->cnpj."</p></div>";
	$apelido				= "<div class='dadoscliente'><label>Nome Fantasia</label><p>".$cliente->nomefantasia."</p></div>";
}
$email 						= "<div class='dadoscliente'><label>E-Mail</label><p>".$cliente->email."</p></div>";
$telefone 					= "<div class='dadoscliente'><label>Telefone</label><p>".$cliente->telefone."</p></div>";
$btnAlterarDadosCliente 	= '<button class="btn btn-success" type="button" id="pedido-editar-cliente">Editar</button>';

# Desabilidado
$btnAlterarDadosCliente 	= '';

// Dados de Pagametno
$datapagamento 				= "<div class='dadospagamento'><label>Data</label><p>".datetimeToMysqlFormat($pedido->datahoraretorno,true)."</p></div>";
$status 					= "<div class='dadospagamento'><label>Status</label><p>".tdc::p("td_ecommerce_statuspedido",$pedido->status)->descricao."</p></div>";
$metodo 					= "<div class='dadospagamento'><label>Método</label><p>".tdc::p("td_ecommerce_metodopagamento",$pedido->metodopagamento)->descricao."</p></div>";
$notafiscal 				= '<button class="btn btn-primary form-control" type="button" disabled>Nota Fiscal</button>';

$datahoraentrega = $entregue = $transportadora = $valorfrete = $pesototal = $rastreamento = $codigorastreamento = "";
$cep = $estado = $cidade = $bairro = $logradouro = $numero = $complemento = "";
$expedicao					= tdc::d("td_ecommerce_expedicao",tdc::f("pedido","=",$pedidoID));

if (sizeof($expedicao) > 0){
	$datahoraentrega 		= $expedicao->datahorarecebimento;
	$entregue 				= $expedicao->entregue;
	$valorfrete				= $expedicao->valorfrete;
	$pesototal				= $expedicao->pesototal;
	$transportadora			= $expedicao->transportadora;
	$codigorastreamento		= $expedicao->codigorastreamento;

	$endereco = getListaRegFilho(getEntidadeId("ecommerce_expedicao"),getEntidadeId("ecommerce_endereco"),$expedicao->id);
	if (sizeof($endereco) > 0){
		$cep 				= $endereco->cep;
		$estado 			= $endereco->estado;
		$cidade 			= $endereco->cidade;
		$bairro 			= $endereco->bairro;
		$logradouro 		= $endereco->logradouro;
		$numero 			= $endereco->numero;
		$complemento 		= $endereco->complemento;
	}
}

// Dados Entrega
$divdataentrega 				= "<div class='dadosentrega'><label>Data Entrega</label><p>{$datahoraentrega}</p></div>";
$divtipoentrega 				= "<div class='dadosentrega'><label>Entrege</label><p>{$entregue}</p></div>";
$divtransportadora 				= "<div class='dadosentrega'><label>Transportadora</label><p>{$transportadora}</p></div>";
$divfrete 						= "<div class='dadosentrega'><label>Frete</label><p>{$valorfrete}</p></div>";
$divpesototal 					= "<div class='dadosentrega'><label>Peso</label><p>{$pesototal}</p></div>";
$divrastreamento 				= "<div class='dadosentrega'><label>Rastreamento</label><p>{$codigorastreamento}</p></div>";

// Dados de Endereço
$divestado 				= "<div class='dadosentrega'><label>Estado</label><p>{$estado}</p></div>";
$divbairro 				= "<div class='dadosentrega'><label>Bairro</label><p>{$bairro}</p></div>";
$divlogradouro 			= "<div class='dadosentrega'><label>Logradouro</label><p>{$logradouro}</p></div>";
$divnumero 				= "<div class='dadosentrega'><label>Número</label><p>{$numero}</p></div>";
$divcomplemente 		= "<div class='dadosentrega'><label>Complemento</label><p>{$complemento}</p></div>";
$divcep 				= "<div class='dadosentrega'><label>CEP</label><p>{$cep}</p></div>";

//Estilo
$estilo = tdClass::Criar("style");
$estilo->type = "text/css";
$estilo->add('
	#dados-itens-pedido .panel-heading b{
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
	#total-pedido{
		text-align:right;
	}
	#total-pedido h1 {
		margin:0px;
	}
	#data-pedido,#hora-pedido{
		float:left;
		margin-top:40px;
		margin-left:10px;		
	}
	#data-pedido span,#hora-pedido span {
		padding-left:5px;
	}
	#pedido-listastatus{
		margin-bottom:5px;
	}
	#dados-pedido , #dados-cliente-pedido{
		height:265px;
	}
	
	#btn-imptimir-pedido{
		float:right;
		margin-top:-7px;
	}
');
$estilo->mostrar();

// Titulo
$titulo = tdClass::Criar("titulo");
$titulo->add("Pedido de Venda");
$titulo->mostrar();

// Linha 1
$linha1 					= tdClass::Criar("div");
$linha1->class 				= "row-fluid";
$colDadosCliente 			= tdClass::Criar("div");
$colDadosCliente->class 	= "col-md-8";
$colDadosPedido 			= tdClass::Criar("div");
$colDadosPedido->class 		= "col-md-4";

// Botão Imprimir Pedido
$btnImprimirPedido = '<button id="btn-imptimir-pedido" type="button" class="btn btn-default" aria-label="Imprimir Pedido"><span class="fas fa-print" aria-hidden="true"></span></button>';

// Panel Pedido
$panelDadosPedido 			= tdClass::Criar("panel");
$panelDadosPedido->id 		= "dados-pedido";
$panelDadosPedido->tipo 	= "default";
$panelDadosPedido->head("Dados do Pedido " . $btnImprimirPedido);
$panelDadosPedido->body($totalpedido . $datadopedido . $horadopedido . $listaStatusPedido . $btnAlterarStatus);
$colDadosPedido->add($panelDadosPedido);
$linha1->add($colDadosPedido);

// Panel Cliente
$panelDadosCliente 			= tdClass::Criar("panel");
$panelDadosCliente->id 		= "dados-cliente-pedido";
$panelDadosCliente->tipo 	= "success";
$panelDadosCliente->head("<b>".strtoupper($cliente->nome)."</b>");
$panelDadosCliente->body($numero_documento . $telefone . $apelido . $email . $btnAlterarDadosCliente);
$colDadosCliente->add($panelDadosCliente);
$linha1->add($colDadosCliente);

$linha1->mostrar();

// Linha 2
$linha2 					= tdClass::Criar("div");
$linha2->class 				= "row-fluid";

$colItensPedido 			= tdClass::Criar("div");
$colItensPedido->class 		= "col-md-12";

// Itens do Pedido
if ($conn = Transacao::Get()){

	$tItens 			= tdClass::Criar("tabela");
	$tItens->class 		= "table table-hover";
	$tItensHead 		= tdClass::Criar("thead");;
	$tItensTR 			= tdClass::Criar("tabelalinha");

	// ID Itens
	$tdItensId = tdClass::Criar("tabelahead");
	$tdItensId->add('ID');
	$tItensTR->add($tdItensId);

	// Produto
	$tdItensProduto = tdClass::Criar("tabelahead");
	$tdItensProduto->add('Produto');
	$tItensTR->add($tdItensProduto);

	// Para exibir as colunas
	$is_variacaotamanho = false;
	$is_referencia		= false;

	// Forma o colspan da tabela na linha de totalização
	$colspan_linha_total 	= 2;

	// Referência
	if ($is_referencia)
	{
		$tdItensReferencia = tdClass::Criar("tabelahead");
		$tdItensReferencia->add('Referência');
		$tItensTR->add($tdItensReferencia);

		$colspan_linha_total++;
	}

	// Tamanho
	if ($is_variacaotamanho)
	{
		$tdItensTamanho = tdClass::Criar("tabelahead");
		$tdItensTamanho->add('Tamanho');
		$tItensTR->add($tdItensTamanho);

		$colspan_linha_total++;
	}

	// Qtde
	$tdItensQtde = tdClass::Criar("tabelahead");
	$tdItensQtde->add('Qtde');
	$tdItensQtde->class = "text-center";
	$tItensTR->add($tdItensQtde);

	// Valor
	$tdItensValor = tdClass::Criar("tabelahead");
	$tdItensValor->add('Valor');
	$tdItensValor->class = "text-right";
	$tItensTR->add($tdItensValor);

	// Total
	$tdItensTotal = tdClass::Criar("tabelahead");
	$tdItensTotal->add('Total');
	$tdItensTotal->class = "text-right";

	$tItensTR->add($tdItensTotal);

	$tBody = tdClass::Criar("tbody");
	
	$totalquantidade = $totalvalorunitario = $totalgeral = 0;
	$sqlItens = "
		SELECT 
			id,
			qtde,
			valor,
			descricao,
			produto,
			referencia,
			produtonome,
			tamanho
		FROM ".getEntidadeEcommercePedidoItem()." 
		WHERE pedido = " .$pedidoID."
		ORDER BY descricao;
	";

	$queryItens = $conn->query($sqlItens);
	while ($linhaItens = $queryItens->fetch()){
		$tr = tdClass::Criar("tabelalinha");

		// ID do Produto
		$td = tdClass::Criar("tabelacelula");
		$td->add($linhaItens["id"]);
		$tr->add($td);

		// Nome do Produto
		$td = tdClass::Criar("tabelacelula");
		$td->add($linhaItens["produtonome"]);		
		$tr->add($td);
		
		// Referência
		if ($is_referencia)
		{
			$td = tdClass::Criar("tabelacelula");
			$td->add($linhaItens["referencia"]);
			$tr->add($td);
		}

		// Tamanho
		if ($is_variacaotamanho)
		{
			$td = tdClass::Criar("tabelacelula");
			$td->add($linhaItens["tamanho"]);
			$tr->add($td);
		}

		$qtde 				= $linhaItens["qtde"];
		$valorunitario 		= $linhaItens["valor"];
		$valortotal			= $qtde * $valorunitario;

		$td 		= tdClass::Criar("tabelacelula");
		$td->class 	= "text-center";
		$td->add($qtde);
		$tr->add($td);

		$td = tdClass::Criar("tabelacelula");
		$td->add("R$ " . moneyToFloat($valorunitario,true));
		$td->class = "text-right";
		$tr->add($td);

		$td = tdClass::Criar("tabelacelula");
		$td->add("R$ " . moneyToFloat($valortotal,true));
		$td->class = "text-right";
		$tr->add($td);

		$tBody->add($tr);

		$totalquantidade 	+= $qtde;
		$totalvalorunitario += $valorunitario;
		$totalgeral 		+= $valortotal;
	}
	
	$tFoot 			= tdClass::Criar("tfoot");
	$trFoot 		= tdClass::Criar("tabelalinha");
	$trFoot->class 	= "success";

	$td 			= tdClass::Criar("tabelahead");
	$td->class 		= "text-left";
	$td->colspan 	= $colspan_linha_total;	
	$td->add("<b>TOTAL</b>");
	$trFoot->add($td);

	$td 			= tdClass::Criar("tabelahead");
	$td->class 		= "text-center";
	$td->add($totalquantidade);
	$trFoot->add($td);
	
	$td 			= tdClass::Criar("tabelahead");
	$td->class 		= "text-right";
	$td->add("R$ " . moneyToFloat($totalvalorunitario,true));
	$trFoot->add($td);
	
	$td 			= tdClass::Criar("tabelahead");
	$td->class 		= "text-right";
	$td->add("R$ " . moneyToFloat($totalgeral,true));
	$trFoot->add($td);

	$tFoot->add($trFoot);	
	$tItensHead->add($tItensTR);
	$tItens->add($tItensHead,$tBody,$tFoot);
}
// Panel Cliente
$panelItensPedido = tdClass::Criar("panel");
$panelItensPedido->id = "dados-itens-pedido";
$panelItensPedido->tipo = "info";
$panelItensPedido->head("Itens do Pedido ( Produtos ) ");
$panelItensPedido->body($tItens);
$colItensPedido->add($panelItensPedido);
$linha2->add($colItensPedido);

$linha2->mostrar();

// Linha 3
$linha3 = tdClass::Criar("div");
$linha3->class = "row-fluid";

$colPagamento = tdClass::Criar("div");
$colPagamento->class = "col-md-4";

$panelPagamento = tdClass::Criar("panel");
$panelPagamento->tipo = "default";
$panelPagamento->head("Pagamento");
$panelPagamento->body($datapagamento . $status . $metodo . $notafiscal);
$colPagamento->add($panelPagamento);

$colEntrega = tdClass::Criar("div");
$colEntrega->class = "col-md-4";

$panelEntrega = tdClass::Criar("panel");
$panelEntrega->tipo = "default";
$panelEntrega->head("Entrega");
$panelEntrega->body($divdataentrega . $divtipoentrega . $divtransportadora . $divfrete . $divpesototal . $divrastreamento);
$colEntrega->add($panelEntrega);

$colEndereco = tdClass::Criar("div");
$colEndereco->class = "col-md-4";

$panelEndereco = tdClass::Criar("panel");
$panelEndereco->tipo = "default";
$panelEndereco->head("Endereço");
$panelEndereco->body($divestado . $divbairro . $divlogradouro . $divnumero . $divcomplemente . $divcep);
$colEndereco->add($panelEndereco);

$jsPedido = tdClass::Criar("script");
$jsPedido->add('
	$(document).ready(function(){
		$("#pedido-listastatus").val("'.$pedido->status.'");
	});
	$("#pedido-editar-cliente").click(function(){
		editarTDFormulario('.$cliente->getID().','.$cliente->id.');
	});
	$("#btn-pedidohome-alterarstatus").click(function(){
		$.ajax({
			url:"index.php",
			data:{
				controller:"ecommerce/alterarstatuspedido",
				pedido:'.$pedidoID.',
				status:$("#pedido-listastatus").val()
			},
			complete:function(ret){
				if (parseInt(ret.responseText) == 1){
					bootbox.alert("Status do Pedido Alterado com Sucesso.");
				}
			}
		});
	});
	$("#btn-imptimir-pedido").click(function(){
		window.open(getURLProject("index.php?controller=ecommerce/pedidoimpressao/pedidoimpressao&registro='.$pedido->id.'"),"_blank");
	});
');
$linha3->add($colPagamento,$colEntrega,$colEndereco,$jsPedido);
$linha3->mostrar();