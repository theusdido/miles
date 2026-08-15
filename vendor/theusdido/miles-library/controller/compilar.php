<?php

$op = tdc::r("op");
if ($op == 'lista-conceito'){
    echo json_encode(tdc::da(ENTIDADE));
}

// Gera o arquivo JavaScript do MDM
$javascriptfile = getUrl(URL_MILES . 'index.php?controller=mdm/javascriptfile');
var_dump($javascriptfile);