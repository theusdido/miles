$(document).ready(function(){
	$("#cep").mask("99999999");
	optionsBairro("#bairro","");
	var cidadepadrao = escape(retirarAcentos("CRICIÚMA"));
	$("#estado").load("index.php?op=retorna_lista_uf&controller=enderecofiltro&selecionado=SC",function(){

		$("#cidade").prop("disabled",true);
		$("#cidade").html("<option value=''>Carregando os Dados ...");
		$("#bairro").prop("disabled",true);
		$("#bairro").html("<option value=''>Carregando os Dados ...");
		$("#cidade").load("index.php?op=retorna_lista_localidade&controller=enderecofiltro&uf=SC&selecionado=" + cidadepadrao,
			function(){
				$("#cidade").prop("disabled",false);
				$("#bairro").load("index.php?op=retorna_lista_bairro&controller=enderecofiltro&localidade="+cidadepadrao+"&uf=SC&selecionado=Centro",function(){
					$("#bairro").prop("disabled",false);
					optionsBairro("#bairro",$(this).html());
				});
			}
		);
		$("#logradouro").val('');
		//if (retornaPermissao(1)){
			$(".nav li a[href='#adicionar-endereco']").show();
		//}else{
		//	$(".nav li a[href='#adicionar-endereco']").hide();
		//}
	});
	$("#input-endereco-busca").keyup(function(e){
		if (e.which == 13){
			$("#btn-buscar-endereco").click();
		}	
	});
	$("#btn-buscar-endereco").click(function(){
		$(".nav li a[href='#busca-endereco']").tab('show');
		$.ajax({
			type:"POST",
			url:"index.php",
			contentType:"application/x-www-form-urlencoded",
			data:{
				op:"busca_geral",
				controller:"enderecofiltro",
				termo:retirarAcentos($('#input-endereco-busca').val())
			},
			beforeSend:function(){
				loader("#tabela-resultado-busca-endereco tbody");
			},
			complete:function(ret){				
				let retorno = ret.responseText;
				$("#loader-endereco").hide();
				if (retorno != ""){
					$("#tabela-resultado-busca-endereco tbody").html("");
					for(l in retorno.split("~")){
						var linha = retorno.split("~")[l];
						if (linha == "") continue;
						let cep                     = linha.split("^")[0].trim();
						let uf                      = linha.split("^")[1].trim();
						let cidade                  = linha.split("^")[2].trim();
						let bairro                  = linha.split("^")[3].trim();
						let logradouro              = linha.split("^")[4].trim();
						
						let trechoinicial           = linha.split("^")[5].trim();
						let trechofinal             = linha.split("^")[6].trim();
						let numerolote              = linha.split("^")[7].trim();
						let informacaoadicional     = linha.split("^")[8].trim();
						
						let descricao_endereco      = logradouro + ", " + 	bairro + " - " + cidade + " / " + uf + ". Cep: " + cep + ". ";
						let complemento             = "";

						if (trechoinicial != "" && trechofinal != "") complemento += " Nº : " + trechoinicial + " até " + trechofinal;
						if (numerolote != "") complemento += numerolote + ". ";
						if (informacaoadicional != "") complemento += "Inf. Adicional: " + informacaoadicional + ".";
						
						let dados = logradouro + "^" + 	bairro + "^" + cidade + "^" + uf + "^" + cep;
						
						// Descrição do Endereço
						let descricao_end   = '<td class="col-descricao">'+descricao_endereco.toUpperCase()+'<small class="text-muted"> '+complemento.toUpperCase()+' </small></td>';
						let retorno_end     = '<td class="col-retorno"><button type="button" class="btn btn-primary retornar-endereco" aria-label="Retornar" onclick=\'carregarEndereco("'+dados+'");\'><span class="fas fa-thumbtack" aria-hidden="true"></span></button></td>';
						$("#tabela-resultado-busca-endereco tbody").append('<tr>'+descricao_end+retorno_end+'</tr>');
					}
				}else{
					$("#tabela-resultado-busca-endereco tbody").html("");
					$("#tabela-resultado-busca-endereco tbody").html('<tr class="warning"><td class="text-center" colspan="2">Nenhum registro encontrado.</td></tr>');
				}
			}
		});
	});
});

function carregarEndereco(dados){
	var bairro = "";
	// Pega ID do bairro na base local
	$.ajax({
		type:"GET",
		url:"index.php",
		contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		data:{
			op:"add_bairro",
			controller:"enderecofiltro",
			uf:dados.split("^")[3],
			cidade:retirarAcentos(dados.split("^")[2]),
			descricao:dados.split("^")[1],
			retorno:"C"
		},
		complete:function(ret){
			var retorno = ret.responseText;
			bairro = retorno;
			savarEndereco(dados,bairro);		
		}		
	});
	
	function savarEndereco(dados,bairro){
		// Utilizar Endereço
		$.ajax({
			type:"GET",
			url:"index.php",
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			data:{
				op:"salva_endereco",
				controller:"enderecofiltro",
				uf:dados.split("^")[3],
				localidade:dados.split("^")[2],
				bairro:bairro,
				cep:dados.split("^")[4],
				logradouro:dados.split("^")[0]
			},
			complete:function(ret){
				var retorno = ret.responseText;
				dadosFiltroEnderecoTemp.filtro.find(".termo-filtro").val(retorno);
				dadosFiltroEnderecoTemp.filtro.find(".descricao-filtro").val(dados.split("^")[0]);
				dadosFiltroEnderecoTemp.filtro.find(".modal").modal('hide');
			}
		});
	}
}
function optionsBairro(campo,valores){
	$(campo).html('<option value="-1">Escolha um bairro ...</option><option value="0">Adicionar Manualmente ...</option>');
	$(campo).append(valores);
}

$("#bairro-manual").keypress(function(e){
    if (e.which == 13){
        //addBairro($("#estado").val(),$("#cidade").val(),$(this).val());
    }		
});
$("#bairro-manual").blur(function(){
    if ($(this).val() != ""){
        addBairro($("#estado").val(),$("#cidade").val(),$(this).val());
    }else{
        $("#bairro-manual").hide();
        $("#bairro-manual").val("");			
        $("#bairro").show();
    }
});
function addBairro(uf,cidade,bairro){
    if (uf == "" || cidade == "" || bairro == "") return false;
    $("#bairro").prop("disabled",true);
    $("#bairro").html("<option value=''>Carregando os Dados ...");		
    $.ajax({
        type:"GET",
        url:"index.php",
        async:false,
        contentType: "application/x-www-form-urlencoded;charset=UTF-8",
        data:{
            op:"add_bairro",
            controller:"enderecofiltro",
            retorno:"W",
            descricao:bairro.trim(),
            cidade:retirarAcentos(cidade.trim()),
            uf:uf.trim()
        },
        complete:function(ret){
            var retorno = ret.responseText;
            $.ajax({
                type:"GET",
                url:"index.php",
                contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                data:{
                    op:"retorna_lista_bairro",
                    controller:"enderecofiltro",
                    uf:uf.trim(),
                    localidade:retirarAcentos(cidade.trim()),
                    selecionado:retorno
                },
                complete:function(ret){
                    var listaBairro = ret.responseText;
                    $("#bairro").prop("disabled",false);
                    optionsBairro("#bairro",listaBairro);
                    $("#bairro").val(bairro.trim());
                }
            });
            $("#bairro-manual").hide();
            $("#bairro").show();
        }
    });	
}

$("#bairro").change(function(){
    if ($(this).val() == "0"){
        $(this).hide();
        $("#bairro-manual").show();
        $("#bairro-manual").focus();
    }
});
$("#estado").change(function(){
    $("#cidade").prop("disabled",true);
    $("#cidade").html("<option value=''>Carregando os Dados ...");
    $("#bairro").prop("disabled",true);
    $("#bairro").html("<option value=''>Carregando os Dados ...");
    var estado = $(this).val();
    $("#cidade").load("index.php?op=retorna_lista_localidade&controller=enderecofiltro&uf="+this.value+"&selecionado=",
        function(){
            $("#cidade").prop("disabled",false);
            $("#bairro").load("index.php?op=retorna_lista_bairro&controller=enderecofiltro&localidade="+escape(retirarAcentos($(this).val()))+"&uf="+estado+"&selecionado=",function(ret){
                $("#bairro").prop("disabled",false);
                optionsBairro("#bairro",ret);
            });
        }
    );
    $("#logradouro").val('');
});

$("#cidade").change(function(){
    $("#bairro").prop("disabled",true);
    $("#bairro").html("<option value=''>Carregando os Dados ...");		
    $("#bairro").load("index.php?op=retorna_lista_bairro&controller=enderecofiltro&localidade="+escape(retirarAcentos($(this).val()))+"&uf="+escape($("#estado").val())+"&selecionado=",function(){
        $("#bairro").show();
        $("#bairro-manual").val('');
        $("#bairro-manual").hide();
        $("#bairro").prop("disabled",false);
        optionsBairro("#bairro",$(this).html());
    });
});
    
$("#btn-salvar-endereco").click(function(){
    $("#msg-erro-endereco").hide();
    $("#msg-erro-endereco").html("");

    if (parseInt($("#estado").val()) <= 0){
        $("#msg-erro-endereco").show(100);
        $("#msg-erro-endereco").html("<strong>Estado</strong> &eacute; um campo obrigat&oacute;rio");
        $("#estado").focus();
        return false;
    }

    if (parseInt($("#cidade").val()) <= 0){
        $("#msg-erro-endereco").show(100);
        $("#msg-erro-endereco").html("<strong>Cidade</strong> &eacute; um campo obrigat&oacute;rio");
        $("#cidade").focus();
        return false;
    }
            
    if (parseInt($("#bairro").val()) <= 0){
        $("#msg-erro-endereco").show(100);
        $("#msg-erro-endereco").html("<strong>Bairro</strong> &eacute; um campo obrigat&oacute;rio");
        $("#bairro").focus();
        return false;
    }

    if ($("#cep").val() == ""){
        $("#msg-erro-endereco").show(100);
        $("#msg-erro-endereco").html("<strong>CEP</strong> &eacute; um campo obrigat&oacute;rio");
        $("#cep").focus();
        return false;
    }
    if ($("#logradouro").val() == ""){
        $("#msg-erro-endereco").show(100);
        $("#msg-erro-endereco").html("<strong>Logradouro</strong> &eacute; um campo obrigat&oacute;rio");
        $("#logradouro").focus();
        return false;
    }
    // Salva Endereço
    $.ajax({
        type:"GET",
        url:"index.php",
        contentType: "application/x-www-form-urlencoded;charset=UTF-8",
        data:{
            op:"salva_endereco",
            controller:"enderecofiltro",
            uf:$("#estado").val(),
            localidade:$("#cidade").val(),
            bairro:$("#bairro").val(),
            cep:$("#cep").val(),
            logradouro:$("#logradouro").val()
        },
        complete:function(ret){
            var retorno = ret.responseText;            
            //dadosFiltroEnderecoTemp.filtro.find(".termo-filtro").val(retorno);
            //dadosFiltroEnderecoTemp.filtro.find(".descricao-filtro").val($("#logradouro").val());
            //dadosFiltroEnderecoTemp.filtro.find(".modal").modal('hide');
            $(".termo-filtro").val(retorno);
            $(".descricao-filtro").val($("#logradouro").val());
            $(".modal").modal('hide');
        }
    });
    
});