$("#btn-alterarsenha").click(function(){
	alterarSenha();
});
function alterarSenha(){
	var campos = new Array("senha","csenha");
	for (c in campos){
		if ($("#" + campos[c]).val() == ""){
			$("#" + campos[c]).parents(".form-group").addClass("has-error");
			$("#" + campos[c]).css("background-color","#ffe7e7");
			return false;
		}else{
			$("#" + campos[c]).parents(".form-group").removeClass("has-error");
			$("#" + campos[c]).css("background-color","#FFF");				
		}
	}
	if ($("#senha").val() != $("#csenha").val()){
		$("#retorno-alterarsenha").html('<div class="alert alert-danger">Senhas não coincidem.</div>');
		return false;
	}
	if ($("#senha").val().length < 8){
		$("#retorno-alterarsenha").html('<div class="alert alert-danger">Senha precisa ter pelo menos 8 digitos.</div>');
		return false;
	}
	$.ajax({
		type:"POST",
		url:session.path_tdecommerce,
		data:{
			controller:"alterarsenha",
			op:"alterar_senha",
			senha:$("#senha").val()
		},
		beforeSend:function(){
			$("#loading-alterarsenha").show();
		},
		complete:function(retorno){
			if (parseInt(retorno.responseText) == 1){
				$("#loading-alterarsenha").hide(200);
				$("#retorno-alterarsenha").html('<div class="alert alert-success" role="alert">Senha alterar com sucesso.</div>');
			}else{
				$("#retorno-alterarsenha").html('<div class="alert alert-danger" role="alert">Ocorreu um erro ao alterar sua senha.</div>');
			}
			$("#retorno-alterarsenha").show(1000);
			setTimeout(function(){
				$("#retorno-alterarsenha").hide(1000);
			},3000);
		}
	});
}