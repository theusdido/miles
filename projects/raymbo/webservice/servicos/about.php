<?php

    // Quem Somos
    $quemsomos     = tdc::rua('td_website_geral_quemsomos');

    // Secções de Quem Somos
    $quemsomos_itens = tdc::da('td_website_geral_quemsomositens');

    // Equipe
    $equipe = tdc::da('td_website_geral_equipe');

    $retorno['_data'] = array(
      'quemsomos'           => $quemsomos,
      'quemsomos_itens'     => $quemsomos_itens,
      'equipe'              => $equipe
    );