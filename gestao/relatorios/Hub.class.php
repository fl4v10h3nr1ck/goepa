<?php

include_once AGRCL_PATH_ABS.'Base.class.php';

include_once AGRCL_PATH_ABS.'movimento/Mod_MovimentoConexao.class.php';
include_once AGRCL_PATH_ABS.'campo/Mod_CampoConexao.class.php';
include_once AGRCL_PATH_ABS.'frota/Mod_FrotaConexao.class.php';

final class Hub extends Base{

	
	
	public function geraDados(){
	
		$relatorio=null;
		
		if(array_key_exists("op", $_GET)){
				
			if(array_key_exists("sd", $_GET) && $_GET['sd']>0){
				
				switch($_GET["op"]){
						
					case Mod_MovimentoConexao::COD_REL_RMB:
					case Mod_MovimentoConexao::COD_REL_RMB_RSM:
					case Mod_MovimentoConexao::COD_REL_RMV:
					$relatorio = (new Mod_MovimentoConexao())->getOpcao();
					break;	
										
					case Mod_CampoConexao::COD_REL_INS_INDV:		
					case Mod_CampoConexao::COD_REL_INS_GERL:
					case Mod_CampoConexao::COD_REL_REC_INDV:
					case Mod_CampoConexao::COD_REL_REC_GERL:
					case Mod_CampoConexao::COD_REL_APL:
					case Mod_CampoConexao::COD_REL_EST_COLH:
					case Mod_CampoConexao::COD_REL_APL_SERV:
					case Mod_CampoConexao::COD_REL_CST_QDR:
					$relatorio = (new Mod_CampoConexao())->getOpcao();
					break;	

					case Mod_FrotaConexao::COD_REL_ABS:
					$relatorio = (new Mod_FrotaConexao())->getOpcao();
					break;															
				}
			}
			else{	
					
				switch($_GET["op"]){
					
					case Mod_MovimentoConexao::COD_REL_RMV_GFC:
					$relatorio = (new Mod_MovimentoConexao())->getOpcao();
					break;
					
					case Mod_FrotaConexao::COD_REL_ABS_GFC:
					$relatorio = (new Mod_FrotaConexao())->getOpcao();
					break;		
				}
			}
		}


		if(is_object($relatorio)){
			
			require_once AGRCL_PATH_ABS.'/vendor/autoload.php';
			
			$relatorio->conteudo();
		}
		else
			$this->erro("Tipo de relatório inválido");	
	}

	
	
	
	

	
	
}
	
?>