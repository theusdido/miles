let mongo = require('../../database/mongo');
let fn = require('../../functions.js');

exports.consultar = async function(params, resposta) {
    let collection = await mongo.collection('boleto');
    let filtros = {};

    if (!fn.is_empty(params.pagador)) {
        let params_pagador = params.pagador.trim();
        if (params_pagador != '') {            
            filtros['pessoa.nome'] = { "$regex" : params_pagador };
        }
    }

    if (!fn.is_empty(params.cpfj)) {
        let params_cpfj = params.cpfj.trim();
        if (params_cpfj != '') {            
            filtros['pessoa.cpf_cnpj'] = { "$regex" : params_cpfj };
        }
    }

    if (!fn.is_empty(params.nossonumero)) {
        let params_nossonumero = params.nossonumero.trim();
        if (params_nossonumero != '') {            
            filtros['boleto.nosso_numero'] = { "$regex" : params_nossonumero };
        }
    }

    // Filtros ( Apenas os registros ativos )
    //filtros['inativo'] = false;
    console.log(filtros);
    return collection.find(filtros).toArray();
}