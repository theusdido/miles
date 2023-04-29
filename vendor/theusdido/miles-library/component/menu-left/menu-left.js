$('.nav-toggle').click(function(e) {
  e.preventDefault();
  recolherSubMenu();
});

function loadSubMenu(menu_principal = 0){  
  $.ajax({
    url:config.urlmenu,
    dataType:'json',
    data:{
      op:'submenu',
      menuprincipal:menu_principal
    },
    complete:function(ret){
      let res = ret.responseJSON;
      if (res.filhos.length > 0){
        toggleSubMenu();
        $('#menuleft-titulo').html(res.descricao);
        addFilhos(res.filhos);
      }
    }
  });

}

function addFilhos(filhos){    
  let menu = $('#nav-menu-left .menu-dropdown');

  filhos.forEach(function(m){
    let linkpath 	= session.folderprojectfiles + m.link;
    let li    = $('<li>');
    let a     = $("<a target='"+(m.target == ""?"_self":m.target)+"' data-path='"+linkpath+"' data-id='"+m.id+"' data-target='#conteudoprincipal' href='"+(m.target == ""?"#":m.link)+"' data-tipomenu='"+m.tipomenu+"'>"+m.descricao+"</a>");
    let span  = $('<span class="icon">');
    let icon  = $('<i class="fa fa-arrow-right">');
    
    a.click(function(dados_menu){
      carregar(linkpath,'#conteudoprincipal',function(){        
        if (dados_menu == undefined || dados_menu == ''){
           console.warn('Dados do menu não foram carregados.');
           console.log('## Tente recarregar a página com CTRL + F5. ##');
        }
        carregarScriptCRUD(m.tipomenu,m.entidade);
      });
    });

    span.append(icon);
    a.html(m.descricao);
    li.append(a);
    li.append(span);
    menu.append(li);
  });
}

function expandirSubMenu(){
  is_opened_submenu = true;  
  $(".nav-toggle").addClass("active");
  moverSubMenu(250);
}

function recolherSubMenu(){
  is_opened_submenu = false;
  moverSubMenu(50);
  $(".nav-toggle").removeClass('active');
}

function moverSubMenu(left,vel = 400){  
  $("html").toggleClass("openNav");
  $( '#conteudoprincipal' ).animate({
    marginLeft: left + "px"
  }, vel );
}

$('#nav-menu-left').mouseover(function(){
  if (!is_opened_submenu){
    expandirSubMenu();
  }
});

function toggleSubMenu(){
  if (is_opened_submenu){
    recolherSubMenu();
  }else{
    expandirSubMenu();
  }
}

function closeSubMenu(){
  is_opened_submenu = false;
  moverSubMenu(0,0);
  $('#menulateralesquerdoprincipal').hide();
}