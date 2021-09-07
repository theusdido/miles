<?php
	if ($connProducao == null){
		echo '<div class="alert alert-warning text-center" role="alert"><b>AVISO!</b> Não foi possível conectar na base de <b>produção</b>.</div>';
	}
?>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">
        <img alt="Brand" src="../tema/padrao/logo-favicon.png">
		<label>Teia Online</label>
      </a>
    </div>
	<ul class="nav navbar-nav">		
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Conceitos <span class="caret"></span></a>
       		<ul class="dropdown-menu">
            	<li><a href="listarEntidade.php?<?=getURLParamsProject()?>">Entidade</a></li>
				<li><a href="listarConsulta.php?<?=getURLParamsProject()?>">Consulta</a></li>
				<li><a href="listarRelatorio.php?<?=getURLParamsProject()?>">Relatório</a></li>
				<li><a href="listarMovimentacao.php?<?=getURLParamsProject()?>">Movimentação</a></li>
          	</ul>
		</li>

		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Website <span class="caret"></span></a>
       		<ul class="dropdown-menu">
            	<li><a href="templateWebsite.php?<?=getURLParamsProject()?>">Template</a></li>
          	</ul>
		</li>
		
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Configurações <span class="caret"></span></a>
       		<ul class="dropdown-menu">
				<li><a href="config.php?<?=getURLParamsProject()?>">Sistema</a></li>
            	<li><a href="permissoes.php?<?=getURLParamsProject()?>">Permissões</a></li>
				<li><a href="atualizar.php?<?=getURLParamsProject()?>">Atualizar</a></li>
				<li><a href="charset.php?<?=getURLParamsProject()?>">CharSet</a></li>
          	</ul>
		</li>
		
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Menu's <span class="caret"></span></a>
       		<ul class="dropdown-menu">
            	<li><a href="menutopohome.php?<?=getURLParamsProject()?>">Menu Topo ( Home )</a></li>
            	<li><a href="menulateralpagina.php?<?=getURLParamsProject()?>">Menu Lateral Página</a></li>
            	<li><a href="listarMenucrud.php?<?=getURLParamsProject()?>">Menu ( CRUD )</a></li>
          	</ul>
		</li>
	</ul>	
  </div>
	<div class="alert alert-info text-right dados-config-projeto">
		<?php
			echo '<b id="titulo-mdm">MILES DATABASE MANAGEMENT</b> ID: [ <b>'.$currentproject.'</b> ] - Projeto: <b>'.$currentprojectname.'</b> | Instância: <b>'.$currenttypedatabase.'</b>';
		?>
	</div>  
</nav>
