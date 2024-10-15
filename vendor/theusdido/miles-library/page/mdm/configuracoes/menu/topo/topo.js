var _menutopo       = 0;
var _menutopo_pai   = 0;
$(document).ready(function(){
    $('#menu-pai-descricao').html('');
    loadListar();
});

function goForm(){
    loadPage('mdm/configuracoes/menu/topo/form','#menutopo-conteudo');
}
function criar(){
    goForm();
}
function editarMenu(menu_topo_id){
    _menutopo       = menu_topo_id;
    goForm();
}

function loadListar(){
    loadPage('mdm/configuracoes/menu/topo/listar','#menutopo-conteudo',{},function(){
        $('#tmenutopo tbody')
        .load(session.urlmiles + '?controller=mdm/menu/topo&op=listar&pai=' + _menutopo_pai);
    });
}

function voltarMenuTopoListar(){
    if (_menutopo_pai != 0 && _menutopo != 0){
        _menutopo       = 0;
    }else{
        _menutopo_pai = 0;
    }

    displayBtnVoltar();
    loadListar();
}