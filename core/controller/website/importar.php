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
$url 			= tdc::r("url");
$arrayurl 		= explode("/",$url);
$urlraiz 		= str_replace(end($arrayurl),"",$url);
$indexfile 		= PATH_CURRENT_WEBSITE . "index.html";

if (!tdFile::add($indexfile,file_get_contents($url))){
	echo 'Não conseguiu gravar o arquivo';
	exit;
}

$arquivo = file($indexfile);
foreach ($arquivo as $linha){
	$tamanho = strlen($linha);
	if(preg_match_all('/<[a-z]+[^>]*>/i', $linha, $matches, PREG_OFFSET_CAPTURE)){
		foreach ($matches as $m){
			foreach($m as $t){
				$posicaotag = $t[1];
				$tagInner = str_replace(array("<",">"),"",$t[0]);
				foreach(explode(" ",$tagInner) as $i => $v){
					if ($i == 0){
						$tag = $v;
					}else{
						// Atributos
						$attr = explode("=",$v);
						if ($attr[0] == "href" || $attr[0] == "src"){
							$attr_value 		= $attr[1];
							$caminhorelativo 	= substr($attr_value,1,strlen($attr_value)-2);
							$attr_value 		= str_replace('"','', str_replace("'",'',$attr_value));
							if (substr($attr_value,0,1) == '/'){
								$attr_value = substr($attr_value, 1, strlen($attr_value));
							}							
							if (!preg_match('/http[s]?:\/\//i',$caminhorelativo)){
								if (substr($attr_value,0,1) != '#' && $attr_value != ''){
									tdFile::download(PATH_CURRENT_WEBSITE . $caminhorelativo,$urlraiz . $caminhorelativo);
								}
							}
						}
					}
				}
				$preconteudo = substr($linha,$posicaotag,$tamanho);
				$posicaoinicial = $posicaotag + strlen($t[0]);				
				try{
					if (!preg_match('/br[\/]?/i',$tag)){
						preg_match('/<\/[ ]*'.trim($tag).'[ ]*>/i',$linha, $tagfechamento , PREG_OFFSET_CAPTURE ,$posicaoinicial);
						if ($tagfechamento){
							$posicaofinal = $tagfechamento[0][1];
							$conteudo = substr($linha,$posicaoinicial,($posicaofinal - $posicaoinicial));
						}						
					}
					throw new Exception('Erro no busca do fechamento da tag');
				}catch(Exception $e){					
					#var_dump($tag);
					#var_dump($linha);
					#var_dump($tagfechamento);
					#var_dump($posicaoinicial);
					break;
					exit;
				}
			}
		}
	}
}	