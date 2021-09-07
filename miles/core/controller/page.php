<?php
	$page = isset($_GET["page"])?$_GET["page"]."/".$_GET["page"]:'';
	$cssFile = PATH_SYSTEM_PAGE . $page . '.css';
	if (file_exists($cssFile)){
		echo '<link href="'.$cssFile.'" rel="stylesheet" />';
	}

    $htmlFile = PATH_SYSTEM_PAGE . $page . '.html';
	if (file_exists($htmlFile)){
		include $htmlFile;
	}else{
        echo 'n';
    }

	$jsFile = PATH_SYSTEM_PAGE . $page . '.js';
	if (file_exists($jsFile)){
		echo '<script type="text/javascript" src="'.$jsFile.'" /></script>';
	}
	
	$phpFile = PATH_SYSTEM_PAGE . $page . '.php';
	if (file_exists($phpFile)){
		include $phpFile;
	}