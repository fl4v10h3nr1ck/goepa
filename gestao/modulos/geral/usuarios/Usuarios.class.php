 <?php

include_once GOEPA_PATH_ABS.'Base.class.php';

include_once GOEPA_TABELA_PATH_ABS.'Tabela.class.php';

include_once GOEPA_PATH_ABS.'modulos/geral/endereco/Endereco.class.php';


final class Usuarios extends Base{


private $tab;




	function __construct() {
		
		parent::__construct();
		
		$this->tab= new Tabela();	
		
		$this->endereco= new Endereco();
	}

	
	


	
	public function dependencias(){
	
		echo "
		
		<script src='".GOEPA_PATH_SMP."modulos/geral/usuarios/usuarios.js?v=".rand(1, 999)."' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".GOEPA_PATH_SMP."modulos/geral/usuarios/usuarios.css?v=".rand(1, 999)."' type='text/css' media='all'>";
	
		$this->tab->dependencias();
		
		$this->endereco->dependencias();
	}

	
	
	
	
	
	public function conteudo(){

		$form = "
		<input type='hidden' id='path'   value='/modulos/geral/usuarios/'/>
		<input type='hidden' id='classe' value='Usuarios'/>";
	
		if(array_key_exists("sop", $_GET) && strcmp($_GET["sop"], "CAD")==0)
			$form .= $this->getForm();
		else{
		
			$form .= "
			<table>
				<tr>
					<td align='center'>
						<button onclick='javascript:novoUsuario()' class='opcao'>
							<img src='".GOEPA_PATH_IMGS."novo.svg'>
						</button>
						<br>Novo
					</td>
					<td  align='center'>
						<button onclick='javascript:editarUsuario()' class='opcao'>
							<img src='".GOEPA_PATH_IMGS."editar.svg'>
						</button>
						<br>Editar
					</td>		
				</tr>
			</table>";	
		
			$this->tab->setPathABSDoObjeto(GOEPA_PATH_ABS."modulos/geral/usuarios/");
			
			$this->tab->setFuncaoDuploClick("editarUsuario");
			
			$this->tab->setMostrarOpcoesDePesquisa(false);
			
			$this->tab->setMostrarPaginacao(true);
			
			$form .= $this->tab->getTabela("BeanUsuario", 'tab_usuarios');
		}
		
		echo $form;
	}
	
	
	
	
	
	public function getForm(){
		
		include_once GOEPA_BD_PATH_ABS."BdUtil.class.php";
		include_once GOEPA_PATH_ABS.'modulos/geral/usuarios/BeanUsuario.class.php';
		include_once GOEPA_CMS_PATH_ABS.'Comuns.class.php';
		include_once GOEPA_PATH_ABS.'modulos/geral/empresa/BeanModulo.class.php';
		include_once GOEPA_PATH_ABS.'modulos/geral/usuarios/BeanUsuarioModulo.class.php';
		
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		$usuario = $bd->getPorId(new BeanUsuario(), array_key_exists("id", $_GET)?$comuns->anti_injection($_GET['id']):0);
		
		if(!is_object($usuario))
			$usuario  = new BeanUsuario();
	
		$form = "
				<div class='formulario'>
					<div id='area_dados'>
						<div id='div_nome' class='item_form'>
							Nome Completo:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='nome' value='".$usuario->nome_completo."'>
						</div>
						<div id='div_cpf' class='item_form'>
							CPF (usado para logar):<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='cpf' value='".$usuario->cpf."' class='campo_centralizado' maxlength='14' onchange='javascript:mascara(this, formatarCPF);'>
						</div>
						<div id='div_email' class='item_form'>
							E-mail:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='email' value='".$usuario->email."'   maxlength='150'>
						</div>
						<div id='div_tel' class='item_form'>
							Celular (xx) xxxxx-xxxx:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='tel' value='".$usuario->tel."' class='campo_centralizado' maxlength='15' onchange='javascript:mascara(this, formatarTEL);'>
						</div>
						<div style='clear:both'></div>";
					
		if($usuario->id>0)				
			$form .= "
						<div id='div_trocar_senha' align='left'>
							<br>
							<input type='checkbox' id='trocar_senha' onChange='javascript:trocarSenha()'> Trocar Senha.<br>
						</div>";
													
		$form .= "		
						<div style='clear:both'></div>
						<div id='div_senha' class='item_form'>
							Senha:<span class='campo_obrigatorio'>* </span><span class='indicadores_ajuda'>(a-z A-Z 0-9) entre 6 e 18 dígitos</span><br>
							<input type='password' id='senha' value=''>
						</div>
						<div id='div_repete_senha' class='item_form'>
							Repita a Senha:<br>
							<input type='password' id='repete_senha' value=''>
						</div>
						<div style='clear:both'></div>
					</div>";
					
		/*			
					<div id='area_modulos'>
						<div id='div_modulos' class='item_form'>
							<div style='border:solid 1px #EEE;padding:5px;min-height:180px'>
								Adicionar Módulo:<span class='campo_obrigatorio'>*</span><br>
								<select id='modulo' onChange='javascript:addModulo()'>
									<option value='0'>...</option>";	
		
		
		$modulos= $bd->getPorQuery(new BeanModulo, 
									"inner join empresas_modulos as exm on exm.fk_modulo=###.id_modulo and exm.fk_empresa=".$this->id_empresa, 
										"###.id_modulo NOT IN (select fk_modulo from usuarios_modulos where fk_usuario=".($usuario->id>0?$usuario->id:0).")", 
											"###.nome ASC");
						
											
		if(count($modulos)>0){
				
			foreach($modulos as $modulo)
				$form .= "
									<option value='".$modulo->id."'>".$modulo->nome."</option>";
		}
			
		$form .= "				</select>
								<table width='100%' id='lista_modulos_adds'>
									<tr>
										<th width='80%' style='background:#000;color:#FFF;padding:5px'>MÓDULO</th>
										<th width='20%' style='background:#000;color:#FFF;padding:5px'></th>
									</tr>";
		
		
		$modulos = $bd->getPorQuery(new BeanUsuarioModulo(), null, "###.fk_usuario=".$usuario->id, "nome_modulo ASC");

		if(count($modulos)>0){
			foreach($modulos as $modulo)
					$form .= "		<tr id='mod_add_".$modulo->fk_modulo."'>
										<td width='80%'>".$modulo->nome_modulo."<input type='hidden' class='id_modulo_add' value='".$modulo->fk_modulo."'></td>
										<td width='20%' align='center'>
											<div class='bt bt_padrao bt_remover_modulo' onclick='javascript:removeModulo(".$modulo->fk_modulo.", \"".$modulo->nome_modulo."\")'>
												X
											</div>
										</td>
									</tr>";
		}
			$form .= "
								</table>
							</div>
						</div>
						<div style='clear:both'></div>							
					</div>*/
					
		$form .= "			
					<div style='clear:both'></div>
					<div align='center'>
						<div class='bt bt_padrao' id='bt_salvar_usuario' onclick='javascript:salvarUsuario(".($usuario->id>0?$usuario->id:0).")'>
							Salvar Operador
						</div>
						<div align='center' class='carregando' id='area_carregando_usuario'>
							<img src='".GOEPA_PATH_IMGS."load.gif'>
						</div>
						<br><br>
					</div>
				</div>";
				
		echo $form;
	}
	
	
	
	
	
	public function salvarUsuario(){
	
		include_once GOEPA_BD_PATH_ABS."BdUtil.class.php";
		include_once GOEPA_PATH_ABS.'modulos/geral/usuarios/BeanUsuario.class.php';
		include_once GOEPA_PATH_ABS.'modulos/geral/usuarios/BeanUsuarioModulo.class.php';
		include_once GOEPA_CMS_PATH_ABS.'Comuns.class.php';

		$comuns = new Comuns();
		$bd = new BdUtil();
	
		$_POST['nome'] 				= $comuns->anti_injection($_POST['nome']);
		$_POST['tel'] 				= $comuns->anti_injection($_POST['tel']);
		$_POST['email'] 			= $comuns->anti_injection($_POST['email']);
		$_POST['cpf'] 				= $comuns->anti_injection($_POST['cpf']);
		$_POST['id_usuario'] 		= $comuns->anti_injection($_POST['id_usuario']);
		//$_POST['ids_modulos'] 		= $comuns->anti_injection($_POST['ids_modulos']);
		
	
		if(strlen($_POST['nome']) == 0){
			
			echo '{"status":"ERRO", "erro":"Informe o nome completo."}';
			return;
		}

		if(!$comuns->validaCPF($_POST['cpf'])){
			
			echo '{"status":"ERRO", "erro":"informe um CPF válido."}';
			return;
		}
		
		if(!$comuns->validaEmail($_POST['email'])){
			
			echo '{"status":"ERRO", "erro":"informe um endereço de E-mail válido."}';
			return;
		}
		
		if( !$comuns->validaTEL($_POST['tel'])){
			
			echo '{"status":"ERRO", "erro":"Informe um número de telefone válido."}';
			return;
		}
		
		$reg = $bd->getPrimeiroOuNada(new BeanUsuario(), 
											null, 
												"###.usuario='".$_POST['cpf']."'".($_POST['id_usuario']>0?" and id_usuario<>".$_POST['id_usuario']:""),
												null);
		
		if($reg!=null){
			
			echo '{"status":"ERRO", "erro":"O CPF informado já está sendo usado por outro usuário."}';
			return;
		}

		
		if($_POST['id_usuario']==0 && strlen($_POST['senha'])==0){
			
			echo '{"status":"ERRO", "erro":"Informe uma senha para o usuario."}';
			return;
		}

		if(strlen($_POST['senha'])>0){
			
			if(strlen($_POST['senha'])<6){
					
				echo '{"status":"ERRO", "erro":"A senha deve ter ao menos 6 dígitos."}';
				return;
			}

			
			if(strcmp($_POST['senha'], $_POST['repete_senha'])!=0){
				echo '{"status":"ERRO", "erro":"As senhas informadas não são iguais."}';
				return;
			}
		}
		
		$usuario = $bd->getPorId(new BeanUsuario(), $_POST['id_usuario']);
				
		if(!is_object($usuario))
			$usuario = new BeanUsuario();
			
		$usuario->nome_completo  		=  	$_POST['nome'];
		$usuario->cpf  					=  	$_POST['cpf'];
		$usuario->tel   				=  	$_POST['tel'];
		$usuario->email  				=  	$_POST['email'];
		
		if($usuario->id<=0){
			
			$usuario->senha 		 	= hash('sha256', $_POST['senha']);	
			$usuario->status			=1;
			$usuario->data_cadastro		= date("Y-m-d");	
			
			$usuario->id = $bd->novo($usuario);
			
			if($usuario->id<=0){
					
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}
			
			$bd->executaQuery("INSERT INTO usuarios_grupos (fk_usuario, fk_grupo)  VALUES (".$usuario->id.", 1)");
			
			$bd->executaQuery("INSERT INTO usuarios_modulos (fk_usuario, fk_modulo)  VALUES (".$usuario->id.", 1);");
		}
		else{
			
			if(strlen($_POST['senha'])>0)
				$usuario->senha 	= hash('sha256', $_POST['senha']);
			
			if(!$bd->altera($usuario)){
			
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}
		}

/*
		$bd->deletaPorQuery(new BeanUsuarioModulo(), "fk_usuario=".$usuario->id);
		
		if(strlen($_POST['ids_modulos'])>0){
			
			$ids = explode("_", $_POST['ids_modulos']);
					
			if(count($ids)>0){
						
				foreach($ids as $id){
						
					if(strlen($id)>0 && $id>0){
							
						$x= new BeanUsuarioModulo();
						$x->fk_usuario=$usuario->id;
						$x->fk_modulo=$id;
						$bd->novo($x);
					}
				}
			}
		}
		*/
		
		echo '{"status":"sucesso"}';	
	}
	

	

	
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
	

	
	

}


?>