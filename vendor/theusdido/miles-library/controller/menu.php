<?php
	$op = tdc::r('op');
	switch ($op)
	{
		case 'retorna_dados':
			$userid 	= Usuario::id();
			$where 		= "AND (EXISTS(SELECT 1 FROM td_entidadepermissoes b WHERE b.entidade = a.entidade AND b.usuario = ".$userid." AND b.visualizar = 1)";
			$where 	   .= " OR EXISTS(SELECT 1 FROM td_menupermissoes c WHERE c.menu = a.id AND c.usuario = ".$userid." AND c.permissao = 1)) AND a.descricao <> '' ";
			if ($conn = Transacao::get()){
				$menu = array();
				$sqlMenu = "
					SELECT a.id,a.descricao,a.pai,icon
					FROM td_menu a					
					WHERE a.pai = 0
					AND a.descricao <> ''
					{$where}
					ORDER BY a.pai,a.ordem;
				";
				$queryMenu = $conn->query($sqlMenu);
				While ($linhaMenu = $queryMenu->fetch()){
					$submenu = array();
					$menu_id = $linhaMenu['id'];
					$sqlSubMenu = "
						SELECT a.id
						FROM td_menu a
						WHERE a.pai = {$menu_id}
						{$where}
						ORDER BY a.ordem;
					";
					$querySubMenu = $conn->query($sqlSubMenu);
					While ($linhaSubMenu = $querySubMenu->fetch()){
						array_push($submenu,Menu::Open($linhaSubMenu['id']));
					}
					array_push($menu, array_merge(Menu::Open($menu_id),array('filhos' => $submenu)) );
				}
				echo json_encode($menu);
			}
		break;
		case 'all':
			$menu = array();
			$sqlMenu = "
				SELECT id,descricao,pai
				FROM td_menu a
				WHERE a.pai = 0
				AND a.descricao <> ''
				ORDER BY a.pai,a.ordem;
			";
			$queryMenu = $conn->query($sqlMenu);
			While ($linhaMenu = $queryMenu->fetch()){
				$submenu = array();
				$menu_id = $linhaMenu['id'];
				$sqlSubMenu = "
					SELECT id,descricao,pai
					FROM td_menu
					WHERE pai = {$menu_id}
					ORDER BY ordem;
				";
				$querySubMenu = $conn->query($sqlSubMenu);
				While ($linhaSubMenu = $querySubMenu->fetch()){
					array_push($submenu,array(
						'id' 		=> $linhaSubMenu['id'],
						'descricao' => $linhaSubMenu['descricao'],
						'pai' 		=> $linhaSubMenu['pai']
					));
				}
				array_push($menu,array(
					'id' 		=> $linhaMenu['id'],
					'descricao' => $linhaMenu['descricao'],
					'filhos'	=> $submenu
				));
			}
			echo json_encode($menu);
		break;
		default:
			$menuClass = tdClass::Criar("script");
			$menuClass->src = URL_CLASS_TDC . "menu.class.js";

			$menu = tdClass::Criar("bloco",array('menu'));
			$menu->class = "col-md-12 col-sm-12";

			$jsMenu = tdClass::Criar("script");
			$jsMenu->add(
				'
					// Variaveis e Funções Globais em JavaScript
					var menuprincipal 			= new Menu();
					menuprincipal.contexto 		= "#menu";
					menuprincipal.exibirbrand 	= true;
					menuprincipal.mostrar();
				'
			);

			$menu->add($menuClass,$jsMenu);
		break;
	}
