<?php

    $_entidade_id_imovel        = getEntidadeId('td_imobiliaria_imovel');
    $_entidade_id_endereco      = getEntidadeId('td_imobiliaria_imovelendereco');
    $_entidade_id_unidadeimovel = getEntidadeId('td_imobiliaria_unidadeimovel');

    $_id                    = tdc::r('id');
    $retorno['_data']       = tdc::dua('td_imobiliaria_imovel',$_id);

    $endereco_imovel                        = getListaRegFilhoArrayUnico($_entidade_id_imovel,$_entidade_id_endereco,$_id);
    $retorno['_data']['endereco']           = $endereco_imovel;
    $retorno['_data']['endereco_obj']       = tdc::dua('td_imobiliaria_endereco',$endereco_imovel['endereco']);
    $retorno['_data']['caracteristicas']    = getListaRegFilhoArray($_entidade_id_imovel,$_entidade_id_unidadeimovel,$_id);