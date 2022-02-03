<?php

define('AMB_PRODUCAO', 	 			true);

define("COD_SISTEMA", 				"/");

define('GOEPA_PATH_SMP', 			"/goepa/gestao".COD_SISTEMA);

define('GOEPA_PATH_ABS', 			$_SERVER['DOCUMENT_ROOT'].GOEPA_PATH_SMP);

define('GOEPA_RAIZ_LIBS_ABS', 		$_SERVER['DOCUMENT_ROOT']."/libs/");

define('GOEPA_RAIZ_LIBS_SMP', 		"/libs/");

define("MAX_CARACTERES_POR_REL", 	300000);

define('DURACAO_DE_COOKIES', 		60*60*24*30); //um mes

?>