<?php
    if (tdc::r('op') == 'instalar'){
        $_htaccess_file = '.htaccess';
        $fpHtaccess = fopen($_htaccess_file,"w");
        fwrite($fpHtaccess,'
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    RewriteRule ^install[\/]?$ index.php?controller=install [L]
    RewriteRule ^mdm[\/]?$ index.php?controller=mdm/index
</IfModule>
    
<IfModule mod_env.c>
    SetEnv _PROJECT_NAME_IDENTIFY_PARAMS "'.$_project_folder.'"
    SetEnv _ENV "dev"
</IfModule>

# php -- BEGIN cPanel-generated handler, do not edit
# Defina o pacote “ea-php74” como a linguagem padrão de programação “PHP”.
<IfModule mime_module>
    #AddHandler application/x-httpd-ea-php74 .php .php7 .phtml
</IfModule>
# php -- END cPanel-generated handler, do not edit
');
    fclose($fpHtaccess);
    chmod($_htaccess_file,0777);
}