<?php


/** @AnotTabela(nome="acessos", prefixo="acs") */
final class BeanAcesso{


/** @AnotCampo(nome="id_acesso", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="fk_modulo", tipo="int") */
public $fk_modulo;

/** @AnotCampo(nome="nome") */
public $nome;

/** @AnotCampo(nome="tipo", tipo="int") */
public $tipo;

/** @AnotCampo(nome="codigo") */
public $codigo;

/** @AnotCampo(nome="ordem", tipo="int") */
public $ordem;


	
	
	

	
	
}
?>