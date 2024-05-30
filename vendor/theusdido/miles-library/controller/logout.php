<?php
	if (isset($_GET["controller"])){
		if ($_GET["controller"] == "logout"){
			Session::del();
		}
	}