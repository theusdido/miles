<?php

    $artista_id = $_data->artista;
    $artista = tdc::pa('td_raymbo_raymboplay_artista', $artista_id);
    $musicas = tdc::da('td_raymbo_raymboplay_musica', ['artista','=', $artista_id]);

    $retorno['_data'] = array(
        'artista' => $artista,
        'musicas' => $musicas
    );