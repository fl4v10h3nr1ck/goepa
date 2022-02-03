 <?php



final class Mod_GeralConexao{


	const COD_MODULO 					= "CDT";
	const NOME_MODULO 					= "Geral";
	const PATH_MODULO 					= GOEPA_PATH_ABS."modulos/geral/";
	
	const COD_CAD_EMPRESA 				= "EMP";
	const COD_CAD_USUARIOS 				= "USR";
	const COD_CAD_GRUPOS 				= "GRP";
	const COD_CAD_MEMBROS 				= "MMB";
	
	const COD_CONFIG 					= "CFG";
	

	function __construct() {}
	
	
	
	public function dependencias(){}
	
	

	
	final function getMenu($selecionado=false, $producao=false){
		/*
		$menu = "
		<li>
			<div class='item_menu_prin ".($selecionado?"item_menu_prin_selec":"")."' onClick='javascript:carregaPagina(\"op=".self::COD_MODULO."\")'>
				".self::NOME_MODULO."
			</div>
			<div class='sub_menu_prin'>
				<div class='menu_separador'>
					<b>Cadastro</b>
					<hr width='97%' style='color:#FFF'>
				</div>
				<div  class='item_sub_menu_prin' onClick='javascript:carregaPagina(\"op=".self::COD_CAD_EMPRESA."\")'>      
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Empresas
				</div>
				<div  class='item_sub_menu_prin' onClick='javascript:carregaPagina(\"op=".self::COD_CAD_USUARIOS."\")'>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Usuários
				</div>
				<!-- 
				<div  class='item_sub_menu_prin' onClick='javascript:carregaPagina(\"op=".self::COD_CAD_GRUPOS."\")'>      
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Grupos de Usuários
				</div> -->
				<div class='menu_separador'>
					<b>Configurações</b>
					<hr width='97%' style='color:#FFF'>
				</div>
				<div  class='item_sub_menu_prin' onClick='javascript:carregaPagina(\"op=".self::COD_CONFIG."\")'>
					&nbsp;&nbsp;&nbsp;&nbsp;Configurações
				</div>";
						
		$menu .= "		
			</div>
		</li>";
		*/
		
		$menu = "
				<div class='item_principal' align='center' onClick='javascript:carregaPagina(\"op=".self::COD_CAD_USUARIOS."\")'>
					<img src='".GOEPA_PATH_IMGS."usuario.svg' class='icon'>
					<br>
					<span class='titulo'>Usuários</span>
					<br>
				</div>
				
				<div class='item_principal' align='center' onClick='javascript:carregaPagina(\"op=".self::COD_CAD_EMPRESA."\")'>
					<img src='".GOEPA_PATH_IMGS."empresa.svg' class='icon'>
					<br>
					<span class='titulo'>Lojas</span>
					<br>
				</div>
				
				<div class='item_principal' align='center' onClick='javascript:carregaPagina(\"op=".self::COD_CAD_MEMBROS."\")'>
					<img src='".GOEPA_PATH_IMGS."membros.svg' class='icon'>
					<br>
					<span class='titulo'>Membros</span>
					<br>
				</div>
				
				<div style='clear:both'></div>";
		
		return $menu;
		
	}
	
	

		
	
	final function getOpcao(){
		
		switch($_GET["op"]){
		
			case self::COD_MODULO:
			return $this;
		
			case self::COD_CAD_EMPRESA:
			include_once self::PATH_MODULO."empresa/Empresa.class.php";
			return new Empresa();
		
			case self::COD_CAD_USUARIOS:
			include_once self::PATH_MODULO."usuarios/Usuarios.class.php";
			return new Usuarios();
					
			case self::COD_CAD_GRUPOS:
			include_once self::PATH_MODULO."grupos/Grupo.class.php";
			return  new Grupo();
			
			case self::COD_CAD_MEMBROS:
			include_once self::PATH_MODULO."membros/Membros.class.php";
			return  new Membros();
		
			case self::COD_CONFIG:
			include_once self::PATH_MODULO."ConfiguracaoGeral.class.php";
			return new ConfiguracaoGeral();
		}
	}
	
	
	
	
	
	final function getTitulo(){
		
		switch($_GET["op"]){
		
			case self::COD_CAD_EMPRESA:
			return "Lojas";
		
			case self::COD_CAD_USUARIOS:		
			return "Usuários";
					
			case self::COD_CAD_GRUPOS:			
			return "Grupos";

		case self::COD_CAD_MEMBROS:			
			return "Membros";			
			
			case self::COD_CONFIG:				
			return  "Configurações";	
		}
	}
	
	
	

	
	public function conteudo(){
		
		$form = "
						<div>
							<b>Cadastro</b>
							<hr width='100%'>
						</div>
						<div style='clear:both'></div>
						<div class='item_principal' align='center' onClick='javascript:carregaPagina(\"op=".self::COD_CAD_EMPRESA."\")'>
							<img src='".GOEPA_PATH_IMGS."empresa.svg' class='icon'>
							<br>
							<span class='titulo'>Empresa</span>
							<br>
						</div>	
				
						
						<div class='item_principal' align='center' onClick='javascript:carregaPagina(\"op=".self::COD_CAD_USUARIOS."\")'>
							<img src='".GOEPA_PATH_IMGS."usuario.svg' class='icon'>
							<br>
							<span class='titulo'>Usuários</span>
							<br>
						</div>
						
						<!-- 
						<div class='item_principal' align='center' onClick='javascript:carregaPagina(\"op=".self::COD_CAD_GRUPOS."\")'>
							<img src='".GOEPA_PATH_IMGS."grupo.svg' class='icon'>
							<br>
							<span class='titulo'>Grupos de Usuários</span>
							<br>
						</div>	
						 -->
						<div style='clear:both'></div>			
						<div>
							<b>Configurações</b>
							<hr width='100%'>
						</div>
						<div style='clear:both'></div>
							
						<div class='item_principal' align='center' onClick='javascript:carregaPagina(\"op=".self::COD_CONFIG."\")'>
							<img src='".GOEPA_PATH_IMGS."configuracoes.svg' class='icon'>
							<br>
							<span class='titulo'>Configurações</span>
							<br>
						</div>";
										
		echo $form;							
	}
	
}


?>