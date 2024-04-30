<?php
	
	$classes_static = array(
		'bd/transacao.class.php',	
		'tdc/tdclass.class.php',
		'tdc/campos.class.php',
		'tdc/debug.class.php',
		'tdc/session.class.php',
		'tdc/tdfile.class.php',
		'tdc/tdlista.class.php',
		'install/install.class.php',
		'interface/cookie.class.php',
		'system/entity.class.php',
		'system/field.class.php',
		'system/relationship.class.php',
		'system/permission.class.php',
		'system/filterattribute.class.php',
		'system/query.class.php',
		'system/reporty.class.php',
		'system/status.class.php',
		'system/movimentation.class.php'
	);

	foreach($classes_static as $c){
		// Carrega as classes que não são instânciadas
		include_once $_path_class . $c;
	}