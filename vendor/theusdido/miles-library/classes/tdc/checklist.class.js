class Checklist {
    constructor() {

        this.list;
        this.selecionados = [];
        this.data;
        this.entidade_pai   = 0;
        this.entidade_filho = 0;
        this.reg_pai        = 0;

        // Método Construtor
        this.construct();
    }
    construct() {
        this.createList();
    }

    createList() {
        this.list = $('<ul class="list-group td-checklist" id="td-checklist-modal">');

        $('#crud-contexto-checklist-td_erp_escola_aula-td_erp_escola_assunto .b-novo').click(function(){
            $('#modal-checklist').modal('show');
        });
    }
    show() {
        this.load();
        $('#crud-contexto-checklist-td_erp_escola_aula-td_erp_escola_assunto #modal-checklist .modal-body').append(this.list);
    }
    load(){
        $.ajax({
            url:session.urlmiles,
            dataType:'json',
            data:{
                controller:'checklist',
                op:'load'
            },
            context:this,
            complete:function(_res){
                this.data = _res.responseJSON;
                this.setItens();
            }
        });
    }

    setItens(_data){        
        this.data.forEach(item => {
            let checkbox    = $('<input type="checkbox" value="'+item.id+'">');
            let label       = $('<label>'+item.descricao+'</label>');
            let li          = $('<li class="list-group-item" data-id="'+item.id+'">');

            let _instancia  = this;
            checkbox.click(function(){
                _instancia.addItem(item);
            });

            li.append(label);
            li.append(checkbox);
            this.list.append(li);
        });
    }

    addItem(_item){
        this.addSelectedItem(_item.id);
    }

    listar(){
        $(this.getListContextId()).html('');
        if (this.selecionados.length > 0){
            this.selecionados.forEach((_item,_index) => {
                
            });
        }else{
            this.nenhumRegistro();
        }
    }

    removeItem(_index_item,_item_id){
        this.selecionados.splice(_index_item,1);
        $('#item-li-' + _item_id).remove();
    }

    nenhumRegistro(_lista = null){
        $(this.getListContextId()).html('');
        let li              = $('<li class="list-group-item list-group-item-warning text-center td-nenhumregistro-list-item">Nenhum Registro</li>');
        if (_lista == null){
            $(this.getListContextId()).append(li);
        }else{
            $(_lista).append(li);
        }
    }

    getSelectedData(){
        return this.selecionados;
    }

    addSelectedItem(_item_id){
        this.data.forEach((_item,_index) => {
            if (_item.id == _item_id){
                this.selecionados.push(_item);
                this.inativarSelecionado(_item_id);
                $(this.getListContextId() + ' .td-nenhumregistro-list-item').remove();
                let li              = $('<li class="list-group-item" id="item-li-'+_item.id+'">');
                let span_text       = $('<span>'+_item.descricao+'</span>');
                let icon_excluir    = $('<i class="fas fa-trash icon-excluir-listitem">');

                icon_excluir.click(() =>{
                    $.ajax({
                        url:session.urlmiles,
                        data:{
                            controller:'checklist',
                            op:'excluir',
                            entidadepai:this.entidade_pai,
                            entidadefilho:this.entidade_filho,
                            regpai:this.reg_pai,
                            regfilho:_item.id
                        },
                        context:this,
                        complete:function(){
                            this.removeItem(_index,_item.id);
                            this.ativarSelecionado(_item.id);
                        }
                    });
                });

                li.append(span_text);
                li.append(icon_excluir);
                console.log(this.getListContextId());
                $(this.getListContextId()).append(li);
            }
        });
    }

    inativarSelecionado(_item_selected_id){
        $('#td-checklist-modal li').each(function(){
            if (_item_selected_id == $(this).data('id')){
                $(this).addClass('disabled');
                $(this).find('input[type="checkbox"]').hide();
            }
        });
    }

    ativarSelecionado(_item_selected_id){
        $('#td-checklist-modal li').each(function(){
            if (_item_selected_id == $(this).data('id')){
                $(this).removeClass('disabled');
                $(this).find('input[type="checkbox"]').show();
            }
        });
    }

    getListContextId(){
        return '#checklist-' + td_entidade[this.entidade_filho].nome;
    }
}