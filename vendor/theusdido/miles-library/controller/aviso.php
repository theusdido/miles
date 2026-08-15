<?php

	$bloco_aviso = tdClass::Criar("bloco");
	$bloco_aviso->class = "col-md-12";

	$sql = "
		SELECT 
			a.id,
			a.tipoaviso,
			a.mensagem
		FROM ".AVISO." a
		LEFT JOIN ".TIPOAVISO." b ON b.id = a.tipoaviso
		WHERE a.datainicio <= NOW()
		AND a.datafinal >= NOW()
		AND (a.inativo <> 1 OR a.inativo IS NULL)
		ORDER BY a.datafinal DESC;
	";

	$query = $conn->query($sql);
	$dataset = $query->fetchAll(PDO::FETCH_OBJ);

	foreach($dataset as $aviso){
		$panel = tdClass::Criar("card");
		$panel->addHeader("AVISO");
		switch ($aviso->tipoaviso){
			case 1:
				$panel->tipo = "success";
			break;
			case 2:
				$panel->tipo = "warning";
			break;
			case 3:
				$panel->tipo = "danger";
			break;
			case 4:
				$panel->tipo = "info";
			break;
			default:
				$panel->tipo = "secondary";
		}
		$panel->addBody($aviso->mensagem);
		$bloco_aviso->add($panel);
	}
	
	if (sizeof($dataset) > 0){
		$bloco_aviso->mostrar();
	}