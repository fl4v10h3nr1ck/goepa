 <?php

include_once GOEPA_PATH_ABS.'Base.class.php';

include_once GOEPA_TABELA_PATH_ABS.'Tabela.class.php';

include_once GOEPA_PATH_ABS.'modulos/geral/endereco/Endereco.class.php';


final class Membros extends Base{


private $tab;




	function __construct() {
		
		parent::__construct();
		
		$this->tab= new Tabela();	
		
		$this->endereco= new Endereco();
	}

	
	


	
	public function dependencias(){
	
		echo "
		
		<script src='".GOEPA_PATH_SMP."modulos/geral/membros/membros.js?v=".rand(1, 999)."' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".GOEPA_PATH_SMP."modulos/geral/membros/membros.css?v=".rand(1, 999)."' type='text/css' media='all'>";
	
		$this->tab->dependencias();
		
		$this->endereco->dependencias();
	}

	
	
	
	
	
	public function conteudo(){

		include_once GOEPA_PATH_ABS.'modulos/geral/membros/BeanMembro.class.php';

		$form = "
		<input type='hidden' id='path'   value='/modulos/geral/membros/'/>
		<input type='hidden' id='classe' value='Membros'/>";
	
		if(array_key_exists("sop", $_GET) && strcmp($_GET["sop"], "CAD")==0){
			
			$form .= "
					<div id='area_form' class='formulario'>";
			
			$form .= $this->getForm();
			
			$form .= "	<div style='clear:both'></div>
					</div>";
		}
		else{
		
			$form .= "
			<table>
				<tr>
					<td align='center'>
						<button onclick='javascript:novoMembro()' class='opcao'>
							<img src='".GOEPA_PATH_IMGS."novo.svg'>
						</button>
						<br>Novo
					</td>
					<td  align='center'>
						<button onclick='javascript:editarMembro()' class='opcao'>
							<img src='".GOEPA_PATH_IMGS."editar.svg'>
						</button>
						<br>Editar
					</td>		
				</tr>
			</table>";	
		
			$this->tab->setPathABSDoObjeto(GOEPA_PATH_ABS."modulos/geral/membros/");
			
			$this->tab->setFuncaoDuploClick("editarMembro");
			
			$this->tab->setMostrarOpcoesDePesquisa(false);
			
			$this->tab->setMostrarPaginacao(true);
			
			$form .= $this->tab->getTabela("BeanMembro", 'tab_membros');
		}
		
		echo $form;
	}
	
	
	
	
	
	
	public function getForm(){
		
		include_once GOEPA_BD_PATH_ABS."BdUtil.class.php";
		include_once GOEPA_CMS_PATH_ABS.'Comuns.class.php';
		include_once GOEPA_PATH_ABS.'modulos/geral/membros/BeanMembro.class.php';
		
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		$membro = null;
		
		if(array_key_exists('id', $_GET) && $_GET['id']>0)
			$membro = $bd->getPorId(new BeanMembro, $comuns->anti_injection($_GET['id']));
		
		if(!is_object($membro))
			$membro  = new BeanMembro;
	
		//$membro->etapa_atual = ETP_CAD_MMB_GERAL;
		
		$_SESSION['membro'] = serialize($membro);
		
		return $this->getFormEtapaGeral();
		
	}
		
	


	
	private function getFormEtapaGeral(){	
		
		include_once GOEPA_PATH_ABS.'modulos/geral/empresa/BeanEmpresa.class.php';
		include_once GOEPA_PATH_ABS.'modulos/geral/membros/BeanMembro.class.php';
		include_once GOEPA_CMS_PATH_ABS.'Comuns.class.php';
		include_once GOEPA_CMS_PATH_ABS.'Data.class.php';
		include_once GOEPA_BD_PATH_ABS."BdUtil.class.php";
		
		$comuns = new Comuns();
		$data = new Data();
		$bd = new BdUtil();
		
		$membro = unserialize($_SESSION['membro']);
		
		$form = "		<div id='div_loja' class='item_form campo_dados'>
							Loja:<span class='campo_obrigatorio'>*</span><br>
							<select id='loja'>
								<option value='0'>...</option>";
																	
		$lojas= $bd->getPorQuery(new BeanEmpresa, null, "###.status>0", null);
		
		if(count($lojas)>0){
			foreach($lojas as $loja)
				$form .= "	<option value='".$loja->id."' ".($membro->fk_loja==$loja->id?"selected":"").">".$loja->nome_fantasia."</option>";
		}	
		
		$form .= "			</select>
						</div>
		
						<div id='div_nome' class='item_form campo_dados'>
							Nome Completo:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='nome' value='".$membro->nome."'>
						</div>
						<div id='div_nascimento' class='item_form campo_dados'>
							Nascimento:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='nascimento' class='campo_centralizado' value='".$data->converteEUAParaBR($membro->nascimento)."' maxlength='10' oninput='javascript:mascara(this, formatarData);'>
						</div>
						<div id='div_uf_naturalidade' class='item_form campo_dados'>
							UF:<span class='campo_obrigatorio'>*</span><br>
							<select id='uf_naturalidade' onChange='javascript:mudouUf()'>
									<option value=''>...</option>";				
			
		foreach(array("PA", "AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO") as $uf)
			$form .= "				<option value='".$uf."' ".(strcmp($uf, $membro->uf)==0?"selected":"").">".$uf."</option>";
			
			$form .= "			
							</select>
						</div>
						<div id='div_naturalidade' class='item_form campo_dados'>
							Naturalidade:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='naturalidade' value=''   maxlength='150' disabled>
						</div>
						<div id='div_nacionalidade' class='item_form campo_dados'>
							Nacionalidade:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='nacionalidade' value='".$membro->nacionalidade."'   maxlength='150'>
						</div>
						<div id='div_estado_civil' class='item_form campo_dados'>
							Estado Civil:<span class='campo_obrigatorio'>*</span><br>
							<select id='estado_civil' onChange='javascript:mudouEstadoCivil()'>
									<option value=''>...</option>
									<option value='".ESTADO_CIVIL_SOL."' ".($membro->estado_civil== ESTADO_CIVIL_SOL?"selected":"").">SOLTEIRO</option>
									<option value='".ESTADO_CIVIL_CAS."' ".($membro->estado_civil== ESTADO_CIVIL_CAS?"selected":"").">CASADO</option>
									<option value='".ESTADO_CIVIL_DIV."' ".($membro->estado_civil== ESTADO_CIVIL_DIV?"selected":"").">DIVORCIADO</option>
									<option value='".ESTADO_CIVIL_VIU."' ".($membro->estado_civil== ESTADO_CIVIL_VIU?"selected":"").">VIÚVO</option>
									<option value='".ESTADO_CIVIL_SEP."' ".($membro->estado_civil== ESTADO_CIVIL_SEP?"selected":"").">SEPARADO</option>
							</select>
						</div>
						<div id='div_data_casamento' class='item_form campo_dados'>
							Data Casamento:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='data_casamento' class='campo_centralizado' value='".$data->converteEUAParaBR($membro->data_casamento)."' disabled maxlength='10' oninput='javascript:mascara(this, formatarData);'>
						</div>
						<div id='div_tipo_sangue' class='item_form campo_dados'>
							Tipo Sanguíneo:<br>
							<select id='tipo_sangue'>
									<option value=''>...</option>
									<option value='A+' ". (strcmp($membro->tipo_sangue, 'A+')==0?"selected":"").">A+</option>
									<option value='O+' ". (strcmp($membro->tipo_sangue, 'O+')==0?"selected":"").">O+</option>
									<option value='B+' ". (strcmp($membro->tipo_sangue, 'B+')==0?"selected":"").">B+</option>
									<option value='AB+' ".(strcmp($membro->tipo_sangue, 'AB+')==0?"selected":"").">AB+</option>
									<option value='O-' ". (strcmp($membro->tipo_sangue, 'O-')==0?"selected":"").">O-</option>
									<option value='A-' ". (strcmp($membro->tipo_sangue, 'A-')==0?"selected":"").">A-</option>
									<option value='B-' ". (strcmp($membro->tipo_sangue, 'B-')==0?"selected":"").">B-</option>
									<option value='AB-' ".(strcmp($membro->tipo_sangue, 'AB-')==0?"selected":"").">AB-</option>
							</select>
						</div>
						<div id='div_profissao' class='item_form campo_dados'>
							Profissão:<br>	
							<input type='text' id='profissao' value='".$membro->profissao."' maxlength='150'>
						</div>
						<div style='clear:both'></div>".
						
						$this->getOpcoes(0, ETP_CAD_MMB_GERAL, ETP_CAD_MMB_DOCS);			
		
		return $form;
	}
	
	
	
	
	
	
	
	private function getFormEtapaDocumentos(){	
		
		include_once GOEPA_PATH_ABS.'modulos/geral/membros/BeanMembro.class.php';
		include_once GOEPA_CMS_PATH_ABS.'Comuns.class.php';
		include_once GOEPA_CMS_PATH_ABS.'Data.class.php';
		
		$comuns = new Comuns();
		$data = new Data();
		
		$membro = unserialize($_SESSION['membro']);
		
		$form = "		<div id='div_nome_pai' class='item_form campo_dados'>
							Nome do pai:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='nome_pai' value='".$membro->nome_pai."'>
						</div>
						<div id='div_nome_mae' class='item_form campo_dados'>
							Nome da Mãe:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='nome_mae' value='".$membro->nome_mae."'>
						</div>
						<div id='div_cim' class='item_form campo_dados'>
							CIM:<br>	
							<input type='text' id='cim' value='".$membro->cim."' class='campo_centralizado' maxlength='10'>
						</div>
						<div id='div_cpf' class='item_form campo_dados'>
							CPF:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='cpf' value='".$membro->cpf."' class='campo_centralizado' maxlength='14' oninput='javascript:mascara(this, formatarCPF);'>
						</div>
						<div id='div_rg' class='item_form campo_dados'>
							RG:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='rg' value='".$membro->rg."' class='campo_centralizado' maxlength='10'>
						</div>
						<div id='div_rg_expeditor' class='item_form campo_dados'>
							RG Expeditor:<br>	
							<input type='text' id='rg_expeditor' value='".$membro->rg_expeditor."' class='campo_centralizado' maxlength='10'>
						</div>
						<div id='div_rg_data_expedicao' class='item_form campo_dados'>
							RG Expedição:<br>	
							<input type='text' id='rg_data_expedicao' class='campo_centralizado' value='".$data->converteEUAParaBR($membro->rg_data_expedicao)."' maxlength='10' oninput='javascript:mascara(this, formatarData);'>
						</div>
						<div id='div_titulo_eleitor' class='item_form campo_dados'>
							Título de Eleitor:<br>	
							<input type='text' id='titulo_eleitor' value='".$membro->titulo_eleitor."' class='campo_centralizado' maxlength='15'>
						</div>
						<div id='div_titulo_eleitor_zona' class='item_form campo_dados'>
							Zona:<br>	
							<input type='text' id='titulo_eleitor_zona' value='".$membro->titulo_eleitor_zona."' class='campo_centralizado' maxlength='15'>
						</div>
						<div id='div_titulo_eleitor_sessao' class='item_form campo_dados'>
							Sessão:<br>	
							<input type='text' id='titulo_eleitor_sessao' value='".$membro->titulo_eleitor_sessao."' class='campo_centralizado' maxlength='15'>
						</div>
						<div style='clear:both'></div>".
						
						$this->getOpcoes(ETP_CAD_MMB_GERAL, ETP_CAD_MMB_DOCS, 0);			
		
		return $form;
	}
	
	
	
	
	
	
	
	private function getOpcoes($opcao_anterior, $opcao_atual, $prox_opcao){
		
		$form = "
			<div align='center'>
				<table>
					<tr>
						<td>";
			
		if($opcao_anterior>0)
			$form .= "	
							<div class='bt bt_padrao' id='bt_anterior' onclick='javascript:anterior()'>
								Anterior
							</div>
							<div align='center' class='carregando' id='area_carregando_anterior'>
								<img src='".GOEPA_PATH_IMGS."load.gif'>
							</div>";
		
		$form .= "					
						</td>
						<td>
							<div class='bt bt_padrao' id='bt_proximo' onclick='javascript:proximo(".$opcao_atual.")'>
								".($prox_opcao>0?"Próximo":"Salvar Membro")."
							</div>
							<div align='center' class='carregando' id='area_carregando_proximo'>
								<img src='".GOEPA_PATH_IMGS."load.gif'>
							</div>
						</td>			
					</tr>
				</table>
			</div>";

		return $form;
	}
	
	



	public function validarGeral(){
	
		include_once GOEPA_PATH_ABS.'modulos/geral/membros/BeanMembro.class.php';
		include_once GOEPA_CMS_PATH_ABS.'Comuns.class.php';
		include_once GOEPA_CMS_PATH_ABS.'Data.class.php';

		$comuns = new Comuns();
		$data = new Data();
	
		$_POST['nome'] 				= $comuns->anti_injection($_POST['nome']);
		$_POST['nascimento'] 		= $comuns->anti_injection($_POST['nascimento']);	
		$_POST['uf'] 				= $comuns->anti_injection($_POST['uf']);
		$_POST['nacionalidade'] 	= $comuns->anti_injection($_POST['nacionalidade']);
		$_POST['estado_civil'] 		= $comuns->anti_injection($_POST['estado_civil']);
		$_POST['data_casamento'] 	= $comuns->anti_injection($_POST['data_casamento']);
		$_POST['tipo_sangue'] 		= $comuns->anti_injection($_POST['tipo_sangue']);
		$_POST['profissao'] 		= $comuns->anti_injection($_POST['profissao']);
		$_POST['loja'] 				= $comuns->anti_injection($_POST['loja']);	
		
		if($_POST['loja'] <= 0){
			
			echo '{"status":"ERRO", "erro":"Selecione uma loja."}';
			return;
		}
		
		if(strlen($_POST['nome']) <= 0){
			
			echo '{"status":"ERRO", "erro":"Informe o nome completo."}';
			return;
		}

		if(!$data->validaDataBR($_POST['nascimento'])){
		
			echo '{"status":"ERRO", "erro":"informe uma data de nascimento válida."}';
			return;
		}
		
		if(strlen($_POST['uf']) <= 0){
			
			echo '{"status":"ERRO", "erro":"Escolha a naturalidade."}';
			return;
		}
		
	
		if(strlen($_POST['nacionalidade']) <= 0){
			
			echo '{"status":"ERRO", "erro":"Informe uma nacionalidade."}';
			return;
		}
		
		
		if($_POST['estado_civil'] <= 0){
			
			echo '{"status":"ERRO", "erro":"Escolha um estado civil."}';
			return;
		}
		
		if($_POST['estado_civil'] == ESTADO_CIVIL_CAS){
			
			if(!$data->validaDataBR($_POST['data_casamento'])){
		
				echo '{"status":"ERRO", "erro":"informe uma data de casamento válida."}';
				return;
			}
		}
	
		$membro = unserialize($_SESSION['membro']);
		
		$membro->fk_loja  				=  	$_POST['loja'];
		$membro->nome  					=  	$_POST['nome'];
		$membro->nascimento   			=  	$data->converteBRParaEUA($_POST['nascimento']);
		$membro->uf  					=  	$_POST['uf'];	
		$membro->nacionalidade  		=  	$_POST['nacionalidade'];
		$membro->estado_civil  			=  	$_POST['estado_civil'];
		$membro->data_casamento  		=  	strlen($_POST['data_casamento'])>0?$data->converteBRParaEUA($_POST['data_casamento']):null;
		$membro->tipo_sangue  			=  	$_POST['tipo_sangue'];
		$membro->profissao  			=  	$_POST['profissao'];
		
		$_SESSION['membro'] = serialize($membro);
		
		echo '{"status":"sucesso", "finalizar":false, "form":"'.$comuns->preparaHTMLParaJson($this->getFormEtapaDocumentos()).'"}';	
	}
	

	

	
	public function validarDocumentos(){
	
		include_once GOEPA_PATH_ABS.'modulos/geral/membros/BeanMembro.class.php';
		include_once GOEPA_CMS_PATH_ABS.'Comuns.class.php';
		include_once GOEPA_CMS_PATH_ABS.'Data.class.php';

		$comuns = new Comuns();
		$data = new Data();
	
		$_POST['nome_pai'] 						= $comuns->anti_injection($_POST['nome_pai']);
		$_POST['nome_mae'] 						= $comuns->anti_injection($_POST['nome_mae']);	
		$_POST['cim'] 							= $comuns->anti_injection($_POST['cim']);
		$_POST['cpf'] 							= $comuns->anti_injection($_POST['cpf']);
		$_POST['rg'] 							= $comuns->anti_injection($_POST['rg']);
		$_POST['rg_expeditor'] 					= $comuns->anti_injection($_POST['rg_expeditor']);
		$_POST['rg_data_expedicao'] 			= $comuns->anti_injection($_POST['rg_data_expedicao']);
		$_POST['titulo_eleitor'] 				= $comuns->anti_injection($_POST['titulo_eleitor']);
		$_POST['titulo_eleitor_zona'] 			= $comuns->anti_injection($_POST['titulo_eleitor_zona']);	
		$_POST['titulo_eleitor_sessao'] 		= $comuns->anti_injection($_POST['titulo_eleitor_sessao']);	
		
		
		if(strlen($_POST['nome_pai']) <= 0){
			
			echo '{"status":"ERRO", "erro":"Informe o nome completo do pai."}';
			return;
		}

		if(strlen($_POST['nome_mae']) <= 0){
			
			echo '{"status":"ERRO", "erro":"Informe o nome completo da mãe."}';
			return;
		}
		
		if(!$comuns->validaCPF($_POST['cpf'])){
			
			echo '{"status":"ERRO", "erro":"informe um CPF válido."}';
			return;
		}
		
		if(strlen($_POST['rg']) < 6){
			
			echo '{"status":"ERRO", "erro":"informe um RG válido."}';
			return;
		}
		
		if(strlen($_POST['rg_data_expedicao'])>0){
			
			if(!$data->validaDataBR($_POST['rg_data_expedicao'])){
		
				echo '{"status":"ERRO", "erro":"informe uma data de expedição do RG válida."}';
				return;
			}
		}
	
		$membro = unserialize($_SESSION['membro']);
		
		$membro->nome_pai  					=  	$_POST['nome_pai'];
		$membro->nome_mae  					=  	$_POST['nome_mae'];
		$membro->cim   						=  	$_POST['cim'];
		$membro->cpf  						=  	$_POST['cpf'];
		$membro->rg  						=  	$_POST['rg'];
		$membro->rg_expeditor  				=  	$_POST['rg_expeditor'];
		$membro->rg_data_expedicao  		=  	strlen($_POST['rg_data_expedicao'])>0?$data->converteBRParaEUA($_POST['rg_data_expedicao']):null;
		$membro->titulo_eleitor  			=  	$_POST['titulo_eleitor'];
		$membro->titulo_eleitor_zona  		=  	$_POST['titulo_eleitor_zona'];
		$membro->titulo_eleitor_sessao  	=  	$_POST['titulo_eleitor_sessao'];
		
		$_SESSION['membro'] = serialize($membro);
		
		//echo '{"status":"sucesso", "finalizar":false, "form":"'.$comuns->preparaHTMLParaJson($this->getFormEtapaDocumentos()).'"}';
		
		$this->salvar();
	}
	

	
	
	
	
	public function salvar(){
		
		include_once GOEPA_BD_PATH_ABS."BdUtil.class.php";
		include_once GOEPA_PATH_ABS.'modulos/geral/membros/BeanMembro.class.php';
		
		
		$bd = new BdUtil();
		
		$membro = unserialize($_SESSION['membro']);
		
		if($membro->id<=0){
		
			$membro->status				=1;
			$membro->data_cadastro		= date("Y-m-d");	
			
			$membro->id 				= $bd->novo($membro);
			
			if($membro->id<=0){
					
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}
		}
		else{
			
			if(!$bd->altera($membro)){
			
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}
		}
		
		echo '{"status":"sucesso", "finalizar":true}';
	}
	
	
	
	
	
	
	
	
	
	
/*
	
	public function ativarDesativarUser(){
			
		include_once GOEPA_BD_PATH_ABS."BdUtil.class.php";
		include_once GOEPA_PATH_ABS.'modulos/geral/usuarios/BeanUsuario.class.php';
		include_once GOEPA_CMS_PATH_ABS.'Comuns.class.php';
	
		$comuns = new Comuns();
		$bd = new BdUtil();
	
		$_POST['id_usuario'] = 	$comuns->anti_injection($_POST['id_usuario']);
	
		$usuario = $bd->getPorId(new BeanUsuario(), $_POST['id_usuario']);
				
		if(is_object($usuario)){
			
			if($usuario->status>0)
				$usuario->status = 0;
			else
				$usuario->status = 1;
			
			if($bd->altera($usuario)){
			
				echo '{"status":"sucesso"}';
				return;
			}
		}
		
		echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
	}
	
*/
	
	

}


?>