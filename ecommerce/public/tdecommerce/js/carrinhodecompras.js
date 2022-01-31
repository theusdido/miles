function CarrinhoCompras(){	
	this.itens 						= [];
	this.addresumo 					= false;
	this.atualizarresumo 			= false;
	this.id 						= "";
	this.cliente 					= "";
	this.datahora 					= "";
	this.sessionid 					= "";
	this.qtdade 					= 0;
	this.valortotalminimopedido 	= 0;
	this.currentetapa 				= 0;
	this.valorfrete					= 0;
	this.layoutcarrinho				= 1;
}
CarrinhoCompras.prototype.add = function(id,qtde,descricao,imgsrc,valor){
	var instancia = this;
	bootbox.dialog({
	  message: "Deseja adicionar este produto ao carrinho de compra ?",
	  title: "Carrinho de Compras",
	  buttons: {
		success: {
		  label: "Adicionar",
		  className: "btn-success",
		  callback: function() {
			instancia.addItem(id,qtde,descricao,imgsrc,valor);
		  }
		},
		danger: {
		  label: "Cancelar",
		  className: "btn-danger",
		  callback: function() {

		  }
		}
	  }
	});
}
CarrinhoCompras.prototype.load = function(){
	var instancia = this;
	$.ajax({
		url:session.path_tdecommerce,
		async:false,
		crossDomain: true,
		data:{
			controller:"sessaocarrinhodecompras",
			op:"carregar"
		},
		complete:function(ret){
			var retorno = JSON.parse(ret.responseText);
			if (retorno.length > 0){
				for (i in retorno){
					instancia.addItemCarrinho(
						retorno[i].id,
						retorno[i].qtde,
						retorno[i].descricao,
						retorno[i].produto_obj.imagemprincipal_src,
						retorno[i].valor,
						retorno[i].produto
					);
				}
			}
		}
	});
}
CarrinhoCompras.prototype.atualizar = function(){
	$("#itens-carrinhodecompras").html("");
	this.itens.splice(0,this.itens.length);
	this.load();
	this.addCarrinho();
	this.resumo();
	this.setValorTotal();
	this.divFinalizarPedido();
}
CarrinhoCompras.prototype.addCarrinho = function(){
	var qtdeitens = 0;
	var valortotal = 0;
	// Baseado em DIV
	if (this.layoutcarrinho == 1){
		for (i in this.itens){
			qtdeitens 		= qtdeitens + parseInt(this.itens[i].qtde);
			valortotal 		= valortotal + (this.itens[i].qtde * this.itens[i].valor);
			var dadositem 	= $('<div class="item-dados">');
			var divimg 		= $('<div class="item-img">');
			var img 		= $('<img class="img-rounded" src="'+this.itens[i].imgsrc+'" alt="'+this.itens[i].descricao+'">');
			var h4 			= $('<p class="item-nome">'+this.itens[i].descricao+'</p>');
			var p 			= $('<p class="item-valor">'+this.itens[i].valorformatado+' - Qtde: <b data-tdqtdadeproduto="'+this.itens[i].id+'">'+this.itens[i].qtde+'</b></p>');
			var item 		= $("<div class='item-carrinhodecompras'>");
			var hr 			= $("<hr>");
			var divexcluir 	= $('<div class="item-excluir">');
			var excluir 	= $('<i class="fa fa-trash" aria-hidden="true" data-itemindice="'+i+'" data-itemid="'+this.itens[i].id+'" aria-label="Excluir Item Carrinho de Compras"></i>');
			var instancia 	= this;
			excluir.click(function(){
				var id 		= $(this).data("itemid");
				var indice 	= $(this).data("itemindice");			
				instancia.excluiritem(id,indice);
			});

			divexcluir.append(excluir);
			divimg.append(img);
			dadositem.append(h4);
			dadositem.append(p);

			item.append(divimg);
			item.append(dadositem);
			item.append(divexcluir);
			item.append(hr);
			$("#itens-carrinhodecompras").append(item);
		}
		this.setQtdade();
		if (qtdeitens > 0){
			$("#itens-carrinhodecompras").append("<div style='float:right;'>Total: <b class='td-valortotal-carrinho'>" + valortotal.toLocaleString("pt-BR",{ style: 'currency', currency: 'BRL' }) + "</b></div>");

			var btnfinalizar = $('<div class="div-finalizar-carrinho"><button type="button" class="btn btn-warning" onclick="location.href=\''+session.path_site+'checkout\'">' +
									'Finalizar Pedido e Pagar' +
								'</button></div>');
			$("#itens-carrinhodecompras").append(btnfinalizar);
		}else{
			$("#itens-carrinhodecompras").html("Não há itens no carrinho de compras.");
		}
	// Baseado em TABELA
	}else{
		var table = $("<table>");
		var tbody = $("<tbody>");
		for (i in this.itens){
			var itemQuantidade = parseInt(this.itens[i].qtde);
			qtdeitens = qtdeitens + itemQuantidade;
			valortotal = valortotal + (this.itens[i].qtde * this.itens[i].valor);
			var tr			= $("<tr>");
			var td 			= $('<td width="25%">');
			var imgproduto	= $('<img src="'+this.itens[i].imgsrc+'" alt="'+this.itens[i].descricao+'">');
			td.append(imgproduto);
			tr.append(td);			

			var td 			= $('<td width="70%">');
			var divDados	= $('<div class="td-carrinho-item-dados">');
			var preco		= $('<p class="td-carrinho-valor-produto">'+this.itens[i].valorformatado+' <small class="text-secondary"> x'+itemQuantidade+'</small></p>');
			var nome		= $('<h6>'+this.itens[i].descricao+'</h6>');			
			divDados.append(preco);
			divDados.append(nome);
			td.append(divDados);
			tr.append(td);
			
			
			var td			= $('<td width="5%" class="si-close">');
			var btnExcluir	= $('<i class="ti-close fa fa-trash td-carrinho-excluir-item" aria-hidden="true" data-itemindice="'+i+'" data-itemid="'+this.itens[i].id+'" aria-label="Excluir Item Carrinho de Compras"></i>');
			var instancia = this;
			btnExcluir.click(function(){
				var id = $(this).data("itemid");
				var indice = $(this).data("itemindice");			
				instancia.excluiritem(id,indice);
			});
			td.append(btnExcluir);
			tr.append(td);
			
			tbody.append(tr);
		}
		table.append(tbody);
		this.setQtdade();
		this.setValorTotal();
		if (qtdeitens > 0){
			$("#itens-carrinhodecompras").append(table);
		}else{
			$("#itens-carrinhodecompras").html("Não há itens no carrinho de compras.");
		}
	}
}
CarrinhoCompras.prototype.checkout = function(){
	this.atualizarresumo = true;	
	this.atualizar();
}
CarrinhoCompras.prototype.resumo = function(){
	var valorminimopedido = parseFloat(this.valortotalminimopedido);
	var tabela = $('<table class="table table-hover" id="lista-itens-checkout">');
	var theadString = 	'	<thead>';
	theadString += 	'		<tr id="travisovalorminimo">';
	theadString += 	'			<td colspan="6"><div class="alert alert-warning text-center"><b>AVISO! </b> Valor total do pedido tem que ser no mínimo de '+valorminimopedido.toLocaleString("pt-BR",{minimumFractionDigits: 2})+'.</div></td>';
	theadString += 	'		</tr>';
	theadString += 	'		<tr class="bg-dark">';
	theadString += 	'			<th width="10%">&nbsp;</th>';
	theadString += 	'			<th width="45%">Produto</th>';
	theadString += 	'			<th width="10%">Valor</th>';
	theadString += 	'			<th width="15%" style="text-align:center;">Quantidade</th>';
	theadString += 	'			<th width="15%" style="text-align:right;">Valor Total</th>';
	theadString += 	'			<th width="5%">&nbsp;</th>';
	theadString += 	'		</tr>';
	theadString += 	'	</thead>';
	var thead = $(theadString);
	tabela.append(thead);
		
	var tbody = $("<tbody>");
	var itens = '';
	var totalcarrinho = 0;
	for (i in this.itens){
		var totalitem = this.itens[i].qtde * this.itens[i].valor;
		var valorunit = this.itens[i].valorformatado;
		totalcarrinho += totalitem;
		var qtde				= this.itens[i].qtde;
		var tr 					= $('<tr data-produto="'+this.itens[i].id+'">');
		var tdImg 				= $('<td><img class="img-produto-checkout" height="75" src="'+this.itens[i].imgsrc+'" alt="'+this.itens[i].descricao+'"></td>');
		var tdDescricao 		= $('<td><span class="conteudo-produto-resumo">'+this.itens[i].descricao+'</span></td>');
		var tdValor 			= $('<td><span class="conteudo-produto-resumo">'+valorunit+'</span></td>');
		var tdQtde 				= $('<td align="center"><span class="conteudo-produto-resumo" data-tdqtdadeproduto="'+this.itens[i].id+'">'+(qtde<10?"0"+qtde:qtde)+'</span></td>');
		var valortotalitem 		= totalitem.toLocaleString("pt-BR",{ style: 'currency', currency: 'BRL' , minimumFractionDigits: 2});
		var tdValortotalitem 	= $('<td align="right"><span class="conteudo-produto-resumo" data-tdvalortotalitem="'+this.itens[i].id+'">'+valortotalitem+'</span></td>');
		var tdExcluir 			= $('<td>');
		var tdCenterExcluir 	= $('<center>');
		var spanExcluir 		= $('<span class="conteudo-produto-resumo">');
		var btnExcluir 			= $('<i class="fa fa-trash excluir-item-resumo" data-itemindice="'+i+'" data-itemid="'+this.itens[i].id+'">');
		
		spanExcluir.append(btnExcluir);
		tdCenterExcluir.append(spanExcluir);
		tdExcluir.append(tdCenterExcluir);
		
		var instancia = this;
		btnExcluir.click(function(){
			var id 		= $(this).data("itemid");
			var indice 	= $(this).data("itemindice");
			instancia.excluiritem(id,indice);
		});
		tr.append(tdImg);
		tr.append(tdDescricao);
		tr.append(tdValor);
		tr.append(tdQtde);
		tr.append(tdValortotalitem);
		tr.append(tdExcluir);
		
		tbody.append(tr);
	}
	tabela.append(tbody);
	
	var rodape = '';	
	rodape +=	'<tfoot>';
	
	$("#btn-checkout-resumocompra").removeClass("btn-default btn-danger btn-success");
	if (this.itens.length <= 0){
		$("#btn-checkout-resumocompra").addClass("btn-danger");
		rodape +=	'<tr><td colspan="6"><div class="alert alert-danger text-center" role="alert"><b>Vazio!</b> Não há nenhum item no carrinho de compras.</div></td></tr>';
	}else{
		$("#btn-checkout-resumocompra").addClass("btn-success");
	}
	rodape +=	'	<tr id="trtotalpedido">';
	rodape +=	'		<td colspan="6" class="active" align="right">Valor Total dos Produtos: <b class="td-valortotal-carrinho">'+totalcarrinho.toLocaleString("pt-BR",{ style: 'currency', currency: 'BRL' })+'</b></td>';
	rodape +=	'	</tr>';
	rodape +=	'</tfoot></table>';
	
	var tfoot = $(rodape);
	tabela.append(tfoot);
	let cabecalho_etapa_html 	=  "<div class='div-cabecalho-atapas td-btn-navegacao-etapa-checkout'>";
	cabecalho_etapa_html 		+= "	<h5 class='texto-etapas'>Resumo da Compra</h5>";
	cabecalho_etapa_html 		+= "	<button type='button' class='btn btn-primary btn-etapa etapa-proxima' onclick=$('#btn-checkout-autenticacao').click()> >> </button>";
	cabecalho_etapa_html 		+= "</div>";
	var cabecalhoetapa 			= $(cabecalho_etapa_html);
	$("#etapa-checkout").html("");
	$("#etapa-checkout").append(cabecalhoetapa);
	$("#etapa-checkout").append(tabela);
	
	this.mensagemValorTotalMinimo();
}
CarrinhoCompras.prototype.excluiritem = function(id,indice){
	var instancia = this;
	$.ajax({
		url:session.path_tdecommerce,
		crossDomain: true,
		data:{
			controller:"sessaocarrinhodecompras",
			op:"deletar",
			item:id
		},
		dataType:"json",
		complete:function(){
			instancia.atualizar();
		}
	});
}
CarrinhoCompras.prototype.esvaziar = function(){
	this.itens.splice(0,this.itens.length);
	this.addQtdade(0);
	$("#itens-carrinhodecompras").html("Não há itens no carrinho de compras.");
}


CarrinhoCompras.prototype.addItemCarrinho = function(id,qtde,descricao,imgsrc,valor,produto){
	var instancia = this;
	var valorformatado = parseFloat(valor).toLocaleString("pt-BR",{ style: 'currency', currency: 'BRL' });
	if (instancia.itens[id] == undefined){
		instancia.itens[id] = {
			"id":id,
			"qtde":qtde,
			"descricao":descricao,
			"imgsrc":imgsrc,
			"valor":valor,
			"valorformatado":valorformatado,
			"produto":produto
		};
	}else{
		var qtdade = parseInt(instancia.itens[id].qtde==undefined?0:instancia.itens[id].qtde) + parseInt(qtde);
		instancia.itens.splice(id,1);
		instancia.itens[id] = {
			"id":id,
			"qtde":qtdade,
			"descricao":descricao,
			"imgsrc":imgsrc,
			"valor":valor,
			"valorformatado":valorformatado,
			"produto":produto
		};
	}
	return instancia.itens[id];
}
CarrinhoCompras.prototype.addItem = function(id,qtde,descricao,imgsrc,valor,produto){
	var instancia = this;
	$.ajax({
		url:session.path_tdecommerce,
		crossDomain: true,
		data:{
			controller:"sessaocarrinhodecompras",
			op:"add",
			dados:instancia.addItemCarrinho(id,qtde,descricao,imgsrc,valor,produto)
		},
		complete:function(){
			if (instancia.addresumo){
				
				instancia.irCheckout();
			}else{
				instancia.atualizar();
			}
		},
		error:function(ret){
			console.log(ret.responseText);
		}
	});
}
CarrinhoCompras.prototype.addQtdade = function(quantidade){
	var elemento = $("#btn-carrinho .qtde,.td-carrinho-qtdade-itens");
	if (quantidade <= 0){
		elemento.addClass("carrinho-com-item");
	}else{
		elemento.removeClass("carrinho-com-item");
	}
	this.qtdade = quantidade;
	elemento.html(this.qtdade);
}
CarrinhoCompras.prototype.setQtdade = function(){
	var total = 0;
	for (i in this.itens){
		var qtdeproduto = parseInt(this.itens[i].qtde);
		var idproduto 	= this.itens[i].id;
		$("[data-tdqtdadeproduto="+idproduto+"]").html(qtdeproduto);
		this.setValorItem(idproduto);		
		total += qtdeproduto;
	}
	this.addQtdade(total);
}
CarrinhoCompras.prototype.getQtdade = function(){
	return this.qtdade;
}
CarrinhoCompras.prototype.getValorTotalItens = function(){
	var totalitens = 0;
	for (i in this.itens){
		totalitens += parseInt(this.itens[i].qtde) * parseFloat(this.itens[i].valor);
	}
	return totalitens;
}
CarrinhoCompras.prototype.getValorTotalCarrinho = function(){
	return this.getValorFrete() + this.getValorTotalItens();
}
CarrinhoCompras.prototype.mensagemValorTotalMinimo = function(){
	if (this.getValorTotalCarrinho() >= parseFloat(this.valortotalminimopedido)){
		$("#travisovalorminimo").hide();
	}else{
		$("#travisovalorminimo").show();
	}
}
CarrinhoCompras.prototype.divFinalizarPedido = function(){	
	if (this.getQtdade() > 0){
		$(".div-finalizar-carrinho").show();
	}else{
		$(".div-finalizar-carrinho").hide();
	}
}
CarrinhoCompras.prototype.irCheckout = function(){
	window.location.href = session.path_site + "checkout";
}
CarrinhoCompras.prototype.setQtadeProduto = function(produto,qtdade){
	this.itens[produto].qtde = qtdade;
	this.setQtdade();
	this.setValorTotal();
}
CarrinhoCompras.prototype.setValorTotal = function(){	
	$(".td-totalitens-carrinho").html(this.getValorTotalItens().toLocaleString("pt-BR",{ style: 'currency', currency: 'BRL' }));
	$(".td-valortotal-carrinho").html(this.getValorTotalCarrinho().toLocaleString("pt-BR",{ style: 'currency', currency: 'BRL' }));
}
CarrinhoCompras.prototype.setValorItem = function(item){
	var totalitem = this.itens[item].qtde * this.itens[item].valor;
	$("[data-tdvalortotalitem="+this.itens[item].id+"]").html(totalitem.toLocaleString("pt-BR",{ style: 'currency', currency: 'BRL' }));
}
CarrinhoCompras.prototype.setValorFrete = function(valor){
	$.ajax({
		url:session.path_tdecommerce,
		crossDomain: true,
		data:{
			controller:"atualizarvalorfrete",
			valorfrete:valor,
			transportadora:1
		}
	});
	if (valor <= 0){
		$("#taxaok").val(0);
		parent.$("#btn-checkout-entrega").removeClass("btn-danger btn-default btn-warning").addClass("btn-warning");
		parent.$("#btn-checkout-pagamento,.etapa-proxima").attr("disabled",true);
	}else{
		this.valorfrete = valor;
		$(".td-valorfrete-carrinho").html(valor.toLocaleString("pt-BR",{ style: 'currency', currency: 'BRL' }));
		$("#taxaok").val(1);
		parent.$("#btn-checkout-entrega").removeClass("btn-danger btn-default btn-warning").addClass("btn-success");
		parent.$("#btn-checkout-pagamento,.etapa-proxima").removeAttr("disabled");
	}
}
CarrinhoCompras.prototype.getValorFrete = function(){
	return this.valorfrete;
}