<?php

$op = tdc::r("op");
if ($op == 'lista-conceito'){
    echo json_encode(tdc::da(ENTIDADE));
}