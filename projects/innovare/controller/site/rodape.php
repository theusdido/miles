<?php
    switch($_op){
        case 'politica-privacidade':
            tdc::wj(tdc::rua('td_website_geral_politicaprivacidade'));
        break;
        case 'politica-cookie':
            tdc::wj(tdc::rua('td_website_geral_politicacookies'));
        break;
        case 'redessociais':
            tdc::wj(tdc::da('td_website_geral_redessociais'));
        break;
    }