<div class="list-group">
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Usuário</h4>
    <p class="list-group-item-text">Este componente cria um cadastro de usuário para a rede social .</p>
	<input type="checkbox" class="checkbox-componente" id="website-redesocial-usuario">
  </a>
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Post</h4>
    <p class="list-group-item-text">Cria uma página de publicações.</p>
	<input type="checkbox" class="checkbox-componente" id="website-redesocial-post">
  </a>
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Like</h4>
    <p class="list-group-item-text">Este componente ativa possibilidade de dar like.</p>
	<input type="checkbox" class="checkbox-componente" id="website-redesocial-like">
  </a>
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Match</h4>
    <p class="list-group-item-text">Este componente ativa possibilidade de dar match no perfil</p>
	<input type="checkbox" class="checkbox-componente" id="website-redesocial-match">
  </a>
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Comentário</h4>
    <p class="list-group-item-text">Este componente ativa possibilidade de dar um comentário</p>
	<input type="checkbox" class="checkbox-componente" id="website-redesocial-comentario">
  </a>
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Espécie</h4>
    <p class="list-group-item-text">Este componente cria uma entidade de espécie. Usuado para rede social para pets.</p>
	<input type="checkbox" class="checkbox-componente" id="website-redesocial-especie">
  </a>
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Perfil</h4>
    <p class="list-group-item-text">Este componente cria uma entidade de perfil.</p>
	<input type="checkbox" class="checkbox-componente" id="website-redesocial-perfil">
  </a>
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Álbum de Fotos</h4>
    <p class="list-group-item-text">Este componente cria uma entidade de album de fotos.</p>
	<input type="checkbox" class="checkbox-componente" id="website-redesocial-album">
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