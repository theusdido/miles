//ultimas-postagens-colunista

$(document).ready(function(){
    loadGradePostagemColunista();

    $('#btn-new-post-coluna').click(function(){
        
        //carregarScriptCRUD('cadastro',131,0,'#ultimas-postagens-colunista');
        // const _entidade_id  = 131;
        // const _contexto     = '#ultimas-postagens-colunista';
        // const _file_crud    = session.folderprojectfiles + "files/cadastro/"+_entidade_id+"/"+td_entidade[_entidade_id].nomecompleto+".html";

        // carregar(_file_crud,_contexto,function(){
        //     carregarScriptCRUD('editarformulario',_entidade_id,0,_contexto,{
        //         is_registrounico:false,
        //         is_init:true,
        //         funcionalidade:'editarformulario'
        //     });
        // });
    });
});

function loadGradePostagemColunista(){
    let gd_colunista				    = new GradeDeDados(131);
    gd_colunista.contexto 				= '#ultimas-postagens-colunista';
    //gd_colunista.index_form			= instancia.getIndexForm();
    //gd_colunista.pesquisar				= false;
    gd_colunista.funcionalidade         = '';
    gd_colunista.retornaFiltro 		    = false;
	gd_colunista.exibirpesquisa         = false;
	gd_colunista.exibireditar           = false;
	gd_colunista.exibirexcluir          = false;
	gd_colunista.exibiremmassa          = false;    
    //gd_colunista.atributoRetorno 		= atributo;
    //gd_colunista.modalName 			= modalName;
    //gd_colunista.entidadeContexto 		= $(this).data("entidade");
    //gd_colunista.entidade_contexto_id 	= instancia.entidade_id;
    
    //gd_colunista.show();
    
    /*
    setTimeout(function(){
        $('#ultimas-postagens-colunista tbody tr').each(function(){
            let status = '';
            let label = '';
            
            switch(parseInt($(this).find('td:nth-child(2) span').html())){
                case 0:
                    status = 'danger';
                    label = 'Negado';
                break;
                case 1:
                    status = 'success';
                    label = 'Aprovado';
                break;
                case 2:
                    status = 'secondary';
                    label = 'Aguardando';
                break;
            }
            const span = `<span class="badge text-bg-${status}">${label}</span>`;
            $(this).find('td:nth-child(2) span').html(span);
        });        
    },1000);
    */
    
    $.ajax({
        url: session.urlmiles,
        dataType: 'json',
        data: {
            controller: 'colunista/posts-home'
        },
        beforeSend: function () {
            
        },
        complete: function (_res) {
            const dados = _res.responseJSON;

            dados.forEach(function(d){
                let tr      = $('<tr>');
                let th_id   = $('<th scope="row">');

                let td_imagem = $('<td>');
                let td_titulo = $('<td>');
                let td_data   = $('<td class="text-center">');
                let td_status = $('<td class="text-center">');

                let imagem_post = $('<img class="imagem-post" src="'+d.imagem_src+'" />'); 
                th_id.append(d.id);
                td_imagem.append(imagem_post);
                td_titulo.append(d.titulo);
                td_data.append(d.data_dateformatted);
                td_status.append(getBadgeStatus(d.is_autorizado));
                
                tr.append(th_id);
                tr.append(td_imagem);
                tr.append(td_titulo);
                tr.append(td_data);
                tr.append(td_status);
                $('#ultimas-postagens-colunista tbody').append(tr);

            });
        }
    });
}

function getBadgeStatus(status){
    switch(parseInt(status)){
        case 0:
            status = 'danger';
            label = 'Negado';
        break;
        case 1:
            status = 'success';
            label = 'Aprovado';
        break;
        case 2:
            status = 'secondary';
            label = 'Aguardando';
        break;
    }
    return $(`<span class="badge text-bg-${status}">${label}</span>`);

}