<?php


/** @AnotTabela(nome="usuarios", prefixo="usr") */
final class BeanUsuario{


/** @AnotCampo(nome="id_usuario", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="senha") */
public $senha;

/** @AnotColuna(rotulo="Nome", posicao=1, comprimento=30, alinhamento="left") 
	@AnotCampo(nome="nome_completo") */
public $nome_completo;

/** @AnotColuna(rotulo="CPF", posicao=2, comprimento=20, alinhamento="center") 
	@AnotCampo(nome="cpf") */
public $cpf;

/** @AnotColuna(rotulo="TEL", posicao=3, comprimento=20, alinhamento="center") 
	@AnotCampo(nome="tel") */
public $tel;

/** @AnotColuna(rotulo="E-mail", posicao=4, comprimento=20, alinhamento="left") 
	@AnotCampo(nome="email") */
public $email;

/** @AnotColuna(rotulo="Status", posicao=6, comprimento=10, alinhamento="center", func_composicao="formataStatus") 
	@AnotCampo(nome="status", tipo="int") */
public $status;

/** @AnotCampo(nome="data_cadastro") */
public $data_cadastro;

/** @AnotCampo(nome="token") */
public $token;





	public function formataStatus(){
		
		return '
			<input  '.($this->status>0?"checked":"").' id="usuario_status_'.$this->id.'" class="switch switch--shadow" type="checkbox" onChange="javascript:ativarDesativarUser()">
			<label for="usuario_status_'.$this->id.'"></label>';
	}


	
	
}
?>