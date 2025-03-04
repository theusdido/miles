<?php

    $_entidade_id_imovel    = getEntidadeId('td_imobiliaria_imovel');
    $_entidade_id_endereco  = getEntidadeId('td_imobiliaria_imovelendereco');
    $_res                   = array();

    $sql = "
        SELECT 
            id
        FROM
            td_imobiliaria_imovel
        WHERE lancamento = 1
        OR ofertasemana = 1
        ORDER BY id DESC;
    ";

    $rs = $conn->query($sql);
    while ($row = $rs->fetch()){
        $_id    = $row['id'];
        $imovel = tdc::pa('td_imobiliaria_imovel',$_id);

        $endereco_imovel = getListaRegFilhoArrayUnico($_entidade_id_imovel,$_entidade_id_endereco,$_id);

        $_endereco = $_endereco_obj = null;

        if (!empty($endereco_imovel)){
            $_endereco      = $endereco_imovel;
            $_endereco_obj  = tdc::dua('td_imobiliaria_endereco',$endereco_imovel['endereco']);
        }

        array_push($_res,array(
            'imovel'        => $imovel,
            'endereco'      => $_endereco,
            'endereco_obj'  => $_endereco_obj
        ));
    }

    $retorno['_data'] = $_res;