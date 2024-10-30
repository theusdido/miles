<?php
	$conn	= Transacao::Get();
	$op 	= tdc::r("op");

	if ($op == "ordenar"){
		$entidade 	= tdc::r("entidade");
		$atributo	= tdc::r("atributo");
		foreach(tdc::r("ordem") as $o){
			$ordem 		= $o["order"];
			$registro	= $o["id"];
			#$conn->exec("UPDATE {$entidade} SET {$atributo} = {$ordem} WHERE id = {$registro};");
			$_entidade = tdc::p($entidade,$registro);
			$_entidade->{$atributo} = $ordem;
			$_entidade->armazenar();
		}
		Transacao::Commit();
		exit;
	}
?>
<style type="text/css">
	.sortable{
		
	}
	.sortable li {
		padding:5px;
		border:1px solid #DDD;
		margin:5px;
		list-style-type:none;
		cursor:pointer;
	}
	.sortable li:hover{
		background-color:#EEE;
	}
	.sortable li img {
		height:50px;
	}
	.pontinhos {
		float:right;
		line-height:50px;
	}
</style>
<ul id="sortable" class="sortable">
	<?php
		
		$_entidade 	= tdc::e(tdc::r('entidade'));
		$_atributo 	= tdc::a(tdc::r('atributo'));
		$direcao	= tdc::r('order') == '' ? 'ASC' : tdc::r('order');
		$campos 	= array('id',$_atributo->nome);
		$filtros	= tdc::r('filtro') == '' ? array() : json_decode(tdc::r('filtro'),true);

		if (tdc::r('display') == ''){
			$campo_display = $_atributo->nome;
		}else{
			$campo_display = tdc::a(tdc::r('display'))->nome;
			array_push($campos,$campo_display);
		}

		if (tdc::r('image') != ''){
			$campo_image 		= tdc::a(tdc::r('image'));
			$campo_image_id		= $campo_image->id;
			$campo_image_nome 	= $campo_image->nome;

			array_push($campos,$campo_image_nome);
		}else{
			$campo_image_id		= 0;
			$campo_image_nome 	= '';
		}

		$criterio = array(' WHERE 1=1 ');
		if ($filtros != ''){
			foreach($filtros as $key => $value){
				array_push($criterio," AND $key = '$value' ");
			}
		}		

		$where = '';
		if (sizeof($criterio) > 0){
			$where = implode(' ',$criterio);
		}

		$entidade	= $_entidade->nome;
		$atributo 	= $_atributo->nome;
		$indice		= 1;

		$sql 		= "SELECT ".implode(',',$campos)." FROM {$entidade} $where ORDER BY {$atributo} {$direcao}";
		$query		= $conn->query($sql);
		while ($linha = $query->fetch()){
			$filename			= $linha[$campo_image_nome];
			$descricao 			= $linha[$campo_display];
			$id					= $linha["id"];
			$filenamefixed		= $campo_image_nome . "-".$_entidade->id."-".$id.".".getExtensao($filename);
			$pathfile       	= PATH_CURRENT_FILE . $filenamefixed;

			if (!file_exists($pathfile)){
				$pathfile = URL_ASSETS . 'img/noimage.png';
			}

			echo '
				<li data-order="'.$indice.'" data-id="'.$id.'">
					<img style="max-width:100%;" src="'.$pathfile.'" />
					'.$descricao.'
					<span class="fas fa-ellipsis-v pontinhos" aria-hidden="true"></span>
				</li>
			';
			$indice++;
		}
	?>
</ul>
<script>
	$("#sortable").sortable({
		update: function( event, ui ) {
			var ordenacao = [];
			$("#sortable li").each(
				(e,elemento) => {
					var id = $(elemento).data("id");
					if (id != undefined){
						ordenacao.push({
							id:id,
							order:e+1
						});
					}					
				}
			);
			console.log(ordenacao);
			$.ajax({
				url:session.urlmiles,
				data:{
					op:"ordenar",
					controller:"sortable",
					entidade:"<?=$entidade?>",
					atributo:"<?=$atributo?>",
					ordem:ordenacao
				}
			});
		}
	});
</script>