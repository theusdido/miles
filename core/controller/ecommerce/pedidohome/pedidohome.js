// Default Pedido Home
var composicao = [];
var gradesdedados = [];

// Pedidos Abertos
var contextoPedidoAberto = "#pedido-aberto-home";
var gdPedidoAberto = new GradeDeDados(getEntidadeId("td_ecommerce_pedido"));
gdPedidoAberto.contexto 				= contextoPedidoAberto;
gdPedidoAberto.exibirpesquisa 			= false;
gradesdedados[contextoPedidoAberto] 	= gdPedidoAberto;
gdPedidoAberto.retornaFiltro 			= true;
gdPedidoAberto.funcaoretorno 			= "abrirPedido";
gdPedidoAberto.qtdeMaxRegistro			= 5;
gdPedidoAberto.setOrder("datahoraretorno","DESC");
gdPedidoAberto.show();

// Carrinhos de Compra
var contextoCarrinhoCompras = "#carrinho-compras-home";
var gdCarrinhoCompras = new GradeDeDados(getEntidadeId("td_ecommerce_carrinhocompras"));
gdCarrinhoCompras.contexto 				= contextoCarrinhoCompras;
gdCarrinhoCompras.exibirpesquisa 		= false;
gradesdedados[contextoCarrinhoCompras] 	= gdCarrinhoCompras;
gdCarrinhoCompras.retornaFiltro 		= true;
gdCarrinhoCompras.funcaoretorno 		= "abrirCarrinho";
gdCarrinhoCompras.qtdeMaxRegistro		= 5;
gdCarrinhoCompras.setOrder("datahoraultimoacesso","DESC");
gdCarrinhoCompras.show();

// Função que abre o pedido
function abrirPedido(pedido){
	carregar("index.php?controller=website/ecommerce/pedido&pedido=" + pedido,"#conteudoprincipal");
}

// Função que abre o carrinho
function abrirCarrinho(id){
	carregar("index.php?controller=website/ecommerce/carrinhocompras&id=" + id,"#conteudoprincipal");
}
setaPrimeiraAba("#pedido-home");