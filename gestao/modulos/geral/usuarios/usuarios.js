
	jQuery(document).ready(function(){
		
		trocarSenha();
	});




	function novoUsuario(){
		
		carregaPagina("op=USR&sop=CAD");
	}
	
	
	
	
	
	function editarUsuario(){
		
		var id = getIdSelecionado("tab_usuarios");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		carregaPagina("op=USR&sop=CAD&id="+id);
	}

	
	
	

	
	function salvarUsuario(id){

		iniciaCarregamento(
			function(){
				/*
				var ids_modulos  = "";
				
				$(".id_modulo_add").each(function(){
					
					ids_modulos += $(this).val()+"_";
					
				});
			

				console.log(ids_modulos);*/
			
				jQuery.post(				
				$("#GOEPA_PATH_SMP").val()+'acao.php',
					{
						funcao:"salvarUsuario",
						path:$("#path").val(),
						classe:$("#classe").val(),
						id_usuario:id,
						nome:$('#nome').val(),
						senha:$('#senha').val(),
						repete_senha:$(repete_senha).val(),
						//ids_modulos:ids_modulos,
						tel:$('#tel').val(),
						email:$('#email').val(),
						cpf:$('#cpf').val()		
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
							alert(aux.erro);
							finalizaCarregamento("bt_salvar_usuario", "area_carregando_usuario");
						}
						else{
							
							alert("Operação realizada com sucesso.");
					
							carregaPagina("op=USR");	
						}			
					}
				);
			},
		"bt_salvar_usuario",
		"area_carregando_usuario");					
	}
	
	
	

	
	function trocarSenha(){
		
		if($("#trocar_senha").length>0){
			if($("#trocar_senha").is(":checked")){
				
				$("#senha").prop("disabled", false);
				$("#repete_senha").prop("disabled", false);
			}
			else{
				
				$("#senha").prop("disabled", "disabled");
				$("#repete_senha").prop("disabled", "disabled");
				
				$("#senha").val("");
				$("#repete_senha").val("");
			}
		}
		else{
			
			$("#senha").prop("disabled", false);
			$("#repete_senha").prop("disabled", false);
		}		
	}
	
	
		
	
	
	
	function ativarDesativarUser(){

		var id = getIdSelecionado("tab_usuarios");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
	
		jQuery.post(				
			$("#GOEPA_PATH_SMP").val()+'acao.php',
			{
				funcao:"ativarDesativarUser",
				path:$("#path").val(),
				classe:$("#classe").val(),
				id_usuario:id
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
							
					alert("Operação realizada com sucesso.");
					
					carregaPagina("op=USR");	
				}			
			}
		);			
	}
	


	
/*	
	
	function addModulo(){
		
		var id = $("#modulo").val();
		
		var rot =$("#modulo option[value='"+id+"']").text();
		
		$("#modulo option[value='"+id+"']").remove();
		
		$("#lista_modulos_adds").html(
			$("#lista_modulos_adds").html()+
				"<tr id='mod_add_"+id+"'><td width='80%'>"+rot+"<input type='hidden' class='id_modulo_add' value='"+id+"'></td><td width='20%' align='center'><div class='bt bt_padrao bt_remover_modulo' onclick='javascript:removeModulo("+id+", \""+rot+"\")'>X</div></td></tr>");
		
	}
	
	
	
	
	
	function removeModulo(id, nome){

		$('#mod_add_'+id).remove();
		
		$('#modulo').append($('<option>', {value: id, text: nome}));
	}
	
	
*/	
	
	
	
	
	
	
	
