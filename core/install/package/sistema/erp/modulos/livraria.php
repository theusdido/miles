<!-- LIVRARIA -->
<?php
	$moduloName 	= 'Livraria';
	$idAccordion 	= 'associacao';
	$pathModulo		= 'sistema-erp-livraria';

	echo '
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="'.$idAccordion.'">
			<h4 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-'.$idAccordion.'" aria-expanded="true" aria-controls="collapse-'.$idAccordion.'">
					'.$moduloName.'
				</a>
			</h4>
		</div>
		<div id="collapse-'.$idAccordion.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="'.$idAccordion.'">
			<div class="panel-body">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Pacote</th>
							<th>Descrição</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
		';
		
		$pacotes = array(
			array( "nome" => "Livro", "descricao" => "Cadastro de livro.", "pacote" => "livro" ),
			array( "nome" => "Autor", "descricao" => "Cadastro de Autor.", "pacote" => "autor" ),
			array( "nome" => "Editora", "descricao" => "Cadastro de Editora.", "pacote" => "editora" ),
			array( "nome" => "Gênero", "descricao" => "Cadastro de Gênero.", "pacote" => "genero" )
		);

		foreach($pacotes as $p){
			echo '
							<tr>
								<td>'.$p['nome'].'</td>
								<td>'.$p['descricao'].'</td>
								<td>
									<input type="checkbox" class="checkbox-componente" id="'.$pathModulo.'-'.$p['pacote'].'" />
								</td>
							</tr>
			';
		}
		echo '
					</tbody>
				</table>
			</div>
		</div>
	</div>
	';