	var campos = new Array("email","senha");
	function validar(){
		for (c in campos){
			if ($("#form-autenticacao-cliente #" + campos[c]).val() == ""){
				$("#form-autenticacao-cliente #" + campos[c]).parents(".form-group").addClass("has-error");
				$("#form-autenticacao-cliente #" + campos[c]).css("background-color","#ffe7e7");				
				return false;
			}else{
				$("#form-autenticacao-cliente #" + campos[c]).parents(".form-group").removeClass("has-error");
				$("#form-autenticacao-cliente #" + campos[c]).css("background-color","#FFF");				
			}
		}
		return true;
	}

	$("#btn-logon-entrar").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		
		if (!validar()){
			return false;
		}		
		$.ajax({
			type:"GET",
			url:session.path_tdecommerce,
			data:{
				controller:"autenticacao",
				email:$("#email").val(),
				senha:$("#senha").val()
			},
			beforeSend:function(){
				$("#iframe-logon").hide();
			},
			complete:function(ret){
				var retorno = JSON.parse(ret.responseText);
				if (parseInt(retorno.error) == 0){
					if (session.logoncheckout || eval($("#form-autenticacao-cliente #ischeckout").val())){
						location.href = session.path_site + "checkout/auth";
					}else{						
						location.href = session.path_site + "minhaconta/";
					}
				}else{
					$("#iframe-logon").show();
					$("#iframe-logon").html(retorno.msg);
				}
			}
		});
	});