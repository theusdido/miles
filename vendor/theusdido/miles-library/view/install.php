<?php

      // Página de Instação
      $install_page = tdClass::Criar("pagina");
      $install_page->setTitle('Miles - Instalar');

      $installCSS         = tdc::html('link');
      $installCSS->href   = URL_SYSTEM_THEME.'install.css';
      $installCSS->rel 	= "stylesheet";

      $install_page->setFavIcon(URL_FAVICON);
      $install_page->addHead($installCSS);

      $row        = tdc::o('div');
      $row->class = 'row-fluid';

      $col        = tdc::o('div');
      $col->class = 'col-md-12';

      $img        = tdc::html('imagem');
      $img->src   = URL_LOGO;
      $img->id    = 'logoinstalacao';

      $h2         = tdClass::Criar("h",array(2));
      $h2->id     = 'guiafrasetopo';
      $h2->add('Guia de Instalação');

      $hr         = tdc::o('hr');
      $hr->id = 'hr-instalacao';

      $col->add($img,$h2,$hr);
      $row->add($col);

      $install_page->body->style = 'background-image:url('.URL_BACKGROUND.');';
      $install_page->addBody($row);

      $row        = tdc::o('div');
      $row->class = 'row-fluid';

      $col_guia               = tdc::html('div');
      $col_guia->class        = 'col-md-3';
      $col_guia->id           = 'menu-instalacao';

      $col_content            = tdc::html('div');
      $col_content->class     = 'col-md-9';
      $col_content->id        = 'conteudo-instalacao';

      $row->add($col_guia);
      $row->add($col_content);
      $install_page->addBody($row);

      $install_view_home = $_is_installed ? 'package' : 'criarbase';
      $js     = tdc::html('script');
      $js->add('
            $.ajax({
                  url:"'.URL_API.'?controller=page&page=install/guia",
                  complete:function(res){
                        console.log(res.responseText);
                  }

            });
            $("#menu-instalacao").load("'.URL_API.'?controller=page&page=install/guia");
            $("#conteudo-instalacao").load("'.URL_API.'?controller=page&page=install/'.$install_view_home.'");
      ');
      $install_page->addBody($js);

      // Renderiza a página
      $install_page->mostrar();