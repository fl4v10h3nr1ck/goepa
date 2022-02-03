<?php


/** @AnotTabela(nome="logs", prefixo="lg", join="inner join usuarios as user on user.id_usuario=###.fk_usuario") */
final class BeanLog{

/**	@AnotCampo(nome="id_log", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="data") */
public $data;

/** @AnotCampo(nome="hora", tipo="int") */
public $hora;

/** @AnotCampo(nome="min", tipo="int") */
public $min;

/** @AnotCampo(nome="fk_usuario", tipo="int") */
public $fk_usuario;

/** @AnotCampo(nome="fk_alvo", tipo="int") */
public $fk_alvo;

/** @AnotCampo(nome="tipo", tipo="int") */
public $tipo;

/** @AnotCampo(nome="acao", tipo="int") */
public $acao;

/** @AnotCampo(nome="user.nome_completo", select_apenas=true, apelido="nome_usuario", sem_prefixo=true) */
public $nome_usuario;
	
	
}
?>