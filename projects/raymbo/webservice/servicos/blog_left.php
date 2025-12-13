<?php

    // Blog
    $ft_blog  = tdc::f();
    $ft_blog->limit(6);
    $ft_blog->desc('id');
    $blog     = tdc::da('td_website_geral_blog', $ft_blog);

    $retorno['_data'] = array(
      'blog'          => $blog,
      'publicidade'   => tdc::dua('td_raymbo_publicity')
    );