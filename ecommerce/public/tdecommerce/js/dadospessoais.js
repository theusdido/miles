$("#datanascimento").mask("99/99/9999");
$("#cpf").mask("999.999.999-99");
$("#telefone").mask("(99) 9.9999-9999");
function validar(){
	var campos = new Array("nome","cpf","telefone");
	
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
	return true;
}
$("#btn-salvar-meusdados").click(function(){
	if (!validar()){
		return false;
	}
	$.ajax({
		type:"POST",
		url:session.path_tdecommerce,
		data:{
			controller:"dadospessoais",
			op:"salvar",
			nome:$("#nome").val(),
			cpf:$("#cpf").val(),
			telefone:$("#telefone").val()
		},
		beforeSend:function(){
			$("#loader-salvar-meusdados").show();
		},
		complete:function(ret){
			$("#retornocadastro").html(ret.responseText);
			$("#loader-salvar-meusdados").hide();
		}
	});
});