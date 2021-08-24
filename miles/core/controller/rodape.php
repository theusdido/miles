<?php
$image_rodape = tdClass::Criar("imagem");
if (file_exists(Session::Get("PATH_CURRENT_IMG_RODAPE_PADRAO"))){
	$image_rodape->src = Session::Get("PATH_CURRENT_IMG_RODAPE_PADRAO");
}
$rodape = $image_rodape;