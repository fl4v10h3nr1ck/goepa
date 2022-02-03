<?php


include_once GOEPA_PATH_ABS.'Base.class.php';

include_once GOEPA_PATH_ABS.'modulos/geral/login/Login.class.php';

include_once GOEPA_PATH_ABS.'modulos/geral/Mod_GeralConexao.class.php';



final class Estrutura extends Base{

	private $login;

	private $opcao;
		
	private $pagina_atual;
		
	private $status;

	private $local;

	private $sandbox = false;

/*************************************************/

	private $mod_geral;
	
	



	function __construct(){
		
		parent::__construct();
		
		$this->login = new Login;
		
		$this->local = "";
		
		
		if($this->login->permitirAcesso()){
		
			$this->status = true;
		
			$this->mod_geral = new Mod_GeralConexao();
		
			$this->setLocal();	
		}
		else
			$this->status = false;
	}

	
	
	
	
	private function setLocal(){
		
		if(array_key_exists("op", $_GET)){
			
			switch($_GET["op"]){
				
		/******************* recursos cadastro ********************/
			
				case Mod_GeralConexao::COD_MODULO:
				case Mod_GeralConexao::COD_CAD_EMPRESA:
				case Mod_GeralConexao::COD_CAD_USUARIOS:
				case Mod_GeralConexao::COD_CAD_GRUPOS:
				case Mod_GeralConexao::COD_CONFIG:
				case Mod_GeralConexao::COD_CAD_MEMBROS:
				
				$this->opcao =  $this->mod_geral->getOpcao();
				
				//$this->setIndicadores(array(Mod_GeralConexao::COD_MODULO, Mod_GeralConexao::NOME_MODULO), $this->mod_geral->getTitulo());
				
				$this->setIndicadores(null, $this->mod_geral->getTitulo());
				break;
												
				
				

				case COD_MOD_PRIN:
				default:
				$this->setIndicadores(null, "");
			}
		}
		else	
			$this->setIndicadores(null, "");
	}
	
	
	
	
	
	
	private function setIndicadores($anterior, $local){
		
		if(array_key_exists("op", $_GET) && strlen($_GET["op"])>0 && strcmp($_GET["op"], COD_MOD_PRIN)!=0){
		
			if(array_key_exists("sop", $_GET) && strcmp($_GET["sop"], "CAD")==0){
																
				if(array_key_exists("id", $_GET) && $_GET["id"]>0){		
					$this->local = "<a href='javascript:carregaPagina(\"op=".COD_MOD_PRIN."\")'>Início</a>".($anterior!=null && count($anterior)>0?" | <a href='javascript:carregaPagina(\"op=".$anterior[0]."\")'>".$anterior[1]."</a>":"")." | <a href='javascript:carregaPagina(\"op=".$_GET["op"]."\")'>".$local."</a> | <a href='javascript:carregaPagina(\"op=".$_GET["op"]."&sop=CAD&id=".$_GET["id"]."\")'>Alteração</a>";
					$this->pagina_atual = "<a href='javascript:carregaPagina(\"op=".$_GET["op"]."&sop=CAD&id=".$_GET["id"]."\")'>Alteração de ".$local."</a>";
				}
				else{
																	
					$this->local = "<a href='javascript:carregaPagina(\"op=".COD_MOD_PRIN."\")'>Início</a>".($anterior!=null && count($anterior)>0?" | <a href='javascript:carregaPagina(\"op=".$anterior[0]."\")'>".$anterior[1]."</a>":"")." | <a href='javascript:carregaPagina(\"op=".$_GET["op"]."\")'>".$local."</a> | <a href='javascript:carregaPagina(\"op=".$_GET["op"]."&sop=CAD\")'>Inclusão</a>";
					$this->pagina_atual = "<a href='javascript:carregaPagina(\"op=".$_GET["op"]."&sop=CAD\")'>Inclusão de ".$local."</a>";
				}	
			}
			else{
				
				if(strcmp($_GET["op"], $anterior[0])==0){
				
					$this->local = "<a href='javascript:carregaPagina(\"op=".COD_MOD_PRIN."\")'>Início</a> | <a href='javascript:carregaPagina(\"op=".$anterior[0]."\")'>".$anterior[1]."</a>";
					$this->pagina_atual = "<a href='javascript:carregaPagina(\"op=".$anterior[0]."\")'>".$anterior[1]."</a>";
				}
				else{
					
					$this->local = "<a href='javascript:carregaPagina(\"op=".COD_MOD_PRIN."\")'>Início</a>".($anterior!=null && count($anterior)>0?" | <a href='javascript:carregaPagina(\"op=".$anterior[0]."\")'>".$anterior[1]."</a>":"")." | <a href='javascript:carregaPagina(\"op=".$_GET["op"]."\")'>".$local."</a>";
					$this->pagina_atual = "<a href='javascript:carregaPagina(\"op=".$_GET["op"]."\")'>".$local."</a>";
				}
			}
		}
		else{
			$this->local = "<a href='javascript:carregaPagina(\"op=".COD_MOD_PRIN."\")'>Início</a>";
			$this->pagina_atual = "<a href='javascript:carregaPagina(\"op=".COD_MOD_PRIN."\")'>Ínicio</a>";
		}
	}
	
	
	
	

	public function dependencias(){
		
		echo "
		
		<link rel='stylesheet' type='text/css' href='".GOEPA_PATH_SMP."sistema/estrutura/estrutura.css?v=".rand(1, 999)."'>
		
		<script type='text/javascript' src='".GOEPA_JS_PATH_SMP."jquery-3.1.1.min.js'></script>
		
		<script type='text/javascript' src='".GOEPA_PATH_SMP."sistema/estrutura/estrutura.js?v=".rand(1, 999)."'></script>
		
		<link rel='stylesheet' href='".GOEPA_JS_PATH_SMP."select2.min.css' type='text/css' media='all'>
		
		<script type='text/javascript' src='".GOEPA_JS_PATH_SMP."select2.min.js'></script>
		
		<link href='".GOEPA_JS_CALENDAR_PATH."styles/glDatePicker.default.css' rel='stylesheet' type='text/css'>
		
		<script type='text/javascript' src='".GOEPA_JS_CALENDAR_PATH."glDatePicker.js'></script>
		
		<link rel='stylesheet' href='".GOEPA_JS_PATH_SMP."jquery-ui.css'>
			  
		<script type='text/javascript'  src='".GOEPA_JS_PATH_SMP."jquery-ui.js'></script>";	
		
		
		$this->login ->dependencias();
		
		if($this->status){
			
			$this->mod_geral->dependencias();
		}
		
		if(is_object($this->opcao))
			$this->opcao ->dependencias();
		
	}


	
	
	
	public function cabecalho(){
	
		$cabecalho = '

		<meta charset="utf-8">
		<meta http-equiv="content-language" content="pt-br">
		<meta name="author" content="Eng. Flavio Henrique P Sousa">
		<meta name="reply-to" content="contato@mscsolucoes.com.br">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width">
		
		<title>'.NOME_SISTEMA.'</title>	
		<link rel="shortcut icon" sizes="32x32" href="'.GOEPA_PATH_IMGS.'favicon.png" type="image/x-icon">';
		
		echo $cabecalho;
	}
	
	
	
	
	
	public function topo(){
	
		$form =
			"	<div id='area_logo'>
					<a href='".GOEPA_PATH_SMP."'>
						<img src='".GOEPA_PATH_IMGS."logo.png' id='logo'>
					</a>
				</div>
				<div id='area_centro' align='center'>";
		
	if($this->status)	
		$form .= "
					<div align='right' id='area_pag_atual'>
					Você está em: <i>".$this->pagina_atual."</i>
					</div>";
	
		$form .= "	
				</div>
				<div id='area_infos' align='right'>";

		if($this->status){
			
			include_once GOEPA_CMS_PATH_ABS.'Comuns.class.php';
		
			$comuns  = new Comuns;
			
			$form .= "
					<table style='margin-right:20px'>
						<tr>
							<td align='left' style='width:120px'>
							Olá, <b>".$comuns->formataNome($this->gandalf->usuarioAtual()->nome_completo)."</b>
							</td>
						</tr>
						<tr>
							<td>
								<input type='hidden' id='path_sair'   value='/modulos/geral/login/'/>
								<input type='hidden' id='classe_sair' value='Login'/>
								<div class='bt bt_padrao' id='bt_sair' onClick='javascript:sair()'>
									Sair
								</div>
								<div align='center' class='carregando' id='area_carregando_sair'>
									<img src='".GOEPA_PATH_IMGS."load.gif'>
								</div>
							</td>
						</tr>
						<tr>
							<td>";
						
		if(!AMB_PRODUCAO)							
			$form .=	"		<span style='color:red'><b>AMB. SANDBOX</b></span>";			
						
		$form .=	"		</td>
						</tr>		
					</table>";
		}
		
		$form .= "
				</div>
				<div style='clear:both'></div>";
		
		echo $form;
	}
	
	
	
	
	
	
	public function barra(){
	
		$op = COD_MOD_PRIN;
		
		if(array_key_exists("op", $_GET))
			$op = $_GET["op"];

		$form = "";
		
		if($this->status){		
			$form .="	
				<div id='menu_prin'>
					<ul>	
						<li>
							<div class='item_menu_prin ".(strcmp($op, COD_MOD_PRIN)==0?"item_menu_prin_selec ":"")."' onClick='javascript:carregaPagina(\"op=".COD_MOD_PRIN."\")'>
								Início
							</div>
						</li>";
						
			//$form  .= $this->mod_geral->getMenu(strcmp($op, Mod_GeralConexao::COD_MODULO)==0);			
						
			$form .= "					
					</ul>
				</div>";
		}
		
		$form .= "
				<div style='clear:both'></div>";
	
		echo $form;
	}
	
	
	
	
	
	public function conteudo(){
	
		echo "<input type='hidden' id='GOEPA_PATH_SMP' value='".GOEPA_PATH_SMP."'>";

		if($this->status){
	
			echo  "<b><i>".$this->local."</i></b><br><br>";
				
			if(is_object($this->opcao))	
				$this->opcao->conteudo();
			else{	
				echo  "
					<div align='center'>
						".$this->mod_geral->getMenu()."
					</div>";
			}
			
			echo  "
					<div id='area_rel_diversos'></div>
					<div id='area_outros_dialogos'></div>";
		}
		else
			$this->login->getFormDeLogin();
			
	}
	
	
	
	
	
	
	public function rodape(){
	
		echo "Copyright ".date("Y")." ".NOME_SISTEMA.". Todos os Direitos Reservados.";
	}



	
	
}	
		
?>