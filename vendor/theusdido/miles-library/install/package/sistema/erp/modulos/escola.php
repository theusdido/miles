<!-- ESCOLA -->
<?php
	$moduloName 	= 'Escola';
	$moduloID 		= 'escola';
	$pathModulo		= 'sistema-erp-escola';

	echo '
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="'.$moduloID.'">
			<h4 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-'.$moduloID.'" aria-expanded="true" aria-controls="collapse-'.$moduloID.'">
					'.$moduloName.'
				</a>
			</h4>
		</div>
		<div id="collapse-'.$moduloID.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="'.$moduloID.'">
			<div class="panel-body">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Pacote</th>
							<th>Descrição</th>							
							<th>Registro</th>
							<th>Instalar</th>
						</tr>
						<tr class="tr-checkbox-all">
							<th>&nbsp;</th>
							<th>&nbsp;</th>
							<th class="text-center">
								<input type="checkbox" id="select-registro-all" class="checkbox-all" data-modulo="'.$moduloID.'" />
							</th>
							<th class="text-center">
								<input type="checkbox" id="select-modulo-all" class="checkbox-all checkbox-modulo-all" data-modulo="'.$moduloID.'" />
							</th>
					</thead>
					<tbody>
		';
		
		$pacotes = array(
			array( "nome" => "Turma", "descricao" => "Cadastro genérico de turma.", "pacote" => "turma" ),
			array( "nome" => "Grupo da Turma", "descricao" => "Cadastro genérico de divisão de turma em grupo.", "pacote" => "turmagrupo" ),
			array( "nome" => "Aluno", "descricao" => "Cadastro genérico de aluno.", "pacote" => "aluno" ),
			array( "nome" => "Professor", "descricao" => "Cadastro genérico de professor.", "pacote" => "professor" ),
			array( "nome" => "Curso", "descricao" => "Cadastro genérico de curso.", "pacote" => "curso" ),
			array( "nome" => "Unidade Curricular", "descricao" => "Cadastro de unidade curricular.", "pacote" => "unidadecurricular" ),
			array( "nome" => "Avaliação", "descricao" => "Cadastro genérico de avaliações.", "pacote" => "avaliacao" ),
			array( "nome" => "Avaliação da Turma", "descricao" => "Vincular avaliações para cada turma.", "pacote" => "avaliacaoturma" ),
			array( "nome" => "Avaliação do Aluno", "descricao" => "Vincular avaliações para cada aluno.", "pacote" => "avaliacaoaluno" ),
			array( "nome" => "Atendimento", "descricao" => "Registro de atendimento ao aluno.", "pacote" => "atendimento" ),
			array( "nome" => "Aula", "descricao" => "Registro de aula.", "pacote" => "aula" ),
			array( "nome" => "Chamada", "descricao" => "Chamada diária de aula.", "pacote" => "chamada" ),
			array( "nome" => "Planejamento", "descricao" => "Planejamento de aula.", "pacote" => "planejamento" ),
			array( "nome" => "Atividade", "descricao" => "Atividade em aula.", "pacote" => "atividade" ),
			array( "nome" => "Competência", "descricao" => "Competência a ser desenvolvida na unidade curricular.", "pacote" => "competencia" ),
			array( "nome" => "Conteúdo", "descricao" => "Conteúdo a ser ministrado em aula.", "pacote" => "conteudo" ),
			array( "nome" => "Critério de Avaliação", "descricao" => "Critério de avaliação.", "pacote" => "criterioavaliacao" ),
			array( "nome" => "Habilidade", "descricao" => "Habilidade a ser desenvolvida na unidade curricular.", "pacote" => "habilidade" ),
			array( "nome" => "Instrumento de Avaliação", "descricao" => "Instrumento de avaliação.", "pacote" => "instrumentoavaliacao" ),
			array( "nome" => "Metodologia", "descricao" => "Metodologia de ensino.", "pacote" => "metodologia" ),
			array( "nome" => "Objetivo Específico", "descricao" => "Objetivo específico da unidade curricular.", "pacote" => "objetivoespecifico" ),
			array( "nome" => "Prática Pedagógica", "descricao" => "Prática pedagógica.", "pacote" => "praticapedagogica" ),
			array( "nome" => "Recurso", "descricao" => "Recurso a ser utilizado em aula.", "pacote" => "recurso" ),
			array( "nome" => "Tempo Atividade", "descricao" => "Tempo no qual o aluno desenvolvera a atividade.", "pacote" => "tempoatividade" ),
			array( "nome" => "Critério de Avaliação", "descricao" => "Critério de avaliação.", "pacote" => "criterioavaliacao" )
		);

		foreach($pacotes as $p){
			echo '
							<tr>
								<td>'.$p['nome'].'</td>
								<td>'.$p['descricao'].'</td>
								<td class="text-center">
									<input 
										type="checkbox" 
										class="checkbox-registro" 
										data-entidaderegistro="'.$p['pacote'].'"
										data-path="'.$pathModulo.'"
									/>
								</td>
								<td class="text-center">
									<input 
										type="checkbox" 
										class="checkbox-componente check-modulo-'.$moduloID.'" 
										id="'.$pathModulo.'-'.$p['pacote'].'" 
										data-modulonome="'.$p['pacote'].'" 
										data-modulodescricao="'.$p["descricao"].'" 
									/>
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
