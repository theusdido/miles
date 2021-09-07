<div class="list-group">
	<a href="#" class="list-group-item active">Menu ( CRUD )</a>
	<a href='criarMenucrud.php?op=add' class='list-group-item'>Criar</a>	
	<?php
		$id = isset($_GET["id"])?$_GET["id"]:"";
		if ($id != ""){
			echo "<a href='criarMenucrud.php?id={$id}' class='list-group-item'>Editar</a>";
		}
	?>
	<a href='listarMenucrud.php' class='list-group-item'>Listar</a>
</div>
