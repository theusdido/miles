<?php
	$conn	= Transacao::Get();
	$op 	= tdc::r("op");

	if ($op == "ordenar"){
		$entidade 	= tdc::r("entidade");
		$atributo	= tdc::r("atributo");
		foreach(tdc::r("ordem") as $o){
			$ordem 		= $o["order"];
			$registro	= $o["id"];
			$conn->exec("UPDATE {$entidade} SET {$atributo} = {$ordem} WHERE id = {$registro};");
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
		height:75px;
	}
	.pontinhos {
		float:right;
		line-height:75px;
	}
</style>
<ul id="sortable" class="sortable">
	<?php
		$direcao	= "ASC";
		$atributo 	= "ordem";
		$entidade	= "td_slider";
		$indice		= 1;

		$sql 	= "SELECT id,{$atributo} FROM {$entidade} ORDER BY {$atributo} {$direcao}";
		$query	= $conn->query($sql);
		while ($linha = $query->fetch()){
			
			//$filenamefixed		= $atributo . "-" . $entidade . "-". $id. "." . getExtensao($filename);
			//$filenametemp		= $src;
			$id					= $linha["id"];
			$filenamefixed		= "imagem-48-{$id}.jpg";
			$pathfile       	= PATH_CURRENT_FILE . $filenamefixed;
			
			if (!file_exists($pathfile)){
				$pathfile = URL_PICTURE . "sem-imagem.jpg";
			}
			echo '
				<li data-order="'.$indice.'" data-id="'.$id.'">
					<img style="max-width:100%;" src="'.$pathfile.'" />
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
				url:session.urlsystem,
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