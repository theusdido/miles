<?php
	$package				= tdc::o('package',array( tdc::r('package') . '-' . tdc::r('component') ));
	$listComponents 		= tdc::o('accordion');
	$listComponents->class	= 'accordion-install';

	foreach($package->getModule() as $m){
		$module_id								= $package->getName() . '-' . $m->getName();
		$checkboxInstallAll 					= tdc::o('checkbox');
		$checkboxInstallAll->class 				= 'checkbox-componente-all';
		$checkboxInstallAll->id					= $module_id;
		
		$checkboxRecordAll 						= tdc::o('checkbox');
		$checkboxRecordAll->class 				= 'checkbox-registro-all';
		$checkboxRecordAll->id					= $module_id;

		$table 			= tdc::o('table');
		$table->class 	= 'table table-hover table-install-component';
		$table->addHeadTR('Componente' , 'Registro ' , 'Instalar');
		$trAll			= $table->addHeadTR('' , $checkboxRecordAll->toString() , $checkboxInstallAll->toString());
		$trAll->class 	= 'tr-all';

		foreach($m->getComponent() as $c){
			$checkboxInstall 							= tdc::o('checkbox');
			$checkboxInstall->class 					= 'checkbox-componente';
			$checkboxInstall->id						= $module_id . '-' . $c->getName();
			$checkboxInstall->data_module_name			= $m->getName();
			$checkboxInstall->data_module_description	= $m->getTitle();
			$checkboxInstall->data_module_name			= $m->getName();
			$checkboxInstall->data_module_id			= $module_id;

			$checkboxRecord								= tdc::o('checkbox');
			$checkboxRecord->class						= 'checkbox-registro';
			$checkboxRecord->data_file					= $c->getName();
			$checkboxRecord->data_path					= $module_id;

			$table->addBodyTR($c->getTitle() , $checkboxRecord , $checkboxInstall);
		}
		$listComponents->addItem($m->getTitle(),$table);
	}
	$listComponents->mostrar();