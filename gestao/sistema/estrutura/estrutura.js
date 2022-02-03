
	const  mostrar_log=  true;

	const  COD_SUCESSO=  "OK";
	
	

	$(document).ready(function(){
		
		posiciona();

		//$(window).resize(posiciona);

		//$(window).on('load', posiciona);
		
		jQuery('select').select2({width : '100%'});
	});
			


	
	
	function posiciona(){		
	
		var altura_topo = jQuery("#topo").outerHeight();
		
		var altura_barra = jQuery("#barra").outerHeight();
		
		var altura_conteudo = jQuery("#conteudo").outerHeight();
		
		var altura_rodape = jQuery("#rodape").outerHeight();
		
		var altura_tela = jQuery(window).outerHeight();
		
		var soma = altura_topo+altura_barra+altura_rodape;
		
		if((soma+altura_conteudo)<=altura_tela){
			
			jQuery("#rodape").css({ position: "fixed", bottom: "0px" });
			
			jQuery("#conteudo").css({ "min-height":
			(altura_tela - 
				(soma + 5 +
					parseInt(jQuery("#conteudo").css("margin-top").replace('px', '')) +
						parseInt(jQuery("#conteudo").css("margin-bottom").replace('px', '')) + 
							parseInt(jQuery("#conteudo").css("padding-top").replace('px', '')) + 
								parseInt(jQuery("#conteudo").css("padding-bottom").replace('px', ''))))+"px"});
		}
		else
			jQuery("#rodape").css({ position: "static"});
		
	}

	
	
	
	
	
	function carregaPagina(parametros){
		
		window.open($("#GOEPA_PATH_SMP").val()+(parametros!=null && parametros.length>0?"?"+parametros:""), "_SELF");
	}
	
	
	
	
	
	
	function carregaPaginaDeRelatorio(parametros){
		
		window.open($("#GOEPA_PATH_SMP_PATH_SMP").val()+"relatorios/index.php"+(parametros!=null && parametros.length>0?"?"+parametros:""), "_BLANK");
	}
	
	
	

	
	
	function iniciaCarregamento(funcao, id_bt, id_carregando){
		
		if($("#"+id_bt).length && $("#"+id_carregando).length){
		
			$("#"+id_bt).fadeOut("fast", 
				function(){
					
					$("#"+id_carregando).fadeIn("fast", 
						function(){
							
						funcao();
						}
					);
				}
			);
		}		
	}
	
	
	
	
	
	
	function finalizaCarregamento(id_bt, id_carregando){
		
		if($("#"+id_bt).length && $("#"+id_carregando).length){
		
			$("#"+id_carregando).fadeOut("fast", 
				function(){
					$("#"+id_bt).fadeIn("fast");
				}
			);
		}
	}
	
	
	
	
	
	function configuraCalendario(id){
		
		var data_atual = new Date();

		var anos=[];
	
		for(var i = 1993; i< data_atual.getFullYear()+5; i++)
			anos.push(i);
		
		if($('#'+id).length>0)
			$('#'+id).glDatePicker({selectableYears: anos});
		else
			$('.campo_data').glDatePicker({selectableYears: anos});
		
	}
	
	
	
	
	
	function mostrarDialogo(id, info, acao, rotulo_botao){
		
		mostrarDialogo(id, info, acao, rotulo_botao, 500, null);
	}
	
	
	
	
	
	function mostrarDialogo(id, info, acao, rotulo_botao, largura){
		
		mostrarDialogo(id, info, acao, rotulo_botao, largura, null);
	}
	
	
	
	
	
	function mostrarDialogo(id, info, acao, rotulo_botao, largura, callback){
		
		if($("#"+id).length){
		
			$("#"+id).html(info);
	
		    $("#"+id).dialog({
				create: callback,
				resizable: false,
				height: "auto",
				width: largura,
				modal: true,
				buttons:[{
					click: acao,
					text:rotulo_botao
				}]
			});
		}
	}
	
	
	
	
	
	function dialogoCarregando(){
		
		$("#area_outros_dialogos").html("<div align='center'><br><br><img src='"+$("#GOEPA_PATH_SMP").val()+"/sistema/imgs/load.gif'></div>");
	
		$("#area_outros_dialogos").dialog(
			{
				resizable: false,
				dialogClass: "no-close",
				height: "auto",
				width: "250px",
				modal: true,
				 title: "Aguarde..."
			}
		);
	}
	
	
	
	
	function fechaDialogoCarregando(){
		
		$("#area_outros_dialogos").dialog("close");
	}
	
	
	
	
	
	
	function carregarSelect(id){
		
		if($(id).length)
			$(id).select2({width : '100%'});
	}
	
	
	