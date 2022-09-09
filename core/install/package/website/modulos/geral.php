<?php
  // Geral
  $modules = array(
    array( "name" => "default", "title" => "Default", "components" => 
      array(
        array('name' => 'configuracoes'           ,'title' => 'Configurações')
      )
    ),
    array( "name" => "menu", "title" => "Menu", "components" => 
      array(
        array('name' => 'menutopo'                ,'title' => 'Menu Topo'),
        array('name' => 'menuprincipal'           ,'title' => 'Menu Principal'),
        array('name' => 'menurodape'              ,'title' => 'Menu Rodapé')
      )
    ),
    array( "name" => "pagina", "title" => "Página", "components" => 
      array(
        array('name' => 'quemsomos'               ,'title' => 'Quem Somos'),
        array('name' => 'politicaprivacidade'     ,'title' => 'Política de Privacidade'),
		array('name' => 'politicacookies'     ,'title' => 'Política de Cookies'),
        array('name' => 'home'                    ,'title' => 'Home'),
        array('name' => 'blog'                    ,'title' => 'Blog')
      )
  ),
  array( "name" => "componente", "title" => "Componente", "components" => 
      array(
        array('name' => 'slider'                  ,'title' => 'Slider'),
        array('name' => 'googlemaps'              ,'title' => 'Google Maps')
      )
  ),
  array( "name" => "sessao", "title" => "Sessão ( Section )", "components" => 
      array(
        array('name' => 'newsletter'              ,'title' => 'Newsletter'),
        array('name' => 'rodape'                  ,'title' => 'Rodapé'),
        array('name' => 'redesocial'              ,'title' => 'Rede Social'),
		    array('name' => 'redessociais'              ,'title' => 'Redes Sociais'),
        array('name' => 'whatsapp'              ,'title' => 'Whatsapp')	
      )
    )
  );