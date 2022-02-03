<?php



final class Log{

	
	final function addLog(&$bd, $tipo, $acao, $alvo){
	
		include_once AGRCL_PATH_ABS.'geral/login/Gandalf.class.php';
		include_once AGRCL_PATH_ABS.'log/BeanLog.class.php';
	
		$log = new BeanLog;
		
		$this->gandalf = new Gandalf;
	
		if(!is_object($this->gandalf->usuarioAtual()))
			return 0;
		
		$log->data 				= date("Y-m-d");
		$log->hora 				= date("H");
		$log->min 				= date("i");
		$log->fk_usuario 		= $this->gandalf->usuarioAtual()->id;
		$log->fk_alvo  			= $alvo;
		$log->tipo				= $tipo;
		$log->acao				= $acao;
		
		return $bd->novo($log);
	}
	
	
	
}
?>