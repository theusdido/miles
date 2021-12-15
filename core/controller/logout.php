<?php	
	if (isset($_GET["controller"])){
		if ($_GET["controller"] == "logout"){
			$urlretorno = Session::Get("URL_MILES");			
			Session::del();
			echo("<script>location.href = '".$urlretorno."';</script>");	
		}
	}