	$(document).ready(function(){
		carregarItem("meusenderecos");
		carregarCidade();
	});
	function carregarItem(controller,op,id){
		
		$.ajax({
			url:session.path_tdecommerce,
			data:{
				controller:controller,
				op:op,
				id:id
			},
			beforeSend:function(){
				$("#minhaconta-item").html(
				'<div style="width:100%;margin:75px auto">' +
					'<center>' +
						'<img width="32" align="middle" src="'+session.url_system_theme+'loading.gif">' +
						'<p class="text-muted">Aguarde</p>' +
					'</center>' +	
				'</div>'
				);				
			},
			complete:function(ret){
				$("#minhaconta-item").html(ret.responseText);
			}
		});
	}
	$(".list-group .list-group-item").click(function(event){
		event.preventDefault();
		if ($(this).hasClass("disabled")) return false;
		$(".list-group .list-group-item").removeClass("disabled");
		$(this).addClass("disabled");
		carregarItem($(this).data("url"));
	});
	function carregarCidade(){
		var uf = 24;
		if ($("#estado").val() != undefined) uf = $("#estado").val();
		$.ajax({
			url:session.path_tdecommerce,
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
	function excluirEndereco(endereco){
		carregarItem("meusenderecos","excluir&endereco",endereco);
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