<div class="list-group">
	<a href="#" class="list-group-item active">Consulta</a>
	<a href='criarConsulta.php?op=add&currentproject=<?=$_SESSION["currentproject"]?>' class='list-group-item'>Criar</a>	
	<?php
		$id = isset($_GET["id"])?$_GET["id"]:"";
		if ($id != ""){
			echo "<a href='criarConsulta.php?id={$id}&currentproject=".$_SESSION["currentproject"]."' class='list-group-item'>Editar</a>";
			echo "<a href='gerarConsulta.php?id={$id}&entidade={$entidade}&currentproject=".$_SESSION["currentproject"]."' class='list-group-item'>Gerar</a>";
		}
	?>
	<a href='listarConsulta.php?currentproject=<?=$_SESSION["currentproject"]?>' class='list-group-item'>Listar</a>
</div>