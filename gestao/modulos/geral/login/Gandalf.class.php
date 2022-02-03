 <?php


class Gandalf{


	
	public function usuarioAtual(){
		
		include_once GOEPA_PATH_ABS.'modulos/geral/usuarios/BeanUsuario.class.php';
		
		if(array_key_exists("usuario", $_SESSION) && $_SESSION['usuario']!=null)
			return unserialize($_SESSION['usuario']);
	
		return null;
	}
	
	

	

	public function temPermissao($cod_permissao, $permissaoPara = ACESSO_SIM){
	
		$usuario = $this->usuarioAtual();
	
		if(!is_object($usuario) || strlen($cod_permissao) == 0)
			return false;
	
		if($usuario->id==99999 && $usuario->status==1 &&
			strlen($usuario->usuario)>0 && strcmp($usuario->usuario, NOME_USER_DEV)==0 && 
				strlen($usuario->senha)>0 && strcmp($usuario->senha, SENHA_USER_DEV)==0)
			return true;
	
		return $this->temPermissaoPorUsuario($usuario->id, $cod_permissao, $permissaoPara);
	}


	
	
	
	public function temPermissaoPorUsuario($id_usuario, $cod_permissao, $permissaoPara = ACESSO_SIM){
	
		if($id_usuario<=0 || strlen($cod_permissao) == 0)
			return false;	
	
		include_once GOEPA_PATH_ABS.'modulos/geral/grupos/BeanGrupo.class.php';
	
		$bd = new BdUtil();

		if($this->usuarioEhAdmin($bd, $id_usuario))
			return true;
		
		// se nao é admin, permissaoPara tem que esta definida. 
		if(strlen($permissaoPara) == 0)
			return false;
	
		$subquery = "";
	
		if($permissaoPara == ACESSO_SIM || $permissaoPara == ACESSO_NAO)
			$subquery = "gxa.valor=".$permissaoPara;
		elseif($permissaoPara == ACESSO_VER || $permissaoPara == ACESSO_EDITAR || $permissaoPara == ACESSO_EXCLUIR){
			
			if($permissaoPara==ACESSO_VER)
				$subquery = "(gxa.valor=".ACESSO_VER." or gxa.valor=".ACESSO_EDITAR." or gxa.valor=".ACESSO_EXCLUIR.")";
			elseif($permissaoPara==ACESSO_EDITAR)
				$subquery = "(gxa.valor=".ACESSO_EDITAR." or gxa.valor=".ACESSO_EXCLUIR.")";
			elseif($permissaoPara==ACESSO_EXCLUIR)
				$subquery = "(gxa.valor=".ACESSO_EXCLUIR.")";
		}
		else
			return false;
		
	
		$grupo=$bd->getPrimeiroOuNada(new BeanGrupo(), 
									"inner join usuarios_grupos as uxg on ###.id_grupo_de_usuario=uxg.fk_grupo and uxg.fk_usuario=".$id_usuario." 
										inner join grupos_acessos as gxa on gxa.fk_grupo=uxg.fk_grupo 
											inner join acessos as acs on acs.id_acesso=gxa.fk_acesso ", 
										"###.status>0 and acs.codigo='".$cod_permissao."' and ".$subquery,
											null);
		
		return is_object($grupo)?true:false;
	}


	
	
	
	public function usuarioEhAdmin(&$bd, $id_usuario){
		
		include_once GOEPA_PATH_ABS.'modulos/geral/grupos/BeanGrupo.class.php';
	
		$grupo=$bd->getPrimeiroOuNada(new BeanGrupo(), 
									"inner join usuarios_grupos as uxg on ###.id_grupo_de_usuario=uxg.fk_grupo and uxg.fk_usuario=".$id_usuario, 
										"###.status>0 and ###.codigo='".COD_GRP_ADMINS."'",
											null);
		return is_object($grupo)?true:false;
	}



}


?>