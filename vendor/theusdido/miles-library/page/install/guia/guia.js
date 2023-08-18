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
        success:function(res){
            try{                
                let _res = JSON.parse(res);
                if (_res.installed){
                    let all_fields = '#host,#base,#porta,#usuario,#senha,#tipo';
                    $(all_fields).attr('disabled',true);
                    $(all_fields).attr('readonly',true);
                    $('#btn-criarbanco').remove();
                }
                check_guia = _res;
                $('#guia-base')         .attr('src',_res.check_criarbase);
                $('#guia-instalacao')   .attr('src',_res.check_instalacaosistema);
                $('#guia-pacote')       .attr('src',_res.check_pacoteconfigurado);

                if (_res.database){
                    $('#host')              .val(_res.database.host);
                    $('#base')              .val(_res.database.base);
                    $('#porta')             .val(_res.database.porta);
                    $('#usuario')           .val(_res.database.usuario);
                    $('#senha')             .val(_res.database.senha);
                }
                permissoesGuia();
            }catch(e){
                console.log(JSON.parse(res));
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
        $('#conteudo-instalacao').load(session.urlmiles + '?controller=page&page=install/' + $(this).data('href'));
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
}