$(document).on("click",".add-produto-comprar",function(e){
	var item 		= $(this)[0].dataset;
	var id 			= item.iditem;
	var valor 		= item.valoritem;
	var descricao 	= item.descricaoitem;
	var img 		= item.imgitem;
	var produto		= item.idproduto;

	if ($(".td-quantidade[data-produto="+produto+"]").val() == undefined){
		var quantidade = 1;
	}else{
		var quantidade = parseInt($(".td-quantidade[data-produto="+produto+"]").val());
	}
	if (quantidade == "" || quantidade == undefined || quantidade <= 0){
		quantidade = 1;
	}
	e.preventDefault();
	e.stopPropagation();
	bootbox.dialog({
		message: "Deseja adicionar este produto ao carrinho de compra ?",
		title: "Carrinho de Compras",
		buttons: {
			add: {
				label: "+ Adicionar",
				className: "btn-success",
				callback: function() {
					carrinho.addItem(id,quantidade,descricao,img,valor,produto);
				}
			},
			finish:{
				label:"$ Comprar",
				className:"btn-warning",
				callback: function() {
					carrinho.addresumo = true;
					carrinho.addItem(id,quantidade,descricao,img,valor,produto);
				}
			}
		}
	});
});


function variacaoPeso(){
	if ($(".lista-peso").val() != undefined){
		var dados 			= JSON.parse($(".lista-peso").val());
		var seletorproduto 	= "[data-idproduto="+dados.produto+"]";	
		var valor 			= $(seletorproduto).data("valoritem");
		var pesoaproximado 	= dados.peso;
		var quantidade 		= $(".td-quantidade").val();

		
		var valorfinal = valor*pesoaproximado*quantidade;
		var unidademedida = pesoaproximado<1?'gramas':'Kg';
		var valorfinaldesc = valorfinal.toLocaleString("pt-BR",{ style: 'currency', currency: 'BRL' });

		$(seletorproduto).attr("data-valoritem",valorfinal);	
		$(seletorproduto).attr("data-iditem",dados.pesoid);
		$(".pd-desc h4").html(valorfinaldesc);
	}	
}