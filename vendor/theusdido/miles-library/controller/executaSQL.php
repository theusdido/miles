<?php
	if (isset($_GET["controller"])){
		if ($_GET["controller"] == "executaSQL"){
			// Adiciona o modelo se existir			
			incluir (PATH_SYSTEM . PATH_MVC_MODEL . $_GET["controller"] . '.php');
			
			
			if (isset($_POST["query"])){
				$sql = $_POST["query"];
				if ($sql!="" && $sql!=null){					
					if ($conn = Transacao::get()){						
						try{							
							$sql = str_replace("PREFIXO_",parse_ini_file("config/config.inc")["PREFIXO"]."_",$sql);
							$result = $conn->query(tdc::utf8($sql));
							$msg = "Instrução executada com sucesso";
							$erro = 0;
							addLog($_POST["query"]);
							$conn->commit();							
						}catch(Exception $e){
							$tipo = "alert-error";
							$msg = $conn->errorInfo()[2];
							$erro = 1;
							$conn->rollback();	
						}					
					}else{
						throw new Exception("Não é transação ativa");
					}
					echo json_encode(array('erro' => $erro,'msg' => $msg));
				}
			}else{			
				// Adiciona a View se existir
				incluir (PATH_SYSTEM . PATH_MVC_VIEW . $_GET["controller"] . '.php');
			}
		}
	}