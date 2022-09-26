<table class="table table-bordered table-hover" id="lista-atributos">
	<thead>
		<tr>
			<th width="80%">Nome</th>
			<!--				
			<th width="10%"><center><span class="fas fa-plus" aria-hidden="true"></span></center></th>
			<th width="10%"><center><span class="fas fa-minus" aria-hidden="true"></span></center></th>
			-->
			<th width="10%">
				<center>					
					<button data-op="atributo-editar" type="button" class="btn btn-default btn-all-tela-atributo" aria-label="Left Align">
						<span class="fas fa-edit" aria-hidden="true"></span>
					</button>
				</center>
			</th>
			<th width="10%">
				<center>					
					<button data-op="atributo-visualizar" type="button" class="btn btn-default btn-all-tela-atributo" aria-label="Left Align">
						<span class="fas fa-eye" aria-hidden="true"></span>
					</button>					
				</center>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php
			include 'conexao.php';
			
			$entidadeID =  $_GET["entidade"];
			$sql = "SELECT id,descricao,entidade FROM td_atributo WHERE entidade = " .$entidadeID;
			$query = $conn->query($sql);
			While ($linha = $query->fetch()){
				
				$sqlpermissao = "SELECT * FROM td_atributopermissoes WHERE atributo = ".$linha["id"]." AND usuario = ".$_GET["usuario"];
				$querypermissao = $conn->query($sqlpermissao);
				$linhapermissao = $querypermissao->fetch();
				$descricao = utf8_encode($linha["descricao"]);
				echo "<tr data-entidadeid='".$linha["entidade"]."' id='".$linha["id"]."'>";
				echo "		<td><small>".$descricao."</small></td>";
				#echo"		<td><center><input type='checkbox' onclick=setaPermissao(this,'atributo'); "_$S(rspermissao.inserir=1:"checked",1:"")_" /></center></td>";
				#echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'atributo'); "_$S(rspermissao.excluir=1:"checked",1:"")_" /></center></td>";
				echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'atributo'); ".($linhapermissao["editar"]==1?"checked":"")." data-op='editar'/></center></td>";
				echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'atributo'); ".($linhapermissao["visualizar"]==1?"checked":"")." data-op='visualizar'/></center></td>";
				echo "	</tr>";
			}
		?>
	</tbody>	
</table>
<script type="text/javascript">
	$(".btn-all-tela-atributo").click(function(){
		var opcao = $(this).data("op").split("-");
		allPermissoes(opcao[1],opcao[0],"#" + $(this).parents("table").first().attr("id"));
	});
</script>