<?php
	if ($conn = Transacao::Get()){
		$sqlFavorito = "select valorid,count(valorid) as qtde from td_log WHERE acao = 4 and td_entidade = " . getEntidadeId("menu",$conn) . " group by valorid order by qtde desc limit 3;";
		$queryFavorito = $conn->query($sqlFavorito);
		if ($queryFavorito->rowCount() > 0){
			$listaFavorito = tdClass::Criar("div");
			$listaFavorito->id = "lista-favoritos-home";
			$listaFavorito->class = "list-group";
			while ($linhaFavorito = $queryFavorito->fetch()){
				$a = tdClass::Criar("hyperlink");
				$a->class="list-group-item";
				$a->href = "#";
				$menu = tdClass::Criar("persistent",array("td_menu",$linhaFavorito["valorid"]))->contexto;

				if ($menu->link != "" && $menu->descricao != ""){
					$a->onclick = "menuprincipal.menuselecionado = ".$menu->id.";menuprincipal.carregarpagina('".Session::Get("PATH_CURRENT_PROJECT").$menu->link."','#conteudoprincipal');";
					$descricaoMenu = $menu->descricao;
					$paiMenu = $menu->td_pai;
					$descricaoMenuPai = ($menu->td_pai != "" && $menu->td_pai > 0)? tdClass::Criar("persistent",array("td_menu",$menu->td_pai))->contexto->descricao . '<span class="fas fa-arrow-right" aria-hidden="true"></span>':"";
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