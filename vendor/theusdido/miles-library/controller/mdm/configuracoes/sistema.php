<?php
    switch($_op){
        case 'load':
            tdc::wj( tdc::rua(CONFIG) );
        break;
        case 'salvar':
            $_data                  = tdc::r('data');
            $urlupload				= $_data["urlupload"];
            $urlrequisicoes			= $_data["urlrequisicoes"];
            $urlsaveform 			= $_data["urlsaveform"];
            $urlloadform			= $_data["urlloadform"];
            $urluploadform			= $_data["urluploadform"];
            $urlpesquisafiltro		= $_data["urlpesquisafiltro"];
            $urlenderecofiltro		= $_data["urlenderecofiltro"];
            $urlexcluirregistros	= $_data["urlexcluirregistros"];
            $urlinicializacao 		= $_data["urlinicializacao"];
            $urlloadgradededados	= $_data["urlloadgradededados"];
            $urlloading 			= $_data["urlloading"];
            $urlrelatorio 			= $_data["urlrelatorio"];
            $urlmenu				= $_data["urlmenu"];
            $linguagemprogramacao 	= $_data["linguagemprogramacao"];
            $bancodados				= $_data["bancodados"];
            $pathfileupload			= $_data["pathfileupload"];
            $pathfileuploadtemp		= $_data["pathfileuploadtemp"];
            $tipogradedados			= $_data["tipogradedados"];
            $casasdecimais			= $_data["casasdecimais"];
    
            $sql = "UPDATE ".CONFIG." SET urlupload = '{$urlupload}' ,urlrequisicoes = '{$urlrequisicoes}', urlsaveform = '{$urlsaveform}' 
            , urlloadform = '{$urlloadform}', urlpesquisafiltro = '{$urlpesquisafiltro}', urlenderecofiltro = '{$urlenderecofiltro}'
            , urlexcluirregistros = '{$urlexcluirregistros}', linguagemprogramacao = '{$linguagemprogramacao}', bancodados = '{$bancodados}' 
            , urlinicializacao = '{$urlinicializacao}', urlloadgradededados = '{$urlloadgradededados}', urlloading = '{$urlloading}'
            , urlrelatorio = '{$urlrelatorio}' , urlmenu = '{$urlmenu}', pathfileupload = '{$pathfileupload}' , pathfileuploadtemp = '{$pathfileuploadtemp}'
            , tipogradedados = '{$tipogradedados}' , urluploadform = '{$urluploadform}', casasdecimais = {$casasdecimais}

            WHERE id=1; ";
            $query = $conn->query($sql);
            if ($query){}
        break;
    }