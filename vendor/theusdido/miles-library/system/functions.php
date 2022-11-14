<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB.
    * @link https://teia.tec.br

    * Arquivo com as funções estáticas ( Não tem vinculo direto com o sistema )
    * Data de Criacao: 20/12/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/

/*
	* removeBarraRoot
	* Data de Criacao: 20/12/2021
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* Retira a barra inicial do path
	* PARAMETROS
	*	@params: String page:"Path"
	* RETORNO
	*	@return: String
*/
function removeBarraRoot($path){
	$new_path = $path;
	// Verifica o primeiro caracter
	if (substr($new_path,0,1) == '/'){
		$new_path = substr($new_path,1,strlen($new_path));
	}

	// Verifica o último caracter
	if (substr($new_path,-1,1) == '/'){
		$new_path = substr($new_path,0,strlen($new_path) - 1);
	}
	return $new_path;
}
/*
	* showMessage
	* Data de Criacao: 28/12/2021
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* Exibe uma mensagem em forma de alerta
	* PARAMETROS
	*	@params: String msg:required
	*	@params: String tipo
	* RETORNO
	*	@return: void
*/
function showMessage($msg,$tipo = 'error'){

	$dom 					= new DOMDocument('1.0','UTF-8');
	$dom->formatOutput 		= true;

	$_style 					= $dom->createElement("style");
	$_style->textContent 		= preg_replace('/[^\S]+/i',' ', '
			.td-showmessage{
				float:left;
				clear:left;
				width:100%;
				padding:15px;
				box-sizing: border-box;
				border-radius:10px;
			}
			.error{
				background-color:#FF0000;
				color:#FFF;
				border:3px solid #D40100;
			}
	');
	$_style->setAttribute("type","text/css");

	$alert 					= $dom->createElement("div");
	$alert->textContent 	= $msg;
	$alert->setAttribute("class",'td-showmessage '.$tipo);

	$dom->appendChild($_style);
	$dom->appendChild($alert);		
	echo preg_replace('/<\?xml.*?>/','',trim($dom->saveHTML()));
}