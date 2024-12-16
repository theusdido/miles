<?php

	switch($op){
		case 'add':

			$telefone = tdc::r("telefone");
			$sqlv  = "SELECT id FROM td_aplicativo_usuario WHERE celular = '{$telefone}' ORDER BY id DESC;";
			$queryv = $conn->query($sqlv);
			if ($queryv->rowCount() > 0){
				$linhav = $queryv->fetch();
				$usuario = tdc::p('td_aplicativo_usuario',$linhav["id"]);
			}else{
				$usuario = tdc::p('td_aplicativo_usuario');
			}

			$usuario->nome 		= tdc::r("nome");
			$usuario->celular 	= $telefone;
			$usuario->senha 	= md5(tdc::r("senha"));
			$usuario->armazenar();

		break;
	}