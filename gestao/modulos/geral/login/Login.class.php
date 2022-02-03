 <?php

include_once GOEPA_PATH_ABS.'Base.class.php';

include_once GOEPA_PATH_ABS.'modulos/geral/login/Gandalf.class.php';




final class Login extends Base{





	function __construct() {
		
		parent::__construct();
		
		$this->configuraCookiesDeUsuario();
	}

	

	
	public function dependencias(){
	
		parent::dependencias();
	
		echo "
		
		<script src='".GOEPA_JS_PATH_SMP."mascaras.js' type='text/javascript'></script>
		
		<script src='".GOEPA_PATH_SMP."modulos/geral/login/login.js' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".GOEPA_PATH_SMP."modulos/geral/login/login.css' type='text/css' media='all'>";
	}

	
	

	
	public function getFormDeLogin(){
	
		echo "
		<input type='hidden' id='path'   value='/modulos/geral/login/'/>
		<input type='hidden' id='classe' value='Login'/>
		<div id='form_login' class='formulario'>
			<div class='item_form' id='item_titulo'>
				<br>
				<b>Autenticação</b>
				<hr width='99%'>
			</div>
			<div class='item_form' id='item_cpf'>
				CPF:<span class='campo_obrigatorio'>*</span><br>
				<input type='text' class='campo' id='cpf' oninput='javascript:mascara(this, formatarCPF);' maxlength='14'>
			</div>
			<div class='item_form' id='item_senha'>
				Senha:<span class='campo_obrigatorio'>*</span><br>
				<input type='password' class='campo' id='senha'  maxlength='16'>
			</div>	
			<div style='clear:both'></div>
			<div align='left' style='margin:10px  0px 0px 10px'>
				<br>
				<input type='checkbox' id='continuar_logado'> Permanecer Logado.
				<br>
			</div>
			<div align='center'>
				<div class='bt bt_padrao' id='bt_logar' onclick='javascript:login()'>
					Entrar
				</div>
				<div align='center' class='carregando' id='area_carregando_logar'>
					<img src='".GOEPA_PATH_IMGS."load.gif'>
				</div>
				<div id='login_msg_erro'>
				</div>
				<br>
			</div>
		</div>";
	}



	
	public function sair(){
	
		unset($_SESSION["usuario"]);	
		$_SESSION["salvar_login"]  =  0;
		$_SESSION["remove_cookies"]  = 	 1;
	}
	
		
	
	
	
	private function loginValido($cpf, $senha){
		
		include_once GOEPA_PATH_ABS.'modulos/geral/usuarios/BeanUsuario.class.php';
	
		include_once GOEPA_BD_PATH_ABS.'BdUtil.class.php';
	
		$bd = new BdUtil();
	
		$retorno = $bd->getPrimeiroOuNada(new BeanUsuario(), null, "###.cpf = '".$cpf."' AND ###.senha = '".$senha."' and ###.status>0", null);		
	
		if(is_object($retorno) && $this->gandalf->temPermissaoPorUsuario($retorno->id, PERM_USER_LOGR)){
		
			$_SESSION["usuario"]  = serialize($retorno);
			return true;
		}
	
		$_SESSION["usuario"]  = null;
		return false;	
	}
	
	
	

	
	public function tentativaDeLogin(){
	
		include_once GOEPA_CMS_PATH_ABS.'Comuns.class.php';
		
		$comuns = new Comuns();

		$_POST["cpf"] = $comuns->anti_injection( $_POST["cpf"]);
		$_POST["senha"] = $comuns->anti_injection($_POST["senha"]);

		if( strlen($_POST["cpf"])== 0 || strlen($_POST["senha"]) < 6 ){
		
			echo '{"status":"erro1"}';
			$_SESSION["usuario"]  = null;
			return;
		}
	
		if( $this->loginValido($_POST["cpf"], hash('sha256', $_POST["senha"]))){
		
			if($_POST["salvar"]>0)
				$_SESSION["salvar_login"] = 1;
			else
				$_SESSION["salvar_login"] = 0;
		
			echo '{"status":"sucesso"}';
			return;
		}
	
	
		$_SESSION["usuario"]  = null;	
		$_SESSION["salvar_login"]  = 0;
		echo '{"status":"erro"}';			
	}
	
	
	
		

	
	public function configuraCookiesDeUsuario(){
		
		if( array_key_exists("remove_cookies", $_SESSION) && $_SESSION["remove_cookies"]>0){

			setcookie("usuario", "", time() - 3600);
			setcookie("senha",   "", time() - 3600);
			$_SESSION["remove_cookies"] = 0;
		}
		else{
	
			$dandalf = new Gandalf;
	
			$usuario =$dandalf->usuarioAtual();
	
			if( array_key_exists("salvar_login", $_SESSION) && $_SESSION["salvar_login"]> 0 && is_object($usuario)){
		
				if(!array_key_exists("usuario", $_COOKIE) || !array_key_exists("senha", $_COOKIE)){
				
					$tempo = time()+DURACAO_DE_COOKIES;	
				
					setcookie("usuario", $usuario->cpf, $tempo);
					setcookie("senha", $usuario->senha, $tempo);
					$_SESSION["salvar_login"] = 0;
				}
			}
		}
	}
	
	
	
	
	
	public function permitirAcesso(){
		
		$gandalf = new Gandalf;
		
		if(is_object($gandalf->usuarioAtual()))
			return true;
		
		if(array_key_exists("usuario", $_COOKIE) && strlen($_COOKIE["usuario"])>0 &&
			array_key_exists("senha", $_COOKIE) && strlen($_COOKIE["senha"])>0){
			
			include_once GOEPA_CMS_PATH_ABS.'Comuns.class.php';
		
			$comuns = new Comuns();
			
			$usuario = $comuns->anti_injection($_COOKIE["usuario"]);
			$senha = $comuns->anti_injection($_COOKIE["senha"]);

			return $this->loginValido($usuario, $senha);	
		}
		
		return false;	
	}
	
	
	
	

}

?>