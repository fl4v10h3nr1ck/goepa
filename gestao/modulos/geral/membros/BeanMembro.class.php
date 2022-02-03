<?php


/** @AnotTabela(nome="membros", prefixo="mmb") */
final class BeanMembro{


/** @AnotCampo(nome="id_membro", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="fk_endereco", tipo="int") */
public $fk_endereco;

/** @AnotCampo(nome="fk_usuario", tipo="int") */
public $fk_usuario;

/** @AnotCampo(nome="fk_loja", tipo="int") */
public $fk_loja;

/** @AnotCampo(nome="fk_trabalho_endereco", tipo="int") */
public $fk_trabalho_endereco;

/** @AnotColuna(rotulo="Nome", posicao=1, comprimento=85, alinhamento="left") 
	@AnotCampo(nome="nome") */
public $nome;

/** @AnotCampo(nome="nascimento") */
public $nascimento;

/** @AnotCampo(nome="uf") */
public $uf;

/** @AnotCampo(nome="nacionalidade") */
public $nacionalidade;

/** @AnotCampo(nome="estado_civil", tipo="int") */
public $estado_civil;

/** @AnotCampo(nome="data_casamento") */
public $data_casamento;

/** @AnotCampo(nome="tipo_sangue") */
public $tipo_sangue;

/** @AnotCampo(nome="profissao") */
public $profissao;

/** @AnotCampo(nome="aposentado", tipo="int") */
public $aposentado;

/** @AnotCampo(nome="nome_pai") */
public $nome_pai;

/** @AnotCampo(nome="nome_mae") */
public $nome_mae;

/** @AnotCampo(nome="cim") */
public $cim;

/** @AnotCampo(nome="cpf") */
public $cpf;

/** @AnotCampo(nome="rg") */
public $rg;

/** @AnotCampo(nome="rg_expeditor") */
public $rg_expeditor;

/** @AnotCampo(nome="rg_data_expedicao") */
public $rg_data_expedicao;

/** @AnotCampo(nome="titulo_eleitor") */
public $titulo_eleitor;

/** @AnotCampo(nome="titulo_eleitor_zona") */
public $titulo_eleitor_zona;

/** @AnotCampo(nome="titulo_eleitor_sessao") */
public $titulo_eleitor_sessao;

/** @AnotCampo(nome="tel_1") */
public $tel_1;

/** @AnotCampo(nome="tel_2") */
public $tel_2;

/** @AnotCampo(nome="tel_3") */
public $tel_3;

/** @AnotCampo(nome="email") */
public $email;

/** @AnotCampo(nome="nome_esposa") */
public $nome_esposa;

/** @AnotCampo(nome="nascimento_esposa") */
public $nascimento_esposa;

/** @AnotCampo(nome="trabalho_empresa") */
public $trabalho_empresa;

/** @AnotCampo(nome="data_cadastro") */
public $data_cadastro;

/** @AnotColuna(rotulo="Status", posicao=6, comprimento=15, alinhamento="center", func_composicao="formataStatus") 
	@AnotCampo(nome="status", tipo="int") */
public $status;




	public function formataStatus(){
		
		return '
			<input  '.($this->status>0?"checked":"").' id="usuario_status_'.$this->id.'" class="switch switch--shadow" type="checkbox" onChange="">
			<label for="usuario_status_'.$this->id.'"></label>';
	}


	
	
}
?>