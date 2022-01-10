<?php
	$package				= tdc::o('package',array( tdc::r('package') . '-' . tdc::r('component') ));
	$listComponents 		= tdc::o('accordion');
	$listComponents->class	= 'accordion-install';

	foreach($package->getModule() as $m){
		$checkboxInstallAll 					= tdc::o('checkbox');
		$checkboxInstallAll->class 				= 'checkbox-componente-all';
		$checkboxInstallAll->id					= $package->getName() . '-' . $m->getName();
		
		$checkboxRecordAll 						= tdc::o('checkbox');
		$checkboxRecordAll->class 				= 'checkbox-registro-all';
		$checkboxRecordAll->id					= $package->getName() . '-' . $m->getName();

		$table 			= tdc::o('table');
		$table->class 	= 'table table-hover table-install-component';
		$table->addHeadTR('Componente' , 'Registro ' , 'Instalar');
		$trAll			= $table->addHeadTR('' , $checkboxRecordAll->toString() , $checkboxInstallAll->toString());
		$trAll->class 	= 'tr-all';

		foreach($m->getComponent() as $c){
			$path										= $package->getName() . '-' . $m->getName();
			$checkboxInstall 							= tdc::o('checkbox');
			$checkboxInstall->class 					= 'checkbox-componente';
			$checkboxInstall->id						= $path . '-' . $c->getName();
			$checkboxInstall->data_module_name			= $m->getName();
			$checkboxInstall->data_module_description	= $m->getTitle();

			$checkboxRecord								= tdc::o('checkbox');
			$checkboxRecord->class						= 'checkbox-registro';
			$checkboxRecord->data_file					= $c->getName();
			$checkboxRecord->data_path					= $path;

			$table->addBodyTR($c->getTitle() , $checkboxRecord , $checkboxInstall);
		}
		$listComponents->addItem($m->getTitle(),$table);
	}
	$listComponents->mostrar();