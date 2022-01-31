// JS Customizado
if (carrinho.getQtdade() > 0){
	//$(".div-finalizar-carrinho").hide();
	//$(".select-total,.select-button").show();
}else{
	//$(".select-total,.select-button").hide();
	//$(".total-carrinho-topo").html("R$ 0,00");
}

/*
$(document).ready(function(){	
	$("#lista-itens-checkout thead th").removeAttr("width");
	
	$("#lista-itens-checkout").removeAttr("class");
	$("#lista-itens-checkout thead th:nth-child(1)").html("Foto");
	$("#lista-itens-checkout thead th:nth-child(2)").addClass("p-name");
	$("#lista-itens-checkout thead th:nth-child(4)").removeAttr("style");
	$("#lista-itens-checkout thead th:nth-child(5)").removeAttr("style");
	$("#lista-itens-checkout thead th:nth-child(5)").html("Total");
	
	var iclose = $('<i class="ti-close">');
	$("#lista-itens-checkout thead th:last-child").append(iclose);
	
	
	$("#lista-itens-checkout tbody td:nth-child(1)").addClass("cart-pic first-row");
	$("#lista-itens-checkout tbody td:nth-child(2)").addClass("cart-title first-row");
	$("#lista-itens-checkout tbody td:nth-child(3)").addClass("p-price first-row");
	$("#lista-itens-checkout tbody td:nth-child(4)").addClass("qua-col first-row");
	$("#lista-itens-checkout tbody td:nth-child(5)").addClass("total-price first-row");
	$("#lista-itens-checkout tbody td:nth-child(6)").addClass("close-td first-row");

	with($("#lista-itens-checkout tbody td:nth-child(1) img")){
		removeAttr("height");
		removeAttr("class");
	}
	
	$("#lista-itens-checkout .cart-title.first-row span").each(function(){
		$(this).replaceWith("<h5>"+$(this).text()+"</h5>");
	});	
	
	$("#lista-itens-checkout .qua-col.first-row span").each(function(){
		var quantidade = $(this).text();
		var produto = $(this).parents("tr").first().data("produto");
		$(this).replaceWith('<div class="quantity"><div class="pro-qty"><span class="dec qtybtn">-</span><input type="text" value="'+parseInt(quantidade)+'" class="td-quantidade" data-produto="'+produto+'"><span class="inc qtybtn">+</span></div></div>');
	});
	$(".fa.fa-trash").removeClass("fa fa-trash").addClass("ti-close");
	
    // -------------------
		Quantity change
	--------------------- //
    var proQty = $('#lista-itens-checkout .pro-qty');
	proQty.on('click', '.qtybtn', function () {
		var $button = $(this);
		var oldValue = $button.parent().find('input').val();
		var produto = $button.parent().find('input').data("produto");
		if ($button.hasClass('inc')) {
			var newVal = parseFloat(oldValue) + 1;
		} else {
			// Don't allow decrementing below zero
			if (oldValue > 1) {
				var newVal = parseFloat(oldValue) - 1;
			} else {
				newVal = 1;
			}
		}
		$button.parent().find('input').val(newVal);
		carrinho.setQtadeProduto(produto,newVal);		
	});
	
	$(".td-quantidade").attr("disabled",true);
	$(".td-quantidade").attr("readonly",true);
	$(".td-quantidade").css("background-color","#FFF");
	$("#trtotalpedido").hide();
	
	carrinho.setValorTotal();
	
	$("#finalizar-checkout").click(function(e){
		e.stopPropagation();
		e.preventDefault();
		$("#btn-checkout-pagamento").click();
	});
	
	$("#finalizar-cartaocredito").click(function(){
		console.log("clicou em finalizar ...");
	});

	$("#btn-enviar-reserva").click(function(){
		$.ajax({
			url:session.path_tdecommerce,
			data:{
				controller:"reserva",
				nome:$("#nomecon").val(),
				fone:$("#fonecon").val(),
				email:$("#emaicon").val(),
				mensagem:$("#mensagemcon").val()
			},
			complete:function(ret){
				$(".contact-form").html(ret.responseText);
			}
		});
	});
});
*/