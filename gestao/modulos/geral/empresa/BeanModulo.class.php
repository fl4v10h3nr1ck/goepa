<?php


/** @AnotTabela(nome="modulos", prefixo="mdl") */
final class BeanModulo{


/** @AnotCampo(nome="id_modulo", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="nome") */
public $nome;

/** @AnotCampo(nome="codigo") */
public $codigo;

/** @AnotCampo(nome="status", tipo="int") */
public $status;

}
?>