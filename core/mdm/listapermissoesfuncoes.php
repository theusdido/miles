<div class="col-md-12">	
	<ul class="list-group">	
		<?php
			include 'conexao.php';
			
			$sql = "SELECT id,descricao FROM td_funcao";
			$query = $conn->query($sql);
			While ($linha = $query->fetch()){
				$descricao = utf8_encode($linha["descricao"]);
	  			echo "	<li href='#' class='list-group-item'>";
				echo "		<button class='btn btn-default add-usuario-funcao' type='button' id='".$linha["id"]."'>";
				echo "			<span class='fas fa-plus-circle'></span>";
				echo " 	</button>";
				echo "		<small class='descricao-funcao'>".$descricao."</small>";
				echo "		<table class='table table-hover table-condensed lista_usuarios_funcoes' id='t-funcao-".$linha["id"]."'>";
				echo "			<tbody>";

				$sqlusuario = "SELECT a.id,b.nome,b.id usuario FROM td_funcaopermissoes a,usuario b WHERE funcao = ".$linha["id"]." AND a.usuario = b.id AND permissao = 1";
				$queryusuario = $conn->query($sqlusuario);
				While ($linhausuario = $queryusuario->fetch()){
					$nome = utf8_encode($linhausuario["nome"]);
					echo "			<tr>";
					echo "				<td width='90%'><small class='descricao-usuario'>".$nome."</small></td>";
					echo "				<td width='10%' align='right'>";
					echo "					<button type='button' class='btn btn-default excluir-usuario-funcao' aria-label='Excluir Usuário Função' data-usuario='".$linhausuario["usuario"]."' data-funcao='".$linha["id"]."'>";
					echo "						<span class='fas fa-trash-alt' aria-hidden='true'></span>";
					echo "					</button>";
					echo "				</td>";
					echo "			</tr>";
				}
				echo "			</tbody>";
				echo "		</table>";
	  			echo "	</li>";
			}
		?>
	</ul>
</div>
<script type="text/javascript">
	$(".excluir-usuario-funcao").click(function(){
		var usuario = $(this).data("usuario");
		var funcao = $(this).data("funcao");
		var obj = $(this);
		excluirPermissao(obj,usuario,funcao);
	});
	$(".add-usuario-funcao").click(function(){
		var funcao = $(this).attr("id");
		$.ajax({
			url:"selecionarusuariospermissoes.php",
			data:{
				funcao:funcao
			},
			success:function(retorno){

				$(".modal .modal-title").html("Selecione o Usuário");
				$(".modal .modal-body p").html(retorno);
			}
		});
		$('#myModal').modal('show');
	});
	function excluirPermissao(obj,usuario,funcao){
		$.ajax({
			url:"excluirpermissaousuario.php",
			data:{
				usuario:usuario,
				funcao:funcao
			},
			success:function(retorno){
				if (retorno==1){
					obj.parents("tr").remove();
				}else{
					alert("Erro ao remover permissão: " + retorno);
				}
			}
		});
	}
</script>