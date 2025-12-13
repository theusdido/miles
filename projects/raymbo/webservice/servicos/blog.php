<?php

    $post           = tdc::rua('td_website_blog_post',$_data->_id);
    $colunista      = tdc::ru('td_website_blog_colunista',$post['colunista']);
    $post['coluna'] = tdc::rua('td_website_blog_coluna',$colunista->coluna);

    $retorno['_data'] = $post;