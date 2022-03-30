<div class="list-group">
	<a href="#" class="list-group-item active">Movimentação</a>
	<a href='criarMovimentacao.php?op=add' class='list-group-item'>Criar</a>	
	<?php
		$id = isset($_GET["id"])?$_GET["id"]:"";
		if ($id != ""){
			echo "<a href='criarMovimentacao.php?id={$id}' class='list-group-item'>Editar</a>";
			echo "<a href='gerarMovimentacao.php?id={$id}&entidade={$entidade}' class='list-group-item'>Gerar</a>";
		}
	?>
	<a href='listarMovimentacao.php' class='list-group-item'>Listar</a>
</div>	
