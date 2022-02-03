<?php


include_once GOEPA_PATH_ABS.'modulos/geral/login/Gandalf.class.php';	

include_once GOEPA_CMS_PATH_ABS.'Comuns.class.php';

include_once GOEPA_BD_PATH_ABS."BdUtil.class.php";

include_once GOEPA_PATH_ABS.'modulos/geral/empresa/BeanEmpresa.class.php';		


	
abstract class Base{


public $gandalf;

public $id_empresa;



	function __construct() {
		
		$this->gandalf = new Gandalf;
		
		$empresa = (new BdUtil)->getPrimeiroOuNada(new BeanEmpresa, null, null, null);
		
		if(!is_object($empresa))
			$this->id_empresa = 0;
		else
			$this->id_empresa = $empresa->id;
	}


	
	
	function dependencias(){}


	
	function conteudo(){}
	

	
	
	final function erro($msg){
		
	
		return "
		
			<div class='erro' align='center'>
				<img src='".GOEPA_PATH_IMGS."erro.png' id='icon'>
				<br><br>".$msg."
				<br><br><br><a href='".GOEPA_PATH_SMP."'>Voltar à tela principal</a>
				<br><br><a href='".GOEPA_PATH_SMP."'>Entrar em contato com o suporte técnico</a>
			</div>
		
		";	
	}
	

}	

?>