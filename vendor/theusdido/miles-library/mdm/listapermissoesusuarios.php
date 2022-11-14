<?php
	include 'autoload.php';
?>
<div class="row">
	<div class="col-md-12">
		<div class="btn-group" role="group" aria-label="" id="gp-btn-op">
			<button id="permissao-btn-tela" type="button" class="btn btn-primary active">Cadastros</button>
			<button id="permissao-btn-consulta" type="button" class="btn btn-primary">Consultas</button>
			<button id="permissao-btn-relatorio" type="button" class="btn btn-primary">Relatórios</button>
			<button id="permissao-btn-funcao" type="button" class="btn btn-primary">Funções</button>
			<button id="permissao-btn-menu" type="button" class="btn btn-primary">Menu</button>
		</div>	
		<div id="dadosusuariopermissao">
			<span>Usuário: <label class="nome_usuario"></label></span>
			<button type="button" class="btn btn-warning" aria-label="Restaurar Permissões" id="btn-restaurar-permissoes">
			  	<span class="fas fa-redo" aria-hidden="true"></span>
			</button>
			<button type="button" class="btn btn-primary" aria-label="Permissão Geral" id="btn-all-permissoes">
			  	<span class="fas fa-asterisk" aria-hidden="true"></span>
			</button>			
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<ul class="list-group">
		<?php
			$sql = "SELECT id,descricao FROM td_grupousuario";
			$query = $conn->query($sql);
			while($linha = $query->fetch()){
				$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
				echo "<li href='#' class='list-group-item'>";
				echo "		<div class='cabecalho-lista-usuario' id='".$linha["id"]."'>";
				echo "			<span class='fas fa-folder' aria-hidden='true'></span>";
				echo "				".$descricao;
				echo "		</div>";
				echo "		<ul class='lista-usuario' id='lista-usuario-".$linha["id"]."'>";
				echo "<b>Usuário</b>";
				$sqlusuarios = "SELECT id,nome,perfil FROM td_usuario WHERE (perfilusuario <> 1 OR perfilusuario IS NULL) AND grupousuario = " . $linha["id"];
				$rsusuarios = $conn->query($sqlusuarios);
				While ($linhausuarios = $rsusuarios->fetch()){
					echo "<li><a href='#' class='usuario-na-lista-porusuario' id='".$linhausuarios["id"]."' data-perfil='".$linhausuarios["perfil"]."'>".$linhausuarios["nome"]."</a></li>";
				}
				if ($rsusuarios->rowcount() <= 0){
					echo "<li><b>Nenhum Usuário</b></li>";
				}			
				echo "<b>Perfil</b>";
				$sqlperfil = "SELECT id,nome,perfil FROM td_usuario WHERE perfilusuario = 1 AND grupousuario = ".$linha["id"];
				$queryperfil = $conn->query($sqlperfil);
				While ($linhaperfil = $queryperfil->fetch()){
					echo "<li><a href='#' class='usuario-na-lista-porusuario' id='".$linhaperfil["id"]."' data-perfil='".$linhaperfil["perfil"]."'>".$linhaperfil["nome"]."</a></li>";
				}
				echo "		</ul>";
				echo "	</li>";
			}
		?>
		</ul>
	</div>
	<script type="text/javascript">
		$(".cabecalho-lista-usuario").click(function(){
			var id = $(this).attr("id");
			if ($("#lista-usuario-" + id).css("display") == "none"){
				$("#lista-usuario-" + id).show();
				$(this).find(".glyphicon").addClass("glyphicon-folder-open");
				$(this).find(".glyphicon").removeClass("glyphicon-folder-close");
			}else{
				$(this).find(".glyphicon").addClass("glyphicon-folder-close");
				$(this).find(".glyphicon").removeClass("glyphicon-folder-open");
				
				$("#lista-usuario-" + id).hide();
			}
		});	
		$(".usuario-na-lista-porusuario").click(function(){
			$(".nome_usuario").html($(this).html());
			permissoes.usuarioSelecionado = $(this).attr("id");
			permissoes.usuarioPerfil = $(this).data("perfil");
			$("tr input[type=checkbox]").attr("checked",false);
			carregarPermissoesUsuario(permissoes.usuarioSelecionado);
		});
		function carregarPermissoesUsuario(usuario){			
			$.ajax({
				url:"listadadospermissoesusuario.php",
				data:{
					usuario:usuario,
					currentproject:<?=$_SESSION["currentproject"]?>
				},
				complete:function(r){
					var retorno = JSON.parse(r.responseText);
					for(e in retorno[0].entidades){
						var dados = retorno[0].entidades[e].entidadedados.split("^");
						if ($("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[0])
							$("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[0].checked = (dados[1]==1?true:false);
						
						if ($("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[1])
							$("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[1].checked = (dados[2]==1?true:false);
						
						if ($("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[2])
							$("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[2].checked = (dados[3]==1?true:false);
						
						if ($("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[3])
							$("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[3].checked = (dados[4]==1?true:false);
					}
					for(a in retorno[1].funcoes){
						var dados = retorno[1].funcoes[a].funcoesdados.split("^");
						$("#lista-funcoes tr[funcaoid="+dados[0]+"] input[type=checkbox]")[0].checked = (dados[1]==1?true:false);
					}
					for(a in retorno[2].menus){
						var dados = retorno[2].menus[a].menu.split("^");
						if ($("#lista-menu tr[menuid="+dados[0]+"] input[type=checkbox]")[0]){
							$("#lista-menu tr[menuid="+dados[0]+"] input[type=checkbox]")[0].checked = (dados[1]==1?true:false);
						}	
					}
					$("#dadosusuariopermissao").show();
					if (permissoes.usuarioPerfil == ""){
						$("#btn-restaurar-permissoes").hide();
					}else{
						$("#btn-restaurar-permissoes").show();
					}
				}
			});
		}
		$(".abrir-atributos").click(function(){
			if (permissoes.usuarioSelecionado != ""){
				var idEntidade = $(this).parents("tr").data("entidadeid");
				var descricaoEntidade = $(this).parents("tr").data("entidadedescricao") + " - <small>( Atributos )</small>";
				$.ajax({
					url:"listapermissoesatributos.php",
					data:{
						entidade:idEntidade,
						usuario:permissoes.usuarioSelecionado,
						currentproject:<?=$_SESSION["currentproject"]?>
					},
					success:function(retorno){
						$(".modal .modal-title").html(descricaoEntidade);
						$(".modal .modal-body p").html(retorno);
					}
				});
				$('#myModal').modal('show');
			}else{
				alert("Selecione um usuário");
			}	
		});
		function setaPermissao(obj,op){
			if (permissoes.usuarioSelecionado != ""){			
				var idEntidade 	= $(obj).parents("tr").data("entidadeid");
				var atributoId 	= $(obj).parents("tr").attr("id");
				var funcaoid	= $(obj).parents("tr").attr("funcaoid");
				var menuid		= $(obj).parents("tr").attr("menuid");
				var acoes = "";
				$(obj).parents("tr").find("input[type=checkbox]").each(function(){
					acoes += (acoes==""?"":"^") + ($(this).prop("checked")?1:0);
				});	
				$.ajax({
					type:"POST",
					url:"setapermissao.php",
					data:{
						op:op,
						entidade:idEntidade,
						usuario:permissoes.usuarioSelecionado,
						acoes:acoes,
						atributo:atributoId,
						funcao:funcaoid,
						menu:menuid,
						currentproject:<?=$_SESSION["currentproject"]?>
					},
					complete:function(){
						$.ajax({
							url:'../../index.php',
							data:{
								controller:'permissoes',
								op:'menu'
							}
						});
					}
				});
			}else{
				var acao = $(obj).prop("checked");
				$(obj).prop("checked",(acao?false:true));
				alert("Selecione um usuário");
			}
		}
		$("#btn-restaurar-permissoes").click(function(){
			bootbox.dialog({
 				message: "Tem certeza que deseja restaurar as permissões de acordo com perfil do usuário?",
  				title: "Permissões",
  				buttons: {
				    success: {
      					label: "Sim",
      					className: "btn-success",
      					callback: function() {

	      					// Seta Permissão
							var perfilusuario = permissoes.usuarioPerfil;
							var usuario = permissoes.usuarioSelecionado;
							if (perfilusuario != "")
							{
								$.ajax({
									url:"../../index.php",
									data:{
										controller:"requisicoes",
										op:"perfilusuario",
										usuario:usuario,
										perfil:perfilusuario,
										currentproject:<?=$_SESSION["currentproject"]?>
										
									},
									complete:function(){
										carregarPermissoesUsuario(usuario);
									}
								});
							}
      					}
    				},
    				danger: {
      					label: "Não",
      					className: "btn-danger",
      					callback: function() {
        					
      					}
    				}
  				}
			});
		});
		$("#btn-all-permissoes").click(function(){
			var usuario = permissoes.usuarioSelecionado;
			bootbox.dialog({
 				message: "Tem certeza que deseja setar todas as permissões ?",
  				title: "Permissões",
  				buttons: {
				    success: {
      					label: "Adicionar Todas",
      					className: "btn-success",
      					callback: function() {
							setarTodasPermissoes(usuario,1);
      					}
    				},
    				danger: {
      					label: "Retirar Todas",
      					className: "btn-danger",
      					callback: function() {
        					setarTodasPermissoes(usuario,0);
      					}
    				}
  				}
			});
		});		
		$("#permissao-btn-tela").click(function(){
			abrirListaPermissoes('tela');
		});
		$("#permissao-btn-consulta").click(function(){
			abrirListaPermissoes('consulta');
		});		
		$("#permissao-btn-relatorio").click(function(){
			abrirListaPermissoes('relatorio');
		});
		$("#permissao-btn-funcao").click(function(){
			abrirListaPermissoes('funcao');
		});
		$("#permissao-btn-menu").click(function(){
			abrirListaPermissoes('menu');
		});
		$(".btn-all-tela").click(function(){
			var opcao = $(this).data("op").split("-");
			allPermissoes(opcao[1],opcao[0],"#" + $(this).parents("table").first().attr("id"));
		});
		function abrirListaPermissoes(lista){
			$("#gp-btn-op button").removeClass("active");
			$("#permissao-btn-" + lista).addClass("active");			
			$(".panel.panel-lista-permissoes").hide();
			$("#permissao-panel-" + lista).show();	
		}
		function allPermissoes(op,panel,obj){
			if (permissoes.usuarioSelecionado == ""){
				alert('Selecione um usuário');
				return false;
			}
			bootbox.dialog({
 				message: "Tem certeza que deseja realizar esta operação?",
  				title: "Permissões",
  				buttons: {
				    success: {
      					label: "Selecionar Todos",
      					className: "btn-success",
      					callback: function() {
							$(obj + " tbody input[type=checkbox][data-op="+op+"]").each(function(){
								this.checked = true;
								setaPermissao(this,panel);
							});
      					}
    				},
    				danger: {
      					label: "Deselecionar Todos",
      					className: "btn-danger",
      					callback: function() {
							$(obj + " tbody input[type=checkbox][data-op="+op+"]").each(function(){
								this.checked = false;
								setaPermissao(this,panel);
							});        					
      					}
    				}
  				}
			});			
		}
		
		function setarTodasPermissoes(usuario,permissao){
			if (usuario != "")
			{
				$.ajax({
					url:"../../index.php",
					data:{
						controller:"requisicoes",
						op:"setar_todas_permissoes",
						auth:1,
						usuario:usuario,
						permissao:permissao,
						currentproject:<?=$_SESSION["currentproject"]?>
					},
					complete:function(){
						carregarPermissoesUsuario(usuario);
					}
				});
			}
		}
	</script>
	<div class="col-md-8">
		<div class="panel panel-default panel-lista-permissoes" id="permissao-panel-tela">
		  <div class="panel-heading">
			<h3 class="panel-title">Cadastros</h3>
		  </div>
		  <div class="panel-body">
			<table class="table table-bordered table-hover" id="lista-usuarios">
				<thead>
					<tr>
						<th width="50%">Nome</th>				
						<th width="10%">
							<center>
								<button data-op="entidade-adicionar" type="button" class="btn btn-default btn-all-tela" aria-label="Left Align">
  									<span class="fas fa-plus" aria-hidden="true"></span>
								</button>
							</center>						
						</th>
						<th width="10%">
							<center>
								<button data-op="entidade-excluir" type="button" class="btn btn-default btn-all-tela" aria-label="Left Align">
  									<span class="fas fa-minus-circle" aria-hidden="true"></span>
								</button>
							</center>
						</th>
						<th width="10%">
							<center>
								<button data-op="entidade-editar" type="button" class="btn btn-default btn-all-tela" aria-label="Left Align">
  									<span class="fas fa-edit" aria-hidden="true"></span>
								</button>
							</center>
						</th>
						<th width="10%">
							<center>
								<button data-op="entidade-visualizar" type="button" class="btn btn-default btn-all-tela" aria-label="Left Align">
  									<span class="fas fa-eye" aria-hidden="true"></span>
								</button>								
							</center>
						</th>
						<th width="10%"><center>#</center></th>					
					</tr>
				</thead>
				<tbody>
					<?php
						$sql = "SELECT id,descricao FROM td_entidade";
						$rs = $conn->query($sql);
						while ($linha = $rs->fetch()){
							$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
							echo "<tr data-entidadeid='".$linha["id"]."' data-entidadedescricao='".$descricao."'>";
							echo "		<td><small>".$descricao."</small></td>";
							echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'entidade'); data-op='adicionar' /></center></td>";
							echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'entidade'); data-op='excluir'/></center></td>";
							echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'entidade'); data-op='editar'/></center></td>";
							echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'entidade'); data-op='visualizar'/></center></td>";
							echo "		<td>";
							echo "			<center>";
							echo "				<button type='button' class='btn btn-default abrir-atributos' aria-label='Atributos' >";
							echo "					<span class='fas fa-list' aria-hidden='true'></span>";
							echo "				</button>";
							echo "			</center>";
							echo "		</td>";
							echo "	</tr>";
						}
					?>
				</tbody>
			</table>
		  </div>
		</div>
		<?php
			include 'listapermissoesconsulta.php';
			include 'listapermissoesrelatorio.php';
		?>
		<div class="panel panel-default panel-lista-permissoes" id="permissao-panel-funcao">
		  <div class="panel-heading">
			<h3 class="panel-title">Funções</h3>
		  </div>
		  <div class="panel-body">
			<table class="table table-bordered" id="lista-funcoes">
				<thead>
					<tr>
						<th width="60%">Descrição</th>				
						<th width="10%">
							<center>								
								<button data-op="funcao-funcao" type="button" class="btn btn-default btn-all-tela" aria-label="Left Align">
  									<span class="fas fa-check-square" aria-hidden="true"></span>
								</button>
							</center>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$sql = "SELECT id,descricao FROM td_funcao";
						$rs = $conn->query($sql);
						while ($linha = $rs->fetch()){
							$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
							echo "	<tr funcaoid='".$linha["id"]."'>";
							echo "		<td><small>".$descricao."</small></td>";
							echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'funcao'); id='".$linha["id"]."' data-op='funcao' /></center></td>";
							echo "	</tr>";
						}
					?>
				</tbody>	
			</table>
		  </div>
		</div>
		<div class="panel panel-default panel-lista-permissoes" id="permissao-panel-menu">
		  <div class="panel-heading">
			<h3 class="panel-title">Menu</h3>
		  </div>
		  <div class="panel-body">
			<table class="table table-bordered" id="lista-menu">
				<thead>
					<tr>
						<th width="60%">Descrição</th>				
						<th width="10%">
							<center>								
								<button data-op="menu-menu" type="button" class="btn btn-default btn-all-tela" aria-label="Left Align">
  									<span class="fas fa-check-square" aria-hidden="true"></span>
								</button>								
							</center>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$sql = "SELECT id,descricao FROM td_menu WHERE pai = 0 or pai is null or entidade = 0 or entidade is null;";
						$rs = $conn->query($sql);
						while ($linha = $rs->fetch()){
							$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
							echo "	<tr menuid='".$linha["id"]."'>";
							echo "		<td><small>".$descricao."</small></td>";
							echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'menu'); id='".$linha["id"]."' data-op='menu' /></center></td>";
							echo "	</tr>";
						}
					?>
				</tbody>	
			</table>
		  </div>
		</div>	
	</div>
</div>	
