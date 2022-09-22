<?php

	$_path_permissoes_json = PATH_CURRENT_BUILD . 'permissoes.json';

    switch(tdc::r('op'))
    {
        // Retorna o menu e suas permissões
        case 'menu':
            $permissoes = [];
            foreach(tdc::da(MENU) as $menu)
            {
				$menu_permissao = tdc::da(MENUPERMISSOES,tdc::f('menu',"=",$menu['id']));
				if (sizeof($menu_permissao) > 0){
					$menu['permissao'] = $menu_permissao[0]['permissao'];
				}else{
					$menu['permissao'] = 0;
				}
                array_push($permissoes,$menu);
            }
            $fp = fopen(PATH_CURRENT_BUILD . 'permissoes.json','w');
            fwrite($fp,utf8_encode(json_encode($permissoes)));
            fclose($fp);
        break;
		case 'load':
			if (file_exists($_path_permissoes_json)){
				echo json_encode(file($_path_permissoes_json)[0]);
			}
		break;
    }