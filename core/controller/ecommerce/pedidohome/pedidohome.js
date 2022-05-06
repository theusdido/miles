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