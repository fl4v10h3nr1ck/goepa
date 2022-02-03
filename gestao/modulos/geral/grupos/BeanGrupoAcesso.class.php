<?php


/** @AnotTabela(nome="grupos_acessos", prefixo="gxa", join="inner join acessos as acs on acs.id_acesso=###.fk_acesso") */
final class BeanGrupoAcesso{


/** @AnotCampo(nome="id_grupo_acesso", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="fk_acesso", tipo="int") */
public $fk_acesso;

/** @AnotCampo(nome="fk_grupo", tipo="int") */
public $fk_grupo;

/** @AnotCampo(nome="valor", tipo="int") */
public $valor;

/** @AnotCampo(nome="acs.tipo", select_apenas=true, apelido="tipo_acs", sem_prefixo=true) */
public $tipo;

/** @AnotCampo(nome="acs.codigo", select_apenas=true, apelido="cod_acs", sem_prefixo=true) */
public $cod_acesso;

}
?>