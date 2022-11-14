<?php
	$op = tdc::r("op");
	
	if ($op == "desenvolvimentotoproducao"){

		$entidadesestrutura = tdc::r("entidadesestrutura");
		$entidadesregistro 	= tdc::r("entidadesregistro");
		$entidadesarquivo 	= tdc::r("entidadesarquivo");

		if ($entidadesestrutura != ''){
			if (isset($_GET["entidade"])){
				$where = " WHERE id = " . $_GET["entidade"];
			}else if (isset($_GET["entidadesestrutura"])){
				$where = " WHERE id in (" . $_GET["entidadesestrutura"] . ")";
			}else{
				$where = "";
			}
			
			$connProducao = Conexao::abrir("producao");

			// Entidades
			$sqlAtual = "SELECT * FROM td_entidade $where";
			$queryAtual = $conn->query($sqlAtual);
			while ($linhaAtual = $queryAtual->fetch()){
				$entidadeID = criarEntidade(
					$connProducao, #0
					str_replace(PREFIXO,"",$linhaAtual["nome"]), #1
					utf8_encode($linhaAtual["descricao"]), #2
					$linhaAtual["ncolunas"], #3
					$linhaAtual["exibirmenuadministracao"], #4
					$linhaAtual["exibircabecalho"], #5
					$linhaAtual["campodescchave"], #6
					$linhaAtual["atributogeneralizacao"], #7
					$linhaAtual["exibirlegenda"], #8
					0, #9
					0, #10
					0, #11
					$linhaAtual["registrounico"], #12
					$linhaAtual["carregarlibjavascript"] #13
				);
				
				// Atributos
				$sqlAtributoAtual = "SELECT * FROM td_atributo WHERE " . PREFIXO . "entidade = " . $entidadeID;
				$queryAtualAtributo = $conn->query($sqlAtributoAtual);
				while ($linhaAtualAtributo = $queryAtualAtributo->fetch()){
					
					if ($linhaAtualAtributo["chaveestrangeira"] != "" && (int)$linhaAtualAtributo["chaveestrangeira"] != 0){
						$nomeatributo = str_replace(PREFIXO,"",$linhaAtualAtributo["nome"]);
					}else{
						$nomeatributo = $linhaAtualAtributo["nome"];
					}
					$atributoID = criarAtributo (
						$connProducao, #0
						$entidadeID,#1
						$nomeatributo, #2
						utf8_encode($linhaAtualAtributo["descricao"]), #3
						$linhaAtualAtributo["tipo"], #4
						$linhaAtualAtributo["tamanho"], #5
						$linhaAtualAtributo["nulo"], #6
						$linhaAtualAtributo["tipohtml"], #7
						$linhaAtualAtributo["exibirgradededados"], #8
						$linhaAtualAtributo["chaveestrangeira"], #9
						$linhaAtualAtributo["dataretroativa"], #10
						$linhaAtualAtributo["inicializacao"], #11
						$linhaAtualAtributo["tipoinicializacao"], #12
						$linhaAtualAtributo["readonly"] #13
					);
				}

			}
		}
		if ($entidadesregistro != ''){
			if (isset($_GET["entidade"])){
				$entidades = array($_GET["entidade"]);
			}else if (isset($_GET["entidadesregistro"])){
				$entidades = explode(",",$_GET["entidadesregistro"]);
			}else{
				//$where = "";
				echo 'Entidade não encontrada !';
				exit;
			}

			foreach($entidades as $e){
				// Entidade
				$sqlEntidade = "SELECT nome FROM td_entidade WHERE id = {$e};";
				$queryEntidade = $conn->query($sqlEntidade);
				if ($linhaEntidade = $queryEntidade->fetch()){
					$entidadeNome =$linhaEntidade["nome"];
				}

				// Atributos
				$atributos = $atributosdados = array();
				$sqlAtual = "SELECT id,nome,tipohtml,tipo FROM td_atributo WHERE entidade = {$e};";
				$queryAtual = $conn->query($sqlAtual);
				while ($linhaAtual = $queryAtual->fetch()){
					array_push($atributosdados,array(
						"nome" => $linhaAtual["nome"],
						"tipohtml" => $linhaAtual["tipohtml"],
						"tipo" => $linhaAtual["tipo"]
					));
					array_push($atributos,$linhaAtual["nome"]);
				}

				// Valores
				$valores = array();
				$dadosinsert = array();
				$sqlDesenv = "SELECT id,".implode(",",$atributos)." FROM {$entidadeNome};";
				$queryDesenv = $conn->query($sqlDesenv);
				while ($linhaDesenv = $queryDesenv->fetch()){
					$valoresLinha = array();
					foreach($atributos as $key => $a){
						array_push($valoresLinha,getValorDefaultAtributo($linhaDesenv[$a] , $atributosdados[$key]["tipohtml"] , $atributosdados[$key]["tipo"]));
					}
					array_push($valores ,
						array (
							"id" => $linhaDesenv["id"] ,
							"dados" => $valoresLinha
						)
					);
				}

				foreach($valores as $v){
					inserirRegistro($connProducao,$entidadeNome,(int)$v["id"],$atributos,$v["dados"]);
				}
			}
		}
		if ($entidadesarquivo != ''){
			$sqlftp = 'SELECT * FROM td_connectionftp WHERE projeto = ' . PROJETO;
			$queryftp = $connMiles->query($sqlftp);
			$linhaftp = $queryftp->fetch();

			// Dados do servidor
			$servidor = $linhaftp["host"]; // Endereço
			$usuario = $linhaftp["user"]; // Usuário
			$senha = $linhaftp["password"]; // Senha

			// Abre a conexão com o servidor FTP
			$ftp = ftp_connect($servidor); // Retorno: true ou false

			if ($ftp){

				// Faz o login no servidor FTP
				$login = ftp_login($ftp, $usuario, $senha); // Retorno: true ou false
				if ($login){

					$entidades = explode (",",$_GET["entidadesarquivo"]);
					foreach($entidades as $e){
						$local_arquivo 	= '../../project/files/cadastro/' . $e . '/'; // Localização (local)
						$ftp_pasta 		= '/public_html/miles/project/files/cadastro/' . $e . '/'; // Pasta (externa)
						if (file_exists($local_arquivo)){
							$res = getRegistro($conn,"td_entidade","nome","id=".$e);
							$extensoes = array("html","js");
							foreach($extensoes as $ext){
								// Envio dos arquivos
								$arquivoHTML = $res["nome"] . ".{$ext}"; // Nome do arquivo (externo)
								if (file_exists($local_arquivo . $arquivoHTML)){
									if (!ftp_chdir ($ftp,$ftp_pasta)) {
										ftp_mkdir($ftp,$ftp_pasta);
									}
									// Envia o arquivo pelo FTP em modo ASCII
									$envio = ftp_put($ftp, $ftp_pasta.$arquivoHTML, $local_arquivo . $arquivoHTML, FTP_ASCII); // Retorno: true / false	
									if (!$envio){
										echo 'erro ao enviar arquivo => ' . $local_arquivo . $arquivoHTML;
									}
								}										
							}
						}else{
							echo 'n existe';
						}								
					}

					/*
					// Define variáveis para o envio de arquivo
					
					
					

					*/
				}else{
					echo 'Erro na autenticação';
				}

				
			}else{
				echo 'Erro ao conectar no ftp';
			}

			exit;
		}
		exit;
	}