<?php

$format     = tdc::r("format");
$filename   = tdc::r("filename");

switch($format){
    case 'excel':
        header("Content-type: application/vnd.ms-excel");
        header("Content-type: application/force-download");
        header("Content-Disposition: attachment; filename=teste.xls");
        header("Pragma: no-cache");
    break;
}