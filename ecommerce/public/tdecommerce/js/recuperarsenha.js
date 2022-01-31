$("#recuperacaosenha-link").click(function(e){
	e.preventDefault();
	$("#form-recuperacaosenha #email").val("");
	$('#retorno-recuperacaosenha').hide();
	$('#modal-recuperacaosenha').modal('show');
});
$("#enviar-recuperacaosenha").click(function(e){
	e.stopPropagation();
	e.preventDefault();
	$.ajax({
		beforeSend:function(){
			$('#loading-recuperacaosenha').show();
			$('#retorno-recuperacaosenha').hide();
		},
		url:session.path_tdecommerce,
		data:{
			controller:"recuperarsenha",
			op:"recuperarsenha",
			senha:$("#form-recuperacaosenha #senha").val(),
			csenha:$("#form-recuperacaosenha #csenha").val(),
			cliente:$("#form-recuperacaosenha #cliente").val()
		},
		complete:function(ret){
			$('#loading-recuperacaosenha').hide();
			$('#retorno-recuperacaosenha').show();
			$('#retorno-recuperacaosenha').html(ret.responseText);
		}
	});
});