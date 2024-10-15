<?php
    $component = tdc::r('component');
    
    $file_name_component = array($component);
    
    $file_path_html = $component . '/' . end($file_name_component) . '.html';
    $file_path_js   = $component . '/' . end($file_name_component) . '.js';
    $file_path_css  = $component . '/' . end($file_name_component) . '.css';

    // Arquivo HTML
    tdFile::add($file_path_html,'');

    // Arquivo JS
    tdFile::add($file_path_html,'');

    // Arquivo CSS
    tdFile::add($file_path_html,'');    