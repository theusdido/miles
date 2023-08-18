<div class="titulo-pagina ">Normalização da Lista</div>
<div id="loader-normaliza"></div>
<form>
	<div class="form-group">
		<label for="entidadepai">Entidade Pai</label>
		<select class="form-control" id="entidadepai"></select>
	</div>
	<div class="form-group">
		<label for="entidadefilho">Entidade Filho</label>
		<select class="form-control" id="entidadefilho"></select>
	</div>
	<div class="form-group">
		<label for="regpai">Registro Pai</label>
		<input type="email" class="form-control" id="regpai" placeholder="Registro Pai">
	</div>
	<div class="form-group">
		<label for="regfilho">Registro Filho</label>
		<input type="email" class="form-control" id="regfilho" placeholder="Registro Filho">
	</div>
	<button type="button" class="btn btn-primary" id="btn-corrigir">Corrigir</button>
</form>
<script type="text/javascript">
	$.ajax({
		url:session.urlmiles,
		dataType:"JSON",
		data:{
			controller:"normalizalista",
			op:"getentidades"
		},
		beforeSend:function(){
			loader("#loader-normaliza");
		},
		complete:function(ret){
			var retorno = ret.responseJSON;
			retorno.forEach(function(dado){
				var option = $("<option value='"+dado.id+"'>" + dado.nome + "</option>");
				$("#entidadepai,#entidadefilho").append(option);
			});
			unloader();
		}
	});	
	
	$("#btn-corrigir").click(function(){
		$.ajax({
			url:session.urlmiles,
			dataType:"JSON",
			data:{
				controller:"normalizalista",
				op:"addToFilhoInPai",
				entidadepai:$("#entidadepai").val(),
				entidadefilho:$("#entidadefilho").val(),
				regpai:$("#regpai").val(),
				regfilho:$("#regfilho").val()
			},
			beforeSend:function(){
				loader("#loader-normaliza");
			},
			complete:function(ret){
				unloader();
			}
		});	
	});
</script>