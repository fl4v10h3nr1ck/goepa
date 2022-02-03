<?php

/** @AnotTabela(nome="enderecos", prefixo="edr") */
final class BeanEndereco{


/** @AnotCampo(nome="id_endereco", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="logradouro") */
public $logradouro;

/** @AnotCampo(nome="numero") */
public $numero;

/** @AnotCampo(nome="cidade") */
public $cidade;

/** @AnotCampo(nome="uf") */
public $uf;

/** @AnotCampo(nome="bairro") */
public $bairro;

/** @AnotCampo(nome="cep") */
public $cep;

/** @AnotCampo(nome="complemento") */
public $complemento;



	
}
?>