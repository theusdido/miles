<!-- CONTABIL -->
<?php
	$modulo_name 		= 'contabil';
	$modulo_descricao	= 'Contábil';
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
								'nome' 			=> 'Centro de Custo',
								'descricao' 	=> 'Item mais amplo para agrupar pagamento.',
								'componente' 	=> 'centrocusto'
							),
							array(
								'nome' 			=> 'Elemento de Custo',
								'descricao' 	=> 'Item para agrupar pagamento.',
								'componente' 	=> 'elementocusto'
							),
							array(
								'nome' 			=> 'Fonte de Renda',
								'descricao' 	=> 'Cadastro de Fontes de Renda.',
								'componente' 	=> 'fonterenda'
							),
							array(
								'nome' 			=> 'NFSe',
								'descricao' 	=> 'Nota Fiscal de Serviço Eletrônica.',
								'componente' 	=> 'nfse'
							),
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