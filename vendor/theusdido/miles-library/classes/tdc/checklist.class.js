class Checklist {
    constructor(_relacionamento)
    {

        this.list;
        this.selecionados   = [];
        this.data;
        this.relacionamento = _relacionamento;
        this.entidade_pai   = _relacionamento.pai;
        this.entidade_filho = _relacionamento.filho;
        this.reg_pai        = 0;
        this.contexto       = '';

        // Método Construtor
        this.createList();
    }

    createList() {        
        this.list = $('<ul class="list-group td-checklist" id="'+getHierarquiaRel(this.relacionamento)+'">');
        this.nenhumRegistro();
    }
    show() {
        this.load();
        let modal_body = $('#crud-contexto-checklist-'+this.contexto+' '+this.getModalName()+' .modal-body');
        modal_body.html('');
        modal_body.append(this.list);
    }
    load(){
        $.ajax({
            url:session.urlmiles,
            dataType:'json',
            data:{
                controller:'checklist',
                op:'load',
                entidade:td_entidade[this.entidade_filho].nome
            },
            context:this,
            complete:function(_res){
                this.setItens(_res.responseJSON);
            }
        });
    }

    setItens(_data){
        
        if (_data.length < 1){
            this.nenhumRegistro();
            return;
        }
        this.data = _data;
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
        let li              = $('<li class="list-group-item list-group-item-warning text-center td-nenhumregistro-list-item">Nenhum Registro</li>');
        if (_lista == null){
            $( this.getListContextId() ).html('');
            $( this.getListContextId() ).append(li);
        }else{
            $(_lista).append(li);
        }
    }

    getSelectedData(){
        return this.selecionados;
    }

    addSelectedItem(_item_id){
        if (this.data == undefined) return;
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
                $(this.getListContextId()).append(li);
            }
        });
    }

    inativarSelecionado(_item_selected_id){
        $(this.list).find('li').each(function(){
            if (_item_selected_id == $(this).data('id')){
                $(this).addClass('disabled');
                $(this).find('input[type="checkbox"]').hide();
            }
        });
    }

    ativarSelecionado(_item_selected_id){
        $(this.list).find('li').each(function(){
            if (_item_selected_id == $(this).data('id')){
                $(this).removeClass('disabled');
                $(this).find('input[type="checkbox"]').show();
            }
        });
    }

    getListContextId(){
        return '#checklist-' + td_entidade[this.entidade_filho].nome;
    }

    setContexto(_contexto){
        this.list.attr('id','td-checklist-modal-'+this.getNomeEntidadeFilho());
        this.contexto = _contexto;
        this.clickAdd();
    }

    clickAdd(){
        let modalname = this.getModalName();
        $('#crud-contexto-checklist-'+this.contexto+' .b-novo').click(function(){
            $(modalname).modal('show');
        });
    }

    getNomeEntidadeFilho(){
        return td_entidade[this.entidade_filho].nome;
    }

    getModalName(){
        return '#modal-checklist-' + this.getNomeEntidadeFilho();
    }
}