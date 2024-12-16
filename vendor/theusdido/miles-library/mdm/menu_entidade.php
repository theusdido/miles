<div class="list-group">
	<a href="#" class="list-group-item active">Entidades</a>
	<a href='criarEntidade.php?<?=getURLParamsProject()?>' class='list-group-item'>Criar</a>
	<?php
		if (isset($entidade)){
		echo '
			<a href="criarEntidade.php?entidade='.$entidade.'&'.getURLParamsProject().'" class="list-group-item">Editar</a>
			<a href="criarAba.php?entidade='.$entidade.'&'.getURLParamsProject().'" class="list-group-item">Aba</a>
			<a href="criarRelacionamento.php?entidade='.$entidade.'&'.getURLParamsProject().'" class="list-group-item">Relacionamento</a>
			<a href="criarAcoes.php?entidade='.$entidade.'&'.getURLParamsProject().'" class="list-group-item">A&ccedil;&otilde;es</a>
			<a href="gerarPagina.php?entidade='.$entidade.'&'.getURLParamsProject().'" class="list-group-item">Gerar ( HTML )</a>
			<a href="filtroAtributo.php?entidade='.$entidade.'&'.getURLParamsProject().'" class="list-group-item">Filtros ( Atributos )</a>
			
		';
	}
	?>
	<a href='listarEntidade.php?<?=getURLParamsProject()?>' class='list-group-item'>Listar</a>
</div>