<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<?php
		include 'modulos/geral.php';
		include 'modulos/pessoa.php';
		include 'modulos/contabil.php';
		include 'modulos/financeiro.php';
		include 'modulos/comercial.php';
		include 'modulos/material.php';
		include 'modulos/imobiliaria.php';
		include 'modulos/escola.php';
		include 'modulos/associacao.php';
	?>
</div>

<script>
	$(".checkbox-componente").click(function(){
		var componenteNome = $(this).attr("id");
		if (this.checked){
			componentes.push(componenteNome);
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