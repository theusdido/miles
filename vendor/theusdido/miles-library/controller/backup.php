<?php
ini_set('display_errors',1); ini_set('display_startup_erros',1); error_reporting(E_ALL);//force php to show any error message

backup_tables("mysql20.redehost.com.br:41890","theusdido","spespcfcdido10","innovare-producao");//don't forget to fill with your own database access informations



function backup_tables($host,$user,$pass,$name)
{
	
    $link = mysqli_connect($host,$user,$pass);
    mysqli_select_db($link, $name);
        $tables = array();
        $result = mysqli_query($link, 'SHOW TABLES');
        $i=0;
        while($row = mysqli_fetch_row($result))
        {
            $tables[$i] = $row[0];
            $i++;
        }
    $return = "";
    foreach($tables as $table)
    {
        $result = mysqli_query($link, 'SELECT * FROM '.$table);
        $num_fields = mysqli_num_fields($result);
        $return .= 'DROP TABLE IF EXISTS '.$table.';';
        $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE '.$table));
        $return.= "\n\n".$row2[1].";\n\n";
        for ($i = 0; $i < $num_fields; $i++)
        {
            while($row = mysqli_fetch_row($result))
            {
                $return.= 'INSERT INTO '.$table.' VALUES(';
                for($j=0; $j < $num_fields; $j++)
                {
                    $row[$j] = addslashes(tdc::utf8($row[$j]));
                    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                    if ($j < ($num_fields-1)) { $return.= ','; }
                }
                $return.= ");\n";
            }
        }
        $return.="\n\n\n";
    }
	$arquivo = '../../arquivos/innovare_'.date("Y-m-d-H-i-s").'_db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql';
    //save file
    $handle = fopen($arquivo,'w+');//Don' forget to create a folder to be saved, "db_bkp" in this case
    fwrite($handle, $return);
    fclose($handle);
	
	// Enviando para o DROPBOX
	/*
	include '../../system/lib/dropbox/DropboxUploader.php';	
	$uploader = new DropboxUploader('theusdido@hotmail.com', 'spespcfc10');
	$uploader->upload($arquivo);
	*/
	
	include("../../system/lib/phpmailer/class.phpmailer.php");
	$mail = new PHPMailer();
	//$mail->SetLanguage("pt-",PATH_LIB . "phpmailer/");

	$mail->IsSMTP(); 			
	$mail->Host = "mail.innovareadministradora.com.br";
	$mail->SMTPAuth = true;
	#$mail->Username = "contato@innovareadministradora.com.br"; 
	#$mail->Password = "Inn2014@@@";				
	#$mail->From = "contato@innovareadministradora.com.br";
	$mail->Username = "ruan@innovareadministradora.com.br";
	$mail->Password = "Hardcore171@@";
	$mail->From = "ruan@innovareadministradora.com.br";

	$mail->FromName = "Innovare Administradora Judicial";

	//Enderecos que devem ser enviadas as mensagens
	$mail->AddAddress("backupinnovare@gmail.com","Backup Automático");
	
	$mail->WordWrap = 50; 
	//anexando arquivos no email
	if (file_exists($arquivo)){
		$mail->AddAttachment($arquivo);
	}			
	//$mail->AddAttachment("imagem/foto.jpg");
	$mail->IsHTML(true); //enviar em HTML
	
	$mail->Subject = "Decisão Proferida";
	$mail->Body = "
		<img src='http://www.innovareadministradora.com.br/sistema/imagens/logo_nova.png' />
		<p>Backup Automático</p>
		<p>".date("d/m/Y H:i:s").".</p>
		<br /><br /><br />
		<p><b>Desenvolvimento - Edilson Bitencourt<br />
			INNOVARE - ADMINISTRADORA EM RECUPERAÇÃO E FALÊNCIA ME - SS</b><br />
			(48) 34138211 | 99757977 | 99783115<br />
			Travessa Germano Magrin, nº 100, sala 407, Edifício Parthenon, Centro, <br />
			88802-090 - Criciúma - Santa Catarina<br />
			<a href='http://www.innovareadministradora.com.br'>http://www.innovareadministradora.com.br</a><br />
		</p>
	";
	if(!$mail->Send()){
		echo '<center><h4 style="color:#FF0000;font-weight:bold;font-size:16px;">Erro ao enviar E-Mail. Motivo: '.$mail->ErrorInfo.'</h4></center>';
		exit;
	}
	
    echo "bkp efetuado com sucesso";//Sucessfuly message
}
?>