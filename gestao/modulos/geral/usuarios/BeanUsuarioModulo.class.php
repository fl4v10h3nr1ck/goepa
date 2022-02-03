<?php


/** @AnotTabela(nome="usuarios_modulos", prefixo="exm", join="inner join modulos as md on md.id_modulo=###.fk_modulo") */
final class BeanUsuarioModulo{


/** @AnotCampo(nome="id_usuario_modulo", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="fk_usuario", tipo="int") */
public $fk_usuario;

/** @AnotCampo(nome="fk_modulo", tipo="int") */
public $fk_modulo;

/** @AnotCampo(nome="md.nome", select_apenas=true, apelido="nome_modulo", sem_prefixo=true)*/
public $nome_modulo;

}
?>