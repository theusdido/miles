$('.nav-toggle').click(function(e) {
  
  e.preventDefault();
  $("html").toggleClass("openNav");
  $(".nav-toggle").toggleClass("active");  

  if ($('.hamburger.active').length > 0){
    $('#conteudoprincipal').css('margin-left','250px');
  }else{
    $('#conteudoprincipal').css('margin-left','50px');
  }
  
});