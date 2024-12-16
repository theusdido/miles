 /*
    * Configurações do E-Commerce
    * @license : Teia Online.
    * @link http://www.teia.online/lib

    * Data de Criacao: 27/06/2020
    * @author: Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
function tdEcommerce(){}

tdEcommerce.prototype.categoria = function(){
	$.ajax({
		url:"requisicoes.php",
		data:{
			resource:"components",
			op:"td.ecommerce.categoria"
		},
		complete:function(ret){
			var retorno = JSON.parse(ret.responseText);
			var modelo = $(document.querySelectorAll('[tdFor=td_ecommerce_categoria]'));
			if (modelo.length > 0){
				var pai = modelo.parents().first();
				for(c in retorno){
					var item = retorno[c];
					var modelostring = modelo.get(0).outerHTML;
					var regexpmodelostring = '';
					var regexp = new RegExp(/{{[a-z]+}}/,'ig');					
					var binds = modelostring.match(regexp);					
					for (b in binds){
						regexpmodelostring += modelostring.replace(regexp,item[binds[b].replace(/[{{]?[}}]?/ig,'')]);
					}
					if (binds == null){
						var copia = $(modelostring);
					}else{
						var copia = $(regexpmodelostring);
					}
					pai.append(copia);
				}			
				modelo.remove();
			}
		}
	});
}
tdEcommerce.prototype.produtohome = function(){
	$.ajax({
		url:"requisicoes.php",
		data:{
			resource:"components",
			op:"td.ecommerce.produtohome",
		},
		complete:function(ret){
			var retorno = JSON.parse(ret.responseText);
			td.bind('[tdFor=td_ecommerce_produtohome]',retorno);
		}
	});
}
tdEcommerce.prototype.produtodetalhe = function(produto){
	$.ajax({
		url:"requisicoes.php",
		data:{
			resource:"pages",
			op:"26.produtodetalhe",
			id:produto
		},
		complete:function(ret){
			var retorno = JSON.parse(ret.responseText);
			td.loadpage('[tdContent=main]',retorno.html);
		}
	});
}
var tdecommerce = new tdEcommerce();
tdecommerce.categoria();
tdecommerce.produtohome();