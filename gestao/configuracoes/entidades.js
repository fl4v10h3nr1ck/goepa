	
	
	
	$(document).ready(function(){
		
		carregarSelect("select");
	});
		

	
	
	function getFormDeAlteracao(id, tipo, cod){

		jQuery.post(				
		$("#GOEPA_PATH_SMP").val()+'acao.php',
			{
				funcao:"getFormDeAlteracao",
				path:$("#path").val(),
				classe:$("#classe").val(),
				id_entidade:id,
				tipo:tipo,
				cod:cod
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
						
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else{
					
					$("#area_alteracao_"+tipo).html(aux.form);	
					
					$("#bloco_"+tipo).fadeOut("fast", function(){
						
						$("#area_alteracao_"+tipo).fadeIn("fast");
						
						carregarSelect("select");
					});
				}
			}
		);		
	}
	
	
	
	
	
	function voltar(tipo){
		
		$("#area_alteracao_"+tipo).html("");	
					
		$("#area_alteracao_"+tipo).fadeOut("fast", function(){
						
			$("#bloco_"+tipo).fadeIn("fast");
		});
	}
	
	
	
	
	
	function novaEntidade(tipo, cod){
		
		iniciaCarregamento(
			function(){
				jQuery.post(				
					$("#GOEPA_PATH_SMP").val()+'acao.php',
					{
					funcao:"salvaEntidade",
					path:$("#path").val(),
					classe:$("#classe").val(),
					id:0,
					tipo:tipo,
					nome:$("#nome_entidade_"+tipo).val(),
					relacao:$("#relacao_entidade_"+tipo).length?$("#relacao_entidade_"+tipo).val():-1,
					relacao_erro:$("#relacao_erro_"+tipo).length?$("#relacao_erro_"+tipo).val():"",
					params:$("#params_entidade_"+tipo).length?$("#params_entidade_"+tipo).val():"#NADA#",
					params_erro:$("#params_erro_"+tipo).length?$("#params_erro_"+tipo).val():""
					},
					function(retorno){ 
													
					if(mostrar_log)
						console.log(retorno);
														
					var erro = false;
					var aux;
						try { aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));}
						catch (e) {erro = true; } 
															
						if(erro || aux.status!='sucesso'){
							
							alert(aux.erro);
							
							finalizaCarregamento("bt_add_entidade_"+tipo, "area_carregando_entidade_"+tipo);
						}
						else{
										
							alert("Operação realizada com sucesso.");
								
							carregaPagina("op="+cod);	
						}	
					}
				)
			},
		"bt_add_entidade_"+tipo,
		"area_carregando_entidade_"+tipo);	
	}
	
	
	
	
	

	function removerEntidade(tipo, id_entidade, cod){
		
		if(!confirm("Você tem certeza que deseja excluir este item?"))
			return;

		jQuery.post(				
			$("#GOEPA_PATH_SMP").val()+'acao.php',
			{
				funcao:"removerEntidade",
				path:$("#path").val(),
				classe:$("#classe").val(),
				id_entidade:id_entidade
			},
			function(retorno){ 
												
				if(mostrar_log)
					console.log(retorno);	
													
				var erro = false;
				var aux;
				try { aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));}
				catch (e) {erro = true; } 
														
				if(erro || aux.status!='sucesso')
					alert(aux.erro);
				else{
										
					alert("Operação realizada com sucesso.");
								
					carregaPagina("op="+cod);	
				}
			}
		);
	}
	
	
	
	

	
	function alteraEntidade(id, tipo, cod){
		
		iniciaCarregamento(
			function(){
				jQuery.post(				
					$("#GOEPA_PATH_SMP").val()+'acao.php',
					{
					funcao:"salvaEntidade",
					path:$("#path").val(),
					classe:$("#classe").val(),
					id:id,
					tipo:tipo,
					nome:$("#nome_alt_entidade").val(),
					relacao:$("#relacao_alt_entidade").length?$("#relacao_alt_entidade").val():-1,
					relacao_erro:$("#relacao_erro_alt").length?$("#relacao_erro_alt").val():"",
					params:$("#params_entidade_alt").length?$("#params_entidade_alt").val():"#NADA#",
					params_erro:$("#params_erro_alt").length?$("#params_erro_alt").val():""
					},
					function(retorno){ 
													
					if(mostrar_log)
						console.log(retorno);	
														
					var erro = false;
					var aux;
						try { aux = $.parseJSON(retorno.substring(retorno.indexOf("{")));}
						catch (e) {erro = true; } 
															
						if(erro || aux.status!='sucesso'){
							
							alert(aux.erro);
							
							finalizaCarregamento("bt_alt_entidade", "area_carregando_entidade_alt");
						}
						else{
										
							alert("Operação realizada com sucesso.");
								
							carregaPagina("op="+cod);		
						}	
					}
				)
			},
		"bt_alt_entidade",
		"area_carregando_entidade_alt");	
	}
	
	
	