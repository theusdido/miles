<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

$conn = Transacao::Get();
$op = tdClass::Read("opt");
$credor = tdClass::Read("credor");

if ($op == "habdiv"){
	
	// Criando arquivo zip
	$zip = new ZipArchive();
	$arquivozip = "../sistema/projects/2/arquivos/zip/".$credor.'.zip';
	if (file_exists($arquivozip)) unlink($arquivozip);
	if( $zip->open( $arquivozip , ZipArchive::CREATE )  === true){
		$sql = "SELECT * FROM td_arquivos_credor WHERE td_relacaocredores = {$credor}";
		$query = $conn->query($sql);
		while ($linha = $query->fetch()){		
				$arquivonome = retirarAcentos(($linha["descricao"]));
				$zip->addFile(  "../site/enviodocumentos/arquivos_temp/" . $linha["nome"] , $arquivonome );
				//$zip->addFile(  'arquivos/not_today.jpg' , 'pasta/not_today.jpg') ;
				//$zip->addFromString('string.txt' , "Uma string qualquer" );
		}
	}	
	$zip->close();

	header('Location: ' . $arquivozip);
}

if ($op == "farein"){
	$sqlf = "SELECT * FROM td_habilitacaodivergencia WHERE td_processo = 34;";
	$queryf = $conn->query($sqlf);
	
		// Criando arquivo zip
		$zip = new ZipArchive();
		$arquivozip = "../sistema/projects/2/arquivos/zip/processo-34.zip";
		if (file_exists($arquivozip)) unlink($arquivozip);
			
		if( $zip->open( $arquivozip , ZipArchive::CREATE )  === true){
			while ($linhaf = $queryf->fetch()){
				$credor = tdClass::Criar("persistent",array("td_relacaocredores",$linhaf["td_credor"]))->contexto;
				$sql = "SELECT * FROM td_arquivos_credor WHERE td_relacaocredores = " . $linhaf["td_credor"];				
				$query = $conn->query($sql);
				while ($linha = $query->fetch()){
						$arquivonome = retirarAcentos(($linha["descricao"]));
						$zip->addFile(  "../site/enviodocumentos/arquivos_temp/" . $linha["nome"] , $linhaf["id"] . "-".retirarAcentos($credor->nome)."/" . $arquivonome );
						//$zip->addFile(  'arquivos/not_today.jpg' , 'pasta/not_today.jpg') ;
						//$zip->addFromString('string.txt' , "Uma string qualquer" );
				}
		}
		$zip->close();
	}
	header('Location: ' . $arquivozip);	
}