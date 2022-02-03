<?php


/** @AnotTabela(nome="entidades", prefixo="etd") */
final class BeanEntidade{


/** @AnotCampo(nome="id_entidade", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="fk_relacao", tipo="int") */
public $fk_relacao;

/** @AnotCampo(nome="nome") */
public $nome;

/** @AnotCampo(nome="codigo") */
public $codigo;

/** @AnotCampo(nome="tipo", tipo="int") */
public $tipo;

/** @AnotCampo(nome="status", tipo="int") */
public $status;

/** @AnotCampo(nome="params") */
public $params;


}

?>