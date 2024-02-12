<?php
	if (isset($_GET["op"])){
		if ($_GET["op"] == "add_bairro"){
			$entidade = tdClass::Criar("persistent",array($_GET["entidade"]));
			$entidade->contexto->cidade = (int)$_GET["cidade"];
			$entidade->contexto->{$_GET["atributo"]} = $_GET["valor"];
			$entidade->contexto->empresa = Session::get()->empresa;
			$entidade->contexto->projeto = Session::get()->projeto;
			$entidade->contexto->armazenar();
			echo $entidade->contexto->getUltimo();
			Transacao::fechar();
			exit;
		}			
	}
?>
<style>
	.col-descricao{
		width:90%;
	}
	.col-retorno{
		width:10%;
		text-align:right;
	}
	#tabela-resultado-busca-endereco .col-descricao{
		height:30px;
		line-height:30px;		
	}
</style>
<div class="bloco col-md-12">
	<form>
		<fieldset>
			<div class="coluns"	data-ncolunas="1">
				<div class="form-group">
					<label class="control-label" for="endereco">Endere&ccedil;o</label>
					<div class="input-group">
					  <input class="form-control" type="text" id="input-endereco-busca" placeholder="Ex.: João Pessoa 270, centro, criciúma">
					  <span class="input-group-btn">
						<button id="btn-buscar-endereco" class="btn btn-default" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar</button>
					  </span>
					</div>
					<p class="text-info" style="float:right;margin:5px;font-size:11px;">Use a Latitude e Longitude se preferir. Ex.: -28.682757,-49.371979</p>
				</div>
			</div>
		</fieldset>
	</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#btn-buscar-endereco").click(function(){
		$(".nav li a[href='#busca-endereco']").tab('show');
		var url_api_maps = 'https://maps.googleapis.com/maps/api/geocode/json';
		var paramentros = [''];
		if($('#input-endereco-busca').val().length)
		{
			paramentros = {sensor: false,address: $('#input-endereco-busca').val()};
		}			
		$('.resultado').html('');

		$.ajax({
			url:url_api_maps,
			async: false,
			type:"get",
			dataType: "json",
			data:paramentros,
			success:function(retorno){
				$("#tabela-resultado-busca-endereco tbody").html('');
				for (a in retorno.results){
					// Endereço ( Array )
					var dados = '';
					for (b in retorno.results[a].address_components){					
						switch(retorno.results[a].address_components[b].types[0]){
							// Bairro
							case "neighborhood":
								dados += 'data-bairro="' + retorno.results[a].address_components[b].long_name + '" ';
							break;
							// Cidade
							case "locality":
								dados += 'data-cidade="' + retorno.results[a].address_components[b].long_name + '" ';
							break;
							// Estado
							case "administrative_area_level_1":
								dados += 'data-estado="' + retorno.results[a].address_components[b].short_name + '" ';
							break;
							// Pais
							case "country":
								dados += 'data-pais="' + retorno.results[a].address_components[b].short_name + '" ';
							break;
							// Número
							case "street_number":
								dados += 'data-numero="' + retorno.results[a].address_components[b].long_name + '" ';
							break;
							// Logradouro
							case "route":
								dados += 'data-logradouro="' + retorno.results[a].address_components[b].long_name + '" ';
							break;
							//CEP
							case "postal_code":
								dados += ' data-cep="' + retorno.results[a].address_components[b].long_name + '"';
							break;
						}
					}
					// Descrição do Endereço
					var descricao_end = '<td class="col-descricao">'+retorno.results[a].formatted_address+'</td>';
					var retorno_end = '<td class="col-retorno"><button type="button" class="btn btn-primary retornar-endereco" aria-label="Retornar" '+dados+'><span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span></button></td>';
					$("#tabela-resultado-busca-endereco tbody").append('<tr>'+descricao_end+retorno_end+'</tr>');				
				}
				$(".retornar-endereco").click(function(){				
					var bairro = $(this).data("bairro");
					$.ajax({
						type:"POST",
						url:"index.php?controller=existe_endereco",
						data:{
							cep:$(this).data("cep"),
							pais:$(this).data("pais"),
							estado:$(this).data("estado"),
							cidade:$(this).data("cidade"),
							bairro:bairro,
							logradouro:$(this).data("logradouro")
						},
						success:function(retorno){
							$(".nav li a[href='#adicionar-endereco']").tab('show');
							var ret = retorno.trim().split("^");
							$("#pais").val(ret[0]);
							$("#estado").load("index.php?controller=requisicoes&op=carregar_options&entidade=19&filtro=pais^" + ret[0] + "&valor=" + ret[1],
								function(){
									$("#cidade").load("index.php?controller=requisicoes&op=carregar_options&entidade=20&filtro=estado^" + ret[1] + "&valor=" + ret[2],
										function(){																				
											if (ret[3] != ""){
												$("#bairro").load("index.php?controller=requisicoes&op=carregar_options&entidade=21&filtro=cidade^" + ret[2] + "&valor=" + ret[3]);											
											}else{
												addBairro(ret[2],bairro);
											}
										}
									);	
								}
							);						
							$("#logradouro").val(ret[4]);
							$("#cep").val(ret[5]);
						}
					});
				});
			}
		});	
	});
});


</script>
<ul class="nav nav-tabs">
	<li role="presentation" class="active">
		<a href="#busca-endereco" data-toggle="tab" role="tab" aria-controls="busca-endereco" aria-expanded="true">Busca</a>
	</li>
	<li role="presentation"	>
		<a href="#adicionar-endereco" data-toggle="tab" role="tab" aria-controls="adicionar-endereco" aria-expanded="true">Adicionar</a>
	</li>
</ul>
<div class="tab-content">
	<div id="busca-endereco" role="tabpanel" class="tab-pane fade active in" aria-labelledby="busca-endereco">
		<table id="tabela-resultado-busca-endereco" class="table table-hover">
			<thead>
				<tr>
					<th class="col-endereco">Endere&ccedil;o</th>
					<th class="col-retorno">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			</tbody>	
		</table>
	</div>	
	<div id="adicionar-endereco" role="tabpanel" class="tab-pane fade in" aria-labelledby="adicionar-endereco">
		<div class="bloco col-md-12">
			<form>
				<fieldset>
					<div class="form-group">
						<input type="button" class="btn btn-success" style="float:right" value="Salvar" id="btn-salvar-endereco" />
					</div>
					<div class="form-group">
						<label class="control-label" for="pais">Pa&iacute;s</label>
						<select id="pais" name="pais" class="form-control">
						<?php
							$sql = tdClass::Criar("sqlcriterio");
							$dataset = tdClass::Criar("repositorio",array(PREFIXO ."_pais"))->carregar($sql);
							foreach ($dataset as $pais){
								echo '<option value="'.$pais->id.'" '.($pais->id==1058?'selected':'').'>'.utf8_encode($pais->nome).'</option>';
							}
						?>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label" for="estado">Estado</label>
						<select id="estado" name="estado" class="form-control">
						</select>
					</div>
					<div class="form-group">
						<label class="control-label" for="cidade">Cidade</label>
						<select id="cidade" name="cidade" class="form-control">
						</select>
					</div>
					<div class="form-group">
						<label class="control-label" for="bairro">Bairro</label>
						<input class="form-control" type="text" id="bairro-manual" name="bairro-manual" style="display:none;">
						<select id="bairro" name="bairro" class="form-control">
						<option value="-1">Escolha um bairro ...</option>
						<option value="0">Adicionar Manualmente ...</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label" for="logradouro">Logradouro</label>
						<input class="form-control" type="text" id="logradouro" name="logradouro">
					</div>
					<div class="form-group">
						<label class="control-label" for="cep">CEP</label>
						<input class="form-control" type="text" id="cep" name="cep">
					</div>					
				</fieldset>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#bairro").change(function(){
		if ($(this).val() == "0"){
			$(this).hide();
			$("#bairro-manual").show();
			$("#bairro-manual").focus();
		}
	});
	$("#bairro-manual").keypress(function(e){
		if (e.which == 13){
			addBairro($("#cidade").val(),$(this).val());
		}		
	});
	function addBairro(cidade,bairro){
		if (cidade == "" || bairro == "") return false;
		$.ajax({
			type:"GET",
			url:"index.php?controller=enderecogoogle",
			data:{
				op:"add_bairro",
				entidade:"td_bairro",
				atributo:"descricao",
				valor:bairro,
				cidade:cidade
			},
			success:function(retorno){
				$("#bairro").load("index.php?controller=requisicoes&op=carregar_options&entidade=21&valor=" + retorno + "&filtro=cidade^" + cidade,
				function(){
					$(this).append('<option value="-1">Escolha um bairro ...</option><option value="0">Adicionar Manualmente ...</option>');
				});
				
				$("#bairro-manual").hide();
				$("#bairro").show();
			}
		});		
	}
	$("#pais").change(function(){
		$("#bairro").show();
		$("#bairro-manual").val('');
		$("#bairro-manual").hide();
		
		$("#estado").prop("disabled",true);
		$("#estado").html("<option value=''>Carregando os Dados ...");
		$("#cidade").prop("disabled",true);
		$("#cidade").html("<option value=''>Carregando os Dados ...");
		$("#bairro").prop("disabled",true);
		$("#bairro").html("<option value=''>Carregando os Dados ...");
		
		$("#estado").load("index.php?controller=requisicoes&op=carregar_options&entidade=8&filtro=pais^" + this.value,
			function(){
				$(this).prop("disabled",false);
				$("#cidade").load("index.php?controller=requisicoes&op=carregar_options&entidade=7&filtro=estado^" + $("#estado option:first-child").val(),					
					function(){
						$(this).prop("disabled",false);						
						$("#bairro").load("index.php?controller=requisicoes&op=carregar_options&entidade=12&filtro=cidade^" + $("#cidade option:first-child").val(),
							function(){
								$(this).prop("disabled",false);
								$("#bairro").html('<option value="-1">Escolha um bairro ...</option><option value="0">Adicionar Manualmente ...</option>');
							}
						);
					}
				);
			}			
		);		
		$("#cidade").html('');
		
		$("#logradouro,#cep").val('');
	});
	$("#estado").change(function(){
		$("#bairro").show();
		$("#bairro-manual").val('');
		$("#bairro-manual").hide();
		
		$("#cidade").prop("disabled",true);
		$("#cidade").html("<option value=''>Carregando os Dados ...");
		$("#bairro").prop("disabled",true);
		$("#bairro").html("<option value=''>Carregando os Dados ...");		
		
		$("#cidade").load("index.php?controller=requisicoes&op=carregar_options&entidade=7&filtro=estado^" + this.value,
			function(){
				$(this).prop("disabled",false);				
				$("#bairro").load("index.php?controller=requisicoes&op=carregar_options&entidade=12&filtro=cidade^" + $("#cidade option:first-child").val(),
					function(){
						$(this).prop("disabled",false);
						$("#bairro").html('<option value="-1">Escolha um bairro ...</option><option value="0">Adicionar Manualmente ...</option>');
					}
				);
			}
		);
		$("#cidade").html('');
		$("#logradouro,#cep").val('');
	});
	$("#cidade").change(function(){
		$("#bairro").show();
		$("#bairro-manual").val('');
		$("#bairro-manual").hide();

		$("#bairro").prop("disabled",true);
		$("#bairro").html("<option value=''>Carregando os Dados ...");		
		
		$("#bairro").load("index.php?controller=requisicoes&op=carregar_options&entidade=12&filtro=cidade^" + this.value,
			function(){
				$(this).prop("disabled",false);				
				$(this).html('<option value="-1">Escolha um bairro ...</option><option value="0">Adicionar Manualmente ...</option>' + $(this).html());
			}
		);
		$("#logradouro,#cep").val('');
	});
		
	$("#btn-salvar-endereco").click(function(){
		if ($("#cep").val() == ""){
			alert("CEP é um campo obrigatório");
			return false;
		}
		$.ajax({
			url:"index.php?controller=existe_endereco",
			type:"POST",
			data:{
				op:"salvar_endereco",
				pais:$("#pais").val(),
				estado:$("#estado").val(),
				cidade:$("#cidade").val(),
				bairro:($("#bairro-manual").val() != ""?$("#bairro-manual").val():$("#bairro").val()),
				logradouro:$("#logradouro").val(),
				cep:$("#cep").val()
			},
			success:function(retorno){
				$("#td_endereco").val(retorno.split("^")[0]);
				$("#descricao-td_endereco").val(retorno.split("^")[1]);
				$("#myModal-td_endereco").modal('hide');
			}
		});
	});
	
	$(document).ready(function(){		
		$("#estado").load("index.php?controller=requisicoes&op=carregar_options&entidade=19&filtro=pais^1&valor=1",
			function(){				
				$("#cidade").load("index.php?controller=requisicoes&op=carregar_options&entidade=20&filtro=estado^1&valor=1",
					function(){
						$("#bairro").load("index.php?controller=requisicoes&op=carregar_options&entidade=21&filtro=cidade^1",
							function(){
								$("#bairro").append('<option value="-1" selected>Escolha um bairro ...</option><option value="0">Adicionar Manualmente ...</option>');
							}
						);
					}
				);
			}			
		);		
		$("#logradouro,#cep").val('');
		$("#cep").mask("99999-999");
	});	
</script>