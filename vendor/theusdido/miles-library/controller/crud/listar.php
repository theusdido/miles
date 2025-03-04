<?php

// Botão Novo
$btn_novo 			= tdClass::Criar("button");
$btn_novo->class 	= "btn {$btnNovoType} b-novo";
$span_novo 			= tdClass::Criar("span");
$span_novo->class 	= "fas fa-plus";
$btn_novo->add($span_novo,$btnNovoLabel);
$crudListar->add($btn_novo);