<?php

    // Blog
    $ft_blog  = tdc::f();
    $ft_blog->limit(1);
    $ft_blog->desc('id');
    $blog     = tdc::dua('td_website_geral_blog', $ft_blog);

    // Posts ( Colunistas )
    $ft_post  = tdc::f();
    $ft_post->limit(4);
    $ft_post->desc('id');
    $post_     = tdc::da('td_website_blog_post', $ft_post);

    function colunistaOBJ($item){
      $item['colunista_obj'] = tdc::pa('td_website_blog_colunista', $item['colunista']);
      return $item;
    }
    $post = array_map('colunistaOBJ', $post_);

    $retorno['_data'] = array(
      'slides'        => tdc::da('td_website_geral_slider'),
      'blog'          => $blog,
      'post'          => $post,
      'publicidade'   => tdc::dua('td_raymbo_publicity')
    );