<?php
// Cadastro de Processo


// Bloco
$bloco = tdClass::Criar("bloco");
$bloco->class="col-md-12";

// Título
$titulo = tdClass::Criar("p");
$titulo->class = "titulo-pagina";
$titulo->add(utf8_decode("Processo"));

// Botão Novo ( Processo )
$btnNovo = tdClass::Criar("button");
$btnNovo->add("Novo");
$btnNovo->class = "btn btn-primary b-novo";
$btnNovo->onclick = "novoProcesso();";

// Contexto - Listar - Processo
$contextoListarProcessoID = "crud-contexto-listar-td_processo";
$contextoListarProcesso = tdClass::Criar("div");
$contextoListarProcesso->id = $contextoListarProcessoID;
$contextoListarProcesso->class = "contexto";




// Pesquisa ( Processo )
$pesquisaProcesso = tdClass::Criar("pesquisa");
$pesquisaProcesso->entidade = tdClass::Criar("persistent",array("td_processo"));

// Grade de Dados ( Processo )
$gdProcesso = tdClass::Criar("tabela");
$gdProcesso->class = "table table-hover gradededados";

$gdTHeadProcesso = tdClass::Criar("thead");
$tr = tdClass::Criar("tabelalinha");
$dgThProcesso = tdClass::Criar("tabelahead");
$dgThProcesso->add("ID");
$tr->add($dgThProcesso);

$dgThProcesso = tdClass::Criar("tabelahead");
$dgThProcesso->add("Processo");
$tr->add($dgThProcesso);

$dgThProcesso = tdClass::Criar("tabelahead");
$dgThProcesso->add("<center>Editar</center>");
$tr->add($dgThProcesso);

$dgThProcesso = tdClass::Criar("tabelahead");
$dgThProcesso->add("<center>Excluir</center>");
$tr->add($dgThProcesso);

$dgThProcesso = tdClass::Criar("tabelahead");
$dgThProcesso->add('<center><button type="button" class="btn btn-link gd-sel-todos" aria-label="Selecionar Todos" data-sel="false"><span class="glyphicon glyphicon-check" aria-hidden="true"></span></button></center>');
$tr->add($dgThProcesso);

$gdTHeadProcesso->add($tr);
$gdProcesso->add($gdTHeadProcesso);

$gdTBodyProcesso = tdClass::Criar("tbody");

$dataset = tdClass::Criar("repositorio",array("td_processo"))->carregar();
foreach($dataset as $data){
	$tr = tdClass::Criar("tabelalinha");
	
	$td = tdClass::Criar("tabelacelula");
	$td->add('<span class="grade-info">'.$data->id.'</span>');
	$tr->add($td);

	$td = tdClass::Criar("tabelacelula");
	$td->add('<span class="grade-info">'.$data->numeroprocesso.'</span>');
	$tr->add($td);
	
	$td = tdClass::Criar("tabelacelula");
	$td->add('<center><span class="botao glyphicon glyphicon-edit btn btn-default"></span></center>');
	$tr->add($td);

	$td = tdClass::Criar("tabelacelula");
	$td->add('<center><span class="botao glyphicon glyphicon-trash btn btn-danger"></span></center>');
	$tr->add($td);
	
	$td = tdClass::Criar("tabelacelula");
	$td->add('<center><input type="checkbox" value="'.$data->id.'"></center>');
	$tr->add($td);
	
	$gdTBodyProcesso->add($tr);
}

$gdProcesso->add($gdTBodyProcesso);

$gdTFootProcesso = tdClass::Criar("tfoot");
$tr = tdClass::Criar("tabelalinha");
$td = tdClass::Criar("tabelacelula");
$td->colspan = "5";
$td->add('<input type="button" class="btn btn-default" value="Excluir Selecionados" style="float:right;">');
$tr->add($td);
$gdTFootProcesso->add($tr);

$gdProcesso->add($gdTFootProcesso);
$contextoListarProcesso->add($btnNovo,$pesquisaProcesso,$gdProcesso);

// Contexto - Add - Processo
$contextoAddProcessoID = "crud-contexto-add-td_processo";
$contextoAddProcesso = tdClass::Criar("div");
$contextoAddProcesso->id = $contextoAddProcessoID;
$contextoAddProcesso->class = "contexto";

$aba_html = tdClass::Criar("aba");
$aba_html->nome = "td_processo_aba";
$aba_html->contexto = $contextoAddProcessoID;

// Capa (Processo)
$row = tdClass::Criar("div");
$row->class = "row-fluid form_campos active in";
$row->add('
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="numeroprocesso">Numero do Processo</label><input required="true" class="form-control input-sm formato-numeroprocessojudicial fp gd" data-entidade="td_processo" name="numeroprocesso" id="numeroprocesso" maxlength="25" autocomplete="off"></div></div>
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="escrivao">Escrivão</label><input data-entidade="td_processo" class="form-control input-sm texto-longo fp" name="escrivao" id="escrivao"></div></div>
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="td_magistrado">Magistrado</label><div class="input-group"><select data-entidade="td_processo" name="td_magistrado" id="td_magistrado" required="true" class="form-control input-sm fp"><option value="2">Rafael Salvan Fernandes</option><option value="3">Eliza Maria Strapazzon</option><option value="4">Quitéria Tamanini Vieira Peres</option><option value="5">Caroline Bundchen Felisbino Teixeira </option><option value="6">Luiz Carlos Cittadin da Silva</option></select><span class="input-group-btn"></span></div></div></div>
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="td_juizo">Juízo</label><div class="input-group"><select data-entidade="td_processo" name="td_juizo" id="td_juizo" required="true" class="form-control input-sm fp"><option value="1">1ª Vara da Fazenda Pública</option><option value="2">Vara Única </option><option value="3">1ª Vara Cível</option><option value="4">2.ª Vara Cível</option></select><span class="input-group-btn"></span></div></div></div>	
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="td_comarca">Comarca</label><div class="input-group"><select data-entidade="td_processo" name="td_comarca" id="td_comarca" required="true" class="form-control input-sm fp"><option value="1">Criciúma</option><option value="2">Urussanga</option><option value="3">Orleans</option><option value="4">Tubar</option><option value="5">Joinville</option><option value="6">Monda</option><option value="7">Blumenau</option><option value="8">São José do Cedro</option></select><span class="input-group-btn"></span></div></div></div>	
');
$aba_html->addItem('Capa',$row,"",1);

$row = tdClass::Criar("div");
$row->class = "row-fluid form_campos active in";
$row->add('
	<div class="row-fluid form_campos active in"><input type="hidden" data-entidade="td_processo" class="form-control gd" name="id" id="id"><div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="telefone">Telefone</label><input required="true" class="form-control input-sm formato-telefone fp" data-entidade="td_processo" name="telefone" id="telefone" maxlength="15" autocomplete="off"></div></div>
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="email">E-Mail</label><input required="true" class="form-control input-sm formato-email fp" data-entidade="td_processo" name="email" id="email"></div></div>
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="cep">CEP</label><input class="form-control input-sm formato-cep fp" data-entidade="td_processo" name="cep" id="cep" maxlength="9" autocomplete="off"></div></div>
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="logradouro">Logradouro</label><input data-entidade="td_processo" class="form-control input-sm texto-longo fp" name="logradouro" id="logradouro"></div></div>
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="numero">Número</label><input data-entidade="td_processo" class="form-control input-sm texto-longo fp" name="numero" id="numero"></div></div>
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="complemento">Complemento</label><input data-entidade="td_processo" class="form-control input-sm texto-longo fp" name="complemento" id="complemento"></div></div>
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="bairro">Bairro</label><input data-entidade="td_processo" class="form-control input-sm texto-longo fp" name="bairro" id="bairro"></div></div>
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="td_cidade">Cidade</label><div class="input-group"><select data-entidade="td_processo" name="td_cidade" id="td_cidade" class="form-control input-sm fp"><option value="1">Criciúma</option><option value="2">Mondaí</option><option value="3">Santa Helena</option><option value="4">Três de Maio</option><option value="5">Caibi</option><option value="6">Guaraciaba</option><option value="7">Frederico Westphalen </option><option value="8">Chapecó </option><option value="9">Riqueza</option><option value="10">Três Passos</option><option value="11">Itapiranga</option><option value="12">Caiçara </option><option value="13">Tunapolis</option><option value="14">Redentora</option><option value="15">Maravilha</option><option value="16">Cunha Porã</option><option value="17">Palmitos</option><option value="18">Nova Candelaria</option><option value="19">São João do Oeste</option><option value="20">Esperança do Sul</option><option value="21">Derrubadas</option><option value="22">Iraceminha</option><option value="23">Barra Bonita</option><option value="24">Santa Rosa</option><option value="25">São Jose do Cedro </option><option value="26">Humaita</option><option value="27">Paula Freitas </option><option value="28">Sao José Do Cedro </option><option value="29">Londrina</option><option value="30">São Paulo</option><option value="31">São José</option><option value="32">Jaraguá do Sul</option><option value="33">Iporã do Oeste</option><option value="34">Princesa</option><option value="35">VISTA ALEGRE</option><option value="36">Palmeira das Missoões</option><option value="37">Barra Bonita</option><option value="38">Toledo</option><option value="39">Tiradentes do Sul</option><option value="40">Dionísio Cerqueira</option><option value="41">Taquaruçu do Sul </option><option value="42">Braga</option><option value="43">Erval Seco</option><option value="44">São José das Missões</option><option value="45">Crissiumal</option><option value="46">Flor do Sertão</option><option value="47">Vista Gaúcha </option><option value="48">Bom Progresso</option><option value="49">Coronel Freitas</option><option value="50">Coronel Bicaco</option><option value="51">Dois Irmãos das Missões</option><option value="52">Águas Frias</option><option value="53">Nova Itaberaba</option><option value="54">Ubiretama</option><option value="55">Candido Godoi</option><option value="56">Belmonte</option><option value="57">Nova Itaberaba</option><option value="58">Miraguai</option><option value="59">Senador Salgado Filho</option><option value="60">Três Pontas</option><option value="61">São Miguel da Boa Vista</option><option value="62">Blumenau</option><option value="63">Guaruja do Sul</option><option value="64">Palmitinho</option><option value="65">Florianópolis</option><option value="66">Tuparendi</option><option value="67">Nova Erechim</option><option value="68">Pinheirinho do Vale</option><option value="69">Pato Branco</option><option value="70">Descanso</option><option value="71">Rio do Sul</option><option value="72">Irai</option><option value="73">União do Oeste</option><option value="74">São Miguel do Oeste</option><option value="75">Tenente Portela</option><option value="76">Itaquaquecetuba</option><option value="77">Lajeado</option><option value="78">Cachoeiriha</option><option value="79">Pinhais</option><option value="80">Ponta Grossa</option><option value="81">Tenente Portela</option><option value="82">Matelandia </option><option value="83">São Pedro das Missões</option><option value="84">Timbo</option><option value="85">Apiúna</option><option value="86">Indaial</option><option value="87">Gaspar</option><option value="88">Ascurra</option><option value="89">Rodeio</option><option value="90">Saudades</option><option value="91">Canoas</option><option value="92">São Miguel da Boa Vista</option><option value="93">Pelotas</option><option value="94">Santo Angelo</option><option value="95">Xanxerê</option><option value="96">Pomerode</option><option value="97">Rio Negrinho</option><option value="98">Brusque</option><option value="99">Três Lagoas</option><option value="100">Itajaí</option><option value="101">Palhoça</option><option value="102">Baruerí</option><option value="103">Belo Horizonte</option><option value="104">Joinville</option><option value="105">Itaquaquecetuba</option><option value="106">Cingapura</option><option value="107">São Leopoldo</option><option value="108">Novo Hamburgo</option><option value="109">São Cristovão do Sul</option><option value="110">Irati</option><option value="111">Garulhos</option><option value="112">Itália</option><option value="113">Guabiruba</option><option value="114">Araquari</option><option value="115">Maringa</option><option value="116">Reboucas</option><option value="117">Massaranduba</option><option value="118">Assai</option><option value="119">Caxias do Sul</option><option value="120">Balneário Camburiú</option><option value="121">Nova Odessa</option><option value="122">Americana</option><option value="123">Cabo de Santo Agostin</option><option value="124">Poços de Caldas</option><option value="125">Imbituba</option><option value="126">Itupeva</option><option value="127">Nova Trento</option><option value="128">Tubarão</option><option value="129">Fortaleza</option><option value="130">Alhanda</option><option value="131">João Pessoa</option><option value="132">Rio Negro</option><option value="133">Lages</option><option value="134">Garopaba</option><option value="135">Divinópolis</option><option value="136">Estância Velha</option><option value="137">Santa Isabel</option><option value="138">São Bernardo do Campo</option><option value="139">Paranaguá</option><option value="140">Guaramirim</option><option value="141">Brasilia</option><option value="142">São José dos Pinhais</option><option value="143">Caieiras</option><option value="144">Tijucas</option><option value="145">Osasco</option><option value="146">Porto Velho</option><option value="147">Guaxupé</option><option value="148">Içara</option><option value="149">Rio de Janeiro</option><option value="150">Curitiba</option><option value="151">Guarapuava</option><option value="152">Foz do Iguaçu</option><option value="153">Ribeirão Preto</option><option value="154">Itapeva</option><option value="155">Contagem</option><option value="156">Pouso Alegre</option><option value="157">Campinas</option><option value="158">Santo André</option><option value="159">Orleans</option><option value="160">São José do Rio Preto</option><option value="161">Monte Sião</option><option value="162">Paulista</option><option value="163">Xaxim</option><option value="164">Agrolandia</option><option value="165">Agrolandia</option><option value="166">Seberi</option><option value="167">Boa Vista do Burica</option><option value="168">Águas de Chapecó </option><option value="169">Santana de Parnaiba</option><option value="170">Concórdia</option><option value="171">Esteio</option><option value="172">Pinhalzinho</option><option value="173">Cordilheira Alta</option><option value="174">Abaira</option><option value="175">Honório Serpa</option><option value="176">Cascavel </option><option value="177">Mandaguaçu</option><option value="178">Campo Ere</option><option value="179">Santana do Livramento</option><option value="180">São Sepe</option><option value="181">Tijucas</option><option value="182">Reboucas</option><option value="183">Matinhos</option><option value="184">São Vicente do Sul</option><option value="185">Porto Alegre</option><option value="186">Mata</option><option value="187">Rosário do Sul</option><option value="188">Mafra</option><option value="189">Luzerna</option><option value="190">Serra Alta</option><option value="191">Abelardo Luz</option><option value="192">Irani</option><option value="193">Araucária</option><option value="194">Bagé</option><option value="195">Guaratuba</option><option value="196">Biguaçú</option><option value="197">Tibagi</option><option value="198">Navegantes</option><option value="199">Piratuba</option><option value="200">Ipira</option><option value="201">Joaçaba</option><option value="202">Telemaco Borba</option><option value="203">São Bento do Sul</option><option value="204">Minas do Leão</option><option value="205">Campo Largo</option><option value="206">Apucarana</option><option value="207">Carapicuiba</option><option value="208">Itacorubi</option><option value="209">São Filho</option><option value="210">Siderópolis</option><option value="211">Limeira</option><option value="212">Artur Nogueira</option><option value="213">Monte Mor</option><option value="214">Cotia</option><option value="215">Erechim</option><option value="217">Rio dos Cedros</option><option value="218">São Francisco do Sul</option><option value="219">São Francisco do Sul</option><option value="220">Jundiai</option><option value="221">Votorantim</option><option value="222">Vinhedo</option><option value="223">Ipeuna</option><option value="224">Caçador</option><option value="225">Corupá</option><option value="226">Schroeder</option><option value="227">Luiz Alves</option><option value="228">Almirante Tamandre</option><option value="229">Ananindeua</option><option value="230">POA</option><option value="231">Cuiaba</option><option value="232">Piracicaba</option><option value="233">Montes Claros</option><option value="234">Vitoria</option><option value="235">Natal</option><option value="236">Hidrolandia</option><option value="237">Santa Maria</option><option value="238">São José do Inhacora</option><option value="239">Sertão</option><option value="240">Ijui</option><option value="241">Veranopolis</option><option value="242">Itapira</option><option value="243">Batatais</option></select><span class="input-group-btn"></span></div></div></div>
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="td_estado">Estado</label><div class="input-group"><select data-entidade="td_processo" name="td_estado" id="td_estado" class="form-control input-sm fp"><option value="1">Santa Catarina</option><option value="2">Rio Grande do Sul</option><option value="3">Paran</option><option value="4">São Paulo</option><option value="5">Minas Gerais</option><option value="6">Exterior</option><option value="7">Pernanbuco</option><option value="8">Cear</option><option value="9">Distrito Federal</option><option value="10">Rio de Janeiro</option><option value="11">Rondônia</option><option value="12">Bahia</option><option value="13">Mato Grosso do Sul</option><option value="14">Pará</option><option value="15">Espirito Santo</option><option value="16">Rio Grande do Norte</option><option value="17">Goias</option></select><span class="input-group-btn"></span></div></div></div></div>
');
$aba_html->addItem('Endereço Juízo',$row,"",2);

$row = tdClass::Criar("div");
$row->class = "row-fluid form_campos active in";
$row->add('
	<div data-ncolunas="" class="coluna" style="width: 100%;">
		<div id="div-editor-dipositivodecisao" class="active in">
			<label class="control-label" for="dipositivodecisao">Dispositivo da Decisão</label>
			<input type="hidden" class="form-control fp ckeditor" data-instanciackeditor="editorCK_dipositivodecisao_td_processo" data-entidade="td_processo" name="dipositivodecisao" id="dipositivodecisao" value="">
		</div>
	</div>		
');
$aba_html->addItem('Edital',$row,"",3);

$row = tdClass::Criar("div");
$row->class = "row-fluid form_campos active in";
$row->add('
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="datainiciohabdiv">Data de Inicio da Habilitação/Divergência</label><div class="input-group calendar-picker-group"><input type="text" value="" class="form-control input-sm formato-data formato-calendario fp" data-entidade="td_processo" name="datainiciohabdiv" id="datainiciohabdiv" maxlength="10" autocomplete="off"><span class="input-group-btn"><button class="btn btn-default" type="button"><span aria-hidden="true" class="glyphicon glyphicon-calendar calendar-icon"></span></button></span></div></div></div>
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="datafinalhabdiv">Data Final da Habilitação/Divergência</label><div class="input-group calendar-picker-group"><input type="text" value="" class="form-control input-sm formato-data formato-calendario fp" data-entidade="td_processo" name="datafinalhabdiv" id="datafinalhabdiv" maxlength="10" autocomplete="off"><span class="input-group-btn"><button class="btn btn-default" type="button"><span aria-hidden="true" class="glyphicon glyphicon-calendar calendar-icon"></span></button></span></div></div></div>
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="datainiciohabimp">Data Inicio da Habilitação/Impugnação</label><div class="input-group calendar-picker-group"><input type="text" value="" class="form-control input-sm formato-data formato-calendario fp" data-entidade="td_processo" name="datainiciohabimp" id="datainiciohabimp" maxlength="10" autocomplete="off"><span class="input-group-btn"><button class="btn btn-default" type="button"><span aria-hidden="true" class="glyphicon glyphicon-calendar calendar-icon"></span></button></span></div></div></div>
	<div data-ncolunas="3" class="coluna"><div class="form-group active in"><label class="control-label" for="datafinalhabimp">Data Final da Habilitação/Impugnação</label><div class="input-group calendar-picker-group"><input type="text" value="" class="form-control input-sm formato-data formato-calendario fp" data-entidade="td_processo" name="datafinalhabimp" id="datafinalhabimp" maxlength="10" autocomplete="off"><span class="input-group-btn"><button class="btn btn-default" type="button"><span aria-hidden="true" class="glyphicon glyphicon-calendar calendar-icon"></span></button></span></div></div></div>
');
$aba_html->addItem('Datas',$row,"",4);
$aba_html->addItem('Recuperanda',"Recuperanda","",5);



$contextoAddProcesso->add($aba_html);


$jsProcesso = tdClass::Criar("script");
$jsProcesso->type = "text/javascript";
$jsProcesso->add('
	function novoProcesso(){
		$("#'.$contextoListarProcessoID.'").hide();
		$("#'.$contextoAddProcessoID.'").show();

		// 
		var ckEditorDispositivoDecisao = "";
		if ( ckEditorDispositivoDecisao )
			return;
		var config = {};
		var valor = "";		
		
		ckEditorDispositivoDecisao = CKEDITOR.appendTo( "div-editor-dispositivodecisao" , config, valor );		
	}
');

// Arquivo de Validação em JS
$jsValidar = tdClass::Criar("script");
$jsValidar->type = "text/javascript";
$jsValidar->src = PATH_LIB . "tails/validar.js";

$bloco->add($titulo,$contextoListarProcesso,$contextoAddProcesso,$jsValidar,$jsProcesso);
$bloco->mostrar();