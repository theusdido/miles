<?php

    include PATH_CLASS . 'imobiliaria/imovel.class.php';

    $_entidade_id_imovel        = getEntidadeId('td_imobiliaria_imovel');
    $_entidade_id_endereco      = getEntidadeId('td_imobiliaria_imovelendereco');
    $_entidade_id_unidadeimovel = getEntidadeId('td_imobiliaria_unidadeimovel');
    $_entidade_id_imovelfoto    = getEntidadeId('td_imobiliaria_imovelfoto');

    $_id                    = tdc::r('id');
    $retorno['_data']       = Imovel::Get($_id);
    $retorno['_data']['caracteristicas']    = getListaRegFilhoArray($_entidade_id_imovel,$_entidade_id_unidadeimovel,$_id);
    $retorno['_data']['fotos']              = getListaRegFilhoArray($_entidade_id_imovel,$_entidade_id_imovelfoto,$_id);
