	<ul class="list-group">
	<?php
		include 'conexao.php';
		$sql = "SELECT id,descricao FROM td_grupousuario";
		$query = $conn->query($sql);
		while($linha = $query->fetch()){
			$descricao = utf8_encode($linha["descricao"]);
			echo "	<li href='#' class='list-group-item'>";
			echo "		<div class='cabecalho-lista-usuario' id='".$linha["id"]."'>";
			echo "			<span class='fas fa-folder' aria-hidden='true'></span>";
			echo "				".$descricao;
			echo "		</div>";
			echo "		<ul class='lista-usuario' id='lista-usuario-".$linha["id"]."'>";
			
			$sqlusuarios = "SELECT a.id,a.nome FROM td_usuario a WHERE a.grupousuario = ".$linha["id"]." AND NOT EXISTS (SELECT 1 FROM td_funcaopermissoes b WHERE a.id = b.usuario AND b.funcao = ".$_GET["funcao"]." AND b.permissao = 1 )";
			$queryusuarios = $conn->query($sqlusuarios);
			if ($queryusuarios->rowcount() <= 0){
				echo "<li><b>Nenhum Usuário</b></li>";
			}else{
				While ($linhausuarios = $queryusuarios->fetch()){
					$nome = utf8_encode($linhausuarios["nome"]);
					echo "<li><a href='#' class='usuario-na-lista-porfuncao' id='".$linhausuarios["id"]."' funcao='".$_GET["funcao"]."'>".$nome."</a></li>";
				}				
			}

			echo "		</ul>";
			echo "	</li>";
		}
		?>
	</ul>
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
		$(".usuario-na-lista-porfuncao").click(function(){
			var usuario = $(this).attr("id");
			var funcao = $(this).attr("funcao");
			var nomeUsuario = $(this).html();
			var obj = $(this);
			$.ajax({
				url:"addpermissaousuario.php",
				data:{
					usuario:usuario,
					funcao:funcao
				},
				success:function(retorno){
					if (retorno==1){
						$("#t-funcao-" + funcao + " tbody").append("<tr><td width='90%'><small class='descricao-usuario'>"+nomeUsuario+"</small></td><td width='10%' align='right'><button data-funcao='"+funcao+"' data-usuario='"+usuario+"' aria-label='Excluir Usuário Função' class='btn btn-default excluir-usuario-funcao' type='button' onclick='excluirPermissao($(this),"+usuario+","+funcao+");'><span aria-hidden='true' class='fas fa-trash-alt'></span></button></td></tr>");
						$('#myModal').modal('hide');
					}else{
						alert("Erro ao remover permissão: " + retorno);
					}
				}
			});			
		});
	</script>