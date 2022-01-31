	function carregarCidade(){
		var uf = 24;
		if ($("#estado").val() != undefined) uf = $("#estado").val();
		$.ajax({
			url:session.path_tdecommerce + "index.php",
			data:{
				controller:"meusenderecos",
				op:"carrega_cidade",
				uf:uf
			},
			success:function(retorno){
				$("#cidade").html(retorno);
			},
			beforeSend:function(){
				$("#cidade").attr("disabled",true);
				$("#cidade").html("Carregando as Cidades");
			},
			complete:function(){
				$("#cidade").attr("disabled",false);
				bairroMapeado();	
			}
		});
	}
	function bairroMapeado(){
		$("#campo-bairro").html("");
		if (parseInt($("#cidade option:checked").data("bairromapeado")) == 1){
			carregarBairro();
		}else{
			bairroComoTexto();
		}
	}
	function carregarBairro(){
		$("#campo-bairro").html(
			'<label for="bairro">Bairro</label>' +
			'<select id="bairro" name="bairro" class="form-control" onchange="mudabairro()"></select>'
		);
		$.ajax({
			url:session.path_tdecommerce,
			data:{
				controller:"meusenderecos",
				op:"carrega_bairro",
				cidade:$("#cidade option:selected").val()
			},
			success:function(retorno){
				if (retorno == ""){
					bairroComoTexto();
				}else{
					$("#bairro").html(retorno);
				}
			},
			beforeSend:function(){
				$("#bairro").attr("disabled",true);
				$("#bairro").html("Carregando os Bairros");
			},
			complete:function(){
				$("#bairro").attr("disabled",false);
			}
		});
	}
	function bairroComoTexto(){
		$("#campo-bairro").html(
			'<label for="bairro">Bairro</label>' + 
			'<input type="text" class="form-control" id="bairro" name="bairro" placeholder="Bairro">'
		);		
	}
	function mudabairro(){
		if ($("#bairro option:selected").val() == "0"){
			bairroComoTexto();
		}
	}
	function mudacidade(){		
		if (parseInt($("#cidade").find("option:checked").data("bairromapeado")) == 1){
			carregarBairro();
		}else{
			bairroComoTexto();
		}
	}
	
	function excluirEndereco(id){
		$.ajax({
			url:session.path_tdecommerce,
			data:{
				controller:"meusenderecos",
				op:"excluir",
				id:id
			},
			success:function(){
				carregarItem("meusenderecos");
			},
			beforeSend:function(){
				$("#loading-excluirendereco").show();
			},
			complete:function(){
				$("#loading-excluirendereco").hide();
			},
			error:function(){
				bootbox.alert('Ops! Erro ao excluir endereço.');
			}
		});		
	}

	$("#btn-meusenderecos-salvarendereco").click(function(){
		salvarEndereco();
	});	
	function salvarEndereco(){		
		if(!validarFormEndereco()){
			return false;
		}
		$.ajax({
			type:"POST",
			url:session.path_tdecommerce,
			data:{
				controller:"meusenderecos",
				op:"salvar",
				cidade:$("#cidade").val(),
				bairro:$("#bairro").val(),
				logradouro:$("#logradouro").val(),
				complemento:$("#complemento").val(),
				cep:$("#cep").val(),
				numero:$("#numero").val(),
				estado:$("#estado").val()
			},
			beforeSend:function(){
				$("#loading-salvarendereco").show();
			},
			success:function(){
				$("#loading-salvarendereco").hide();
				carregarItem("meusenderecos");
			}
		});
	}

	function validarFormEndereco(){
		var campos = new Array("bairro","logradouro","cep");
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
		var cep = $("#cep").val().replace("-","");
		if (cep.length != 8){
			$("#cep").parents(".form-group").addClass("has-error");
			$("#cep").css("background-color","#ffe7e7");
			return false;
		}
		return true;
	}
	$("#cep").mask("99999-999");
	carregarCidade();