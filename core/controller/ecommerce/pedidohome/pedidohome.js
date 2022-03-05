// -- Default Pedido Home -- //

// Pedidos Abertos
let contextoPedidoAberto 	= "#pedido-aberto-home";
let gdPedidoAberto 			= new GradeDeDados(getEntidadeId("td_ecommerce_pedido"));

gdPedidoAberto.contexto 				= contextoPedidoAberto;
gdPedidoAberto.exibirpesquisa 			= false;
gdPedidoAberto.retornaFiltro 			= true;
gdPedidoAberto.funcaoretorno 			= "abrirPedido";
gdPedidoAberto.qtdeMaxRegistro			= 5;
gdPedidoAberto.setOrder("datahoraretorno","DESC");
gdPedidoAberto.show();

// Carrinhos de Compra
let contextoCarrinhoCompras 			= "#carrinho-compras-home";
let gdCarrinhoCompras 					= new GradeDeDados(getEntidadeId("td_ecommerce_carrinhocompras"));
gdCarrinhoCompras.contexto 				= contextoCarrinhoCompras;
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