<?php
	function retornaEstado($sigla){
		$retorno = 0;
		if ($conn = Transacao::Get()){
			$sql = "SELECT id FROM td_estado WHERE sigla = '$sigla'";
			$query = $conn->query($sql);
			$linha = $query->fetch();
			$retorno = $linha["id"];
		}else{
			$retorno = 0;
		}
		return $retorno;
	}	
	function trocavazio($str){
		return trim(str_replace("#","",$str));
	}
	function msgErroValidacao($erroMSG,$td = ''){
		if ($td != ''){
			echo '<tr>' . $td . '<tr/>';
		}
		echo '<tr><td colspan="20"><div class="alert alert-danger" role="alert">'.$erroMSG.'</div></td></tr>';
	}

	function finalizar(){
		$js_finalizado = tdClass::Criar('script');
		$js_finalizado->add('
			parent.$("#progress-importar-assembleia").hide();
			parent.$("#retorno").show();
		');
		$js_finalizado->mostrar();
	}

	function validaColuna($celula,$indice){
		if (isset($celula[$indice])){
			if ($celula[$indice]->Data == '#'){
				return '';
			}else{
				return $celula[$indice]->Data;
			}
		}else{
			return '';
		}
	}	