$('#td-btn-carrinho').click(function(){
    if ($('.td-carrinho-topo').css('display') == 'none'){
        $('.td-carrinho-topo').show('100');
    }else{
        $('.td-carrinho-topo').hide('100');
    }
});