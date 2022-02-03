

	function novoGrupo(){
		
		carregaPagina("op=GRP&sop=CAD");
	}
	
	
	
	
	function editarGrupo(){
		
	var id = getIdSelecionado("tab_grupos");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		carregaPagina("op=GRP&sop=CAD&id="+id);
	}
	
	
	
	
	function salvarGrupo(id){

		iniciaCarregamento(
			function(){
				jQuery.post(					
				$("#AGRCL_PATH_SMP").val()+'acao.php',
					{
						funcao:"salvarGrupo",
						path:$("#path").val(),
						classe:$("#classe").val(),
						id_grupo:id,
						codigo:$('#codigo').val(),
						nome:$('#nome').val(),
						descricao:$('#descricao').val()
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
							finalizaCarregamento("bt_salvar_grupo", "area_carregando_grupo");
						}
						else{
							
							finalizaCarregamento("bt_salvar_grupo", "area_carregando_grupo");
							
							alert("Operação realizada com sucesso.");
					
							carregaPagina("op=GRP");	
						}			
					}
				);
			},
		"bt_salvar_grupo",
		"area_carregando_grupo");					
	}
	
	
	
	
	
	function ativarDesativar(id){

		jQuery.post(				
		$("#AGRCL_PATH_SMP").val()+'acao.php',
			{
				funcao:"ativarDesativar",
				path:$("#path").val(),
				classe:$("#classe").val(),
				id_grupo:id
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
			
					carregaPagina("op=GRP");	
				}			
			}
		);	
	}
	
	
	
	
	
	function permissoes(){
		
		var id = getIdSelecionado("tab_grupos");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		carregaPagina("op=GRP&sop=PMS&id="+id);	
	}
	
	
	
	

	function salvarPermissoes(id){
		
		var dados = "";
	
		$(".permissao_item").each(function(){
			
			if($(this).is(":checked")){
			
				var id = $(this).attr('id').replace(/\D+/g, '');	
				
				dados += id+"@";
				
				if($(this).hasClass("vsn"))
					dados += "4";
				
				else if($(this).hasClass("ver"))
					dados += "1";
				
				else if($(this).hasClass("edt"))
					dados += "2";
				
				else if($(this).hasClass("exr"))
					dados += "3";
			
					
			dados += "#";
			}	
		});
		
		jQuery.post(				
		$("#AGRCL_PATH_SMP").val()+'acao.php',
			{
				funcao:"salvarPermissoes",
				path:$("#path").val(),
				classe:$("#classe").val(),
				id_grupo:id,
				permissoes:dados
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
			
					carregaPagina("op=GRP");	
				}			
			}
		);		
	}
	
	
	
	
	
	
	function marcaPermissao(id, tipo){
		
		if(tipo==0){
		
			$("#editar_"+id).prop('checked', false);
			$("#excluir_"+id).prop('checked', false);
		}
		else if(tipo==1){
		
			if($("#editar_"+id).is(":checked")){
				
				$("#ver_"+id).prop('checked', true);
				$("#excluir_"+id).prop('checked', false);	
			}
			else{
				
				$("#excluir_"+id).prop('checked', false);
			}
		}
		else if(tipo==2){
		
			if($("#excluir_"+id).is(":checked")){
				
				$("#ver_"+id).prop('checked', true);
				$("#editar_"+id).prop('checked', true);	
			}
		}
	}
	
	
	
	
	
	function adicionarUsuario(){
		
		var id = getIdSelecionado("tab_grupos");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		carregaPagina("op=GRP&sop=ADU&id="+id);	
	}
	
	
	
	
	
	function selecionaListaDeUsuario(id){
		
		$(".linha_para_add").css("background", "#FFF");
			
		$(".linha_para_add").removeClass("selec_lista_usuario_p_add");

		$("#linha_para_add_"+id).addClass("selec_lista_usuario_p_add");
			
		$("#linha_para_add_"+id).css("background", "#32baff");
	}
	
	

	
	
	function getIdSelecionadoListaDeUSuario(){
	
		if($(".selec_lista_usuario_p_add").length){
	
			var linha = $(".selec_lista_usuario_p_add")[0];
	
			var aux  =	$(linha).attr('id').split("_");
			
			if(aux.length>0)
				return aux[aux.length-1]; 
			
			return 0;
		}
		
	return 0;
	}
	
	
	
	
	
	function transferirUsuario(id_grupo){
		
		var id = getIdSelecionadoListaDeUSuario();
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		jQuery.post(				
		$("#AGRCL_PATH_SMP").val()+'acao.php',
			{
				funcao:"salvarAdicaoDeUsuario",
				path:$("#path").val(),
				classe:$("#classe").val(),
				id_grupo:id_grupo,
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
					
					var formularios  = aux.msg.split("##_##");
					
					if(formularios.length ==2){
						$("#area_para_add").html(formularios[0]);
						$("#area_ja_add").html(formularios[1]);
					}
				}			
			}
		);		
	}
	
	
	
	
	
	function removerUsuarioDeGrupo(id_grupo, id_usuario){
		
		jQuery.post(				
		$("#AGRCL_PATH_SMP").val()+'acao.php',
			{
				funcao:"removerUsuarioDeGrupo",
				path:$("#path").val(),
				classe:$("#classe").val(),
				id_grupo:id_grupo,
				id_usuario:id_usuario
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
					
					var formularios  = aux.msg.split("##_##");
					
					if(formularios.length ==2){
						$("#area_para_add").html(formularios[0]);
						$("#area_ja_add").html(formularios[1]);
					}
				}			
			}
		);		
		
		
	}
	
	
	
	
	function excluirGrupo(){
	
		var id = getIdSelecionado("tab_grupos");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		if(!confirm("Tem certeza que deseja excluir este grupo (operação irreversível)?"))
			return;
		
		
		jQuery.post(				
		$("#AGRCL_PATH_SMP").val()+'acao.php',
			{
				funcao:"excluirGrupo",
				path:$("#path").val(),
				classe:$("#classe").val(),
				id_grupo:id
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
			
					carregaPagina("op=GRP");	
				}			
			}
		);	
	}
	
	
	
	