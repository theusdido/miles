<?php
  // Blog
  $modules = array(
    array( "name" => "geral", "title" => "Geral", "components" => 
      array(
        array('name' => 'perfil' , 'title' => 'Perfil'),
        array('name' => 'formemail' , 'title' => 'Formulário de E-Mail'),
        array('name' => 'post' , 'title' => 'Post'),
        array('name' => 'tagsocorrencia' , 'title' => 'Tags Ocorrência')
      )
    ),
    array( "name" => "editorial", "title" => "Editorial", "components" => 
      array(
        array('name' => 'coluna' , 'title' => 'Coluna'),
        array('name' => 'colunista' , 'title' => 'Colunista')
      )
    )
  );