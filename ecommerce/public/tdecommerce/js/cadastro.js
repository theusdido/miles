	$("#telefone").mask("(99) 9.9999-9999");
	$("#cnpj").mask("99.999.999/9999-99");
	$("#cep").mask("99999-999");
	$("#cpf").mask("999.999.999-99");
	
	var isvalidemail = false;
	var isvalidcnpj = false;
	var isvalidcpf = false;
	
	function validar(){
		var campos = new Array("nome","endereco","cpf","cep","endereco","bairro","cidade","estado","telefone","email","senha","csenha");
		for (c in campos){
			if ($("#" + campos[c]).val() == ""){
				$("#" + campos[c]).parents(".form-group").addClass("has-error");
				$("#" + campos[c]).css("background-color","#ffe7e7");
				setTimeout(function(){
					$("#" + campos[c]).focus();
				},100);
				return false;
			}else{
				$("#" + campos[c]).parents(".form-group").removeClass("has-error");
				$("#" + campos[c]).css("background-color","#FFF");
			}
		}
		if ($("#email").val().indexOf("@") <= 0 || !isvalidemail){
			bootbox.alert("E-Mail está no formato inválido.",function(){
				setTimeout(function(){
					$("#email").focus();
				},100);				
			});
			return false;
		}
		/*
		if (!isvalidcnpj){
			bootbox.alert("Este CNPJ já está sendo utilizado.",function(){
				setTimeout(function(){
					$("#cnpj").focus();
				},100);				
			});
		}
		*/
		if ($("#senha").val() != $("#csenha").val()){
			bootbox.alert("Senhas não coincidem");
			return false;
		}
		if ($("#senha").val().length < 8){
			bootbox.alert("Senha precisa ter pelo menos 8 digitos.");
			return false;
		}		
		return true;
	}
	$("#email").blur(function(){
		if ($(this).val() != ""){
			$.ajax({
				url:session.path_tdecommerce,
				data:{
					op:"verificar_email_existente",
					controller:"cadastro",
					email:$(this).val()
				},
				complete:function(retorno){
					switch (parseInt(retorno.responseText)){
						case 0:
							$("#email").parents(".form-group").addClass("has-error");
							$("#email").css("background-color","#ffe7e7");
							$("#erro-email").show(300);
							$("#erro-email").html("E-Mail com formato inválido.");
						break;
						case 1:
							$("#email").parents(".form-group").addClass("has-error");
							$("#email").css("background-color","#ffe7e7");
							$("#erro-email").show(300);
							$("#erro-email").html("Este e-mail já está sendo utilizado por outro usuário.");
						break;
						default:
							$("#email").parents(".form-group").removeClass("has-error");
							$("#email").css("background-color","#FFF");
							$("#erro-email").hide(100);
							isvalidemail = true;
					}
				}
			});
		}else{
			$("#email").parents(".form-group").removeClass("has-error");
			$("#email").css("background-color","#FFF");
			$("#erro-email").hide(100);
		}
	});
	
	$("#cnpj").blur(function(){
		if ($(this).val() != ""){
			$.ajax({
				url:session.path_tdecommerce,
				data:{
					op:"verificar_cnpj_existente",
					controller:"cadastro",
					cnpj:$(this).val()
				},
				complete:function(retorno){
					switch (parseInt(retorno.responseText)){
						case 0:
							$("#cnpj").parents(".form-group").addClass("has-error");
							$("#cnpj").css("background-color","#ffe7e7");
							$("#erro-cnpj").show(300);
							$("#erro-cnpj").html("CNPJ com formato inválido.");
						break;
						case 1:
							$("#cnpj").parents(".form-group").addClass("has-error");
							$("#cnpj").css("background-color","#ffe7e7");
							$("#erro-cnpj").show(300);
							$("#erro-cnpj").html("Este CNPJjá está sendo utilizado por outro usuário.");
						break;
						default:
							$("#cnpj").parents(".form-group").removeClass("has-error");
							$("#cnpj").css("background-color","#FFF");
							$("#erro-cnpj").hide(100);
							isvalidcnpj = true;
					}
				}
			});
		}else{
			$("#cnpj").parents(".form-group").removeClass("has-error");
			$("#cnpj").css("background-color","#FFF");
			$("#erro-cnpj").hide(100);
		}
	});

	$("#tipo").change(function(){
		if (parseInt($(this).val()) == 1){
			$("#pessoafisicacadastro").show();
			$("#pessoajuridicacadastro").hide();
		}else{
			$("#pessoafisicacadastro").hide();
			$("#pessoajuridicacadastro").show();			
		}
	});
	
	$("#btn-salvar").click(function(){
		if(!validar()){
			return false;
		}
		if (!$("#chktermo").is(":checked")){
			bootbox.alert("Você precisa aceitar os termos da <b>Política de Privacidade</b>.");
			return false;
		}		
		$.ajax({
			type:"POST",
			url:session.path_tdecommerce,
			data:{
				controller:"cadastro",
				op:"salvar",
				tipo:$("#tipo").val(),
				nome:$("#nome").val(),
				endereco:$("#endereco").val(),
				bairro:$("#bairro").val(),
				numero:$("#numero").val(),
				complemento:$("#complemento").val(),
				telefone:$("#telefone").val(),
				cpf:$("#cpf").val(),
				cep:$("#cep").val(),
				cidade:$("#cidade").val(),
				estado:$("#estado").val(),
				email:$("#email").val(),
				senha:$("#senha").val(),
				csenha:$("#csenha").val(),
			},
			beforeSend:function(){				
				$("#loading-cadastro").show();
			},
			complete:function(ret){
				var retorno = JSON.parse(ret.responseText);
				if (retorno.error == 0){
					if (session.irminhacontaaposcadastro){
						realizaLogon();
					}else{
						$("#msg-cadastrorealizado").html(ret.responseText);
						$("#loading-cadastro").hide();
						$("#linha-cadastro").hide('slow');
					}
				}else{

				}
			}
		});
	});

	function realizaLogon(){
		$.ajax({
			type:"GET",
			url:session.path_tdecommerce,
			data:{
				controller:"autenticacao",
				email:$("#email").val(),
				senha:$("#senha").val()
			},
			complete:function(ret){
				location.href = session.path_site + "minhaconta/";
			}
		});
	}

	/*
	$("#link-dicasenhaforte").click(function(){
		$("#dicasdesenha").show();
	});
	*/

	$.ajax({
		url:session.path_tdecommerce,
		data:{
			controller:"politicaprivacidade"
		},
		complete:function(retorno){
			$("#politicaprivacidade").html(retorno.responseText);
		}
	});