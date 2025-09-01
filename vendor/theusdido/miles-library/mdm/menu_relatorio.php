<div class="list-group">
	<a href="#" class="list-group-item active">Relatório</a>
	<a href='criarRelatorio.php?op=add&<?=getURLParamsProject()?>' class='list-group-item'>Criar</a>	
	<?php
		$id = isset($_GET["id"])?$_GET["id"]:"";
		if ($id != ""){
			echo "<a href='criarRelatorio.php?id={$id}&".getURLParamsProject()."' class='list-group-item'>Editar</a>";
			echo "<a href='gerarRelatorio.php?id={$id}&entidade={$entidade}&".getURLParamsProject()."' class='list-group-item'>Gerar</a>";
		}
	?>
	<a href='listarRelatorio.php?<?=getURLParamsProject()?>' class='list-group-item'>Listar</a>
</div>
