<?php
	if (isset($_GET["controller"])){
		if ($_GET["controller"] == "logout"){
			$urlretorno = URL_MILES;
			Session::del();
			echo("<script>location.href = '".$urlretorno."';</script>");
		}
	}