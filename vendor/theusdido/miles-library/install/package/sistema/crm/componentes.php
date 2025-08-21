<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

 <!-- IMOBILIARIA --> 
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="imobiliaria">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseImobiliaria" aria-expanded="true" aria-controls="collapseImobiliaria">
          Imobiliaria
        </a>
      </h4>
    </div>
    <div id="collapseImobiliaria" class="panel-collapse collapse" role="tabpanel" aria-labelledby="imobiliaria">
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
						<td>Agenciador</td>
						<td>Cadastro de agenciador</td>
						<td><input type="checkbox" class="checkbox-componente" id="sistema-crm-imobiliaria-agenciador"></td>
					</tr>
					<tr>
						<td>Corretor</td>
						<td>Cadastro de corretor</td>
						<td><input type="checkbox" class="checkbox-componente" id="sistema-crm-imobiliaria-corretor"></td>
					</tr>
					<tr>
						<td>Lista de Interesse de Imóveis</td>
						<td>Tela de Lista de Interesse de Imóveis</td>
						<td><input type="checkbox" class="checkbox-componente" id="sistema-crm-imobiliaria-listainteresseimovel"></td>
					</tr>
					<tr>
						<td>Manutenção</td>
						<td>Manutenção de Imóvel</td>
						<td><input type="checkbox" class="checkbox-componente" id="sistema-crm-imobiliaria-manutencao"></td>
					</tr>
					<tr>
						<td>Negociação</td>
						<td>Negociação de Imóvel</td>
						<td><input type="checkbox" class="checkbox-componente" id="sistema-crm-imobiliaria-negociacao"></td>
					</tr>
					<tr>
						<td>Newsletter</td>
						<td>Cadastro de Newsletter</td>
						<td><input type="checkbox" class="checkbox-componente" id="sistema-crm-imobiliaria-newsletter"></td>
					</tr>
					<tr>
						<td>Registro de Atendimento</td>
						<td>Registro de Atendimento</td>
						<td><input type="checkbox" class="checkbox-componente" id="sistema-crm-imobiliaria-registroatendimento"></td>
					</tr>
				</tbody>
			</table>
      </div>
    </div>
  </div>

</div>

<script>
	$(".checkbox-componente").click(function(){
		var componenteNome = $(this).attr("id");
		if (this.checked){
			componentes.push(componenteNome);
		}else{
			excluirComponenteLista(componenteNome);
		}
	});
</script>