
		
	function login(){
	
		$("#login_msg_erro").html("");
	
		iniciaCarregamento(
			function(){
				
				jQuery.post(
							
				$("#GOEPA_PATH_SMP").val()+'acao.php',
					{
						funcao:"tentativaDeLogin",
						path:$("#path").val(),
						classe:$("#classe").val(),
						cpf:$("#cpf").val(), 
						senha:$("#senha").val(), 
						salvar:$("#continuar_logado").is(":checked")?1:0
					},
					function(retorno){ 
						
						if(mostrar_log)
							console.log(retorno);
						
						var erro = false;
						var aux;
						try { 
							aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));
						}
						catch (e) { 
							erro = true; 
						} 
						
						if(erro || aux.status!='sucesso'){
						
							$("#login_msg_erro").html("Usuário ou senha inválidos.");
								
							finalizaCarregamento("bt_logar", "area_carregando_logar");
						}
						else
							location.reload();
						
					}
				);		
			},
		"bt_logar",
		"area_carregando_logar");
	}
	
	
	
	
	
	function sair(){
	
		iniciaCarregamento(
			function(){
				jQuery.post(
							
				$("#GOEPA_PATH_SMP").val()+'acao.php',
					{
						funcao:"sair",
						path:$("#path_sair").val(),
						classe:$("#classe_sair").val(),
					},
					function(retorno){ 
							
						location.reload();
					}
				);
			},
		"bt_sair",
		"area_carregando_sair");
	}
	
	