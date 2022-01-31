	$("#b-gravarnewsletter").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		var campos = new Array("newsletter_email");
		for (c in campos){
			if ($("#" + campos[c]).val() == ""){
				$("#" + campos[c]).css("background-color","#fa0000");
				$("#newsletter_email").css("color","#FFF");
				return false;
			}else{
				$("#" + campos[c]).css("background-color","#FFF");
			}
		}
		if ($("#newsletter_email").val().indexOf("@") <= 0){
			bootbox.alert("E-Mail está no formato inválido.");
			$("#newsletter_email").css("background-color","#fa0000");
			$("#newsletter_email").css("color","#FFF");
			return false;
		}
		$("#loader-newsletter").show();
		$.ajax({
			url:carrinho.pathsite + "index.php",
			data:{
				controller:"newsletter",
				op:"addnewsletter",
				email:$("#newsletter_email").val()
			},
			complete:function(retorno){
				if (parseInt(retorno.responseText) == 1){
					$("#retorno-newsletter").html("<div class='alert alert-success'><b>Obrigado!</b> E-Mail Adicionado.</div>");					
					setTimeout(function(){
						$("#newsletter_email").val("");
						$("#retorno-newsletter").html("");
					},3000);
					
				}else if(parseInt(retorno.responseText) == 2){
					$("#retorno-newsletter").html("<div class='alert alert-danger'><b>Ops!</b> Esse e-mail já está cadastrado.</div>");
				}else{
					$("#retorno-newsletter").html("<div class='alert alert-danger'><b>Ops!</b> Erro ao processar envio.</div>");
				}
			}
		});
	});
	$("#b-gravarnewsletter").blur(function(e){
		if ($(this).val() == ''){
			$("#newsletter_email").css("background-color","#FFFFFF");
			$("#newsletter_email").css("color","#555555");
		}
	});