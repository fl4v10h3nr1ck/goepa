<?php


/** @AnotTabela(nome="empresas", prefixo="emp") */
final class BeanEmpresa{


/** @AnotCampo(nome="id_empresa", tipo="int", ehId=true) */
public $id;

/** @AnotColuna(rotulo="Código", posicao=1, comprimento=10, alinhamento="center") 
	@AnotCampo(nome="codigo") */
public $codigo;

/** @AnotCampo(nome="nome_fantasia") */
public $nome_fantasia;

/** @AnotColuna(rotulo="Razão Social", posicao=2, comprimento=35, alinhamento="left") 
	@AnotCampo(nome="razao_social") */
public $razao_social;

/** @AnotColuna(rotulo="CNPJ", posicao=3, comprimento=20, alinhamento="center") 
	@AnotCampo(nome="cnpj") */
public $cnpj;

/** @AnotCampo(nome="status", tipo="int") */
public $status;

/** @AnotCampo(nome="fk_endereco", tipo="int") */
public $fk_endereco;

/** @AnotColuna(rotulo="TEL", posicao=4, comprimento=15, alinhamento="left") 
	@AnotCampo(nome="fone_1") */
public $fone_1;

/** @AnotCampo(nome="fone_2") */
public $fone_2;

/** @AnotColuna(rotulo="E-mail", posicao=5, comprimento=20, alinhamento="left") 
	@AnotCampo(nome="email") */
public $email;

/** @AnotCampo(nome="site") */
public $site;

/** @AnotCampo(nome="data_cadastro") */
public $data_cadastro;


}
?>