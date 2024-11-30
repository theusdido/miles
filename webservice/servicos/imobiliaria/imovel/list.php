<?php

    $_entidade_id_imovel    = getEntidadeId('td_imobiliaria_imovel');
    $_entidade_id_endereco  = getEntidadeId('td_imobiliaria_imovelendereco');

    //$retorno['_data']       = tdc::da('td_imobiliaria_imovel');
    $_res = array();

    $sql = "
        SELECT 
            id
        FROM
            td_imobiliaria_imovel a
        ORDER BY id DESC;
    ";

    $rs = $conn->query($sql);
    while ($row = $rs->fetch()){
        $_id    = $row['id'];
        $imovel = tdc::pa('td_imobiliaria_imovel',$_id);

        $endereco_imovel = getListaRegFilhoArrayUnico($_entidade_id_imovel,$_entidade_id_endereco,$_id);



        array_push($_res,array(
            'imovel' => $imovel,
            'endereco' => $endereco_imovel,
            'endereco_obj' => tdc::dua('td_imobiliaria_endereco',$endereco_imovel['endereco'])
        ));
    }

    $retorno['_data'] = $_res;