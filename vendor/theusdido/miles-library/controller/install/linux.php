<?php
    #echo "\n\n";
    #echo shell_exec('whoami');
    #exit;
    //$dominio = $_GET['dominio'];
    $dominio = 'villafrancioni.com.br';

    $fp = fopen("/etc/apache2/sites-available/{$dominio}.conf",'w');
    fwrite($fp,trim('
        <VirtualHost *:80>
            ServerName '.$dominio.'
            ServerAlias www.'.$dominio.'

            ServerAdmin webmaster@localhost
            DocumentRoot /var/www/'.$dominio.'/

            ErrorLog ${APACHE_LOG_DIR}/error.log
            CustomLog ${APACHE_LOG_DIR}/access.log combined
        </VirtualHost>
    '));
    fclose($fp);
    //shell_exec('mkdir teste');