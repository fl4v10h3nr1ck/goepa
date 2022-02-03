<?php 
header('Content-type: text/html; charset=UTF-8');

if(!isset($_SESSION))
session_start();

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

chdir(dirname(__FILE__));

chdir('../');

require_once getcwd().'/vendor/autoload.php';

include_once getcwd().'/define.php';

include_once getcwd()."/relatorios/Relatorios.class.php";

$relatorio = new Relatorios;

$relatorio->conteudo();	
	
?>