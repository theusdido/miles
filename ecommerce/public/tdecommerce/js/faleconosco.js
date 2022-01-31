	$("#fone").mask("(99) 9999-99999");
	$("#btn-enviar-contato").click(function(){
		$("#loading-faleconosco").show();
		var campos = new Array("nome","mensagem");
		for (c in campos){
			if ($("#" + campos[c]).val() == ""){
				$("#" + campos[c]).parents(".form-group").addClass("has-error");
				$("#" + campos[c]).css("background-color","#ffe7e7");
				$("#" + campos[c]).focus();
				console.log(campos[c]);
				$("#loading-faleconosco").hide();
				return false;
			}else{
				$("#" + campos[c]).parents(".form-group").removeClass("has-error");
				$("#" + campos[c]).css("background-color","#FFF");
			}
		}
		if ($("#email").val().indexOf("@") <= 0){
			bootbox.alert("E-Mail está no formato inválido.");
			$("#loading-faleconosco").hide();
			return false;
		}
		$.ajax({
			url:session.path_tdecommerce,
			data:{
				op:"enviar",
				controller:"faleconosco",
				nome:$("#nome").val(),				
				fone:$("#fone").val(),
				email:$("#email").val(),
				mensagem:$("#mensagem").val()
			},
			complete:function(retorno){
				$(".contact-form").html(retorno.responseText);
				$("#loading-faleconosco").hide();
			}
		});
	});
	$("#email").blur(function(){
		var valor = $(this).val();
		if (valor != ""){
			var retorno = validarEmail(valor);
			if (retorno){
				$(this).parents(".form-group").removeClass("has-error");
				$(this).css("background-color","#FFF");
			}else{	
				$(this).parents(".form-group").addClass("has-error");
				$(this).css("background-color","#ffe7e7");
				$(this).focus();
			}
		}
	});