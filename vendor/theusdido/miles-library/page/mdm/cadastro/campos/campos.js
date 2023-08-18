$(document).ready(function(){
    loadCamposContent('listar');
});

function loadCamposContent(_page){
    $('#campos-conteudo').load(session.urlmiles + '?controller=page&page=mdm/cadastro/campos/' + _page);
}

function goListarCampos(){
    loadCamposContent('listar');
}

// function setTituloPagina(_titulo_pagina_mdm,_subtitulo_pagina_mdm = ''){
//     $('.titulo-pagina-mdm').html(_titulo_pagina_mdm);
//     $('.subtitulo-pagina-mdm').html(_subtitulo_pagina_mdm);
    
// }