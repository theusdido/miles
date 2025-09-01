<?php
	if (!defined("PREFIXO")) define("PREFIXO", (isset($config["PREFIXO"]) ? $config["PREFIXO"] : 'td_') . "_");
	if (!defined("PROJETO")) define("PROJETO", (isset($config["CURRENT_PROJECT"]) ? $config["CURRENT_PROJECT"] : 1));