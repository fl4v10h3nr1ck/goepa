<?php

include_once GOEPA_PATH_ABS.'configuracoes/Configuracoes.class.php';



final class ConfiguracaoGeral extends Configuracoes{


	
	
	
	final function conteudo(){
	
		include_once GOEPA_BD_PATH_ABS."BdUtil.class.php";
		
		$bd = new BdUtil();
		
		$form = "
			<input type='hidden' id='path'   value='/geral/'/>
			<input type='hidden' id='classe' value='ConfiguracaoGeral'/>
			<div id='areas'>
				<p><b>DOCUMENTOS</b><br><hr width='100%'></p>
				".$this->getArea($bd, TP_ENTDD_DOC_CATGS)."
				<div style='clear:both'></div>
			</div>";
		
		echo $form;
	}
	
	
	
	
			
	
	final function getDados($tipo, &$bd){
	
		$dados = array();
		$dados['titulo'] 				= "";
		$dados['rotulo_novo'] 			= "";
		$dados['rotulo_altera'] 		= "";
		$dados['titulo_tab'] 			= "";
		$dados['relacoes']				= null;
		$dados['rotulo_relacao'] 		= "";
		$dados['relacao_erro'] 			= "";
		$dados['params'] 				= null;
	
		switch($tipo){
		
			case TP_ENTDD_DOC_CATGS:
				$dados['titulo']="CATEGORIAS";
				$dados['rotulo_novo'] = "Nova Categoria";
				$dados['rotulo_altera'] = "Atualizar Categoria";
				$dados['titulo_tab'] = "Categorias Cadastradas";
				break;
		}
		
		return $dados;
	}
	
	

	
	
	
	
	
	

	
	
}
	
?>