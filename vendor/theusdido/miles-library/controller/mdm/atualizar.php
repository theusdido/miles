<?php
	$op = tdc::r("op");
		
	switch($op){
		case 'update':

			$environment = tdc::r('environment');
			switch($environment){
				case 'producaotodesenvolvimento':
					$_conn_origem	= Conexao::abrir("producao");
					$_conn_destino	= Conexao::abrir("current");
				break;
				case 'desenvolvimentotoproducao':				
				default:
					$_conn_origem	= Conexao::abrir("current");
					$_conn_destino	= Conexao::abrir("producao");
			}


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

				// Entidades
				$sqlAtual = "SELECT * FROM td_entidade $where";
				$queryAtual = $_conn_origem->query($sqlAtual);
				while ($linhaAtual = $queryAtual->fetch()){
					$entidadeID = criarEntidade(
						$_conn_destino, #0
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
					$sqlAtributoAtual 	= "SELECT * FROM td_atributo WHERE " . PREFIXO . "entidade = " . $entidadeID;
					$queryAtualAtributo = $_conn_origem->query($sqlAtributoAtual);
					while ($linhaAtualAtributo = $queryAtualAtributo->fetch()){

						if ($linhaAtualAtributo["chaveestrangeira"] != "" && (int)$linhaAtualAtributo["chaveestrangeira"] != 0){
							$nomeatributo = str_replace(PREFIXO,"",$linhaAtualAtributo["nome"]);
						}else{
							$nomeatributo = $linhaAtualAtributo["nome"];
						}
						$atributoID = criarAtributo (
							$_conn_destino, #0
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

					$_entidade 		= tdc::e($e);

					// Entidade
					$sqlEntidade 	= "SELECT id,nome FROM td_entidade WHERE nome = '{$_entidade->nome}' LIMIT 1;";
					$queryEntidade 	= $_conn_origem->query($sqlEntidade);
					if ($linhaEntidade = $queryEntidade->fetch()){
						$entidade_origem_nome 	= $linhaEntidade["nome"];
						$entidade_origem_id		= $linhaEntidade["id"];
					 	$entidade_nome 			= $linhaEntidade["nome"];
					}

					// Atributos
					$atributos 	= $atributosdados = array();
					$sqlAtual 	= "SELECT id,nome,tipohtml,tipo FROM td_atributo WHERE entidade = {$entidade_origem_id};";
					$queryAtual = $_conn_origem->query($sqlAtual);
					
					while ($linhaAtual = $queryAtual->fetch()){
						array_push($atributosdados,array(
							"nome" 			=> $linhaAtual["nome"],
							"tipohtml" 		=> $linhaAtual["tipohtml"],
							"tipo" 			=> $linhaAtual["tipo"]
						));
						array_push($atributos,$linhaAtual["nome"]);
					}

					// Valores
					$valores 			= array();
					$dadosinsert 		= array();
					$sql_desenv 		= "SELECT id,".implode(",",$atributos)." FROM {$entidade_nome};";
					$query_origem 		= $_conn_origem->query($sql_desenv);
					
					while ($linha_origem = $query_origem->fetch()){
						$valoresLinha = array();
						foreach($atributos as $key => $a){
							array_push($valoresLinha,getValorDefaultAtributo(utf8_decode($linha_origem[$a]) , $atributosdados[$key]["tipohtml"] , $atributosdados[$key]["tipo"]));
						}
						array_push($valores ,
							array (
								"id" 	=> $linha_origem["id"],
								"dados" => $valoresLinha
							)
						);
					}

					foreach($valores as $v){
					 	inserirRegistro($_conn_destino,$entidade_nome,(int)$v["id"],$atributos,$v["dados"]);
					}
				}

				echo 1;
			}
			if ($entidadesarquivo != ''){
				$sqlftp = 'SELECT * FROM td_connectionftp WHERE projeto = ' . PROJETO;
				$queryftp = $_conn_origem->query($sqlftp);
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
								$res = getRegistro($_conn_origem,"td_entidade","nome","id=".$e);
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
		break;
		case 'lista-estrutura':
			$sql 	= "SELECT descricao,id FROM td_entidade";
			$query 	= $conn->query($sql);
			while ($linha = $query->fetch()){
				echo '
					<div class="checkbox">
						<label>
							<input type="checkbox" class="entidadeestrutura" data-entidade="'.$linha["id"].'">'.tdc::utf8($linha["descricao"]).'
						</label>
					</div>
				';
			}
		break;
		case 'lista-registro':
                $sql = "SELECT descricao,id FROM td_entidade";
                $query = $conn->query($sql);
                while ($linha = $query->fetch()){
                    echo '
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="entidaderegistro" data-entidade="'.$linha["id"].'">'.tdc::utf8($linha["descricao"]).'
                            </label>
                        </div>
                    ';
                }
		break;
		case 'lista-arquivo':
			$sql = "SELECT descricao,id FROM td_entidade";
			$query = $conn->query($sql);
			while ($linha = $query->fetch()){
				echo '
					<div class="checkbox">
						<label>
							<input type="checkbox" class="entidadearquivo" data-entidade="'.$linha["id"].'">'.tdc::utf8($linha["descricao"]).'
						</label>
					</div>
				';
			}
		break;
	}