<?php
/*
    * Framework MILES
    * @license : Teia
    * @link http://www.teia.online

    * Controller
    * Data de Criacao: 25/06/2020
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* Importar Site
*/
$url 				= tdc::r("url");
$arrayurl 			= explode("/",$url);
$urlraiz 			= str_replace(end($arrayurl),"",$url);
$path_root_website	= FOLDER_PROJECT . '/' . CURRENT_PROJECT_ID . '/' . FOLDER_WEBSITE . '/';
$local_filename		= tdc::r('filename') == '' ? 'index' : tdc::r('filename');
$template_file		= $path_root_website . 'template.html';
$url_root_website	= URL_PROJECT . FOLDER_WEBSITE . '/';
$index_file			= $path_root_website . $local_filename . '.php';

if (!tdFile::add($template_file,file_get_contents($url))){
	echo 'Não conseguiu gravar o arquivo';
	exit;
}

// Arquivo index.php
$fp_index = fopen($index_file,'w');

$arquivo = file($template_file);
foreach ($arquivo as $linha){
	$tamanho 	= strlen($linha);
	$new_linha	= $linha;
	// Encontra as TAGS
	if ( preg_match_all('/<[^>]+>/i',$linha, $match , PREG_OFFSET_CAPTURE) ){
		foreach($match[0] as $m){
			$content = $m[0];
			if (preg_match_all('/ ([\w]+)="([^"]+)"/i',$content, $match_content , PREG_SET_ORDER) ){
				foreach($match_content as $c){
					
					#echo 'Nome => ' . $c[1] . "\n";
					#echo 'Valor => ' . $c[2] . "\n";
					

					$attr 	= $c[1];
					$value	= $c[2];

					if ($attr == 'href' || $attr == 'src'){
						if (substr($value,0,1) == '/'){
							$file_downloaded = substr($value,1,strlen($value));
							if (getExtensao($value) != ''){
								tdFile::download($path_root_website . $file_downloaded,$urlraiz . $file_downloaded);
								$new_atributo 	= ' ' . $attr . '="' . $file_downloaded . '" ';
								$new_linha 		= str_replace($c[0],$new_atributo,$new_linha);								
							}
						}

							echo 'Atributo => ' . $c[0] . "\n";
							echo $value . "\n";
							echo getExtensao($value) . "\n";
							echo "------------------\n";						
					}
				}
			}
		}
	}

	fwrite($fp_index,$new_linha);
}

fclose($fp_index);
