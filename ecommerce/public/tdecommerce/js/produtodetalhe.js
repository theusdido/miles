// JS - Produto Detalhe
$(document).ready(function(){
	$("#descricao-texto-produto").show(500);
	variacaoPeso();
});

/*-------------------
	Quantity change
--------------------- */    
var proQty = $('.pro-qty');
proQty.prepend('<span class="dec qtybtn">-</span>');
proQty.append('<span class="inc qtybtn">+</span>');
proQty.on('click', '.qtybtn', function () {
	var $button = $(this);
	var oldValue = $button.parent().find('input').val();
	if ($button.hasClass('inc')) {
		var newVal = parseFloat(oldValue) + 1;
	} else {
		// Don't allow decrementing below zero
		if (oldValue > 1) {
			var newVal = parseFloat(oldValue) - 1;
		} else {
			var newVal = 1;
		}
	}
	$button.parent().find('input').val(newVal);
	variacaoPeso();
});

$(".lista-peso").change(function(){
	variacaoPeso();
});