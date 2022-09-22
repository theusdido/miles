<?php

	$path = PATH_CURRENT_FILE;
	$diretorio = dir($path);

	echo "Lista de Arquivos do diretório '<strong>".$path."</strong>':<br />";
	while($arquivo = $diretorio -> read()){
		if ($arquivo != '.' && $arquivo != '..'){
			
			if (!is_dir($arquivo)){
				$newfile = str_replace('imagemprincipal-49-','imagemprincipal-85-',$arquivo);
				rename($path.$arquivo,$path.$newfile);
				echo $arquivo." => ".$newfile."<br />";
			}
		}
	}
	$diretorio -> close();