<?php
    switch($_op){
        case 'atualizar-list':
            $environment_list_target = array();
            $environment_current = NULL;
            foreach($mjc->environments as $key => $env)
            {
                $env_obj = array(
                    'label'     => $env->label,
                    'database'  => $env->database
                );

                if ($mjc->environment == $key){
                    $environment_current = $env_obj;
                }else{
                    array_push($environment_list_target,$env_obj);
                }
            }
            tdc::wj(array(
                'current' => $environment_current,
                'target' => $environment_list_target
            ));
        break;
    }