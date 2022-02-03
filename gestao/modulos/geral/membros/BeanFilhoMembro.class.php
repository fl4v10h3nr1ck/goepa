<?php


/** @AnotTabela(nome="usuarios_grupos", prefixo="uxg") */
final class BeanUsuarioGrupo{


/** @AnotCampo(nome="id_usuario_grupo", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="fk_usuario", tipo="int") */
public $fk_usuario;

/** @AnotCampo(nome="fk_grupo", tipo="int") */
public $fk_grupo;


}
?>