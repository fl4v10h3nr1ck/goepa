<?php


final class Endereco{

	

	
	public function dependencias(){
	
		echo "
		
		<script src='".GOEPA_PATH_SMP."modulos/geral/endereco/endereco.js' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".GOEPA_PATH_SMP."modulos/geral/endereco/endereco.css' type='text/css' media='all'>";
	}

	
	
	
	
	public function formDeEndereco($id_endereco){

		include_once GOEPA_BD_PATH_ABS."BdUtil.class.php";
		include_once GOEPA_PATH_ABS.'modulos/geral/endereco/BeanEndereco.class.php';				
		
		$bd = new BdUtil();
		
		$endereco = $bd->getPorId(new BeanEndereco(), $id_endereco);

		if(!is_object($endereco))
			$endereco  = new BeanEndereco();
		
		$form = "
				<div style='clear:both'></div>
				<div id='area_endereco'>
					<div style='border:solid 1px #EEE;margin:5px 0px 5px 0px;padding:5px'>
						<div id='div_logradouro' class='item_form'>
							Logradouro:<span class='campo_obrigatorio'>*</span><br>
							<input type='text' id='logradouro' value='".$endereco->logradouro."' maxlength='200'>	
						</div>
						<div id='div_numero' class='item_form'>
							N&#170;:<span class='campo_obrigatorio'>*</span><br>
							<input type='text' id='numero' value='".$endereco->numero."' class='campo_centralizado' maxlength='40'>
						</div>
						<div id='div_cidade' class='item_form'>
							Cidade:<span class='campo_obrigatorio'>*</span><br>
							<input type='text' id='cidade' value='".$endereco->cidade."' maxlength='150'>
						</div>
						<div id='div_uf' class='item_form'>
							UF:<span class='campo_obrigatorio'>*</span><br>
							<select id='uf'>
									<option value=''>...</option>";				
			
		foreach(array("PA", "AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO") as $uf)
			$form .= "				<option value='".$uf."' ".(strcmp($uf, $endereco->uf)==0?"selected":"").">".$uf."</option>";
			
			$form .= "			
							</select>
						</div>
						<div id='div_bairro' class='item_form'>
							Bairro:<br>
							<input type='text' id='bairro' value='".$endereco->bairro."'  maxlength='150'>	
						</div>
						<div id='div_cep' class='item_form'>
							CEP:<br>
							<input type='text' id='cep' value='".$endereco->cep."' class='campo_centralizado' maxlength='8' onchange='javascript:mascara(this, formatarCEP)'>	
						</div>
						<div id='div_complemento' class='item_form'>
							Complemento:<br>
							<input type='text' id='complemento' value='".$endereco->complemento."'  maxlength='150'>	
						</div>
						<div style='clear:both'></div>
					</div>
				</div>";
		
		return $form;
	}
	
	
	
		
	
	public function validacao($dados){
	
		include_once GOEPA_PATH_ABS.'modulos/geral/endereco/BeanEndereco.class.php';	
		include_once GOEPA_CMS_PATH_ABS.'Comuns.class.php';
		
		$comuns = new Comuns();
	
		$dados->logradouro 		= $comuns->anti_injection($dados->logradouro);
		$dados->numero 			= $comuns->anti_injection($dados->numero);
		$dados->cidade 			= $comuns->anti_injection($dados->cidade);
		$dados->uf 				= $comuns->anti_injection($dados->uf);
		$dados->bairro 			= $comuns->anti_injection($dados->bairro);
		$dados->cep 			= $comuns->anti_injection($dados->cep);
		$dados->complemento 	= $comuns->anti_injection($dados->complemento);
		
		
		if(strlen($dados->logradouro) <= 0)
			return '{"status":"ERRO", "erro":"Informe um logradouro."}';
		
		
		if(strlen($dados->numero) <= 0)
			return '{"status":"ERRO", "erro":"Informe um número residencial."}';
		
		
		if(strlen($dados->cidade) <= 0)
			return '{"status":"ERRO", "erro":"Informe uma cidade."}';
	
		
		if(strlen($dados->uf) <= 0)
			return '{"status":"ERRO", "erro":"Selecione uma UF (estado)."}';
		
		
		if(strlen($dados->cep)>0){
			
			if(!$comuns->validaCEP($dados->cep))
				return '{"status":"ERRO", "erro":"Informe um CEP válido."}';
		}
		
		return "";
	}
	

	
	
	
	public function salvaEndereco($bd, $dados, $id_endereco){
		
		include_once GOEPA_PATH_ABS.'modulos/geral/endereco/BeanEndereco.class.php';	
		
		$endereco = null;
		
		if($id_endereco>0)
			$endereco = $bd->getPorId(new BeanEndereco(), $id_endereco);

		if(!is_object($endereco))
			$endereco  = new BeanEndereco();
			
		$endereco->logradouro  		=  	$dados->logradouro;
		$endereco->numero  			=  	$dados->numero;
		$endereco->cidade  			=  	$dados->cidade;
		$endereco->uf  				=  	$dados->uf;
		$endereco->cep  			=  	$dados->cep;
		$endereco->bairro  			=  	$dados->bairro;
		$endereco->complemento  	=  	$dados->complemento;
		
		if($endereco->id<=0)
			return $bd->novo($endereco);
		
		return $bd->altera($endereco)?$endereco->id:0;
	}
	
	

	
}


?>