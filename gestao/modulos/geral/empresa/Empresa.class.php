 <?php


include_once GOEPA_PATH_ABS.'Base.class.php';

include_once GOEPA_TABELA_PATH_ABS.'Tabela.class.php';

include_once GOEPA_PATH_ABS.'modulos/geral/endereco/Endereco.class.php';


final class Empresa extends Base{


private $tab;

private $endereco;




	function __construct() {
		
		parent::__construct();
		
		$this->tab= new Tabela();	
		
		$this->endereco= new Endereco();	
	}

	
	


	
	public function dependencias(){
	
		echo "
		
		<link rel='stylesheet' href='".GOEPA_JS_PATH_SMP."select2.min.css' type='text/css' media='all'>
		
		<script type='text/javascript' src='".GOEPA_JS_PATH_SMP."select2.min.js'></script>
		
		<script src='".GOEPA_PATH_SMP."modulos/geral/empresa/empresa.js' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".GOEPA_PATH_SMP."modulos/geral/empresa/empresa.css' type='text/css' media='all'>";
		
		$this->tab->dependencias();
		
		$this->endereco->dependencias();
	}

	
	
	
	
		
	
	public function conteudo(){

		$form = "
		<input type='hidden' id='path'   value='/modulos/geral/empresa/'/>
		<input type='hidden' id='classe' value='Empresa'/>";
		
		$form .= $this->getForm();
		
		echo $form;
	}
	
	
	
	
	
	
	
	public function getForm(){

		include_once GOEPA_PATH_ABS.'modulos/geral/empresa/BeanModulo.class.php';
		include_once GOEPA_PATH_ABS.'modulos/geral/empresa/BeanEmpresaModulo.class.php';
		
		$bd = new BdUtil();
		$comuns = new Comuns();
	
		$empresa = $bd->getPorId(new BeanEmpresa(), $this->id_empresa);

		if(!is_object($empresa))
			$empresa  = new BeanEmpresa();

		$form = "
				<div class='formulario'>
					<div id='area_dados'>
						<div id='div_codigo' class='item_form'>
							Código:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='codigo' value='".$empresa->codigo."' maxlength='40'>
						</div><!-- 
						<div id='div_cnpj' class='item_form'>
							CNPJ:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='cnpj' value='".$empresa->cnpj."'  class='campo_centralizado' maxlength='19' onchange='javascript:mascara(this, formatarCNPJ)'>
						</div>
						<div id='div_razao' class='item_form'>
							Razão Social:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='razao' value='".$empresa->razao_social."'  maxlength='150'>
						</div>-->
						<div id='div_fantasia' class='item_form'>
							Nome:<br>	
							<input type='text' id='fantasia' value='".$empresa->nome_fantasia."' maxlength='150'>
						</div>
						".$this->endereco->formDeEndereco($empresa->fk_endereco)."
						<div id='div_tel' class='item_form'>
							Fone:<span class='campo_obrigatorio'>*</span><br>
							<input type='text' id='tel'  class='campo_centralizado' value='".$empresa->fone_1."'  maxlength='15' onchange='javascript:mascara(this, formatarTEL)'>	
						</div>
						<div id='div_tel_2' class='item_form'>
							Fone:<br>
							<input type='text' id='tel_2' class='campo_centralizado' value='".$empresa->fone_2."'  maxlength='15' onchange='javascript:mascara(this, formatarTEL)'>	
						</div>
						<div id='div_email' class='item_form'>
							Email:<span class='campo_obrigatorio'>*</span><br>	
							<input type='text' id='email' value='".$empresa->email."'   maxlength='150'>
						</div>
						<div id='div_site' class='item_form'>
							Site:<br>	
							<input type='text' id='site' value='".$empresa->site."'   maxlength='150'>
						</div>
						<div style='clear:both'></div>
					</div>";
		/*						
		$form .= "			
					<div id='div_modulos'>
						<div id='modulos_area_interna'>
							<div id='div_selecao_modulo' class='item_form'>
								Adicionar Módulo:<span class='campo_obrigatorio'>*</span><br>
								<select id='modulo'>
									<option value='0'>...</option>";		
		
		$modulos= $bd->getPorQuery(new BeanModulo, 
										"inner join modulos_permitidos as mp on mp.fk_modulo=###.id_modulo and mp.fk_empresa=".$empresa->id, 
											"###.id_modulo NOT IN (select fk_modulo from empresas_modulos where fk_empresa=".$empresa->id.")", 
											"###.nome ASC");
													
		if(count($modulos)>0){
				
			foreach($modulos as $modulo)
				$form .= "
									<option value='".$modulo->id."'>".$modulo->nome."</option>";
		}
			
		$form .= "				</select>
							</div>
							<div id='div_selecao_bt_novo' class='item_form'>
								<div class='bt bt_padrao bt_add_modulo' onclick='javascript:addModulo()'>
									ADD
								</div>
							</div>
							<div style='clear:both'></div>
							<table width='100%' id='lista_modulos_adds'>
								<tr>
									<th width='80%' style='background:#000;color:#FFF;padding:5px'>MÓDULO</th>
									<th width='20%' style='background:#000;color:#FFF;padding:5px'></th>
								</tr>";
		
		
		$modulos = $bd->getPorQuery(new BeanEmpresaModulo(), null, "###.fk_empresa=".$empresa->id, "nome_modulo ASC");

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
						</div>*/
						
		$form .= "				
						<div style='clear:both'></div>
					</div>
					<div style='clear:both'></div>
					<div align='center'>
						<div class='bt bt_padrao' id='bt_salvar_empresa' onclick='javascript:salvarEmpresa(".($empresa->id>0?$empresa->id:0).")'>
							Salvar Empresa
						</div>
						<div align='center' class='carregando' id='area_carregando_empresa'>
						<img src='".GOEPA_PATH_IMGS."load.gif'>
						</div>
						<br><br>
					</div>
					
				</div>";
		
		return $form;
	}
	
	
	
	
	
	
	public function salvarEmpresa(){
	
		include_once GOEPA_PATH_ABS.'modulos/geral/empresa/BeanEmpresa.class.php';
		include_once GOEPA_PATH_ABS.'modulos/geral/empresa/BeanEmpresaModulo.class.php';
		
		$comuns = new Comuns();
		$bd = new BdUtil();
	
		$_POST['id_empresa'] 	= $comuns->anti_injection($_POST['id_empresa']);
		$_POST['codigo'] 		= $comuns->anti_injection($_POST['codigo']);
		//$_POST['razao'] 		= $comuns->anti_injection($_POST['razao']);
		$_POST['fantasia'] 		= $comuns->anti_injection($_POST['fantasia']);
		//$_POST['cnpj'] 			= $comuns->anti_injection($_POST['cnpj']);
		$_POST['tel'] 			= $comuns->anti_injection($_POST['tel']);
		$_POST['tel_2'] 		= $comuns->anti_injection($_POST['tel_2']);
		$_POST['email'] 		= $comuns->anti_injection($_POST['email']);
		$_POST['site'] 			= $comuns->anti_injection($_POST['site']);
		

		if(strlen($_POST['codigo']) == 0){
		
			echo '{"status":"ERRO", "erro":"Informe um código para a empresa."}';
			return;
		}
		
		$empresa = $bd->getPrimeiroOuNada(new BeanEmpresa(), null, 
							"###.codigo='".$_POST['codigo']."'".($_POST['id_empresa']>0?" and id_empresa<>".$_POST['id_empresa']:""), null);

		if(is_object($empresa)){
		
			echo '{"status":"ERRO", "erro":"O código informado já sendo usado por outra loja."}';
			return;
		}
		
		/*
		if(!$comuns->validaCNPJ($_POST['cnpj'])){
		
			echo '{"status":"ERRO", "erro":"Informe um número de cnpj válido."}';
			return;
		}
		
		
		if(strlen($_POST['razao']) == 0){
		
			echo '{"status":"ERRO", "erro":"Informe uma razão social."}';
			return;
		}*/
	
		$endereco = json_decode($_POST["endereco"]);
		
		$erro= $this->endereco->validacao($endereco);
		
		if(strlen($erro)>0){
			
			echo $erro;
			return;
		}
		
		if(!$comuns->validaTEL($_POST['tel'])){
		
			echo '{"status":"ERRO", "erro":"Informe um telefone válido."}';
			return;
		}
		
		
		if(strlen($_POST['tel_2']) >0){
		
			if(!$comuns->validaTEL($_POST['tel_2'])){
			
				echo '{"status":"ERRO", "erro":"Informe um segundo telefone válido."}';
				return;
			}
		}
		
		if(strlen($_POST['email']) == 0){
		
			echo '{"status":"ERRO", "erro":"Informe um endereço de e-mail."}';
			return;
		}
		
		$empresa = $bd->getPorId(new BeanEmpresa(), $_POST['id_empresa']);

		if(!is_object($empresa))
			$empresa = new BeanEmpresa;
		
		$empresa->codigo  =  		$_POST['codigo'];
		$empresa->razao_social  =  	$_POST['fantasia'];
		$empresa->nome_fantasia  =  $_POST['fantasia'];
		//$empresa->cnpj  =  			$_POST['cnpj'];
		$empresa->fone_1  =  		$_POST['tel'];
		$empresa->fone_2  =  		$_POST['tel_2'];
		$empresa->email  =  		$_POST['email'];
		$empresa->site  =  			$_POST['site'];
		
		$id_endereco = $this->endereco->salvaEndereco($bd, $endereco, $empresa->fk_endereco);
		
		if($id_endereco<=0){
			
			echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
			return;
		}
		
		$empresa->fk_endereco  =  $id_endereco;
		
		
		if($empresa->id<=0){
			
			$empresa->status			=1;
			$empresa->data_cadastro		= date("Y-m-d");	
			
			$empresa->id = $bd->novo($empresa);
			
			if($empresa->id<=0){
					
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}
		}
		else{
			if(!$bd->altera($empresa)){
				
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}
		}
		
		
		/*
		$bd->deletaPorQuery(new BeanEmpresaModulo(), "fk_empresa=".$empresa->id);
		
		$modulos = json_decode($_POST["modulos"]);
		
		foreach($modulos->modulos as $modulo){
				
			if($modulo>0){
					
				$x= new BeanEmpresaModulo();
				$x->fk_empresa= $empresa->id;
				$x->fk_modulo= $modulo;
				$bd->novo($x);
			}
		}
*/
		echo '{"status":"sucesso"}';
	}
	

	
	
	
	
	
}


?>