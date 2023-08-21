var permissoes_guia = {
    criarbanco:true,
    instalarsistema:false,
    configurarpacotes:false,
    checkout:false
}
var check_guia = {};
$(document).ready(function(){
    getStatusGuia();
});

function getStatusGuia(){
    $.ajax({
        url:session.urlmiles,
        data:{
            _controller:"install/guia",
            _op:"status"
        },
        success:function(_res){
            try{
                check_guia = JSON.parse(_res);
                if (check_guia.installed){
                    let all_fields = '#host,#base,#porta,#usuario,#senha,#tipo';
                    $(all_fields).attr('disabled',true);
                    $(all_fields).attr('readonly',true);
                    $('#btn-criarbanco').remove();
                }else{
                    $('#host')              .val('localhost');
                    $('#base')              .val('');
                    $('#porta')             .val('3306');
                    $('#usuario')           .val('root');
                    $('#senha')             .val('');
                }                
                $('#guia-base')         .attr('src',check_guia.check_criarbase);
                $('#guia-instalacao')   .attr('src',check_guia.check_instalacaosistema);
                $('#guia-pacote')       .attr('src',check_guia.check_pacoteconfigurado);

                if (check_guia.database){
                    $('#host')              .val(check_guia.database.host);
                    $('#base')              .val(check_guia.database.base);
                    $('#porta')             .val(check_guia.database.porta);
                    $('#usuario')           .val(check_guia.database.usuario);
                    $('#senha')             .val(check_guia.database.senha);
                }
                permissoesGuia();
            }catch(e){
                console.log(JSON.parse(_res));
            }
        }
    });
}

$('#menu-guia a').click(function(event){
    event.preventDefault();
    event.stopPropagation();
    $('#menu-guia a').removeClass('guia-current');
    if ($(this).attr('data-habilitado')){
        $(this).addClass('guia-current');
        loadContent($(this).data('href'));
    }
});

function permissoesGuia(){
    $('#menu-guia [data-href="criarbase"]').attr('data-habilitado',true);
    $('#menu-guia a').each(function(){
        let habilitado = false;
        let item = $(this);
        if (item.find('img').attr('src').indexOf('check.gif') > 0){
            habilitado = true;            
        }
        switch(item.data('href')){
            case 'criarbase':
                if (habilitado){
                    permissoes_guia.instalarsistema = true;
                    $('#menu-guia [data-href="setup"]').attr('data-habilitado',true);
                }else{
                    desabilitarGuia('setup');
                }
            break;
            case 'setup':
                if (habilitado){
                    permissoes_guia.configurarpacotes = true;
                    $('#menu-guia [data-href="package"]').attr('data-habilitado',true);
                    $('.guia-access').show();
                }else{
                    desabilitarGuia('package');
                }
            break;
        }
    });
}

function desabilitarGuia(_guia){
    $('#menu-guia [data-href="'+_guia+'"]').addClass('guia-disabled');
}

function habilitarGuia(_guia){
    $('#menu-guia [data-href="'+_guia+'"]').removeClass('guia-disabled');
    switch(_guia){
        case 'criarbase':
            permissoes_guia.criarbanco = true;
        break;
        case 'setup':
            permissoes_guia.instalarsistema = true;
        break;
        case 'package':
            permissoes_guia.configurarpacotes = true;
        break;
    }    
}

function setCurrentGuia(_guia){
    $('#menu-guia a').removeClass('guia-current');
    habilitarGuia(_guia);
    $('#menu-guia a[data-href="'+_guia+'"]').addClass('guia-current');
    loadContent(_guia);
}

function loadContent(_page){
    $('#conteudo-instalacao').load(session.urlmiles + '?controller=page&page=install/' + _page);
}