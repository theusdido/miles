<!-- CONTABIL -->
<?php
	$modulo_name 		= 'nfse';
	$modulo_descricao	= 'NFSe';
?>
<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="<?=$modulo_name?>">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$modulo_name?>" aria-expanded="true" aria-controls="collapse<?=$modulo_name?>">
          <?=$modulo_descricao?>
        </a>
      </h4>
    </div>
    <div id="collapse<?=$modulo_name?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="<?=$modulo_name?>">
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
					<?php
						$pacotes = array(
							array(
								'nome' 			=> 'NFSe',
								'descricao' 	=> 'Nota Fiscal de Serviço Eletrônica.',
								'componente' 	=> 'nfse'
							),                            
							array(
								'nome' 			=> 'Tomador',
								'descricao' 	=> 'Tomador da NFSE.',
								'componente' 	=> 'tomador'
							),
							array(
								'nome' 			=> 'Serviço',
								'descricao' 	=> 'Serviço da NFSE.',
								'componente' 	=> 'servico'
							),
							array(
								'nome' 			=> 'Item',
								'descricao' 	=> 'Item da NFSE.',
								'componente' 	=> 'item'
                            ),
							array(
								'nome' 			=> 'Parcelas',
								'descricao' 	=> 'Parcelas da NFSE.',
								'componente' 	=> 'parcelas'
                            ),							
							array(
								'nome' 			=> 'Deduções',
								'descricao' 	=> 'Deduções da NFSe.',
								'componente' 	=> 'deducoes'
							),
							array(
								'nome' 			=> 'Intermediário',
								'descricao' 	=> 'Intermediário da NFSe.',
								'componente' 	=> 'intermediario'
							),
							array(
								'nome' 			=> 'Construção Civil',
								'descricao' 	=> 'Construção da NFSe.',
								'componente' 	=> 'construcaocivil'
							),
							array(
								'nome' 			=> 'Prestador',
								'descricao' 	=> 'Prestador da NFSe.',
								'componente' 	=> 'prestador'
							),
							array(
								'nome' 			=> 'Transportadora',
								'descricao' 	=> 'Transportadora da NFSe.',
								'componente' 	=> 'transportadora'
							)
						);

						foreach($pacotes as $p){
							echo '
							<tr>
								<td>'.$p['nome'].'</td>
								<td>'.$p['descricao'].'</td>
								<td><input type="checkbox" class="checkbox-componente" data-modulonome="'.$modulo_name.'" data-modulodescricao="'.$modulo_descricao.'" id="sistema-erp-'.$modulo_name.'-'.$p['componente'].'"></td>
							</tr>							
							';
						}
					?>
				</tbody>
			</table>
      </div>
    </div>
  </div>