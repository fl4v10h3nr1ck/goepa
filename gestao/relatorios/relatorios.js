	
	
	
	function navegacao(nav){
		
		var atual = $("#indice_atual").val();
		
		var provavel_prox = parseInt(atual)+nav;
		
		console.log(atual+" "+provavel_prox);
		
		if($("#mes_"+provavel_prox).length){
			
			$("#mes_"+atual).fadeOut("fast", 
				function(){
					
					$("#indice_atual").val(provavel_prox);
					
					$("#titulo_atual").html($("#titulo_"+provavel_prox).val());
					
					$("#mes_"+provavel_prox).fadeIn("fast");
					
					if($("#mes_"+(provavel_prox-1)).length)
						$("#bt_anterior").fadeIn("fast");
					else
						$("#bt_anterior").fadeOut("fast");
					
					if($("#mes_"+(provavel_prox+1)).length)
						$("#bt_proximo").fadeIn("fast");
					else
						$("#bt_proximo").fadeOut("fast");
				}
			);
		}
	}
	
	
	
	
	

	function novoCliente(){
		
		carregaPagina("op=CLT&sop=CAD");
	}
	
	
	
	
	function editarCliente(){
		
	var id = getIdSelecionado("tab_clientes");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		carregaPagina("op=CLT&sop=CAD&id="+id);
	}
	

	
	
	function salvarCliente(id){
	
		iniciaCarregamento(
			function(){
				jQuery.post(				
				$("#AGRCL_PATH_SMP").val()+'acao.php',
					{
						funcao:"salvarCliente",
						path:$("#path").val(),
						classe:$("#classe").val(),
						nome_razao:$('#nome_razao').val(),
						fantasia:$('#fantasia').val(),
						cpf_cnpj:$('#cpf_cnpj').val(),
						rg_ie:$('#rg_ie').val(),
						tel:$('#tel').val(),
						tel_2:$('#tel_2').val(),
						cliente_id:id,
						endereco:getEndereco()	
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
							finalizaCarregamento("bt_salvar_cliente", "area_carregando_cliente");
						}
						else{
							
							finalizaCarregamento("bt_salvar_cliente", "area_carregando_cliente");
							
							alert("Operação realizada com sucesso.");
							
							carregaPagina("op=CLT");
						}			
					}
				);
			},
		"bt_salvar_cliente",
		"area_carregando_cliente");				
	}
	

 
	
	
	function excluirCliente(){

		var id = getIdSelecionado("tab_clientes");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
	
	
		if(!confirm("Você tem certeza que deseja excluir este cliente?"))
			return;
		
	
		jQuery.post(				
			$("#AGRCL_PATH_SMP").val()+'acao.php',
			{
				funcao:"excluirCliente",
				path:$("#path").val(),
				classe:$("#classe").val(),
				cliente_id:id
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
					
					carregaPagina("op=CLT");	
				}			
			}
		);			
	}
	

	
	