<div class="list-group">
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Perfil</h4>
    <p class="list-group-item-text">Este componente cria uma página de perfil.</p>
	<input type="checkbox" class="checkbox-componente" id="website-blog-perfil">
  </a> 
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Form E-Mail</h4>
    <p class="list-group-item-text">Este componente cria uma página de contato com formulário de E-Mail.</p>
	<input type="checkbox" class="checkbox-componente" id="website-blog-formmail">
  </a>
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Post</h4>
    <p class="list-group-item-text">Cria uma página de publicações.</p>
	<input type="checkbox" class="checkbox-componente" id="website-blog-post">
  </a>
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Tags Ocorrência</h4>
    <p class="list-group-item-text">Cria entidade para armazenar a quantidade de ocorrências de uma tag.</p>
	<input type="checkbox" class="checkbox-componente" id="website-blog-tagsocorrencia">
  </a>
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