<?php
	switch($op){
		case 'get':
            tdc::wj( tdc::da('td_website_geral_perguntasfrequentes',tdc::f()->asc('ordem')));
        break;
    }