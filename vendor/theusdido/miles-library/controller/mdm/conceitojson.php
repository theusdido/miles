<?php

    $_entidade 		    = [];
    $_atributo 		    = [];
    $_relacionamento 	= [];
    $_permissoes 		= [];
    $_filtroatributo 	= [];
    $_consulta 		    = [];
    $_relatorio 		= [];
    $_status 			= [];
    $_movimentacao 	    = [];
    $_retorno           = [];

    $dataset 		= tdClass::Criar("repositorio",array(ENTIDADE))->carregar();
    if ($dataset){
        foreach ($dataset as $entidade){
            array_push($_entidade,Entity::getJSON($entidade->id));
        }

        array_push($_retorno,array(
            '_conceito' => 'entidade',
            '_data'     => $_entidade
        ));
    }

	$dataset = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar();	
	if ($dataset){
		foreach ($dataset as $atributo){
            array_push($_atributo,Field::getJSON($atributo->id));
		}

        array_push($_retorno,array(
            '_conceito' => 'atributo',
            '_data'     => $_atributo
        ));        
	}

	$dataset = tdClass::Criar("repositorio",array(RELACIONAMENTO))->carregar();		
	if ($dataset){
		foreach ($dataset as $relacionamento){
            array_push($_relacionamento,Relationship::getJSON($relacionamento->id));
		}

        array_push($_retorno,array(
            '_conceito' => 'relacionamento',
            '_data'     => $_relacionamento
        ));         
	}

	$sqlPermissoes = tdClass::Criar("sqlcriterio");
	$sqlPermissoes->addFiltro(USUARIO,"=",(isset(Session::Get()->userid)?Session::Get()->userid:0));
	$dataset = tdClass::Criar("repositorio",array(PERMISSOES))->carregar();
	if ($dataset){
		foreach ($dataset as $permissoes){
            //array_push($_permissoes,Permission::getJSON($permissoes->id));
		}
	}

	$dataset = tdClass::Criar("repositorio",array(FILTROATRIBUTO))->carregar();		
	if ($dataset){
		foreach ($dataset as $filtroatributo){
            array_push($_filtroatributo,FilterAttribute::getJSON($filtroatributo->id));
		}

        array_push($_retorno,array(
            '_conceito' => 'filtroatributo',
            '_data'     => $_filtroatributo
        ));          
	}

	$dataset = tdClass::Criar("repositorio",array(CONSULTA))->carregar();		
	if ($dataset){
		foreach ($dataset as $consulta){
            array_push($_consulta,Query::getJSON($consulta->id));
		}

        array_push($_retorno,array(
            '_conceito' => 'consulta',
            '_data'     => $_consulta
        ));        
	}

	$dataset = tdClass::Criar("repositorio",array(RELATORIO))->carregar();		
	if ($dataset){
		foreach ($dataset as $relatorio){
            array_push($_relatorio,Reporty::getJSON($relatorio->id));
		}

        array_push($_retorno,array(
            '_conceito' => 'relatorio',
            '_data'     => $_relatorio
        ));
	}

	$dataset = tdClass::Criar("repositorio",array("td_status"))->carregar();
	if ($dataset){
		foreach ($dataset as $status){
            //array_push($_status,Status::getJSON($status->id));
		}
	}

	$dataset = tdClass::Criar("repositorio",array("td_movimentacao"))->carregar();
	if ($dataset){
		foreach ($dataset as $movimentacao){
            array_push($_movimentacao,Movimentation::getJSON($movimentacao->id));
		}

        array_push($_retorno,array(
            '_conceito' => 'movimentacao',
            '_data'     => $_movimentacao
        ));        
	}

    tdc::wj($_retorno);