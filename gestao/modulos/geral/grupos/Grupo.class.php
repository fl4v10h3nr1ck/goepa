 <?php

include_once AGRCL_PATH_ABS.'Base.class.php';

include_once AGRCL_TABELA_PATH_ABS.'Tabela.class.php';



final class Grupo extends Base{


private $tab;



	function __construct() {
		
		parent::__construct();
		
		$this->tab= new Tabela();	
	}

	


	
	public function dependencias(){
	
		echo "
		
		<script src='".AGRCL_PATH_SMP."geral/grupos/grupo.js' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".AGRCL_PATH_SMP."geral/grupos/grupo.css' type='text/css' media='all'>";
	
		$this->tab->dependencias();
	}

	
	
	
	
	
	public function conteudo(){

		$_SESSION['PD_EDT_STTS_GRP'] = true;
		
		$form = "
		<input type='hidden' id='path'   value='/geral/grupos/'/>
		<input type='hidden' id='classe' value='Grupo'/>";
	
		if(array_key_exists("sop", $_GET) && strcmp($_GET["sop"], "CAD")==0)
			$form .= $this->getForm();
		else if(array_key_exists("sop", $_GET) && strcmp($_GET["sop"], "PMS")==0)
			$form .= $this->getFormDePermissoes();
		else if(array_key_exists("sop", $_GET) && strcmp($_GET["sop"], "ADU")==0)
			$form .= $this->getFormDeUsuarios();
		else{
					
			$form .= "
				<table>
					<tr>
						<td align='center' class='opcao'>
							<button onclick='javascript:novoGrupo()'>
								<img src='".AGRCL_PATH_IMGS."novo.png' class='bt_opcao'>
							</button>
							<br>Novo
						</td>
						
						<td  align='center' class='opcao'>
							<button onclick='javascript:editarGrupo()'>
								<img src='".AGRCL_PATH_IMGS."alterar.png' class='bt_opcao'>
							</button>
							<br>Editar
						</td>
	
						<td  align='center' class='opcao'>
							<button onclick='javascript:excluirGrupo()' class='bt_opcao'>
								<img src='".AGRCL_PATH_IMGS."excluir.png'>
							</button>
							<br>Excluir
						</td>
						
						<td  align='center' class='opcao'>
							<button onclick='javascript:permissoes()'>
								<img src='".AGRCL_PATH_IMGS."permissao.png' class='bt_opcao'>
							</button>
							<br>Permissões
						</td>
						
						<td  align='center' class='opcao'>
							<button onclick='javascript:adicionarUsuario()'>
								<img src='".AGRCL_PATH_IMGS."transferir.png' class='bt_opcao'>
							</button>
							<br>Usuários
						</td>
					</tr>
				</table>";
		
			$this->tab->setPathABSDoObjeto(AGRCL_PATH_ABS."geral/grupos/");
		
			$this->tab->setOrderByFixo("###.nome ASC");
			
			$this->tab->setFuncaoDuploClick("editarGrupo()");
		
			$this->tab->setMostrarOpcoesDePesquisa(false);
			
			$this->tab->setMostrarPaginacao(false);
		
			$form .= $this->tab->getTabela("BeanGrupo", 'tab_grupos');
		}
		
		
		echo $form;
	}
	
	
	
	
	
	
	public function getForm(){
	
		include_once AGRCL_PATH_ABS.'geral/grupos/BeanGrupo.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';		
		
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		$grupo = $bd->getPorId(new BeanGrupo(), array_key_exists("id", $_GET)?$comuns->anti_injection($_GET['id']):0);

		if(!is_object($grupo))
			$grupo  = new BeanGrupo();
	
		$form = "
				<div class='formulario'>
					<div id='div_codigo' class='item_form'>
						Código:<span class='campo_obrigatorio'>*</span><br>
						<input type='text' id='codigo' value='".$grupo->codigo."' maxlength='18' ".($grupo->id>0 && strcmp($grupo->codigo, COD_GRP_ADMINS)==0?"disabled":"").">
					</div>
					<div id='div_nome' class='item_form'>
						Nome:<span class='campo_obrigatorio'>*</span><br>	
						<input type='text' id='nome' value='".$grupo->nome."' maxlength='45'>
					</div>
					<div id='div_descricao' class='item_form'>
						Descrição:<br>
						<input type='text' id='descricao' value='".$grupo->descricao."' maxlength='150'>
					</div>
					<div style='clear:both'></div>
					<div align='center'>
						<div class='bt bt_padrao' id='bt_salvar_grupo' onclick='javascript:salvarGrupo(".($grupo->id>0?$grupo->id:0).")'>
							Salvar Grupo
						</div>	
						<div align='center' class='carregando' id='area_carregando_grupo'>
							<img src='".AGRCL_PATH_IMGS."load.gif'>
						</div>
					<br><br>
				</div>";
	
		return $form;
	}
	
	

	
	
	public function salvarGrupo(){
	
		include_once AGRCL_PATH_ABS.'geral/grupos/BeanGrupo.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';		
		
		$comuns = new Comuns();
		$bd = new BdUtil();
	
		$_POST['id_grupo'] = 	$comuns->anti_injection($_POST['id_grupo']);
		$_POST['codigo'] = 		$comuns->anti_injection($_POST['codigo']);
		$_POST['nome'] = 		$comuns->anti_injection($_POST['nome']);
		$_POST['descricao'] = 	$comuns->anti_injection($_POST['descricao']);

	
		if(strlen($_POST['codigo']) == 0){
			
			echo '{"status":"ERRO", "erro":"Informe um código para o grupo."}';
			return;
		}
	
		$reg = $bd->getPrimeiroOuNada(new BeanGrupo(), 
										null, 
											"###.codigo='".$_POST['codigo']."'".($_POST['id_grupo']>0?" and ###.id_grupo_de_usuario<>".$_POST['id_grupo']:""),
											null);
	
		if($reg!=null){
			
			echo '{"status":"ERRO", "erro":"O código informado já está sendo usado por outro grupo."}';
			return;
		}
		
		if(strlen($_POST['nome']) == 0){
			
			echo '{"status":"ERRO", "erro":"Informe um nome para o grupo."}';
			return;
		}
		
		$grupo = $bd->getPorId(new BeanGrupo(), $_POST['id_grupo']);
		
		if(!is_object($grupo))
			$grupo = new BeanGrupo();
			

		$grupo->nome  =  		$_POST['nome'];
		$grupo->descricao = 	$_POST['descricao'];
		$grupo->codigo = 		$_POST['codigo'];
		

		if($grupo->id<=0){
			
			$grupo->status 		= 1;
				
			$grupo->id = $bd->novo($grupo);
			
			if($grupo->id<=0){
					
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}
		}
		else{
			
			if(!$bd->altera($grupo)){
			
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}
		}
			
		echo '{"status":"sucesso"}';	
	}
	
	
	
	
		
	public function ativarDesativar(){
			
		include_once AGRCL_PATH_ABS.'geral/grupos/BeanGrupo.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';		
	
		$comuns = new Comuns();
		$bd = new BdUtil();
	
		$grupo = $bd->getPorId(new BeanGrupo(), $comuns->anti_injection($_POST['id_grupo']));
				
		if(is_object($grupo)){
			
			if($grupo->status>0)
				$grupo->status = 0;
			else
				$grupo->status = 1;
			
			if($bd->altera($grupo)){
			
				echo '{"status":"sucesso"}';
				return;
			}
		}
		
		echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
	}
	

	
	
	

	public function getFormDePermissoes(){
	
		include_once AGRCL_PATH_ABS.'geral/grupos/BeanGrupo.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';	
		
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		$grupo = $bd->getPorId(new BeanGrupo(), array_key_exists("id", $_GET)?$comuns->anti_injection($_GET['id']):0);

		if(!is_object($grupo)){
			
			return $this->erro("Grupo no Encontrado.");
		}
		
		
		$form ="";
		
		if(strcmp($grupo->codigo, COD_GRP_ADMINS)==0){
			
			$form = "
					<div align='center' style='width:100%;padding-top:100px'>
						&laquo; Grupos de administradores já possuem todas as permissões habilitadas por padrão &raquo;
					</div>";
					
			return $form;
		}
		
		$form ="	<div align='center'>
					<b>PERMISSÕES PARA O GRUPO ".strtoupper($grupo->codigo)."</b>
					</div>";
		
		
		include_once AGRCL_PATH_ABS.'geral/grupos/BeanGrupoAcesso.class.php';
		include_once AGRCL_PATH_ABS.'geral/grupos/BeanAcesso.class.php';
		
			
		$ids_permissoes = array();
		$valores_permissoes = array();
		
		$permissoes_grupo = $bd->getPorQuery(new BeanGrupoAcesso(), null, "###.fk_grupo=".$grupo->id, null);

					
		if(count($permissoes_grupo)>0){
			
			foreach($permissoes_grupo as $permissao){
				
				$ids_permissoes[] 		= $permissao->fk_acesso;
				$valores_permissoes[] 	= $permissao->valor;	
			}
		}
		
		$acessos = $bd->getPorQuery(new BeanAcesso(), null, null, "###.ordem ASC, ###.nome ASC");

		$form .= "
				<div id='area_acessos'>
					<table border= '1' id='tab_acessos'>
						<tr>
							<th width='15%' align='center' class='acesso_ocultar'>CÓDIGO</th>
							<th width='50%' align='center'>ACESSO</th>
							<th width='35%' align='center'>PERMISSÃO</th>
						</tr>";
		
		if(count($acessos)>0){
		
		$ordem_atual = 0;
		
			foreach($acessos as $acesso){
				
				if($ordem_atual != $acesso->ordem){
					
					$ordem_atual = 	$acesso->ordem;
					
					$titulo = "";

					switch($ordem_atual){
						
						case 1:
							$titulo = "Permissões Globais";
							break;
							case 2:
							$titulo = "";
							break;
								case 3:
								$titulo = "";
								break;
									case 4:
									$titulo = "";
									break;
										case 5:
										$titulo = "";
										break;
											case 6:
											$titulo = "";
											break;
												case 7:
												$titulo = "";
												break;
													default:
													$titulo = "Outros";
													break;
					}
					
					
					$form .= "	
						<tr>
							<td align='center' class='acesso_ocultar' colspan='3' align='center'><b>".$titulo."</b></td>
						</tr>";	
				}
				
				$form .= "	
						<tr>
							<td align='center' class='acesso_ocultar'>".strtoupper($acesso->codigo)."</td>
							<td align='left'>".$acesso->nome."</td>
							<td align='center'>";
							
				if(strcmp($acesso->tipo, ACESSO_SIM_NAO)==0){
				
					$cv = array_search($acesso->id, $ids_permissoes);
				
					$checkado = false;
					
					if($cv!==false && 
						strlen($valores_permissoes[$cv])>0 && 
							strcmp($valores_permissoes[$cv], ACESSO_SIM)==0)
					$checkado = true;		
						
					$form .= "
								<input  ".($checkado?"checked":"")." id='acesso_".$acesso->id."' class='switch switch--shadow permissao_item vsn' type='checkbox'>
								<label for='acesso_".$acesso->id."'></label>";		
				}			
				else{
					
					$cv = array_search($acesso->id, $ids_permissoes);
				
					$ver = false;
					$editar = false;
					$excluir = false;
					
					if($cv!==false && strlen($valores_permissoes[$cv])>0){

						if(strcmp($valores_permissoes[$cv], ACESSO_VER)==0)
						$ver = true;		
						
						if(strcmp($valores_permissoes[$cv], ACESSO_EDITAR)==0)
						$editar = true;	
					
						if(strcmp($valores_permissoes[$cv], ACESSO_EXCLUIR)==0)
						$excluir = true;	
					}
					
						
					$form .= "	<table width='100%'>
									<tr>
										<td width='33%' align='center'>
										Ver:
										<input  ".($ver || $editar || $excluir?"checked":"")." id='ver_".$acesso->id."' class='permissao_item ver' type='checkbox' onclick='javascript:marcaPermissao(".$acesso->id.", 0)'>
										</td>
										<td width='34%' align='center'>
										Editar:
										<input  ".($editar || $excluir?"checked":"")." id='editar_".$acesso->id."' class='permissao_item edt' type='checkbox' onclick='javascript:marcaPermissao(".$acesso->id.", 1)'>
										</td>
										<td width='33%' align='center'>
										Excluir:
										<input  ".($excluir?"checked":"")." id='excluir_".$acesso->id."' class='permissao_item exr' type='checkbox' onclick='javascript:marcaPermissao(".$acesso->id.", 2)'>
										</td>
									</tr>
								</table>";	
				}

				$form .= "	</td>
						</tr>";
			}
		}
	
		$form .= "	</table>
				</div>
				<div align='center'>
					<div class='bt bt_padrao' id='bt_salvar_permissoes' onclick='javascript:salvarPermissoes(".($grupo->id>0?$grupo->id:0).")'>
						Salvar Permissões
					</div>	
					<div align='center' class='carregando' id='area_carregando_permissoes'>
						<img src='".AGRCL_PATH_IMGS."load.gif'>
					</div>
				</div>";
	
		echo $form;
	}
	
	
	

	
	public function salvarPermissoes(){
		
		include_once AGRCL_PATH_ABS.'geral/grupos/BeanGrupoAcesso.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';	
	
		$comuns = new Comuns();
		$bd = new BdUtil();
	
		$_POST['id_grupo'] = 	$comuns->anti_injection($_POST['id_grupo']);
		
		// remove as referencias de permissoes a esse grupo
		$bd->deletaPorQuery(new BeanGrupoAcesso, "fk_grupo=".$_POST['id_grupo']);
		
		$permissoes = explode("#", $_POST['permissoes']);
		
		if(count($permissoes)>0){

			$ids = array();
			$valores = array();
			
			foreach($permissoes as $permissao){
			
				if(strlen($permissao)>0){
			
					$args = explode("@", $permissao);
					
					if(count($args)==2 &&
							strlen($args[0])>0 &&
								strlen($args[1])>0){
			
						$cv = array_search($args[0], $ids);
			
						if($cv===false){
			
							$ids[] = $args[0];
							$valores[] = $args[1];
						}
						else{
								
							if(intval($args[1])>intval($valores[$cv]))
								$valores[$cv] = $args[1];
						}
					}
				}
			}
			

			foreach($ids as $i=>$id){

				$valor = "";
					
				if($valores[$i] == 4)
					$valor = ACESSO_SIM;
				elseif($valores[$i] == 1)
					$valor = ACESSO_VER;
				elseif($valores[$i] == 2)
					$valor = ACESSO_EDITAR;
				elseif($valores[$i] == 3)
					$valor = ACESSO_EXCLUIR;
				
				if(strlen($valor)>0){
			
					$grupoAcesso  =  new BeanGrupoAcesso();
					$grupoAcesso->fk_acesso = 		$id;
					$grupoAcesso->fk_grupo = 		$_POST['id_grupo'];
					$grupoAcesso->valor= 			$valor;
					
					$grupoAcesso->id = $bd->novo($grupoAcesso);
			
					if($grupoAcesso->id<=0){
					
						echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
						return;
					}
				}
			}
		}
				
		echo '{"status":"sucesso"}';
	}

	
	
	
	
	
	public function getFormDeUsuarios(){
		
		include_once AGRCL_PATH_ABS.'geral/grupos/BeanGrupo.class.php';
		include_once AGRCL_PATH_ABS.'geral/usuarios/BeanUsuario.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';	
	
		$bd = new BdUtil();
		$comuns = new Comuns();
	
		$grupo = $bd->getPorId(new BeanGrupo(), array_key_exists("id", $_GET)?$comuns->anti_injection($_GET['id']):0);

		if(!is_object($grupo)){
			
			return $this->erro("Grupo no Encontrado.");
		}
	
		$form = "
			<div class='formulario'>
				<div align='center'>
					<b>USUÁRIOS DO GRUPO ".strtoupper($grupo->codigo)."</b>
				</div>
				<div class='area_lista_usuarios'>
					<div style='margin:10px 0px 10px 10px'>
						<b>Usuários disponíveis:</b>
					</div>
					<div id='area_para_add'>
					".$this->getParaAdicionar($bd, $grupo->id)."
					</div>
				</div>
				<div id='area_transferir_usuarios' align='center'>
					<div  class='bt bt_padrao' id='bt_transferir_usuario' onclick='javascript:transferirUsuario(".$grupo->id.")'>
						&raquo ADD &raquo;
					</div>	
				</div>
				<div class='area_lista_usuarios'>
					<div style='margin:10px 0px 10px 10px'>
						<b>Usuários do Grupo:</b>
					</div>
					<div id='area_ja_add'>
					".$this->getjaAdicionados($bd, $grupo->id)."
					</div>
				</div>
				<div style='clear:both'></div>
			</div>";
		
		echo $form;
	}
	
	
	
	
	
	
	private function getParaAdicionar(&$bd, $id_grupo){
		
		$para_add = $bd->getPorQuery(new BeanUsuario(), 
											null, 
											"###.id_usuario NOT IN (select fk_usuario from usuarios_grupos where fk_grupo=".$id_grupo.")", 
											null);
		
		$form = "
				<table class='tab_lista_usuarios' cellspacing=0 id='tab_para_add'>
					<tr>
						<th align='center'>
						NOME
						</th>
					</tr>";
		
		if(count($para_add)>0){
			
			foreach($para_add as $usuario)
				$form .= "
					<tr class='linha_para_add' id='linha_para_add_".$usuario->id."' onclick='javascript:selecionaListaDeUsuario(".$usuario->id.")'> 
						<td align='left'>
							".(strlen($usuario->nome_completo)<=0?$usuario->cpf:$usuario->nome_completo)."
						</td>
					</tr>";	
		}		
						
		$form .= "
			</table>";
			
		return $form;
	}
	
	
	
	
	
	private function getjaAdicionados(&$bd, $id_grupo){
		
		
		$ja_add = $bd->getPorQuery(new BeanUsuario(), 
											null, 
											"###.id_usuario IN (select fk_usuario from usuarios_grupos where fk_grupo=".$id_grupo.")", 
											null);

		$form = "
				<table class='tab_lista_usuarios' cellspacing=0  id='tab_ja_add'>
					<tr>
						<th align='center'>
						NOME
						</th>
					</tr>";
		
		if(count($ja_add)>0){

			foreach($ja_add as $usuario)
				$form .= "	
					<tr id='linha_ja_add_".$usuario->id."'>
						<td align='left'>
							<table width='100%'>
								<tr>
									<td align='left' width='85%' style='border:none'>
										".(strlen($usuario->nome_completo)<=0?$usuario->cpf:$usuario->nome_completo)."
									</td>
									<td align='center' width='15%' style='border:none'>
										<div class='bt bt_padrao bt_remover_usuario' onclick='javascript:removerUsuarioDeGrupo(".$id_grupo.", ".$usuario->id.")'>
											X
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>";	
		}	
		
		$form .= "
				</table>";
		
		return $form;
	}
	
	
	
	
	
	public function salvarAdicaoDeUsuario(){
		
		include_once AGRCL_PATH_ABS.'geral/usuarios/BeanUsuario.class.php';
		include_once AGRCL_PATH_ABS.'geral/grupos/BeanGrupo.class.php';
		include_once AGRCL_PATH_ABS.'geral/usuarios/BeanUsuarioGrupo.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';	
	
		$bd = new BdUtil();
		$comuns = new Comuns();
		
		$para_add = $bd->getPorId(new BeanUsuario(), $comuns->anti_injection($_POST['id_usuario']));
	
		$grupo = $bd->getPorId(new BeanGrupo(), $comuns->anti_injection($_POST['id_grupo']));
		
		$msg ="";
		
		if(is_object($para_add) && is_object($grupo)){
			
			$add = new BeanUsuarioGrupo;
				
			$add->fk_usuario = $para_add->id;
			$add->fk_grupo = $grupo->id;
			
			$add->id = $bd->novo($add);
			
			if($add->id<=0){
					
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}
			
			$msg =	$this->getParaAdicionar($bd, $_POST['id_grupo'])."##_##".$this->getjaAdicionados($bd, $_POST['id_grupo']);
				
		}
		else{
			
			echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
			return;	
		}
		
		echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($msg).'"}';
	}
	
	
	
	
	
	public function removerUsuarioDeGrupo(){
		
		include_once AGRCL_PATH_ABS.'geral/usuarios/BeanUsuario.class.php';
		include_once AGRCL_PATH_ABS.'geral/grupos/BeanGrupo.class.php';
		include_once AGRCL_PATH_ABS.'geral/usuarios/BeanUsuarioGrupo.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';	
	
		$bd = new BdUtil();
		$comuns = new Comuns();
		
		$para_remover = $bd->getPorId(new BeanUsuario(), $comuns->anti_injection($_POST['id_usuario']));
	
		if(is_object($para_remover)){
		
			if(!$bd->deletaPorQuery(new BeanUsuarioGrupo(), 
							"fk_grupo=".$comuns->anti_injection($_POST['id_grupo']).
								" and fk_usuario=".$para_remover->id)){
				
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}

			echo '{"status":"sucesso", "msg":"'.$comuns->preparaHTMLParaJson($this->getParaAdicionar($bd, $_POST['id_grupo'])."##_##".$this->getjaAdicionados($bd, $_POST['id_grupo'])).'"}';			
			return;
		}
		
		echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
	}


	
	
	
	
	public function excluirGrupo(){
		
		include_once AGRCL_PATH_ABS.'geral/grupos/BeanGrupo.class.php';
		include_once AGRCL_PATH_ABS.'geral/usuarios/BeanUsuarioGrupo.class.php';
		include_once AGRCL_PATH_ABS.'geral/grupos/BeanGrupoAcesso.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';	
	
		$comuns = new Comuns();
		$bd = new BdUtil();
		
		$grupo = $bd->getPorId(new BeanGrupo(), $comuns->anti_injection($_POST['id_grupo']));
		
		if(!is_object($grupo)){
			
			echo '{"status":"ERRO", "erro":"Grupo não encontrado."}';
			return;
		}
			
		if(strcmp($grupo->codigo, COD_GRP_ADMINS)==0){
			
			echo '{"status":"ERRO", "erro":"O grupo padrão de administradores não pode ser excluído."}';
			return;
		}
		

		// remove as referencias de usuários a esse grupo
		$bd->deletaPorQuery(new BeanUsuarioGrupo, "fk_grupo=".$grupo->id);
		
		// remove as referencias de permissoes a esse grupo
		$bd->deletaPorQuery(new BeanGrupoAcesso, "fk_grupo=".$grupo->id);
		
		if($bd->deletaPorId(new BeanGrupo, $grupo->id) !== false)
			echo '{"status":"sucesso"}';
		else
			echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
	}
	
	
	

	
}

?>