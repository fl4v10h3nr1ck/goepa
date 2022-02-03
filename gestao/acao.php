<?php

header('Content-type: text/html; charset=UTF-8');

if(!isset($_SESSION))
session_start();

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

chdir(dirname(__FILE__)); 

include_once getcwd().'/define.php';


	if((array_key_exists( "funcao", $_POST) || array_key_exists( "funcao", $_GET)) && 
			(array_key_exists( "path", $_POST) || array_key_exists( "path", $_GET)) &&
				(array_key_exists( "classe", $_POST) || array_key_exists( "classe", $_GET))){
	
		$funcao  = "";
		$path = "";
		$classe="";
	
		if(array_key_exists( "funcao", $_POST))
			$funcao  = $_POST["funcao"];
		else
			$funcao  = $_GET["funcao"];

		if(array_key_exists( "path", $_POST))
			$path  = $_POST["path"];
		else
			$path  = $_GET["path"];
		
		if(array_key_exists( "classe", $_POST))
			$classe  = $_POST["classe"];
		else
			$classe  = $_GET["classe"];
		
		include_once getcwd().$path.$classe.'.class.php';
		
		$aux = new $classe;
		
		if(!is_object($aux)){
			
			echo '{"status":"ERRO", "ERRO":"Parâmetros inválidos (N_CLASSE)."}';
			return;
		}
		
		if (method_exists( $aux, $funcao))
			call_user_func(array( $aux, $funcao));
		else
			echo '{"status":"ERRO", "ERRO":"Parâmetros inválidos (N_FUNCAO)."}';
	}
	else
		echo '{"status":"ERRO", "ERRO":"Parâmetros inválidos (N_POST)."}';



	
?>