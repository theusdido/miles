let mongo = require('../../database/mongo');
let fn = require('../../functions.js');

exports.consultar = async function(params, resposta) {
    let collection = await mongo.collection('extrato_impostorenda');
    let filtros = {};

    let p_ano = params['ano[]'];
    if (!fn.is_empty(p_ano)) {
        let ano = typeof p_ano === 'string' ? [p_ano] : p_ano;
        filtros['cabecalho.ano'] = { $in: ano };
    }

    if (!fn.is_empty(params.especificacao)) {
        if (params.especificacao > 0) {
            filtros['cabecalho.tipo_cliente'] = params.especificacao;
        }
    }

    if (!fn.is_empty(params.pessoa)) {
        let params_pessoa = params.pessoa.trim();
        if (params_pessoa != '') {
            
            if (params_pessoa.length <= 7 && !isNaN(params_pessoa))
            {
                filtros["pessoa.codigos"] =  parseInt(params_pessoa);
            }else{
                filtros["$or"] = [
                    { "pessoa.nome" : { "$regex" : params_pessoa }},
                    { "pessoa.cpf_cnpj" : params_pessoa }
                ];
            }
        }
    }

    if (!fn.is_empty(params.contrato)) {
        if (params.contrato > 0) {
            filtros['contratos.contrato.numero'] = params.contrato;
        }
    }

    if (!fn.is_empty(params.imovel)) {
        if (params.imovel > 0) {
            filtros["$or"] = [
                {'contratos.imovel.codigo':params.imovel},
                {'contratos.imovel.endereco':params.imovel}
            ];
        }
    }

    // Filtros ( Apenas os registros ativos )
    filtros['cabecalho.inativo'] = false;
    console.log(filtros);
    return collection.find(filtros).toArray();
}