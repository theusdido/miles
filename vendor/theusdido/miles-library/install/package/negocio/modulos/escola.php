<?php
  // Módulo Escola
  $modules = array(
    array( "name" => "secretaria", "title" => "Secretaria", "components" =>
      array(
        array('name' => 'aluno' , 'title' => 'Aluno'),        
        array('name' => 'turma' , 'title' => 'Turma')
      )
    ),
    array( "name" => "soe", "title" => "Serviço de Orientação Escolar", "components" =>
        array(
            array('name' => 'atendimento' , 'title' => 'Atendimento')
        )
    ),
    array( "name" => "pedagogico", "title" => "Pedagógico", "components" =>
        array(
            array('name' => 'atividade' , 'title' => 'Atividade'),
            array('name' => 'aula' , 'title' => 'Aula'),
            array('name' => 'chamada' , 'title' => 'Chamada'),
            array('name' => 'avaliacao' , 'title' => 'Avaliação'),
            array('name' => 'criterioavaliacao' , 'title' => 'Critério de Avaliação'),
            array('name' => 'instrumentoavaliacao' , 'title' => 'Instrumento de Avaliação'),
            array('name' => 'metodologia' , 'title' => 'Metodologia'),
            array('name' => 'assunto' , 'title' => 'Assunto')
        )
    ),
    array( "name" => "itinerarioformativo", "title" => "Itinerario Formativo", "components" =>
        array(
            array('name' => 'competencia' , 'title' => 'Competência'),
            array('name' => 'conteudo' , 'title' => 'Conteúdo'),            
            array('name' => 'curso' , 'title' => 'Curso'),
            array('name' => 'habilidade' , 'title' => 'Habilidade'),
            array('name' => 'objetivoespecifico' , 'title' => 'Objetivos Específicos'),
            array('name' => 'unidadecurricular' , 'title' => 'Unidade Curricular'),
            array('name' => 'trilha' , 'title' => 'Trilha'),
            array('name' => 'eixo' , 'title' => 'Eixo')
        )
    ),
    array( "name" => "planoensino", "title" => "Plano de Ensino", "components" =>
        array(
            array('name' => 'planejamento' , 'title' => 'Planejamento'),
            array('name' => 'praticapedagogica' , 'title' => 'Prática Pedagogica'),
            array('name' => 'recurso' , 'title' => 'Recurso'),
            array('name' => 'tempoatividade' , 'title' => 'Tempo da Atividade')
        )
    ),
    array( "name" => "rh", "title" => "Recursos Humanos", "components" =>
        array(
            array('name' => 'professor' , 'title' => 'Professor')
        )
    ),
  );