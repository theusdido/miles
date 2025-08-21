<?php
    #$json_teste = '{"id":3}';
    #tdc::wj(  preg_replace('/[\s]+/', '', Entity::getJSON(106)));
    //tdc::wj(json_encode($json_teste));
    //tdc::wj($json_teste,true);


			// Config
			inserirRegistro($conn,CONFIG,1, array(
				"urlupload",
				"urlrequisicoes",
				"urlsaveform",
				"urlloadform",
				"urluploadform",
				"urlpesquisafiltro",
				"urlenderecofiltro",
				"urlexcluirregistros",
				"urlinicializacao",
				"urlloading",
				"urlloadgradededados",
				"urlrelatorio",
				"urlmenu",
				"bancodados",
				"linguagemprogramacao",
				"pathfileupload",
				"pathfileuploadtemp",
				"testecharset",
				"tipogradedados",
				"casasdecimais",
				"multiidioma"
			), array(
				"'index.php?controller=upload'",
				"'index.php?controller=requisicoes'",
				"'index.php?controller=salvarform'",
				"'index.php?controller=loadform'",
				"'index.php?controller=upload'",
				"'index.php'",
				"'index.php'",
				"'index.php?controller=excluirregistros'",
				"'index.php?controller=inicializacao'",
				"'index.php?controller=loading'",
				"'index.php?controller=gradededados'",
				"'index.php?controller=relatorio'",
				"'index.php?controller=menu'",
				"'mysql'",
				"'php'",
				"'project/arquivos'",
				"'project/arquivos/temp'",
				"'á'",
				"'table'",
				2,
				0
			));    