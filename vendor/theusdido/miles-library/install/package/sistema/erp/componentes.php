<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<?php
		$modulos = array(
			'geral',
			'pessoa',			
			'contabil',
			'nfse',
			'financeiro',
			'comercial',
			'material',
			'imobiliaria',
			'escola',
			'associacao',
			'livraria'
		);
		foreach($modulos as $m){
			include 'modulos/'.$m.'.php';
		}
	?>
</div>

<script>
	$(".checkbox-componente").click(function(){
		var componentPath		= $(this).attr("id");
		var componenteNome 		= $(this).data("modulonome");
		var componenteDescricao	= $(this).data("modulodescricao");
		if (this.checked){
			componentes.push({
				path:componentPath,
				nome:componenteNome,
				descricao:componenteDescricao
			});
		}else{
			excluirComponenteLista(componenteNome);
		}
	});

	$(".checkbox-registro").click(function(){
		var registroName = $(this).data("entidaderegistro");
		if (this.checked){
			registros.push(registroName);
		}else{
			excluirRegistroLista(registroName);
		}
	});
</script>