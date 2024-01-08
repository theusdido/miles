var _conceito               = '';
var _conceito_id            = 0;
var termo_buscar_cadastro   = '';

var _entidade           = 0;
var _entidade_obj       = {};
var _entidade_display   = '';
var _entidade_nome      = '';

$(document).ready(function(){
    $('#menu-topo').load(session.urlmiles + '?controller=page&page=mdm/menu-topo');
    atualizarMDMFile();
});

function loadMDMContent(_page_mdm){
    $('#mdm-conteudo').load(session.urlmiles + '?controller=page&page=mdm/' + _page_mdm);
}

function atualizarMDMFile(){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/javascriptfile'
        }
    });
}

function mdmToastMessage(message,class_name = 'td-message-success'){
    Toastify({
        text: message,
        duration: 3000,
        className:class_name
    }).showToast();    
}

function setTituloPagina(_titulo_pagina_mdm,_subtitulo_pagina_mdm = '')
{
    let _subtitulo = _subtitulo_pagina_mdm == '' ?_entidade_display : _subtitulo_pagina_mdm;
    $('.titulo-pagina-mdm').html(_titulo_pagina_mdm);
    $('.subtitulo-pagina-mdm').html(_subtitulo);
    
}