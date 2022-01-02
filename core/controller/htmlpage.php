<?php
	switch(tdc::r("op")){
		case 'html':
			$entidade 		= tdc::e(tdc::r("entidade"));
			$path_html_file = PATH_FILES_CADASTRO . $entidade->id . "/" . $entidade->nome . ".html";
			if (file_exists($path_html_file)){
				$js = tdc::o("script");
				$js->add('
					var registrounico 	= true;
					var funcionalidade 	= "add-emexecucao";
				');
				$js->mostrar();
				include $path_html_file;
			}else{
				Alert::showMessage('Arquivo HTML não encontrado.');
			}
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