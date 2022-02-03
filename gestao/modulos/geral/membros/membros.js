

	const ETP_CAD_MMB_GERAL     = 1;
	const ETP_CAD_MMB_DOCS      = 2;

	const COD_CAD_MEMBROS       = "MMB";


	jQuery(document).ready(function(){
		
		mudouUf();
		
		mudouEstadoCivil();
	});




	function novoMembro(){
		
		carregaPagina("op="+COD_CAD_MEMBROS+"&sop=CAD");
	}
	
	
	
	
	
	function editarMembro(){
		
		var id = getIdSelecionado("tab_membros");
	
		if(id<=0){
		
		alert("Clique em uma linha da tabela para selecioná-la.");
		return;
		}
		
		carregaPagina("op="+COD_CAD_MEMBROS+"&sop=CAD&id="+id);
	}

	
	
	
	function getNaturalidades(uf){
		
		if(uf=="")
			return "";
		
		if(uf=="PA")
			return "PARAENSE";
		
		if(uf=="AC")
			return "ACRIANO";
		
		if(uf=="AL")
			return "ALAGOANO";
		
		if(uf=="AP")
			return "AMAPAENSE";
		
		if(uf=="AM")
			return "AMAZONENSE";
		
		if(uf=="BA")
			return "BAIANO";
		
		if(uf=="CE")
			return "CEARENSE";
		
		if(uf=="DF")
			return "GOIANO";
		
		if(uf=="ES")
			return "ESPÍRITO-SANTENSE";
		
		if(uf=="GO")
			return "GOIANO";
		
		if(uf=="MA")
			return "MARANHENSE";
		
		if(uf=="MT")
			return "MATO-GROSSENSE";
		
		if(uf=="MS")
			return "SUL-MATO-GROSSENSE";
		
		if(uf=="MG")
			return "MINEIRO";
		
		if(uf=="PB")
			return "PARAIBANO";
		
		if(uf=="PR")
			return "PARANAENSE";
		
		if(uf=="PE")
			return "PERNAMBUCANO";
		
		if(uf=="PI")
			return "PIAUIENSE";
		
		if(uf=="RJ")
			return "FLUMINENSE";
		
		if(uf=="RN")
			return "RIO-GRANDENSE-DO-NORTE";
		
		if(uf=="RS")
			return "RIO-GRANDENSE-DO-SUL";
		
		if(uf=="RO")
			return "RONDONIENSE";
		
		if(uf=="RR")
			return "RORAIMENSE";
		
		if(uf=="SC")
			return "CATARINENSE";

		if(uf=="SP")
			return "PAULISTA";
		
		if(uf=="SE")
			return "SERGIPENSE";
		 
		if(uf=="TO")
			return "TOCANTINENSE";
	}




	function mudouUf(){
		
		$("#naturalidade").val(getNaturalidades($("#uf_naturalidade").val()));
	}




	function mudouEstadoCivil(){
		
		if($("#estado_civil").val() == 2)
			$("#data_casamento").prop("disabled", false);
		else{
			
			$("#data_casamento").prop("disabled", "disabled");
			$("#data_casamento").val("");
		}	
	}




	function proximo(atual){

		var dados;
		
		if(atual == ETP_CAD_MMB_GERAL)
			dados =	{
							funcao:"validarGeral",
							nome:$('#nome').val(),
							loja:$('#loja').val(),
							nascimento:$('#nascimento').val(),
							uf:$('#uf_naturalidade').val(),
							nacionalidade:$('#nacionalidade').val(),
							estado_civil:$('#estado_civil').val(),
							data_casamento:$('#data_casamento').val(),
							tipo_sangue:$('#tipo_sangue').val(),
							profissao:$('#profissao').val()	
					};
		else if(atual == ETP_CAD_MMB_DOCS)
			dados =	{
							funcao:"validarDocumentos",
							nome_pai:$('#nome_pai').val(),
							nome_mae:$('#nome_mae').val(),
							cim:$('#cim').val(),
							cpf:$('#cpf').val(),
							rg:$('#rg').val(),
							rg_expeditor:$('#rg_expeditor').val(),
							rg_data_expedicao:$('#rg_data_expedicao').val(),
							titulo_eleitor:$('#titulo_eleitor').val(),
							titulo_eleitor_zona:$('#titulo_eleitor_zona').val(),
							titulo_eleitor_sessao:$('#titulo_eleitor_sessao').val()
							
							
					};

		dados.path = $("#path").val();
		dados.classe = $("#classe").val();

		iniciaCarregamento(
			function(){
				
				jQuery.post(				
				$("#GOEPA_PATH_SMP").val()+'acao.php',
				dados,
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
							finalizaCarregamento("bt_proximo", "area_carregando_proximo");
						}
						else{
							
							if(aux.finalizar){
								
								alert("Cadastro realizada com sucesso.");
								carregaPagina("op="+COD_CAD_MEMBROS);
							}
							else
								$("#area_form").html(aux.form);
						}			
					}
				);
			},
		"bt_proximo",
		"area_carregando_proximo");					
	}
	
	



	function salvar(){

		iniciaCarregamento(
			function(){
				
				jQuery.post(				
				$("#GOEPA_PATH_SMP").val()+'acao.php',
					{
						funcao:"salvar",
						path:$("#path").val(),
						classe:$("#classe").val(),
						nome:$('#nome').val(),
						cpf:$('#cpf').val(),
						uf:$('#uf').val(),
						nacionalidade:$('#nacionalidade').val(),
						estado_civil:$('#estado_civil').val(),
						data_casamento:$('#data_casamento').val(),
						tipo_sangue:$('#tipo_sangue').val(),
						profissao:$('#profissao').val()	
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
							finalizaCarregamento("bt_salvar", "area_carregando_salvar");
						}
						else{
							
							alert("Operação realizada com sucesso.");
					
							carregaPagina("op=MMB");	
						}			
					}
				);
			},
		"bt_salvar",
		"area_carregando_salvar");					
	}
	
	
	
	
/*	
	
	function ativarDesativarMembro(){

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
	
	
	
	
	
	
	
