// -- Default Pedido Home -- //

// Pedidos Abertos
var gdPedidoAberto 						= new GradeDeDados(getEntidadeId("td_ecommerce_pedido"));
gdPedidoAberto.contexto 				= '#pedido-aberto-home';
gdPedidoAberto.exibirpesquisa 			= false;
gdPedidoAberto.retornaFiltro 			= true;
gdPedidoAberto.funcaoretorno 			= "abrirPedido";
gdPedidoAberto.qtdeMaxRegistro			= 5;
gdPedidoAberto.setOrder("datahoraretorno","DESC");	
gdPedidoAberto.show();


// Carrinhos de Compra
var gdCarrinhoCompras 					= new GradeDeDados(getEntidadeId("td_ecommerce_carrinhocompras"));
gdCarrinhoCompras.contexto 				= '#carrinho-compras-home';
gdCarrinhoCompras.exibirpesquisa 		= false;
gdCarrinhoCompras.retornaFiltro 		= true;
gdCarrinhoCompras.funcaoretorno 		= "abrirCarrinho";
gdCarrinhoCompras.qtdeMaxRegistro		= 5;
gdCarrinhoCompras.setOrder("datahoraultimoacesso","DESC");
gdCarrinhoCompras.show();

// Função que abre o pedido
function abrirPedido(pedido){
	carregar("index.php?controller=ecommerce/pedido&pedido=" + pedido,"#conteudoprincipal");
}

// Função que abre o carrinho
function abrirCarrinho(id){
	carregar("index.php?controller=ecommerce/carrinhocompras&id=" + id,"#conteudoprincipal");
}


	statusPedidoHome();
	$('#pedido-aberto-home .pagination a').click(function(){
		debugger;
		statusPedidoHome();
	});	

function statusPedidoHome(){
	setTimeout(function(){
	$('#pedido-aberto-home table tbody tr td:nth-child(4) span')
	.each(function(i,e){
		let label_color;
		let label_text = $(e).html();
		switch(label_text){
			case 'Paga':
			case 'Disponível':
				label = 'success';
			break;
			case 'Cancelada':
				label = 'danger';
			break;
			case 'Aguardando Pagamento':
			case 'Em Análise':
			case 'Em Disputa':
				label = 'warning';
			break;
			case 'Devolvida':
				label = 'primary';
			break;
			default:
				label = 'default';
		}
		$(e).addClass('label label-'+label+' label-status');
	});	
},1000);
}

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	e.target 
	e.relatedTarget 
	statusPedidoHome();
});

$(document).on('click','#pedido-aberto-home .pagination a',function(){
	debugger;
	statusPedidoHome();
});	

