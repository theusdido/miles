<?php

    $_entidade          = tdc::e(tdc::r('entidade'));
    $path_file_conceito = PATH_FILES_CONCEITO . $_entidade->nome . '.html';

    $bloco = tdc::html('div');
    $bloco->class = 'row menu-conceito';

    $bloco_cadastro = tdc::html('div');
    $bloco_cadastro->class = 'col-md-6';

    $bloco_consulta = tdc::html('div');
    $bloco_consulta->class = 'col-md-6';

    $bloco_relatorio = tdc::html('div');
    $bloco_relatorio->class = 'col-md-6';
    
    $bloco_movimentacao = tdc::html('div');
    $bloco_movimentacao->class = 'col-md-6';
    
    $titulo         = tdc::html('div');
    $titulo->class  = 'titulo-pagina';
    $titulo->add($_entidade->descricao);
    $bloco->add($titulo);


    // Cadastros
    $panel_cadastro = tdc::o('panel');
    $panel_cadastro->head('Cadastros');
    $panel_cadastro->tipo = 'default';
    
    $lista_cadastro = tdc::o('listgroup');
    $filtro = tdc::f();
    $filtro->addFiltro('entidade','=',$_entidade->id);
    $filtro->addFiltro('tipomenu','=','cadastro');
    foreach(tdc::d('td_menu',$filtro) as $cadastro){
        $lista_cadastro->addItemList(Menu::link($cadastro->id));
    }
    $panel_cadastro->append($lista_cadastro->toString());
    $bloco_cadastro->add($panel_cadastro);
    if (tdc::c('td_menu',$filtro) > 0) $bloco->add($bloco_cadastro);

    // Consultas
    $panel_consulta = tdc::o('panel');
    $panel_consulta->head('Consultas');
    $panel_consulta->tipo = 'primary';

    $lista_consulta = tdc::o('listgroup');
    $filtro = tdc::f();
    $filtro->addFiltro('entidade','=',$_entidade->id);
    $filtro->addFiltro('tipomenu','=','consulta');
    foreach(tdc::d('td_menu',$filtro) as $consulta){
		$lista_consulta->addItemList(Menu::link($consulta->id));
    }

    $panel_consulta->append($lista_consulta->toString());
    $bloco_consulta->add($panel_consulta);    
    if (tdc::c('td_menu',$filtro) > 0) $bloco->add($bloco_consulta);

    // Relatórios
    $panel_relatorio = tdc::o('panel');
    $panel_relatorio->head('Relatórios');
    $panel_relatorio->tipo = 'warning';

    $lista_relatorio = tdc::o('listgroup');
    $filtro = tdc::f();
    $filtro->addFiltro('entidade','=',$_entidade->id);
    $filtro->addFiltro('tipomenu','=','relatorio');
    foreach(tdc::d('td_menu',$filtro) as $relatorio){
		$lista_relatorio->addItemList(Menu::link($relatorio->id));
    }

    $panel_relatorio->append($lista_relatorio->toString());
    $bloco_relatorio->add($panel_relatorio);      
    if (tdc::c('td_menu',$filtro) > 0) $bloco->add($bloco_relatorio);

    // Movimentação
    $panel_movimentacao = tdc::o('panel');
    $panel_movimentacao->head('Movimentação');
    $panel_movimentacao->tipo = 'info';

    $lista_movimentacao = tdc::o('listgroup');
    $filtro = tdc::f();
    $filtro->addFiltro('entidade','=',$_entidade->id);
    $filtro->addFiltro('tipomenu','=','movimentacao');
    foreach(tdc::d('td_menu',$filtro) as $movimentacao){
		$lista_movimentacao->addItemList(Menu::link($movimentacao->id));
    }

    $panel_movimentacao->append($lista_movimentacao->toString());
    $bloco_movimentacao->add($panel_movimentacao);    
    if (tdc::c('td_menu',$filtro) > 0) $bloco->add($bloco_movimentacao);
    
	// Script que ativa o click do menu
	$script = tdc::html('script');
	$script->add(
	"  		
		$('.menu-conceito a').click(function(dados_menu){			
			let menu_item 	= $(dados_menu.target);
			let linkpath 	= session.folderprojectfiles + menu_item.data('path');
			carregar(linkpath,'#conteudoprincipal',function(){
			  if (dados_menu == undefined || dados_menu == ''){
				 console.warn('Dados do menu não foram carregados.');
				 console.log('## Tente recarregar a página com CTRL + F5. ##');
			  }
			  carregarScriptCRUD(menu_item.data('tipomenu'),menu_item.data('entidade'));
			});
		  });"
	);
	$bloco->add($script);

    tdFile::add($path_file_conceito, $bloco->toString());