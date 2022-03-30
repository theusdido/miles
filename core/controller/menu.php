 <?php
if (isset($_GET["op"])){
	if ($_GET["op"] == "retorna_dados"){
		$userid 	= Usuario::id();
		$where 		= "WHERE (EXISTS(SELECT 1 FROM td_entidadepermissoes b WHERE b.entidade = a.entidade AND b.usuario = ".$userid." AND b.visualizar = 1)";
		$where 	   .= " OR EXISTS(SELECT 1 FROM td_menupermissoes c WHERE c.menu = a.id AND c.usuario = ".$userid." AND c.permissao = 1)) AND a.descricao <> '' ";

		if ($conn = Transacao::get()){
			$retornomenu = array();
			$sqlMenu = "SELECT * FROM td_menu a ".$where." ORDER BY a.pai,a.ordem;";
			$queryMenu = $conn->query($sqlMenu);
			While ($linhaMenu = $queryMenu->fetch()){
				array_push ($retornomenu,Menu::open($linhaMenu["id"]));
			}
			echo json_encode($retornomenu);
		}
		exit;
	}
}

$menuClass = tdClass::Criar("script");
$menuClass->src = Session::Get("URL_CLASS_TDC") . "menu.class.js";

$menu = tdClass::Criar("bloco",array('menu'));
$menu->class = "col-md-12 col-sm-12";

$jsMenu = tdClass::Criar("script");
$jsMenu->add(
	'
		// Variaveis e Funções Globais em JavaScript		
		var menuprincipal 			= new Menu();
		menuprincipal.contexto 		= "#menu";
		menuprincipal.exibirbrand 	= true;
		menuprincipal.mostrar();
	'
);

$menu->add($menuClass,$jsMenu);