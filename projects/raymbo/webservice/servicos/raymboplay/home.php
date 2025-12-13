<?php

    $criterio = tdc::f();
    $criterio->limit(4);
    $criterio->desc();
    $popular_artists = tdc::da('td_raymbo_raymboplay_artista', $criterio);
    $top_playlist = tdc::da('td_raymbo_raymboplay_playlistgrupo', $criterio);

    $criterio = tdc::f();
    $criterio->limit(6);
    $criterio->desc();    
    $playlist_recomendada = tdc::da('td_raymbo_raymboplay_playlist', $criterio);

    $retorno['_data'] = array(
        'popular_artists' => $popular_artists,
        'top_playlist' => $top_playlist,
        'playlist_recomendada' => $playlist_recomendada
    );