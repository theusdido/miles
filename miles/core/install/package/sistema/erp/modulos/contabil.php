<!-- CONTABIL --> 
<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="contabil">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseContabil" aria-expanded="true" aria-controls="collapseContabil">
          Contabil
        </a>
      </h4>
    </div>
    <div id="collapseContabil" class="panel-collapse collapse" role="tabpanel" aria-labelledby="contabil">
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
					<tr>
						<td>Centro de Custo</td>
						<td>Item mais amplo para agrupar pagamento</td>
						<td><input type="checkbox" class="checkbox-componente" id="sistema-erp-contabil-centrocusto"></td>
					</tr>
					<tr>
						<td>Elemento de Custo</td>
						<td>Item para agrupar pagamento</td>
						<td><input type="checkbox" class="checkbox-componente" id="sistema-erp-contabil-elementocusto"></td>
					</tr>		
					<tr>
						<td>Fonte de Renda</td>
						<td>Cadastro de Fontes de Renda</td>
						<td><input type="checkbox" class="checkbox-componente" id="sistema-erp-contabil-fonterenda"></td>
					</tr>									
				</tbody>	
			</table>
      </div>
    </div>
  </div>