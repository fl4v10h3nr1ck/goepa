 <?php


include_once GOEPA_PATH_ABS.'Base.class.php';



class Configuracoes extends Base{





	function __construct() {
		
		parent::__construct();
	}

	


	
	public function dependencias(){
	
		echo "
		
		<link rel='stylesheet' href='".GOEPA_JS_PATH_SMP."select2.min.css' type='text/css' media='all'>
		
		<script type='text/javascript' src='".GOEPA_JS_PATH_SMP."select2.min.js'></script>
		
		<script src='".GOEPA_PATH_SMP."configuracoes/entidades.js?v=".rand(1, 999)."' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".GOEPA_PATH_SMP."configuracoes/entidades.css?v=".rand(1, 999)."' type='text/css' media='all'>";
	}

	
	

	
	
	public function getArea(&$bd, $tipo){
	
		include_once GOEPA_PATH_ABS.'configuracoes/BeanEntidade.class.php';
	
		$comuns = new Comuns();
	
		$dados = $this->getDados($tipo, $bd);
	
		$titulo = 			$dados['titulo'];
		$rotulo_novo = 		$dados['rotulo_novo'];
		$titulo_tab = 		$dados['titulo_tab'];
		$relacoes=			$dados['relacoes'];
		$rotulo_relacao = 	$dados['rotulo_relacao'];
		$relacao_erro = 	$dados['relacao_erro'];
		$params = 			array_key_exists('params', $dados)?$dados['params']:null;
	
		$form = "
				<div class='area_bloco' id='entidades_".$tipo."'>
					<div class='bloco'>
						<div align='center'>
							<b>".$titulo."</b><br>
							<hr width='100%'>
						</div>
						<div id='area_alteracao_".$tipo."' style='display:none'>
						</div>
						<div id='bloco_".$tipo."'>
							<div class='div_nome_entidade item_form'>
								".$rotulo_novo.":<span class='campo_obrigatorio'>*</span><br>
								<input type='text' class='nome_entidade' id='nome_entidade_".$tipo."' value=''>
							</div>";
		
		$subtrador= 0;
		
		if($relacoes!==null){
				
			$subtrador += 50;
				
			$form .= "			
							<div class='div_relacao_entidade item_form'>
								".$rotulo_relacao.":<span class='campo_obrigatorio'>*</span><br>
								<input type='hidden' value='".$relacao_erro."' id='relacao_erro_".$tipo."'>
								<select class='relacao_entidade'  id='relacao_entidade_".$tipo."'>
									<option value='0'>...</option>";
								
			if(count($relacoes)>0){
				
				foreach($relacoes as $relacao)
					$form .= "
									<option value='".$relacao['id']."'>".$relacao['nome']."</option>";
			}
			
			$form .= "			</select>
							</div>";
		}
		
		$tem_params = false;
		
		
		if($params!=null){
		
			$tem_params = true;
		
			$subtrador += 50;
			
			$form .= "			
							<div class='div_params_entidade item_form'>
								".$params['rotulo'].":<span class='campo_obrigatorio'>*</span><br>
								<input type='hidden' value='".$params['erro']."' id='params_erro_".$tipo."'>
								<input type='text' class='params_entidade' id='params_entidade_".$tipo."' value='' ".(strlen($params['filtro'])>0?$params['filtro']:"").">
							</div>";
		
		}
						
		$form .= "	
							<div style='clear:both'></div>
							<br>
							<div align='center'>
								<div class='bt bt_padrao bt_add_entidade' id='bt_add_entidade_".$tipo."' onclick='javascript:novaEntidade(".$tipo.", \"".$_GET["op"]."\")'>
									Adicionar
								</div>
								<div align='center' class='carregando' id='area_carregando_entidade_".$tipo."'>
									<img src='".GOEPA_PATH_IMGS."load.gif'>
								</div>
							</div>
							<div style='clear:both'></div>
							<div class='area_lista_entidade' style='height:".(350 - $subtrador)."px'>
								<table width='100%'>
									<tr>
										<th width='".($tem_params?"65":"80")."%'>".$titulo_tab."</th>
										".($tem_params?"<th width='20%'>".$params['rotulo_tab']."</th>":"")."
										<th width='".($tem_params?"15":"20")."%'></th>
									</tr>";
		
		
		$entidades = $bd->getPorQuery(new BeanEntidade(), null, "###.tipo=".$tipo." and ###.status>0", "###.nome ASC");

		if(count($entidades)>0){
			foreach($entidades as $i=>$entidade){
				
				$aux_relacao = "";
				
				if($dados['relacoes']!=null && count($dados['relacoes'])>0){
					
					foreach($dados['relacoes'] as $relacao){
					
						if($relacao['id'] == $entidade->fk_relacao){
							
							$aux_relacao = "(".$relacao['nome'].") ";
							break;
						}
					}
				}
				

				$form .= "	
									<tr>
										<td>
											<div style='padding-left:5px'>
												<a href='javascript:getFormDeAlteracao(".$entidade->id.", ".$tipo.", \"".$_GET["op"]."\")'>".$aux_relacao.$entidade->nome."</a>
											</div>
										</td>
										".($tem_params?"<td align='center'>".$entidade->params."</td>":"")."
										<td align='center'>
											<div class='bt bt_padrao bt_remover_entidade' onclick='javascript:removerEntidade(".$tipo.", ".$entidade->id.", \"".$_GET["op"]."\")'>
												X
											</div>
										</td>
									</tr>";	
									
				if($i<count($entidades)-1)
					$form .= "	
									<tr>
										<td colspan='".($tem_params?3:2)."'>
											<hr class='linha'>
										</td>
									</tr>";	
									

			}									
		}
		
		
		$form .= "				</table>
							</div>
						</div>
					</div>
				</div>";
	
		
		return $form;
	}
	
	
		

	
			
	public function salvaEntidade(){
	
		include_once GOEPA_PATH_ABS.'configuracoes/BeanEntidade.class.php';
	
		$comuns = new Comuns();
		$bd = new BdUtil();
	
		$_POST['id'] 				= 	$comuns->anti_injection($_POST['id']);
		$_POST['tipo'] 				= 	$comuns->anti_injection($_POST['tipo']);
		$_POST['nome'] 				= 	$comuns->anti_injection($_POST['nome']);
		$_POST['relacao'] 			= 	$comuns->anti_injection($_POST['relacao']);
		$_POST['relacao_erro'] 		= 	$comuns->anti_injection($_POST['relacao_erro']);
		$_POST['params_erro'] 		= 	$comuns->anti_injection($_POST['params_erro']);
		
		$user_atual = $this->gandalf->usuarioAtual();
		
		if(!is_object($user_atual)){
			
			echo '{"status":"ERRO", "erro":"Usuário atual não encontrado."}';
			return;
		}
		
		if(strlen($_POST['nome'])==0){
			
			echo '{"status":"ERRO", "erro":"Informe um nome."}';
			return;
		}	
		
		if($_POST['relacao']>=0){
		
			if($_POST['relacao']==0){
				
				echo '{"status":"ERRO", "erro":"'.$_POST['relacao_erro'].'"}';
				return;
			}	
		}
		
		if(strcmp($_POST['params'], "#NADA#")!=0){
		
			if(strlen($_POST['params'])<=0){
				
				echo '{"status":"ERRO", "erro":"'.$_POST['params_erro'].'"}';
				return;
			}	
		}
		
		$entidade = $bd->getPorId(new BeanEntidade(), $_POST['id']);

		if(!is_object($entidade))
			$entidade  = new BeanEntidade();
				
		$entidade->nome 		= $_POST['nome'];	
		$entidade->fk_relacao 	= $_POST['relacao']>0?$_POST['relacao']:NULL;
		$entidade->params 		= strcmp($_POST['params'], "#NADA#")!=0?$_POST['params']:NULL;
		
		if($entidade->id<=0){
		
			$entidade->tipo 		= 	$_POST['tipo'];	
			$entidade->status 		= 	1;
		
			$entidade->id = $bd->novo($entidade);
			
			if($entidade->id<=0){
					
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}	
		}
		else{
			
			if(!$bd->altera($entidade)){
			
				echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
				return;
			}
		}
		
		echo '{"status":"sucesso"}';	
	}
	
	
	
		
			
	public function removerEntidade(){
	
		include_once GOEPA_PATH_ABS.'configuracoes/BeanEntidade.class.php';
	
		$comuns = new Comuns();
		$bd = new BdUtil();
	
		$_POST['id_entidade'] 	= 	$comuns->anti_injection($_POST['id_entidade']);
	
		$entidade = $bd->getPorId(new BeanEntidade(), $_POST['id_entidade']);
				
		if(!is_object($entidade)){
			
			echo '{"status":"ERRO", "erro":"Item não encontrado."}';
			return;
		}	
	
		$entidade->status 		= 0;
		
		if(!$bd->altera($entidade)){
				
			echo '{"status":"ERRO", "erro":"Falha na gravação, por favor, tente novamente."}';
			return;
		}
					
		echo '{"status":"sucesso"}';	
	}
	
	
	
	
	
	public function getFormDeAlteracao(){
		
		include_once GOEPA_PATH_ABS.'configuracoes/BeanEntidade.class.php';
		
		$comuns = new Comuns();
		$bd = new BdUtil();
	
		$dados = $this->getDados($_POST['tipo'], $bd);
		
		$_POST['id_entidade'] = $comuns->anti_injection($_POST['id_entidade']);
		
		$entidade = $bd->getPorId(new BeanEntidade(), $_POST['id_entidade']);

		if(!is_object($entidade))
			$entidade  = new BeanEntidade();
	
		$form = "
				<div id='entidades_alt_".$_POST['tipo']."'>
					<div class='div_nome_alt_entidade item_form'>
						".$dados['rotulo_altera'].":<span class='campo_obrigatorio'>*</span><br>
						<input type='text' class='nome_alt_entidade' id='nome_alt_entidade' value='".$entidade->nome."'>
					</div>";
		
		if($dados['relacoes']!==null){
						
			$form .= "			
					<div class='div_relacao_alt_entidade item_form'>
						".$dados['rotulo_relacao'].":<span class='campo_obrigatorio'>*</span><br>
						<input type='hidden' value='".$dados['relacao_erro']."' id='relacao_erro_alt'>
						<select class='relacao_alt_entidade'  id='relacao_alt_entidade'>
							<option value='0'>...</option>";
								
			if(count($dados['relacoes'])>0){
				
				foreach($dados['relacoes'] as $relacao)
					$form .= "
							<option value='".$relacao['id']."' ".($relacao['id']==$entidade->fk_relacao?"selected":"").">".$relacao['nome']."</option>";
			}
			
			$form .= "	</select>
					</div>";
		}
		
		
		if($dados['params']!=null){
			
			$form .= "			
							<div class='div_params_entidade item_form'>
								".$dados['params']['rotulo'].":<span class='campo_obrigatorio'>*</span><br>
								<input type='hidden' value='".$dados['params']['erro']."' id='params_erro_alt'>
								<input type='text' class='params_entidade' id='params_entidade_alt' value='' ".(strlen($dados['params']['filtro'])>0?$dados['params']['filtro']:"").">
							</div>";
		
		}
				
		$form .= "	
						<div style='clear:both'></div>
						<br>
						<div align='center'>
							<div class='bt bt_padrao bt_add_entidade' id='bt_alt_entidade' onclick='javascript:alteraEntidade(".$_POST['id_entidade'].", ".$_POST['tipo'].", \"".$_POST['cod']."\")'>
								Salvar
							</div>
							<div align='center' class='carregando' id='area_carregando_entidade_alt'>
								<img src='".GOEPA_PATH_IMGS."load.gif'>
							</div>
						</div>
						<div align='right'>
							<img src='".GOEPA_PATH_IMGS."voltar2.svg' class='bt_voltar' onclick='javascript:voltar(".$_POST['tipo'].")'>
						</div>
					</div>
				</div>";
				
		echo '{"status":"sucesso", "form":"'.$comuns->preparaHTMLParaJson($form).'"}';
	}
	
	
}

?>