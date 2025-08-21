<div class="list-group">
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Configurações</h4>
    <p class="list-group-item-text">Este componente cria uma página de configurações do Website.</p>
	<input type="checkbox" class="checkbox-componente" id="website-geral-configuracoes" checked>
  </a>
</div>
<div class="list-group">
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Menu no Topo</h4>
    <p class="list-group-item-text">Cria um menu no topo do site.</p>
	<input type="checkbox" class="checkbox-componente" id="website-geral-menutopo">
  </a>
</div>
<div class="list-group">
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Menu Principal</h4>
    <p class="list-group-item-text">Cria um menu na home do site.</p>
	<input type="checkbox" class="checkbox-componente" id="website-geral-menuprincipal">
  </a>
</div>
<div class="list-group">
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Slider</h4>
    <p class="list-group-item-text">Componente que passa informações em forma de slide na home do site.</p>
	<input type="checkbox" class="checkbox-componente" id="website-geral-slider">
  </a>
</div>
<div class="list-group">
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Newsletter</h4>
    <p class="list-group-item-text">Cria um campo para usuários do site deixarem seu nome e e-mail.</p>
	<input type="checkbox" class="checkbox-componente" id="website-geral-newsletter">
  </a>
</div>
<div class="list-group">
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Menu Rodapé</h4>
    <p class="list-group-item-text">Exibe menu na parte inferior do site.</p>
	<input type="checkbox" class="checkbox-componente" id="website-geral-menurodape">
  </a>
</div>
<div class="list-group">
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Rodapé</h4>
    <p class="list-group-item-text">Exibe informações personalizadas na parte inferior do site.</p>
	<input type="checkbox" class="checkbox-componente" id="website-geral-rodape">
  </a>
</div>
<div class="list-group">
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Blog</h4>
    <p class="list-group-item-text">Cria uma entidade de blog.</p>
	<input type="checkbox" class="checkbox-componente" id="website-geral-blog">
  </a>
</div>
<div class="list-group">
  <a href="#" class="list-group-item">
    <h4 class="list-group-item-heading">Quem Somos</h4>
    <p class="list-group-item-text">Cria uma entidade de Quem Somos.</p>
	<input type="checkbox" class="checkbox-componente" id="website-geral-quemsomos">
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