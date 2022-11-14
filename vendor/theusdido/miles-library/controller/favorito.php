<?php
	if ($conn = Transacao::Get()){
		$sqlFavorito = "
			SELECT valorid,count(valorid) AS qtde
			FROM ".LOG."
			WHERE acao = 4
			AND ".ATRIBUTO_ENTIDADE." = " . getEntidadeId("menu",$conn) . "
			GROUP BY valorid
			ORDER BY qtde DESC
			LIMIT 3;
		";
		$queryFavorito = $conn->query($sqlFavorito);
		if ($queryFavorito->rowCount() > 0){
			$listaFavorito 			= tdClass::Criar("div");
			$listaFavorito->id 		= "lista-favoritos-home";
			$listaFavorito->class 	= "list-group";
			while ($linhaFavorito = $queryFavorito->fetch()){
				$a 			= tdClass::Criar("hyperlink");
				$a->class	= "list-group-item";
				$a->href 	= "#";
				$menu = tdClass::Criar("persistent",array(MENU,$linhaFavorito["valorid"]))->contexto;

				if ($menu->link != "" && $menu->descricao != ""){
					$a->onclick = "menuprincipal.menuselecionado = ".$menu->id.";menuprincipal.carregarpagina('".PATH_CURRENT_PROJECT.$menu->link."','#conteudoprincipal');";
					$descricaoMenu = $menu->descricao;
					$paiMenu = $menu->pai;
					$descricaoMenuPai = ($menu->pai != "" && $menu->pai > 0)? tdClass::Criar("persistent",array(MENU,$menu->pai))->contexto->descricao . '<span class="fas fa-arrow-right" aria-hidden="true"></span>':"";
					$qtdeMenu = '<small class="qtde-menu-favorito">' . $linhaFavorito["qtde"] . "</small>";
					$a->add( $descricaoMenuPai . $descricaoMenu . $qtdeMenu);
					$listaFavorito->add($a);
				}
			}
			$panel = tdClass::Criar("panel");
			$panel->head("Favoritos" . '<span class="icone-favorito-home fas fa-star" aria-hidden="true"></span>');
			$panel->tipo = "warning";
			$panel->body($listaFavorito);
			
			$bloco_favorito = tdClass::Criar("bloco");
			$bloco_favorito->class = "col-md-6";
			$bloco_favorito->add($panel);
			$bloco_favorito->mostrar();
		}
	}