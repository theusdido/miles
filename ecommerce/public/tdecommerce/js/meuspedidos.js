function abrirItens(linha,item){
	if ($("#tritenspedido-"+item).css("display") == 'none'){
		$("#tritenspedido-"+item).css("background-color","#ececec");
		$(linha).css("background-color","#ececec");
		$("#tritenspedido-"+item).show();
	}else{
		$(linha).css("background-color","transparent");
		$("#tritenspedido-"+item).hide();
	}	
}