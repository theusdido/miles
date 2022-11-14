<?php

	$fp	 	= fopen(PATH_CURRENT_FILE . 'convert.sql','w+');
	$file 	= file(PATH_CURRENT_FILE_TEMP . 'bkp.sql');

	foreach($file as $linha){

		$palavras 			= explode(' ',$linha);	
		$fw 				= $palavras[0]; // First Word
		
		if (substr($linha,0,3) != '/*!' &&  substr($linha,0,2) != '--' && $fw != 'LOCK' && $fw != 'UNLOCK'){
			$linha = str_replace('COLLATE=utf8mb4_0900_ai_ci','',$linha);
			$linha = str_replace('CHARSET=utf8mb4','CHARSET=utf8',$linha);
			$linha = str_replace('ENGINE=MyISAM','ENGINE=InnoDB',$linha);
			fwrite($fp,$linha);
		}
	}

	fclose($fp);