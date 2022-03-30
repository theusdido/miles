 /*
    * Configurações do Website
    * @license : Teia Online.
    * @link http://www.teia.online/lib

    * Data de Criacao: 26/06/2020
    * @author: Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
var configWebSite = {
	projeto:22,
	slider:true,
	pathsystem:"requisicoes.php"
};
$(document).ready(function(){
	/*
	if (configWebSite.slider){
		$.ajax({
			url:configWebSite.pathsystem,
			data:{
				resource:"components",
				op:"td.website.slider"
			},
			complete:function(ret){
				var resposta = JSON.parse(ret.responseText);
				var modelo = $("div[tdFor=td_slider]");
				var pai = $(".slick1");
				for(s in resposta){
					var copia = modelo.clone();
					var item = resposta[s];
					copia.css("background-image","url('http://localhost/miles/sistema/projects/22/arquivos/imagem-48-"+item.id+".jpg')");
					pai.append(copia);
				}
				modelo.remove();
				$('.slick1').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					fade: true,
					dots: false,
					appendDots: $('.wrap-slick1-dots'),
					dotsClass:'slick1-dots',
					infinite: true,
					autoplay: true,
					autoplaySpeed: 6000,
					arrows: true,
					appendArrows: $('.wrap-slick1'),
					prevArrow:'<button class="arrow-slick1 prev-slick1"><i class="fa  fa-angle-left" aria-hidden="true"></i></button>',
					nextArrow:'<button class="arrow-slick1 next-slick1"><i class="fa  fa-angle-right" aria-hidden="true"></i></button>',  
				});
			}
		});
	}
	*/
});