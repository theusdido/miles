let mongo   = require('../../database/mongo');
let fn      = require('../../functions.js');

exports.consultar = async function(params, resposta){
    let collection  = await mongo.collection('extrato_proprietario');
    let filtros     = {};

    // Filtros ( Apenas os registros ativos )
    filtros['cabecalho.inativo'] = false;

    if (!fn.is_empty(params.locador)){
        if (new RegExp('[0-9]+','g').exec(params.locador) == null){
            filtros['proprietario.nome'] = eval('/' + params.locador + '/i');
        }else{
            filtros['proprietario.codigo'] = params.locador;
        }
    }

    let p_referencia = params['referencia[]'];
    if (!fn.is_empty(p_referencia)){
        let referencia  = typeof p_referencia === 'string' ? [p_referencia] : p_referencia;
        filtros['cabecalho.referencia'] = { $in: referencia};
    }

    if (!fn.is_empty(params.diapagamento)){
        filtros['cabecalho.dia'] = params.diapagamento;
    }

    if (params.pendente == 1){
        filtros['cabecalho.pendente'] = 'S';
    }

    if (!fn.is_empty(params.dataliberacao)){
        filtros['titulo.data_geracao'] = params.dataliberacao;
    }

    if (!fn.is_empty(params.titulo)){
        filtros['titulo.numero'] = params.titulo;            
    }

    let p_istitulo = params.comtitulo;
    if (!fn.is_empty(p_istitulo) && p_istitulo == 0){
        filtros['titulo.comtitulo'] = { $ne : '' };
    }

    let p_geracao = params['geracao[]'];
    if (!fn.is_empty(p_geracao)){
        let geracao  = typeof p_geracao === 'string' ? [p_geracao] : p_geracao;
        filtros['cabecalho.gerador'] = { $in: geracao};
    }

    let p_formapagamento = params['formapagamento[]'];
    if (!fn.is_empty(p_formapagamento)){
        let formapagamento  = typeof p_formapagamento === 'string' ? [p_formapagamento] : p_formapagamento;
        filtros['forma_pagamento.codigo'] = { $in: formapagamento};
    }

    let p_negativo = params['negativo'];
    if (!fn.is_empty(p_negativo) && p_negativo != '-1'){
        filtros['cabecalho.is_negativo'] = p_negativo;
    }

    console.log(filtros);
    return collection.find(filtros).sort({'proprietario.nome':1}).toArray();
}