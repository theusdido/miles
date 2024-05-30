class gerarHTML {
    _entidade_id    = 0;
    _entidade_nome  = '';
    _conceito       = '';
    _conceito_id    = 0;

    conceito(){
        $.ajax({
            url:session.urlmiles,
            data:{
                controller:"gerar" + this._conceito,
                entidade:this._entidade_id,
                id:this._conceito_id,
                principal:true
            },
            context:this,
            complete:function(retorno){
                this.pagina(retorno.responseText);
            }
        });
    }

    pagina(_html){
        $.ajax({
            url:session.urlmiles,
            type:"POST",
            data:{
                controller:"mdm/componente",
                op:"criar" + this._conceito,
                html:_html,
                filename:td_entidade[this._entidade_id].nome + '.html',
                filenamejs:td_entidade[this._entidade_id].nome + '.js',
                filenamecss:td_entidade[this._entidade_id].nome + '.css',
                filenamehtm:td_entidade[this._entidade_id].nome + '.htm',
                entidade:this._entidade_id,
                id:this._conceito_id,
                reset:false
            },
            complete:function(){

            }
        });
    }

    cadastro(_entidade){
        this._conceito      = 'cadastro';
        this._entidade_id   = _entidade;
        this._entidade_nome = td_entidade[_entidade].nome;
        this._conceito_id   = _entidade;
        this.conceito();
    }

}