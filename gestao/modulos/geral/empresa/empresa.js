


	$(document).ready(function(){
		
		carregarSelect("select");
	});







	function salvarEmpresa(id){

		iniciaCarregamento(
			function(){
	/*
				var modulos  = '{"modulos":[0';
				
				$(".id_modulo_add").each(function(){
					
					modulos += ", "+$(this).val();
					
				});
				
				modulos  += ']}';
				
				if(mostrar_log)
					console.log(modulos);
	*/
				jQuery.post(				
				$("#GOEPA_PATH_SMP").val()+'acao.php',
					{
						funcao:"salvarEmpresa",
						path:$("#path").val(),
						classe:$("#classe").val(),
						id_empresa:id,
						codigo:$('#codigo').val(),
						/*razao:$('#razao').val(),*/
						fantasia:$('#fantasia').val(),
						/*cnpj:$('#cnpj').val(),*/
						tel:$('#tel').val(),
						tel_2:$('#tel_2').val(),
						email:$('#email').val(),
						site:$('#site').val(),
						/*modulos:modulos,*/
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
							finalizaCarregamento("bt_salvar_empresa", "area_carregando_empresa");
						}
						else{
							
							alert("Operação realizada com sucesso.");
					
							carregaPagina("op=EMP");	
						}			
					}
				);
			},
		"bt_salvar_empresa",
		"area_carregando_empresa");				
	}
	

	
	
	
	function addModulo(){
		
		var id = $("#modulo").val();
		
		if(id<=0){
			
			alert("Escolha um módulo.");
			return;
		}
		
		var rot =$("#modulo option[value='"+id+"']").text();
	
		
		$("#lista_modulos_adds").html(
			$("#lista_modulos_adds").html()+
				"<tr id='mod_add_"+id+"'>"+
					"<td width='80%'>"+rot+
						"<input type='hidden' class='id_modulo_add' value='"+id+"'>"+
					"</td>"+
					"<td width='20%' align='center'>"+
						"<div class='bt bt_padrao bt_remover_modulo' onclick='javascript:removeModulo("+id+", \""+rot+"\")'>"+
						"X"+
						"</div>"+
					"</td>"+
				"</tr>");
		
		$("#modulo option[value='"+id+"']").remove();
		
		$("#modulo").select2();
	}
	
	
	
	
	
	function removeModulo(id, nome){

		$('#mod_add_'+id).remove();
		
		$('#modulo').append($('<option>', {value: id, text: nome}));
		
		$("#modulo").select2();
	}
	
	
	
