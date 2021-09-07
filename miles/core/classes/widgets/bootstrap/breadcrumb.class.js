function Breadcrumb (objtext){
	this.elemento;
	this.construct(objtext);
}
Breadcrumb.prototype.construct = function(objtext){
	this.elemento = $(".breadcrumb[id=" + objtext + "]");
}			
Breadcrumb.prototype.add = function(descricao,link,nivel=1){

	if (nivel == 1){
		this.goHome();
	}
		
	var li = $("<li>");
	var a = $("<a href='#' onclick=carregar('"+link+"'','#conteudoprincipal');>"+descricao+"</a>");
	li.append(a);
	this.elemento.append(li);
	this.setActive();


}
Breadcrumb.prototype.setActive = function(){
	this.elemento.find("li").removeClass("active");
	this.elemento.find("li").last().addClass("active");
	var descricao = this.elemento.find("li").last().find("a").html();
	this.elemento.find("li").last().find("a").remove();
	this.elemento.find("li").last().html(descricao);
	this.setHome();
}
Breadcrumb.prototype.goHome = function(){
	this.elemento.find("li").each(function(){
		if ($(this).attr("id") != "li-home-bc"){
			$(this).remove();
		}
	});
}
Breadcrumb.prototype.setHome = function(){
	var aHome = $("<a href='#' onclick='goHome();'>Home</a>")
	$("#li-home-bc").html("");
	$("#li-home-bc").append(aHome);
}