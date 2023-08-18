<?php

class tdEcommerceProduto
{
    public static function calcularPrecoPorkG($valor,$peso){
        return ($peso * $valor);
    }

    public static function retornarPrecoDescricao($produto,$valor,$conn){
    
        // Array Retorno do Preço
        $retornoPreco = array();
    
        // Representante
        $representanteid = isset($_SESSION["representanteid"])?$_SESSION["representanteid"]:0;
        
        // Unidade de Medida
        if ($_SESSION["UNIDADEMEDIDAID"] > 0){
            $sqlUN = "SELECT b.sigla,IFNULL(a.peso,1) peso FROM td_ecommerce_produto a,td_ecommerce_unidademedida b WHERE a.unidademedida = b.id AND a.id = {$produto};";
            $queryUN = $conn->query($sqlUN);
            if ($queryUN->rowCount() > 0){
                $linhaUN 		= $queryUN->fetch();
                $unidadeMedida 	= $linhaUN["sigla"];
                $peso 			= ($linhaUN["peso"]=="0"?1:$linhaUN["peso"]);
            }else{
                $unidadeMedida 	= "";
                $peso 			= 1;
            }
        }
    
        // Promoção
        $sqlPromocao = "SELECT IFNULL(valorfixo,0) valorfixo,IFNULL(percentualdesconto,0) percentualdesconto FROM td_ecommerce_promocao WHERE produto = " .$produto . " AND (DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') >= DATE_FORMAT(datahorainicio,'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') <= DATE_FORMAT(datahorafim,'%Y-%m-%d %H:%i:%s'))";
        $queryPromocao = $conn->query($sqlPromocao);
        
        if ($queryPromocao->rowCount() > 0){
            $linhaPromocao 	= $queryPromocao->fetch();
            $de 			= $valor;
            if ($linhaPromocao["valorfixo"] != "" && $linhaPromocao["valorfixo"] != 0){
                $para 									= $linhaPromocao["valorfixo"];
            }else if ($linhaPromocao["percentualdesconto"] != "" && $linhaPromocao["percentualdesconto"] != 0){
                $para 									= $de - (($de * $linhaPromocao["percentualdesconto"]) / 100);
            }else{
                $para 									= $de;
            }
            $retornoPreco["descricao"]					= '<small><del class="text-danger">R$ '.moneyToFloat($de,true).'</del></small> <p> R$ '.moneyToFloat($para,true).'</p>';
            $retornoPreco["de"] 						= $de;
            $retornoPreco["para"] 						= $para;
            $retornoPreco["de_formato_br"] 				= 'R$ '.moneyToFloat($de,true);
            $retornoPreco["para_formato_br"] 			= 'R$ '.moneyToFloat($para,true);	
            return $retornoPreco;
        }else{
            $sqlProduto = "SELECT IFNULL(valorfixo,0) valorfixo,IFNULL(percentualdesconto,0) percentualdesconto  FROM td_ecommerce_tabelaprecoproduto WHERE produto = {$produto}";
            $queryProduto = $conn->query($sqlProduto);
            if ($queryProduto->rowCount() > 0){
                $linhaProduto = $queryProduto->fetch();
                $de 									= $valor;
                if ($linhaProduto["valorfixo"] != "" && $linhaProduto["valorfixo"] != 0){
                    $para 								= $linhaProduto["valorfixo"];
                }else if ($linhaProduto["percentualdesconto"] != "" && $linhaProduto["percentualdesconto"] != 0){
                    $para 								= $de - (($de * $linhaProduto["percentualdesconto"]) / 100);
                }else{
                    $para 								= $de;
                }
                $retornoPreco["descricao"] 				= '<small><del class="text-danger">R$ '.moneyToFloat($de,true).'</del></small> <p> R$ '.moneyToFloat($para,true).'</p>';
                $retornoPreco["de"] 					= $de;
                $retornoPreco["para"] 					= $para;
                $retornoPreco["de_formato_br"] 			= 'R$ '.moneyToFloat($de,true);
                $retornoPreco["para_formato_br"] 		= 'R$ '.moneyToFloat($para,true);
                return $retornoPreco;
            }else{
                $sqlGeral 	= "SELECT IFNULL(valorfixo,0) valorfixo,IFNULL(percentualdesconto,0) percentualdesconto  FROM td_ecommerce_tabelaprecogeral WHERE id = 1 AND (valorfixo <> '' AND valorfixo > 0) OR (percentualdesconto <> '' AND percentualdesconto > 0)";
                $queryGeral = $conn->query($sqlGeral);
                if ($queryGeral->rowCount() > 0 && $representanteid > 0){
                    $linhaGeral = $queryGeral->fetch();
                    $de 								= $valor;
                    if ($linhaGeral["valorfixo"] != "" && $linhaGeral["valorfixo"] != 0){
                        $para 							= $linhaGeral["valorfixo"];
                    }else if ($linhaGeral["percentualdesconto"] != "" && $linhaGeral["percentualdesconto"] != 0){
                        $para 							= $de - (($de * $linhaGeral["percentualdesconto"]) / 100);
                    }else{
                        $para 							= $de;
                    }
                    $retornoPreco["descricao"] 			= '<small><del class="text-danger">R$ '.moneyToFloat($de,true).'</del></small> <p> R$ '.moneyToFloat($para,true).'</p>';
                    $retornoPreco["de"] 				= $de;
                    $retornoPreco["para"] 				= $para;
                    $retornoPreco["de_formato_br"] 		= 'R$ '.moneyToFloat($de,true);
                    $retornoPreco["para_formato_br"] 	= 'R$ '.moneyToFloat($para,true);
                    return $retornoPreco;
                }else{
                    if($unidadeMedida != ""){
                        $valor = self::calcularPrecoPorkG($valor,$peso);
                    }
                    $retornoPreco["de"] 				= $valor;
                    $retornoPreco["para"] 				= $valor;
                    $retornoPreco["de_formato_br"] 		= 'R$ '.($valor>0?moneyToFloat($valor,true):"0,00");
                    $retornoPreco["para_formato_br"] 	= 'R$ '.($valor>0?moneyToFloat($valor,true):"0,00");
                    $retornoPreco["unidademedida"]		= $unidadeMedida;
                    $retornoPreco["descricao"] 			=  'R$ '.moneyToFloat($valor,true).' '.($unidadeMedida!=""?" por ".$unidadeMedida:" ");
                    return $retornoPreco;
                }
            }
        }
    }

    function getSRCImagemPrincipal($produto,$filaname = ""){
        global $conn;
        $idEntidadeProduto = getEntidadeId("ecommerce_produto");
        if ($filaname == ""){
            $sql = "SELECT imagemprincipal FROM td_ecommerce_produto WHERE id = " . $produto;
            $query = $conn->query($sql);
            if ($linha = $query->fetch()){
                $extensao = getExtensao($linha["imagemprincipal"]);
            }else{
                $extensao = ".jpg";
            }
        }else{
            $extensao = getExtensao($filaname);
        }
    
        $fileCompleteName 		= 'imagemprincipal-'.$idEntidadeProduto.'-'.$produto.'.'.$extensao;
        $path_src_produto 		= PATH_CURRENT_FILE . $fileCompleteName;
        $url_src_produto 		= URL_CURRENT_FILE . $fileCompleteName;
        if (file_exists($path_src_produto)){
            return $url_src_produto;
        }else{
            return URL_TDECOMMERCE.'img/sem-imagem.jpg';
        }
    }

    function isVariacaoPeso($produto){
        global $conn;
        $sql = "
            SELECT 1 FROM td_ecommerce_produto a 
            WHERE (a.unidademedida IS NOT NULL AND a.unidademedida > 0)
            AND EXISTS (SELECT 1 FROM td_ecommerce_peso b WHERE a.id = b.produto AND b.inativo <> 1 OR b.inativo IS NULL);
        ";
        $query = $conn->query($sql);
        if ($query->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function home(){
        $criterio = tdc::f();
        $criterio->isTrue('exibirhome');
        $criterio->onlyActive();

        return tdc::d('td_ecommerce_produto',$criterio);
    }

    public static function getSaldo($produto_id){
		$saldo_estoque 	= 1;
		$ds_estoque 	= tdc::da('td_ecommerce_posicaogeralestoque',tdc::f('produto','=',$produto_id));
		if (sizeof($ds_estoque) > 0){
			$saldo_estoque = $ds_estoque[0]['saldo'];
		}
        return $saldo_estoque;
    }
}