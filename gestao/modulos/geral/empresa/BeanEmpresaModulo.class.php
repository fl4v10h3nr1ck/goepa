<?php


/** @AnotTabela(nome="empresas_modulos", prefixo="exm", join="inner join modulos as xmdl on xmdl.id_modulo=###.fk_modulo") */
final class BeanEmpresaModulo{


/** @AnotCampo(nome="id_empresa_modulo", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="fk_empresa", tipo="int") */
public $fk_empresa;

/** @AnotCampo(nome="fk_modulo", tipo="int") */
public $fk_modulo;

/** @AnotCampo(nome="xmdl.nome", select_apenas=true, apelido="nome_modulo", sem_prefixo=true)*/
public $nome_modulo;

}
?>