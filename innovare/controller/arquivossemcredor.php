<?php
	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);
	
	$titulo = tdClass::Criar("p");
	$titulo->class = "titulo-pagina";
	$titulo->add(utf8_decode("Arquivos Sem Credor"));
	$titulo->mostrar();
	
?>	
<table class="table table-hover">
	<thead>
		<tr>
			<th width="25%">Arquivo</th>
			<th width="10%">Processo</th>
			<th width="25%">FA / RE / IN</th>
			<th width="25%">Credor</th>
			<th width="10%">Origem</th>
			<th width="5%">Associar</th>
		</tr>
	</thead>
	<tbody>
		<?php
			if ($conn = Transacao::Get()){
				$processoOptions = "<option>--Selecione--</option>";

				// Processo				
				$sqlProcesso = "SELECT id,numeroprocesso FROM td_processo ORDER BY id DESC";
				$queryProcesso = $conn->query($sqlProcesso);
				While ($linhaProcesso = $queryProcesso->fetch()){
					$processoOptions .= "<option value='".$linhaProcesso["id"]."'>".$linhaProcesso["numeroprocesso"]."</option>";
				}
				
				$sql = "SELECT a.descricao,a.id,a.origem,a.nome,a.td_relacaocredores FROM td_arquivos_credor a WHERE NOT EXISTS (SELECT 1 FROM td_relacaocredores b WHERE a.td_relacaocredores = b.id) ORDER BY a.origem;";
				$query = $conn->query($sql);
				while ($linha = $query->fetch()){
					$descricao = utf8_encode($linha["descricao"]);
					if ($descricao == "" || $descricao == null) continue; 
					$processoLista = "<select id='processo_".$linha["id"]."' name='processo_".$linha["id"]."' data-linha='".$linha["id"]."' class='form-control'>";
					$processoLista .= $processoOptions;
					$processoLista .= "</select>";
					
					// LINK
					$link = '<a href="http://innovareadministradora.com.br/site/enviodocumentos/arquivos_temp/' . $linha["nome"] . '" target=_blank>'.$descricao.'</a>';
					
					// FA - RE - IN
					$fareinLista = "<select id='farein_".$linha["id"]."' name='farein_".$linha["id"]."' class='form-control'></select>";

					// Credor
					$credorLista = "<select id='credor_".$linha["id"]."' name='credor_".$linha["id"]."' class='form-control'></select>";
					
					switch($linha["origem"]){
						case 1:
							$origem = "Habilitação";
						break;
						case 2:
							$origem = "Divergência";
						break;
						case 3:
							$origem = "Assembleia";
						break;
					}
					
					$btnAssociar = '
						<center>
							<button type="button" class="btn btn-default" aria-label="Associar">
							  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							</button>
						</center>	
					';
					echo '	<tr>
								<td>'.$link.' - [ '.$linha["td_relacaocredores"].' ]</td>
								<td>'.$processoLista.'</td>
								<td>'.$fareinLista.'</td>
								<td>'.$credorLista.'</td>
								<td>'.$origem.'</td>
								<td>'.$btnAssociar.'</td>
							</tr>
					';
				}
			}
		?>		
	</tbody>
</table>