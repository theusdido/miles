<?php
	switch(tdc::r("op")){
		case 'html':
			$js = tdc::o("script");
			$js->add('
				var registrounico 	= 1;
				var funcionalidade 	= "add-emexecucao";
			');
			$js->mostrar();
			$entidade 	= tdc::e(tdc::r("entidade"));
			include FOLDER_FILES_CADASTRO . $entidade->id . "/" . $entidade->nome . ".html";
		break;
		case 'cadastro':

			$pagina 	= tdClass::Criar("pagina");
			$entidade 	= tdc::r("entidade");

			$content = tdc::o("div");
			$content->id = "td-pagina-content";

			$pagina->add($content);
			$pagina->mostrar();

			$js = tdc::o("script");
			$js->add('
				carregar(session.urlmiles + "?controller=htmlpage&entidade='.$entidade.'&op=html","#td-pagina-content");
			');
			$js->mostrar();
		break;
		default:
			$file = "files/cadastro/" . $_GET["file"];
			if (file_exists($file)){
				$fp = fopen($file,"r");
				while (!feof($fp)){
					echo htmlspecialchars_decode(fgets($fp,4096));
				}
				fclose($fp);
			}else{
				echo "<b>{$file}</b> arquivo não encontrado.";
			}
	}