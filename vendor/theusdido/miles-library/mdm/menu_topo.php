<?php
	// if ($connProducao == null){
	// 	echo '<div class="alert alert-warning text-center" role="alert"><b>AVISO!</b> Não foi possível conectar na base de <b>produção</b>.</div>';
	// }
?>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">
        <img alt="Brand" src="<?=URL_SYSTEM_THEME?>favicon.png">
		<label>Teia</label>
      </a>
    </div>
	<ul class="nav navbar-nav">		
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Conceitos <span class="caret"></span></a>
       		<ul class="dropdown-menu">
            	<li><a href="listarEntidade.php">Cadastro</a></li>
				<li><a href="listarConsulta.php">Consulta</a></li>
				<li><a href="listarRelatorio.php">Relatório</a></li>
				<li><a href="listarMovimentacao.php">Movimentação</a></li>
          	</ul>
		</li>

		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Website <span class="caret"></span></a>
       		<ul class="dropdown-menu">
            	<li><a href="templateWebsite.php">Template</a></li>
          	</ul>
		</li>
		
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Configurações <span class="caret"></span></a>
       		<ul class="dropdown-menu">
				<li><a href="config.php">Sistema</a></li>
            	<li><a href="permissoes.php">Permissões</a></li>
				<li><a href="atualizar.php">Atualizar</a></li>
				<li><a href="charset.php">CharSet</a></li>
          	</ul>
		</li>
		
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Menu's <span class="caret"></span></a>
       		<ul class="dropdown-menu">
            	<li><a href="menutopohome.php">Menu Topo ( Home )</a></li>
            	<li><a href="menulateralpagina.php">Menu Lateral Página</a></li>
            	<li><a href="listarMenucrud.php">Menu ( CRUD )</a></li>
          	</ul>
		</li>
	</ul>	
  </div>
	<div class="alert alert-info text-right dados-config-projeto">
		<?php
			echo '<b id="titulo-mdm">MILES DATABASE MANAGEMENT</b> ID: [ <b>'.PROJECT_ID.'</b> ] - Projeto: <b>'.PROJECT_NAME.'</b> | Instância: <b>'._ENVIROMMENT.'</b>';
		?>
	</div>  
</nav>
