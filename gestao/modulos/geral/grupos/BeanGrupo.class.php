<?php

chdir(dirname(__FILE__)); 

chdir('../../../');

include_once getcwd().'/define.php';



/** @AnotTabela(nome="grupos_de_usuarios", prefixo="gp") */
final class BeanGrupo{



/** @AnotCampo(nome="id_grupo_de_usuario", tipo="int", ehId=true) */
public $id;

/** @AnotColuna(rotulo="Nome", posicao=2, comprimento=20, alinhamento="center") 
	@AnotCampo(nome="nome") */
public $nome;

/** @AnotColuna(rotulo="Código", posicao=1, comprimento=15, alinhamento="center") 
	@AnotCampo(nome="codigo") */
public $codigo;

/** @AnotColuna(rotulo="Descrição", posicao=3, comprimento=30, alinhamento="left", nao_pesquisar=true) 
	@AnotCampo(nome="descricao") */
public $descricao;

/** @AnotColuna(rotulo="Status", posicao=4, comprimento=10, alinhamento="center", func_composicao="formataStatus") 
	@AnotCampo(nome="status", tipo="int") */
public $status;




	public function formataStatus(){
		
		if(strcmp($this->codigo, COD_GRP_ADMINS)!=0 && 
			array_key_exists('PD_EDT_STTS_GRP', $_SESSION) && 
				$_SESSION['PD_EDT_STTS_GRP'])
			return '
				<input  '.($this->status>0?"checked":"").' id="grupo_status_'.$this->id.'" class="switch switch--shadow" type="checkbox" onChange="javascript:ativarDesativar('.$this->id.')">
				<label for="grupo_status_'.$this->id.'"></label>';
		
		
		return "<span style='color:".($this->status>0?"green'>":"red'>IN")."ATIVO</span>";	
	}

	

	
	
}
?>