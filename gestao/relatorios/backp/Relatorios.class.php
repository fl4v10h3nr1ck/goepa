<?php

include_once AGRCL_PATH_ABS.'Base.class.php';


define("STYLE_TABCAB", "border:solid 1px #000;background:#FFFFFF;color:#000000");


final class Relatorios extends Base{

private $fonte_maior;
private $fonte_normal;
private $fonte_menor;

private $cor_grafico;

private $bg_cabecalho;

private $cor_fonte;



	function __construct() {
		
		parent::__construct();
		
		$this->fonte_maior = "14px";
		$this->fonte_normal = "12px";
		$this->fonte_menor = "10px";
		
		$this->bg_cabecalho= "#FFF";
		
		$this->cor_fonte= "#333";
		
		$this->cor_grafico = array('#F3BB2A', '#87CEEB', '#00FF00', '#f25004');
	}

	
	
	
	public function dependencias(){
	
		echo "
		
		<script src='".AGRCL_PATH_SMP."relatorios/relatorios.js' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".AGRCL_PATH_SMP."relatorios/relatorios.css' type='text/css' media='all'>";
	}

	
	
	
	
	final function conteudo(){

		if(array_key_exists("sd", $_GET) && $_GET['sd']>0){
		
			$mpdf= new \Mpdf\Mpdf();

			$nome_arquivo = "REL_".rand(1, 1000000).".pdf";
			
			$mpdf->SetTitle($this->getTitulo());
				
			$mpdf->WriteHTML($this->getRelatorio());
			
			if(array_key_exists("op", $_GET) && strcmp($_GET["op"], "RELRCI")!=0)
				$mpdf->SetHTMLFooter($this->getRodape());
			
			$mpdf->Output($nome_arquivo, 'I');
			
			//echo $this->getRelatorio();
		}
		else
			echo $this->getRelatorio();
		
	}
	
	
	
	
	
	private function getTitulo(){
		
		if(array_key_exists("op", $_GET)){
			
			switch($_GET["op"]){
				
				case "RELREB":
				return "Relatório de Romaneio de Embarque";
					case "RELRVN":
					return "Relatório de Romaneio de Venda";
						case "RELRVN":
						return "Relatório de Abastecimento";
							case "RELINS":
							return "Relatório de Inspeções";
								case "RELAPL":
								return "Relatório de Aplicação";
									case "RELRCM":
									case "RELRCI":
									return "Relatório de Recomendações";
										case "RELINSIN":
										return "INSPEÇÕES";
											default:
											return "ERRO";
			}
		}
			
		return "ERRO";
	}
	
	
	
	
	
	private function getRodape(){
		
		return "<div align='center' style='font-family:Times;font-size:12px;background:#000;color:#FFF'>
					Copyright ".date("Y")." Agrocontrole. Todos os Direitos Reservados.
					</div>";
	}

	
	
	
	
	
	private function getCabecalho($bd){
		
		include_once AGRCL_PATH_ABS.'geral/empresa/BeanEmpresa.class.php';
		include_once AGRCL_PATH_ABS.'geral/endereco/BeanEndereco.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';
		
		$empresa = $bd->getPorId(new BeanEmpresa(), $this->id_empresa);
		$comuns = new Comuns();
		
		$rel = "
				<table width='100%' style='font-family:Times;font-size:".$this->fonte_normal."'>
					<tr>
						<td width='40%' align='left'>
							<img src='".AGRCL_PATH_IMGS."banner.jpg' height='70px'>
						</td>
						<td width='60%' align='right' style='font-family:Times;font-size:".$this->fonte_menor."'>";
			
		if(is_object($empresa))	{							
				
			$endereco = $bd->getPorId(new BeanEndereco(), $empresa->fk_endereco);
				
			$rel .= "		<b>".mb_strtoupper($empresa->razao_social, 'UTF-8')."</b><br>
								C.N.P.J.: ".$empresa->cnpj."<br>
								".(is_object($endereco)?mb_strtoupper($comuns->formataEndereco($endereco->logradouro, $endereco->numero, $endereco->bairro, $endereco->cep, $endereco->cidade, $endereco->uf, $endereco->complemento), 'UTF-8'):"");
								
								/*."<br>
								FONE: ".$empresa->fone_1.(strlen($empresa->fone_1)>0 && strlen($empresa->fone_2)>0?" | ":"").$empresa->fone_2."<br>
								".$empresa->email.(strlen($empresa->email)>0 && strlen($empresa->site)>0?" | ":"").$empresa->site;*/
		}				
			
		$rel .= "					
						</td>
					</tr>
				</table>
				<table width='100%' style='border-bottom: 1px solid #000;border-top: 1px solid #000;'>
					<tr>
						<td align='left' width='20%'>
						</td>
						<td align='center' width='60%'>
							<b><span style='font-size:".$this->fonte_maior."'>".mb_strtoupper($this->getTitulo(), 'UTF-8')."</span></b>
						</td>
						<td align='right' width='20%' style='font-size:".$this->fonte_normal."'>
							<b>Gerado em:<br></b>".date("d/m/Y")."
						</td>
					</tr>
				</table>
				<br>";
		
		return $rel;
	}

	
	
	
	
	private function getRelatorio(){
	
		include_once AGRCL_BD_PATH_ABS."BdUtil.class.php";
		
		$bd = new BdUtil();
	
		if(array_key_exists("op", $_GET)){
			
			if(array_key_exists("sd", $_GET) && $_GET['sd']>0){
			
				switch($_GET["op"]){
					
					case "RELREB":
					return $this->getRelatorioDeRomaneioDeEmbarque($bd);	
						case "RELRVN":
						return $this->getRelatorioDeRomaneioDeVenda($bd);	
							case "RELINS":
							return $this->getRelatorioDeInspecoes($bd);	
								case "RELAPL":
								return $this->getRelatorioDeAplicacoes($bd);	
									case "RELRCM":
									return $this->getRelatorioDeRecomendacoes($bd);
										case "RELABS":
										return $this->getRelatorioDeAbastecimento($bd);
											case "RELRCI":
											return $this->getRelatorioDeRecomendacaoIndividual($bd);
												case "RELINSIN":
												return $this->getRelatorioDeInspecao($bd);
													default:
														return $this->erro("Tipo de relatório desconhecido (ERRO REL001).");
				}
			}
			else{
				
				switch($_GET["op"]){
				
				case "RELREB":	
				return;
					case "RELRVN":
					return $this->getRelatorioDeRomaneioDeVendaGrafico($bd);
						case "RELABS":
						return $this->getRelatorioDeAbastecimentoGrafico($bd);	
							default:
							return $this->erro("Tipo de relatório desconhecido (ERRO REL001).");
							
							
				}
			}
		}
		else
			return $this->erro("Tipo de relatório indefinido (ERRO REL002).");
		
	}
	
	
		
	
	
	
	private function setTemplate(&$bd, $conteudo){
		
		
		$rel = "<div align='left'>";
		
		if(array_key_exists("op", $_GET) && strcmp($_GET["op"], "RELRCI")!=0)
			$rel .= $this->getCabecalho($bd);
		
		$rel .= $conteudo."
			</div>";
			
		return $rel;
	}
	
	
	
	
	
	
	private function naoHaDados($titulo){
		
		return "<div align='center'>
					<b>".$titulo."</b>
					<br><br><br>
					Não há dados para o período.
					<br><br><br>
					</div>";
	}
	
	
	
	
	
	
	private function preparaNome($valor){
		
		
		$valor = str_replace("á", "a", $valor);
		$valor = str_replace("â", "a", $valor);
		$valor = str_replace("à", "a", $valor);
		$valor = str_replace("ã", "a", $valor);
		$valor = str_replace("é", "e", $valor);
		$valor = str_replace("ê", "e", $valor);
		$valor = str_replace("í", "i", $valor);
		$valor = str_replace("ó", "o", $valor);
		$valor = str_replace("ô", "o", $valor);
		$valor = str_replace("õ", "o", $valor);
		$valor = str_replace("ú", "u", $valor);
		$valor = str_replace("ç", "c", $valor);
	   
		$valor = str_replace("Á", "A", $valor);
		$valor = str_replace("Â", "A", $valor);
		$valor = str_replace("À", "A", $valor);
		$valor = str_replace("Ã", "A", $valor);
		$valor = str_replace("É", "E", $valor);
		$valor = str_replace("Ê", "E", $valor);
		$valor = str_replace("Í", "I", $valor);
		$valor = str_replace("Ó", "O", $valor);
		$valor = str_replace("Ô", "O", $valor);
		$valor = str_replace("Õ", "O", $valor);
		$valor = str_replace("Ú", "U", $valor);
		$valor = str_replace("Ç", "C", $valor);
		
		return $valor;
	}
	
	
	
	
	
	
	/**************************************************************************/
	
	
	
	
	
	private function getRelatorioDeRomaneioDeVenda(&$bd){
	
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Data.class.php';
		include_once AGRCL_BD_PATH_ABS."BdUtil.class.php";
		include_once AGRCL_PATH_ABS.'movimento/romaneio_venda/BeanRomaneioVenda.class.php';
		include_once AGRCL_PATH_ABS.'movimento/clientes/BeanCliente.class.php';
		include_once AGRCL_PATH_ABS.'movimento/romaneio_venda/BeanRomaneioVendaItem.class.php';
		include_once AGRCL_CALC_PATH_ABS.'Calculo.class.php';
		include_once AGRCL_PATH_ABS.'configuracoes/BeanEntidade.class.php';
		
		$data = new Data();
		$bd = new BdUtil();
		$comuns = new Comuns();
		$calculo = new Calculo();
		
		$subquery = "";
		
		$subquery_ds = "";
		
		$subquery_su = "";
		
		$subquery_pl = "";
		
		$data_inicial= "";
		$data_final= "";
		$tem_duas_datas= false;
		$cliente= null;
		$detalhado= false;
		$detalhes= "";
		
		if(array_key_exists("i", $_GET) && strlen($_GET['i'])==10){
			
			$data_inicial= $data->converteEUAParaBR($_GET['i']);
			
			if(array_key_exists("f", $_GET) && strlen($_GET['f'])==10){
			
				$data_final= $data->converteEUAParaBR($_GET['f']);
				
				$subquery = " (###.data between '".$_GET['i']."' and '".$_GET['f']."') ";
				$tem_duas_datas = true;
			}
			else
				$subquery = " (###.data>='".$_GET['i']."') ";
		}
		else{
			
			if(array_key_exists("f", $_GET) && strlen($_GET['f'])==10){
				
				$data_final= $data->converteEUAParaBR($_GET['f']);
				
				$subquery = " (###.data<='".$_GET['f']."') ";
			}
		}
		
		
		if(array_key_exists("c", $_GET) && $_GET['c']>0){
			
			$subquery .= (strlen($subquery)>0?" AND ":"")." (###.fk_cliente=".$_GET['c'].") ";
			
			$cliente = $bd->getPorId(new BeanCliente(), $_GET['c']);

			$detalhes = "<b>Cliente: </b>".(is_object($cliente)?
											$cliente->nome_razao:"")."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		
		if(array_key_exists("le", $_GET) && $_GET['le']>0){
			
			$subquery .= (strlen($subquery)>0?" AND ":"")." (###.fk_local_embarque=".$_GET['le'].") ";
			
			$local = $bd->getPorId(new BeanEntidade(), $_GET['le']);
			
			$detalhes .="<b>Local: </b>".(is_object($local)?
											$local->nome:"")."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		
		if((array_key_exists("ds", $_GET) && $_GET['ds']>0) || 
			(array_key_exists("pl", $_GET) && $_GET['pl']>0)){
		
			$subquery .= (strlen($subquery)>0?" AND ":"").
							" (###.id_romaneio_de_venda IN (select x.fk_romaneio_venda from itens_romaneio_venda as x where ";
		
			$aux = "";
			
			if(array_key_exists("ds", $_GET) && $_GET['ds']>0){
			
				$aux = " x.fk_descricao=".$_GET['ds'];
								
				$subquery_ds = 	" AND ###.fk_descricao=".$_GET['ds'];			
		
				$descricao = $bd->getPorId(new BeanEntidade(), $_GET['ds']);
			
				$detalhes .="<b>Descrição: </b>".(is_object($descricao)?$descricao->nome:"")."&nbsp;&nbsp;&nbsp;&nbsp;";
			}
		
			if(array_key_exists("su", $_GET) && $_GET['su']>0){
			
				$aux = " x.fk_subdescricao=".$_GET['su'];
								
				$subquery_su = 	" AND ###.fk_subdescricao=".$_GET['su'];			
		
				$subdescricao = $bd->getPorId(new BeanEntidade(), $_GET['su']);
			
				$detalhes .="<b>Subdescrição: </b>".(is_object($subdescricao)?$subdescricao->nome:"")."&nbsp;&nbsp;&nbsp;&nbsp;";
			}
		
		
		
			if(array_key_exists("pl", $_GET) && $_GET['pl']>0){
			
				$aux .= (strlen($aux)>0?" AND ":"")." x.fk_planta=".$_GET['pl'];
								
				$subquery_pl = 	" AND ###.fk_planta=".$_GET['pl'];			
		
				$planta = $bd->getPorId(new BeanEntidade(), $_GET['pl']);
			
				$detalhes .="<b>Planta: </b>".(is_object($planta)?$planta->nome:"")."&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			
			$aux .= "))";
			$subquery .= $aux;
		}
		
		if(array_key_exists("d", $_GET) && $_GET['d']>0)
			$detalhado = true;
		
		$rel = "";
							
		$form ="";			
		
		$romaneios = $bd->getPorQuery(new BeanRomaneioVenda(), null, $subquery, "###.data DESC");

		$peso_total = 0;
		$preco_total = 0;
		
		if(count($romaneios)>0){
			
			$form .= "
					<table width='100%'  style='font-size:".$this->fonte_menor."'>";
			
			$dia = $romaneios[0]->data;
			
			$peso_dia = 0;
			$preco_dia = 0;
			
			foreach($romaneios as $i=>$romaneio){
				
				if(strcmp($dia, $romaneio->data)!=0){
					
					$form .= "
						<tr>
							<td align='center' colspan='5'><hr width='100%'></td>
						</tr>
						<tr>
							<td align='right' colspan='3' style='font-weight:bold'>Total para ".$data->converteEUAParaBR($dia).":&nbsp;&nbsp;</td>
							<td align='center' style='font-weight:bold'>".$calculo->formataParaMostrar($peso_dia, false)."</td>
							<td align='center' style='font-weight:bold'>".$calculo->formataParaMostrar($preco_dia)."</td>
						</tr>
						<tr>
							<td align='center' colspan='5'><hr width='100%'></td>
						</tr>";	
						
					$dia = $romaneio->data;	
					$peso_dia = 0;
					$preco_dia = 0;
				}
				
				$itens = $bd->getPorQuery(new BeanRomaneioVendaItem(), null, "###.fk_romaneio_venda=".$romaneio->id.$subquery_ds.$subquery_su.$subquery_pl, "###.id_item_romaneio_venda ASC");

				$total = "0.00";
				$peso = "0.00";
				
				if(count($itens)>0){
			
					foreach($itens as $item){
						
						$total = $calculo->soma($total, $item->valor);
						$peso = $calculo->soma($peso, $item->peso);
					}
				}
				
				$form .= "
						<tr>
							<td align='center' style='width:10%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>Data</td>
							<td align='center' style='width:10%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>N&#176</td>
							<td align='center' style='width:44%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>Cliente</td>
							<td align='center' style='width:23%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>QTDe (Kg)</td>
							<td align='center' style='width:13%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>C. Total (R$)</td>
						</tr>
						<tr>
							<td align='center' ".($detalhado?"rowspan='3'":"").">".$romaneio->formataData()."</td>
							<td align='center' ".($detalhado?"rowspan='3'":"").">".$romaneio->formataId()."</td>
							<td align='left'>".$romaneio->nome_cliente."</td>
							<td align='right'>".$calculo->formataParaMostrar($peso, false)."</td>
							<td align='right'>".$calculo->formataParaMostrar($total)."</td>
						</tr>";

				
				if($detalhado){
					
					$form .= "
						<tr>
							<td align='center' colspan='3'>
								<table width='100%' style='font-size:".$this->fonte_menor."'>
									<tr>
										<td align='center' style='width:27.5%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>Descrição</td>
										<td align='center' style='width:27.5%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>Variedade</td>
										<td align='center' style='width:15%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>Peso(Kg)</td>
										<td align='center' style='width:15%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>V.Uni.</td>
										<td align='center' style='width:15%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>Total (R$)</td>
									</tr>";
									
					if(count($itens)>0){		
							
						foreach($itens as $item)
							$form .= "
									<tr>
										<td align='left'>".$item->nome_descricao."</td>
										<td align='left'>".$item->nome_variedade."</td>
										<td align='right'>".$calculo->formataParaMostrar($item->peso, false)."</td>
										<td align='right'>".$calculo->formataParaMostrar($item->custo_unitario)."</td>
										<td align='right'>".$calculo->formataParaMostrar($item->valor)."</td>
									</tr>";	
					}
					
					$form .= "	</table>
							</td>
						</tr>";
				}
					
					
				$peso_total = $calculo->soma($peso_total, $peso);
				$preco_total = $calculo->soma($preco_total, $total);	
				
				$peso_dia = $calculo->soma($peso_dia, $peso);
				$preco_dia = $calculo->soma($preco_dia, $total);
			
				$form .= "	
						<tr>
							<td align='center' colspan='3' style='font-weight:bold'>De ".$romaneio->nome_local_embarque." para ".$romaneio->nome_destino."</td>
						</tr>";
			}
			
			$form .= "	
						<tr>
							<td align='center' colspan='5'><hr width='100%'></td>
						</tr>
						<tr>
							<td align='right' colspan='3'><b>Total para ".$data->converteEUAParaBR($dia).":</b>&nbsp;&nbsp;</td>
							<td align='center'><b>".$calculo->formataParaMostrar($peso_dia, false)."</b></td>
							<td align='center'><b>".$calculo->formataParaMostrar($preco_dia)."</b></td>
						</tr>";	
			$form .= "
					</table>";
		}
		else
			$form = "
					<div align='center'>
					<br><br><br><br>
					Sem dados para o período informado
					<br><br><br><br>
					</div>".$form;
					
		$rel .= "	
					<div align='center' style='border:solid 1px #333;padding:10px;margin:10px;font-size:".$this->fonte_normal."'>
						<table width='100%' style='font-size:".$this->fonte_normal."'>
							<tr>";
							
		if(strlen($detalhes)>0)
			$rel .= "			<td align='left' colspan='2' width='100%'>
									".$detalhes."
								</td>
							</tr>
							<tr>";

			$rel .= "				
								<td align='left' width='50%'>
									<b>Período: </b>".$data_inicial.($tem_duas_datas?" à ":"").$data_final."
								</td>
								<td align='left' width='50%'>
									<b>Total (R$): ".$calculo->formataParaMostrar($preco_total)."</b>
								</td>
							</tr>
							<tr>
								<td align='left'>
								</td>
								<td align='left'>
									<b>Total (Kg): ".($peso_total==0?"0":$calculo->formataParaMostrar($peso_total, false))."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<b>Média (R$): ".$calculo->formataParaMostrar($preco_total/($peso_total<=0?1:$peso_total))."</b>
								</td>
							</tr>
						</table>
					".$form."</div>";	
		
		
		return $this->setTemplate($bd, $rel);
	}
	
	
	
	
	
	
	private function getRelatorioDeRomaneioDeVendaGrafico(&$bd){
	
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Data.class.php';
		include_once AGRCL_BD_PATH_ABS."BdUtil.class.php";
		include_once AGRCL_PATH_ABS.'movimento/romaneio_venda/BeanRomaneioVenda.class.php';
		include_once AGRCL_PATH_ABS.'movimento/clientes/BeanCliente.class.php';
		include_once AGRCL_PATH_ABS.'movimento/romaneio_venda/BeanRomaneioVendaItem.class.php';
		include_once AGRCL_CALC_PATH_ABS.'Calculo.class.php';
		
		$data = new Data();
		$bd = new BdUtil();
		$comuns = new Comuns();
		$calculo = new Calculo();
		
		$subquery_filtros = "";
		
		$data_inicial= "";
		$data_final= "";
		$cliente= null;
		$detalhado= false;
		
		$periodo= "";
		
		if(array_key_exists("i", $_GET) && 
				strlen($_GET['i'])==10 && 
					array_key_exists("f", $_GET) && 
						strlen($_GET['f'])==10){
			
				$data_inicial= $data->converteEUAParaBR($_GET['i']);
			
				$data_final= $data->converteEUAParaBR($_GET['f']);
				
				$periodo = "(".$data_inicial." à ".$data_final.")";
		}
		else
			return $this->erro("Período inválido (ERRO REL003).");
		
		
		if(array_key_exists("c", $_GET) && $_GET['c']>0){
			
			$subquery_filtros .= (strlen($subquery_filtros)>0?" AND ":"")." (rmv.fk_cliente=".$_GET['c'].") ";
			
			$cliente = $bd->getPorId(new BeanCliente(), $_GET['c']);		
		}
		
		if(array_key_exists("le", $_GET) && $_GET['le']>0)
			$subquery_filtros .= (strlen($subquery_filtros)>0?" AND ":"")." (rmv.fk_local_embarque=".$_GET['le'].") ";
			
		if(array_key_exists("ds", $_GET) && $_GET['ds']>0)
			$subquery_filtros .= (strlen($subquery_filtros)>0?" AND ":"")." (###.fk_descricao=".$_GET['ds'].") ";
		
		if(array_key_exists("su", $_GET) && $_GET['su']>0)
			$subquery_filtros .= (strlen($subquery_filtros)>0?" AND ":"")." (###.fk_subdescricao=".$_GET['su'].") ";
		
		if(array_key_exists("pl", $_GET) && $_GET['pl']>0)
			$subquery_filtros .= (strlen($subquery_filtros)>0?" AND ":"")." (###.fk_planta=".$_GET['pl'].") ";
		
		if(array_key_exists("d", $_GET) && $_GET['d']>0)
			$detalhado = true;
		
		$rel_mes_atual_e_anterior = array();	
		$leg_mes_atual_e_anterior = array(substr($_GET['i'], 0, 4)-1, substr($_GET['i'], 0, 4));	
		
		$rel_fatura_diaria = array();
		
		$rel_media_mes = array();	
		
		$rel_peso_mes = array();	
		
		$rel_quant_pdd_mes = array();
		
		$mes_atual= "";
			
		$ano_atual= "";	
			
		$data_aux = $_GET['i'];
					
		$i = -1;	
		
		$retorno = -1;
		
		$total_mensal_ano_atual = "0.00";	
		$quant_pedidos_ano_atual = 0;
		
		$total_mensal_ano_anterior = "0.00";	
		$quant_pedidos_ano_anterior = 0;
		
		$peso_mensal_ano_atual = "0.00";
		$peso_mensal_ano_anterior = "0.00";
		
		$sigla_meses = array("Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez");
		
		$cont_mes = 0;
		
		while($retorno!==null && $retorno<=0){
			
			if($mes_atual != substr($data_aux, 5, 2)){
					
				$cont_mes++;
					
				if(strlen($mes_atual)>0){
						
					$rel_media_mes[] = array("mes"=>$sigla_meses[$mes_atual-1]."/".$ano_atual,
												"ano_anterior"=>$total_mensal_ano_anterior/($quant_pedidos_ano_anterior<=0?1:$quant_pedidos_ano_anterior),
													"ano_atual"=>$total_mensal_ano_atual/($quant_pedidos_ano_atual<=0?1:$quant_pedidos_ano_atual));
																	
					$rel_peso_mes[] = array("mes"=>$sigla_meses[$mes_atual-1]."/".$ano_atual, 
														"quant_ano_anterior"=>$peso_mensal_ano_anterior,
															"quant_ano_atual"=>$peso_mensal_ano_atual);										
					
					$rel_quant_pdd_mes[] = array("mes"=>$sigla_meses[$mes_atual-1]."/".$ano_atual, 
														"ano_anterior"=>$quant_pedidos_ano_anterior,
															"ano_atual"=>$quant_pedidos_ano_atual);
					
					$total_mensal_ano_atual = "0.00";	
					$quant_pedidos_ano_atual = 0;
		
					$total_mensal_ano_anterior = "0.00";	
					$quant_pedidos_ano_anterior = 0;
					
					$peso_mensal_ano_atual = "0.00";
					$peso_mensal_ano_anterior = "0.00";
				}
						
				$mes_atual = substr($data_aux, 5, 2);
				
				$ano_atual = substr($data_aux, 0, 4);
				
				$rotulo = substr($data_aux, 5, 2)."/".substr($data_aux, 0, 4);		
					
				$rel_fatura_diaria[] = array("mes"=>$rotulo, 
													"ano"=>substr($data_aux, 0, 4), 
														"descricao"=>$rotulo,
															"dias"=>array());
				$i++;				
			}
				
				
			$total_diario_ano_atual = "0.00";	
			$total_diario_ano_anterior = "0.00";	
			$peso_diario = "0.00";	

			$itens_vendas = $bd->getPorQuery(new BeanRomaneioVendaItem(), 
											"inner join romaneios_de_venda as rmv on rmv.id_romaneio_de_venda=###.fk_romaneio_venda", 
												$subquery_filtros.(strlen($subquery_filtros)>0?" AND ":"")." rmv.data='".$data_aux."'", 
													null);
			
	
			$id_pedido_atual = 0;
	
			if(count($itens_vendas)>0){
			
				foreach($itens_vendas as $item){
					
					$total_diario_ano_atual = $calculo->soma($total_diario_ano_atual, $item->valor);
					$peso_diario = $calculo->soma($peso_diario, $item->peso);
				
					if($id_pedido_atual!=$item->fk_romaneio_venda){
						
						$quant_pedidos_ano_atual++;
						$id_pedido_atual = $item->fk_romaneio_venda;
					}
				}
				
				$total_mensal_ano_atual = $calculo->soma($total_mensal_ano_atual, $total_diario_ano_atual);	
				$peso_mensal_ano_atual = $calculo->soma($peso_mensal_ano_atual, $peso_diario);	
			}
			
			$aux = intval(substr($data_aux, 0, 4));
	
			$peso_diario = "0.00";	


			$itens_vendas = $bd->getPorQuery(new BeanRomaneioVendaItem(), 
											"inner join romaneios_de_venda as rmv on rmv.id_romaneio_de_venda=###.fk_romaneio_venda", 
												$subquery_filtros.(strlen($subquery_filtros)>0?" AND ":"")." rmv.data='".($aux-1).substr($data_aux, 4)."'", 
													null);
			
			$id_pedido_atual = 0;
			
			if(count($itens_vendas)>0){
			
				foreach($itens_vendas as $item){
					
					$total_diario_ano_anterior = $calculo->soma($total_diario_ano_anterior, $item->valor);	
					$peso_diario = $calculo->soma($peso_diario, $item->peso);
				
					if($id_pedido_atual!=$item->fk_romaneio_venda){
						
						$quant_pedidos_ano_anterior++;
						$id_pedido_atual = $item->fk_romaneio_venda;
					}
				}
				
				$total_mensal_ano_anterior = $calculo->soma($total_mensal_ano_anterior, $total_diario_ano_anterior);	
				$peso_mensal_ano_anterior = $calculo->soma($peso_mensal_ano_anterior, $peso_diario);
			}

			$rel_fatura_diaria[$i]['dias'][] = array("dia"=>$data->converteEUAParaBR($data_aux), 
															"ano_anterior"=>$total_diario_ano_anterior,
																"ano_atual"=>$total_diario_ano_atual);

			$data_aux  = $data->somaDiasADataEUA($data_aux, 1);
				
			$retorno = $data->comparaDatasEUA($data_aux, $_GET['f']);
		}
			
		if($cont_mes > count($rel_media_mes)){
						
			$rel_media_mes[] = array("mes"=>$sigla_meses[$mes_atual-1]."/".$ano_atual,
										"ano_anterior"=>$total_mensal_ano_anterior/($quant_pedidos_ano_anterior<=0?1:$quant_pedidos_ano_anterior),
											"ano_atual"=>$total_mensal_ano_atual/($quant_pedidos_ano_atual<=0?1:$quant_pedidos_ano_atual));
																	
			$rel_peso_mes[] = array("mes"=>$sigla_meses[$mes_atual-1]."/".$ano_atual, 
											"ano_anterior"=>$peso_mensal_ano_anterior,
												"ano_atual"=>$peso_mensal_ano_atual);	
												
			$rel_quant_pdd_mes[] = array("mes"=>$sigla_meses[$mes_atual-1]."/".$ano_atual, 
											"ano_anterior"=>$quant_pedidos_ano_anterior,
												"ano_atual"=>$quant_pedidos_ano_atual);
		}
		

		$mes_atual= substr($_GET['i'], 5, 2);
		$ano_atual= substr($_GET['i'], 0, 4);		
		
		$ult_mes = 	substr($_GET['f'], 5, 2);			
		
		while($mes_atual<=$ult_mes){											
															
			$venda_ano_atual = $bd->getPorQuery(new BeanRomaneioVendaItem(), 
													"inner join romaneios_de_venda as rmv on rmv.id_romaneio_de_venda=###.fk_romaneio_venda", 
														$subquery_filtros.(strlen($subquery_filtros)>0?" AND ":"")." (rmv.data BETWEEN '".$ano_atual."-".$mes_atual."-01' AND '".$ano_atual."-".$mes_atual."-31')", 
															null);

			$venda_ano_anterior = $bd->getPorQuery(new BeanRomaneioVendaItem(), 
													"inner join romaneios_de_venda as rmv on rmv.id_romaneio_de_venda=###.fk_romaneio_venda", 
														$subquery_filtros.(strlen($subquery_filtros)>0?" AND ":"")." (rmv.data BETWEEN '".($ano_atual-1)."-".$mes_atual."-01' AND '".($ano_atual-1)."-".$mes_atual."-31')",  
															null);
			
			$total_atual = "0.00";
			$total_anter = "0.00";
			
			if(count($venda_ano_atual)>0){
			
				foreach($venda_ano_atual as $venda)
					$total_atual = $calculo->soma($total_atual, $venda->valor);
			}
			
			if(count($venda_ano_anterior)>0){
			
				foreach($venda_ano_anterior as $venda)
					$total_anter = $calculo->soma($total_anter, $venda->valor);
			}
			
			$rel_mes_atual_e_anterior[] = array("mes"=>$sigla_meses[$mes_atual-1]."/".$ano_atual,
														"anterior"=>$total_anter,
															"atual"=>$total_atual);												
			
			if($mes_atual>=12)
				break;
			else
				$mes_atual++;
			
		}

		$rel = "";
		
		if(array_key_exists("sd", $_GET) && $_GET['sd']==0){
		
			$rel .= "<div width='100%'>
						<div align='left'>";
						
			if(count($rel_fatura_diaria)>1)			
				$rel .= "	<table>
								<tr>
									<td align='center' width='50px'>
										<img src='".AGRCL_PATH_IMGS."anterior.png' class='navegacao' id='bt_anterior' onClick='javascript:navegacao(-1)' style='display:none'>
									</td>
									<td align='center'>
										<input type='hidden' id='indice_atual' value='0'>
										<div style='margin:5px 10px 5px 10px' id='titulo_atual'>".$rel_fatura_diaria[0]['descricao']."</div>
									</td>
									<td align='center' width='50px'>
										<img src='".AGRCL_PATH_IMGS."proximo.png' class='navegacao' id='bt_proximo' onClick='javascript:navegacao(1)' ".(count($rel_fatura_diaria)<=1?"style='display:none'":"").">
									</td>
								</tr>
							</table>";
							
			$rel .= "				
						</div>
						<div width='100%'>".
				$this->constroiGraficoDeDataPorDia($rel_fatura_diaria, 
														'VENDA DIÁRIA '.$periodo, 
															TP_GRAFC_LINHA, 
																true,
																	"DIAS DO MES",
																		"TOTAL FATURADO",
																		"R$: ",
																			"",
																				$leg_mes_atual_e_anterior).
				"</div>
				<div  class='area_relatorio_flutuante'>".
				$this->constroiGraficoDeDataPorMes($rel_mes_atual_e_anterior, 
														'TOTAL ACUMULADO '.$periodo, 
															TP_GRAFC_BARHO, 
																true,
																	"",
																		"",
																			"R$: ",
																				"",
																					$leg_mes_atual_e_anterior).
				"</div>
				<div  class='area_relatorio_flutuante'>".
				$this->constroiGraficoDeDataPorMes($rel_peso_mes, 
														'QUANTIDADE VENDIDA ACUMULADA '.$periodo, 
															TP_GRAFC_BARHO, 
																false,
																	"",
																		"",
																			"",
																				" Kg",
																					$leg_mes_atual_e_anterior).
				"</div>
				<div style='clear:both'></div>
				<div  class='area_relatorio_flutuante'>".
				$this->constroiGraficoDeDataPorMes($rel_media_mes, 
														'MÉDIA NO PERÍODO '.$periodo, 
															TP_GRAFC_BARHO, 
																true,
																	"",
																		"",
																			"R$: ",
																				"",
																					$leg_mes_atual_e_anterior).
				"</div>
				<div  class='area_relatorio_flutuante'>".
				$this->constroiGraficoDeDataPorMes($rel_quant_pdd_mes, 
														'NÚMERO DE PEDIDOS NO PERÍODO '.$periodo, 
															TP_GRAFC_BARHO, 
																false,
																	"",
																		"",
																			"",
																				"",
																					$leg_mes_atual_e_anterior).
				"</div>
				<div style='clear:both'></div>";
							
			$rel .= "	
				<div style='clear:both'></div>";														
		}

		
		return $rel;																
																		
	}
	
	
	
	
	
	private function getRelatorioDeRomaneioDeEmbarque(&$bd){
	
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Data.class.php';
		include_once AGRCL_BD_PATH_ABS."BdUtil.class.php";
		include_once AGRCL_PATH_ABS.'movimento/romaneio_embarque/BeanRomaneioEmbarque.class.php';
		include_once AGRCL_PATH_ABS.'movimento/romaneio_embarque/BeanRomaneioEmbarqueItem.class.php';
		include_once AGRCL_CALC_PATH_ABS.'Calculo.class.php';
		
		$data = new Data();
		$bd = new BdUtil();
		$comuns = new Comuns();
		$calculo = new Calculo();
		
		$subquery = "";
		
		$subquery3 = "";
		
		$data_inicial= "";
		$data_final= "";
		$tem_duas_datas= false;
		$detalhado= false;
		
		$parcial = false;
		
		if(array_key_exists("i", $_GET) && strlen($_GET['i'])==10){
			
			$data_inicial= $data->converteEUAParaBR($_GET['i']);
			
			if(array_key_exists("f", $_GET) && strlen($_GET['f'])==10){
			
				$data_final= $data->converteEUAParaBR($_GET['f']);
			
				$subquery = " (###.data between '".$_GET['i']."' and '".$_GET['f']."') ";
				$tem_duas_datas = true;
			}
			else
				$subquery = " (###.data>='".$_GET['i']."') ";
		}
		else{
			
			if(array_key_exists("f", $_GET) && strlen($_GET['f'])==10){
				
				$data_final= $data->converteEUAParaBR($_GET['f']);
				
				$subquery = " (###.data<='".$_GET['f']."') ";
			}
		}
		
		
		if(array_key_exists("fz", $_GET) && $_GET['fz']>0)
			$subquery .= (strlen($subquery)>0?" AND ":"")." (###.fk_fazenda=".$_GET['fz'].") ";
		
		if(array_key_exists("mt", $_GET) && $_GET['mt']>0)
			$subquery .= (strlen($subquery)>0?" AND ":"")." (###.fk_motorista=".$_GET['mt'].") ";
		
		
		if((array_key_exists("tp", $_GET) && $_GET['tp']>0) || 
				(array_key_exists("q", $_GET) && $_GET['q']>0) || 
					(array_key_exists("tc", $_GET) && $_GET['tc']>0) || 
						(array_key_exists("pr", $_GET) && $_GET['pr']>0)){
			
			$parcial = true;
			
			$subquery .= (strlen($subquery)>0?" AND ":"")." (###.id_romaneio_de_embarque IN (select x.fk_romaneio_embarque from itens_romaneio_embarque as x where ";
			
			$subquery2 = "";
			
			if((array_key_exists("tp", $_GET) && $_GET['tp']>0)){
				
				$subquery2 .= (strlen($subquery2)>0?" AND ":"")." x.tipo=".$_GET['tp']." ";
				$subquery3 .= "###.tipo=".$_GET['tp'];
			}
			
			if((array_key_exists("q", $_GET) && $_GET['q']>0)){
				
				$subquery2 .= (strlen($subquery2)>0?" AND ":"")." x.fk_quadra=".$_GET['q']." ";
				$subquery3 .= (strlen($subquery3)>0?" AND ":"")."###.fk_quadra=".$_GET['q'];
			}
			
			if((array_key_exists("tc", $_GET) && $_GET['tc']>0)){
				
				$subquery2 .= (strlen($subquery2)>0?" AND ":"")." x.tipo_colheita=".$_GET['tc']." ";
				$subquery3 .= (strlen($subquery3)>0?" AND ":"")."###.tipo_colheita=".$_GET['tc'];
			}
			
			if((array_key_exists("pr", $_GET) && $_GET['pr']>0)){
				$subquery2 .= (strlen($subquery2)>0?" AND ":"")." x.fk_prestador=".$_GET['pr']." ";
				$subquery3 .= (strlen($subquery3)>0?" AND ":"")."###.fk_prestador=".$_GET['pr'];
			}
			
			$subquery .= $subquery2.")) ";
		}
		
		if(array_key_exists("d", $_GET) && $_GET['d']>0)
			$detalhado = true;
		
		$rel = "";
							
		$form ="";			
		
		
		
		$romaneios = $bd->getPorQuery(new BeanRomaneioEmbarque(), null, $subquery, "###.data DESC");

		$peso_liquido_total = 0;
		$quant_bags_total = 0;
		$preco_total = 0;
		
		if(count($romaneios)>0){
			
			$form .="
					<table width='100%'  style='font-size:".$this->fonte_menor."'>";
			
			$dia = $romaneios[0]->data;
			
			$peso_liquido_dia = 0;
			$preco_dia = 0;
			$quant_bags_dia = 0;
			
			foreach($romaneios as $i=>$romaneio){
				
				if(strcmp($dia, $romaneio->data)!=0){
					
					$form .= "
						<tr>
							<td align='center' colspan='7'><hr width='100%'></td>
						</tr>
						<tr>
							<td align='right' colspan='4'><b>Total para ".$data->converteEUAParaBR($dia).":</b>&nbsp;&nbsp;</td>
							<td align='right'><b>".$calculo->formataParaMostrar($peso_liquido_dia, false)."</b></td>
							<td align='right'><b>".$calculo->formataParaMostrar($quant_bags_dia, false)."</b></td>
							<td align='right'><b>".$calculo->formataParaMostrar($preco_dia)."</b></td>
						</tr>
						<tr>
							<td align='center' colspan='7'><hr width='100%'></td>
						</tr>";	
						
					$dia = $romaneio->data;	
					$peso_liquido_dia = 0;
					$preco_dia = 0;
					$quant_bags_dia = 0;
				}
				
				
				$form2  ="";
				
				$itens = $bd->getPorQuery(new BeanRomaneioEmbarqueItem(), null, "###.fk_romaneio_embarque=".$romaneio->id." AND ".$subquery3, "###.id_item_romaneio_embarque ASC");

				if($detalhado)
					$form2 .= "
						<tr>
							<td align='center' colspan='5'>
								<table width='100%' style='font-size:".$this->fonte_menor."'>
									<tr>
										<td align='center' style='width:10%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>Colheita</td>
										<td align='center' style='width:10%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>Tipo</td>
										<td align='center' style='width:20%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>Prestador</td>
										<td align='center' style='width:20%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>Quadra</td>
										<td align='center' style='widtd:10%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>Variedade</td>
										<td align='center' style='widtd:10%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>C. Serv. (R$)</td>
										<td align='center' style='widtd:10%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>QTDe BAGs</td>
										<td align='center' style='widtd:10%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>Total (R$)</td>
									</tr>";
				
				$peso_romaneio = "0.00";
				$quant_bags_romaneio = "0";
				$preco_total_romaneio = "0.00";

				
				if(count($itens)>0){		
							
					foreach($itens as $item){
							
						if($detalhado){
							
							$tipo = "SAFRA";
						
							if($item->tipo_colheita == TIPO_COLHEITA_TEMPORA_T1)
								$tipo = "Temporã Tipo I";
							else if($item->tipo_colheita == TIPO_COLHEITA_TEMPORA_T2)
								$tipo = "Temporã Tipo II";
							
							$form2 .= "
									<tr>
										<td align='left'>".($item->tipo==1?"PROPRIA":"PRESTADOR")."</td>
										<td align='left'>".$tipo."</td>
										<td align='left'>".$item->nome_prestador."</td>
										<td align='left'>".$item->nome_quadra."</td>
										<td align='left'>".$item->nome_variedade."</td>
										<td align='right'>".$calculo->formataParaMostrar($item->custo_servico)."</td>
										<td align='right'>".$calculo->formataParaMostrar($item->qtde_bag_caixa, false)."</td>
										<td align='right'>".$calculo->formataParaMostrar($item->total_servico)."</td>
									</tr>";	
						}
						
						$peso_liquido_total = $calculo->soma($peso_liquido_total, $item->peso_liquido);
						$quant_bags_total = $calculo->soma($quant_bags_total, $item->qtde_bag_caixa);
						$preco_total = $calculo->soma($preco_total, $item->total_servico);	
							
						$peso_liquido_dia = $calculo->soma($peso_liquido_dia, $item->peso_liquido);
						$quant_bags_dia = $calculo->soma($quant_bags_dia, $item->qtde_bag_caixa);
						$preco_dia = $calculo->soma($preco_dia, $item->total_servico);
						
						$peso_romaneio = $calculo->soma($peso_romaneio, $item->peso_liquido);
						$quant_bags_romaneio = $calculo->soma($quant_bags_romaneio, $item->qtde_bag_caixa);
						$preco_total_romaneio = $calculo->soma($preco_total_romaneio, $item->total_servico);
					}
				}
				

				if($detalhado)
					$form2 .= "	</table>
							</td>
						</tr>";
				
				
				
				$form .= "
						<tr>
							<td align='center' style='widtd:10%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>Data</td>
							<td align='center' style='widtd:10%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>N&#176</td>
							<td align='center' style='widtd:50%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>Fazenda</td>
							<td align='center' style='widtd:15%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>P. Médio (Kg)</td>
							<td align='center' style='widtd:15%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>P. Líquido (Kg)</td>
							<td align='center' style='widtd:15%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>QTDe BAGs</td>
							<td align='center' style='widtd:15%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>Total (R$)</td>
						</tr>
						<tr>
							<td align='center' ".($detalhado?"rowspan='2'":"").">".$romaneio->formataData()."</td>
							<td align='center' ".($detalhado?"rowspan='2'":"").">".$romaneio->formataId()."</td>
							<td align='left'>".$romaneio->nome_fazenda."</td>
							<td align='right'>".$calculo->formataParaMostrar($calculo->dividi($romaneio->peso_medio, count($itens)>0?count($itens):1))."</td>
							<td align='right'>".$calculo->formataParaMostrar($peso_romaneio, false)."</td>
							<td align='right'>".$calculo->formataParaMostrar($quant_bags_romaneio, false)."</td>
							<td align='right'>".$calculo->formataParaMostrar($preco_total_romaneio)."</td>
						</tr>".$form2;
				
			}
			
			$form .= "	
						<tr>
							<td align='center' colspan='7'><hr width='100%'></td>
						</tr>
						<tr>
							<td align='right' colspan='4'><b>Total para ".$data->converteEUAParaBR($dia).":</b>&nbsp;&nbsp;</td>
							<td align='right'><b>".$calculo->formataParaMostrar($peso_liquido_dia, false)."</b></td>
							<td align='right'><b>".$calculo->formataParaMostrar($quant_bags_dia, false)."</b></td>
							<td align='right'><b>".$calculo->formataParaMostrar($preco_dia)."</b></td>
						</tr>";	
			$form .= "
					</table>";
		}
		else
			$form = "
					<div align='center'>
					<br><br><br><br>
					Sem dados para o período informado
					<br><br><br><br>
					</div>".$form;
		

		if($parcial)
			$rel .= "<div align='center' style='font-size:".$this->fonte_menor.";color:red'>Os dados foram filtrados, portanto, os valores apresentados são parciais.</div>";
		
		$rel .= "	
					<div align='center' style='border:solid 1px #333;padding:10px;margin:10px;font-size:".$this->fonte_normal."'>
						<table width='100%' style='font-size:".$this->fonte_normal."'>
							<tr>
								<td align='left' width='40%'>
									<b>Período: </b>".$data_inicial.($tem_duas_datas?" à ":"").$data_final."
								</td>
								<td align='left' width='60%'>
									<b>Total (R$): ".$calculo->formataParaMostrar($preco_total)."</b>
								</td>
							</tr>
							<tr>
								<td align='left'>
								</td>
								<td align='left'>
									<b>Total Líquido (Kg): ".($peso_liquido_total==0?"0":$calculo->formataParaMostrar($peso_liquido_total, false))."</b>&nbsp;&nbsp;&nbsp;&nbsp;
									<b>Total BAGs: ".($quant_bags_total==0?"0":$calculo->formataParaMostrar($quant_bags_total, false))."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</td>
							</tr>
						</table>
					".$form."</div>";	
		
		
		return $this->setTemplate($bd, $rel);		
	}
	
	


	
	private function getRelatorioDeAbastecimentoGrafico(&$bd){
	
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Data.class.php';
		include_once AGRCL_PATH_ABS.'frota/abastecimento/BeanAbastecimento.class.php';
		include_once AGRCL_PATH_ABS.'patrimonio/equipamentos/BeanEquipamento.class.php';
		include_once AGRCL_CALC_PATH_ABS.'Calculo.class.php';
		include_once AGRCL_PATH_ABS.'configuracoes/BeanEntidade.class.php';
		
		$data = new Data();
		$comuns = new Comuns();
		$calculo = new Calculo();
		
		$subquery_filtros = "###.status>0 and ###.fk_veiculo>0";
		$subquery_modelo = "";
		$modelo = "";
	
		$data_inicial= "";
		$data_final= "";
		
		
		$periodo= "";
		
		if(array_key_exists("i", $_GET) && 
				strlen($_GET['i'])==10 && 
					array_key_exists("f", $_GET) && 
						strlen($_GET['f'])==10){
			
				$data_inicial= $data->converteEUAParaBR($_GET['i']);
			
				$data_final= $data->converteEUAParaBR($_GET['f']);
				
				$periodo = "(".$data_inicial." à ".$data_final.")";
		}
		else
			return $this->erro("Período inválido (ERRO REL003).");
		
	
		if(array_key_exists("fz", $_GET) && $_GET['fz']>0)
			$subquery_filtros .= (strlen($subquery_filtros)>0?" AND ":"").
			"  (###.fk_tanque IN (select x.id_tanque from tanques as x where x.fk_fazenda=".$_GET['fz']."))  ";
			
		if(array_key_exists("tq", $_GET) && $_GET['tq']>0)
			$subquery_filtros .= (strlen($subquery_filtros)>0?" AND ":"")." (###.fk_tanque=".$_GET['tq'].") ";
		
		if(array_key_exists("vc", $_GET) && $_GET['vc']>0)
			$subquery_filtros .= (strlen($subquery_filtros)>0?" AND ":"")." (###.fk_veiculo=".$_GET['vc'].") ";
		
		if(array_key_exists("md", $_GET) && $_GET['md']>0){
			
			$subquery_filtros .= (strlen($subquery_filtros)>0?" AND ":"").
			"  (###.fk_veiculo IN (select x.id_equipamento from equipamentos as x where x.fk_modelo=".$_GET['md']." and x.status>0))  ";
		
			$subquery_modelo .= " ###.status>0 and ###.controlar_combustivel>0 and ###.fk_modelo=".$_GET['md'];
		
			$modelo = $bd->getPorId(new BeanEntidade(), $_GET['md']);
			
		}
		
		$rel_litros_por_medida = array();	
		
		$abastecimentos = $bd->getPorQuery(new BeanAbastecimento(), 
											null, 
												$subquery_filtros, 
													"###.fk_veiculo DESC");
			
		$id_veiculo_atual = 0;
		
		$nome_veiculo_atual = "";
		
		$consumo = "0,00";
		
		$num_abastecimento = 0;
		
		if(count($abastecimentos)>0){
			
			$id_veiculo_atual = $abastecimentos[0]->fk_veiculo;
			
			$nome_veiculo_atual = $abastecimentos[0]->codigo_veiculo." (L/".($abastecimentos[0]->usa_horimetro>0?"H":"Km").")";
			
			foreach($abastecimentos as $abastecimento){
				
				if($id_veiculo_atual!= $abastecimento->fk_veiculo){
					
					$rel_litros_por_medida[] = array("veiculo"=>$nome_veiculo_atual, 
															"consumo"=>$calculo->dividi($consumo, $num_abastecimento));
			
					$nome_veiculo_atual = $abastecimento->codigo_veiculo." (L/".($abastecimento->usa_horimetro>0?"H":"Km").")";
					
					$id_veiculo_atual = $abastecimento->fk_veiculo;
					
					$consumo = "0,00";
					
					$num_abastecimento = 0;
				}

				$medida= 0;
				
				if($abastecimento->calculo_direto>0)
					$medida = $abastecimento->hora_km_trabalho;
				else{
				
					if($abastecimento->usa_horimetro>0)
						$medida = $abastecimento->horimetro_final - $abastecimento->horimetro;
					else
						$medida = $abastecimento->odometro_final - $abastecimento->odometro;	
				}
				
				if($medida<=0)
					$medida = 1;
				
				$consumo = $calculo->soma($consumo, $calculo->dividi($medida, $abastecimento->litros));	
				
				$num_abastecimento++;
			}
			
			$rel_litros_por_medida[] = array("veiculo"=>$nome_veiculo_atual, 
															"consumo"=>$calculo->dividi($consumo, $num_abastecimento));
		}		
		

		$por_modelo_aux = array();
		$legenda_por_modelo = array();
			
		if(strlen($subquery_modelo)>0){
			
			
			$veiculos = $bd->getPorQuery(new BeanEquipamento(), 
												null, 
													$subquery_modelo, 
														"###.fk_modelo DESC");
		
			if(count($veiculos)>0){
				
				$i =0;
				foreach($veiculos as $veiculo){
					
					$abastecimentos_veiculo = $bd->getPorQuery(new BeanAbastecimento(), 
												null, 
													"###.fk_veiculo=".$veiculo->id." and ###.status>0", 
														"###.data DESC");
					
					if(count($abastecimentos_veiculo)>0){
				
						$por_modelo_aux[]= array();
						$legenda_por_modelo[] = $veiculo->codigo." (L/".($veiculo->atp>0?"H":"Km").")";
					
					
						foreach($abastecimentos_veiculo as $abastecimento){
					
							$medida = 0;
					
							if($abastecimento->calculo_direto>0)
								$medida = $abastecimento->hora_km_trabalho;
							else{
								
								if($abastecimento->usa_horimetro>0)
									$medida = $abastecimento->horimetro_final - $abastecimento->horimetro;
								else
									$medida = $abastecimento->odometro_final - $abastecimento->odometro;	
							}
							
							if($medida<=0)
								$medida = 1;
					
							$consumo = $calculo->dividi($medida, $abastecimento->litros);	
					
							$por_modelo_aux[$i][]= $consumo;
						}
					
						$i++;
					}
				}
			}
		}

		$por_modelo = array();
		
		if(count($por_modelo_aux)>0){
	
			$ainda_tem = true; 
			
			for($i=0; $ainda_tem; $i++){
			
				$por_modelo[] = array($i+1);
			
				$ainda_tem = false;
			
				foreach($por_modelo_aux as $item){
				
					if(array_key_exists($i, $item)){
				
						$por_modelo[$i][] = $item[$i];
						$ainda_tem = true;
					}
				}
			}
		}
		

		$rel = "<div width='100%'>
					<div class='item_relatorio'>
						<div align='center' style='margin:20px 0px 0px 0px'>
						<b>CONSUMO POR MODELO NO PERÍODO ".$periodo."</b>
					</div>
					<div class='area_relatorio'>";
			
		if(count($por_modelo)>0){

			$rel .= $this->setGrafico($por_modelo, 
											'MODELO '.(is_object($modelo)?$modelo->nome:" INDIFINIDO"), 
												TP_GRAFC_LINHA, 
													true,
														"NUMERO DO ABASTECIMENTO",
															"H | Km / L",
																"",
																	"",
																	null);

			$rel .= $this->getLegendas($legenda_por_modelo);													
		}
		else{
			
			$rel .= "
					<div align='center'>
						<br><br><br><br><br><br><br><br><br><br>
						Selecione um modelo para gerar o gráfico de modelos
						<br><br><br><br><br><br><br><br><br><br>
						<br><br><br><br><br><br><br><br>
					</div>";
		}
		
		$rel .= "	<div  class='area_relatorio_flutuante'>".
					
					$this->constroiGraficoDeDataPorMes($rel_litros_por_medida, 
														'MÉDIA DE CONSUMO NO PERÍODO '.$periodo, 
															TP_GRAFC_BARHO, 
																true,
																	"VEICULO",
																		"",
																		"",
																			"",
																				NULL).
				"	</div>";
					
			
		$rel .= "	</div>
				</div>
				<div style='clear:both'></div>";
																				
		return $rel;																																
	}
	
	
	
	
	
	private function getRelatorioDeInspecoes(&$bd){
		
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Data.class.php';
		include_once AGRCL_PATH_ABS.'geral/fazendas/BeanFazenda.class.php';
		include_once AGRCL_PATH_ABS.'campo/inspecao/BeanInspecao.class.php';
		include_once AGRCL_PATH_ABS.'campo/inspecao/BeanOcorrencia.class.php';
		include_once AGRCL_PATH_ABS.'campo/pragas/BeanPraga.class.php';
		include_once AGRCL_CALC_PATH_ABS.'Calculo.class.php';
		include_once AGRCL_PATH_ABS.'campo/quadras/BeanQuadra.class.php';	
		
		$data = new Data();
		$comuns = new Comuns();
		$calculo = new Calculo();
		
		$subquery = "";
		
		$data_inicial= "";
		$data_final= "";
		$tem_duas_datas= false;
		
		$nome_fazenda= "TODAS";
		$nome_quadra= "TODAS";
		
		if(array_key_exists("i", $_GET) && strlen($_GET['i'])==10){
			
			$data_inicial= $data->converteEUAParaBR($_GET['i']);
			
			if(array_key_exists("f", $_GET) && strlen($_GET['f'])==10){
			
				$data_final= $data->converteEUAParaBR($_GET['f']);
			
				$subquery = " (###.data between '".$_GET['i']."' and '".$_GET['f']."') ";
				$tem_duas_datas = true;
			}
			else
				$subquery = " (###.data>='".$_GET['i']."') ";
		}
		else{
			
			if(array_key_exists("f", $_GET) && strlen($_GET['f'])==10){
				
				$data_final= $data->converteEUAParaBR($_GET['f']);
				
				$subquery = " (###.data<='".$_GET['f']."') ";
			}
		}
		
		if(array_key_exists("fz", $_GET) && $_GET['fz']>0){
			
			$subquery .= (strlen($subquery)>0?" AND ":"")." (###.fk_fazenda=".$_GET['fz'].") ";
		
			$fazenda = $bd->getPorId(new BeanFazenda(), $_GET['fz']);

			if(is_object($fazenda))
				$nome_fazenda= $fazenda->nome;
		}
		
		
		if(array_key_exists("fq", $_GET) && $_GET['fq']>0){
			
			$subquery .= (strlen($subquery)>0?" AND ":"")." (###.fk_quadra=".$_GET['fq'].") ";
		
			$quadra = $bd->getPorId(new BeanQuadra(), $_GET['fq']);

			if(is_object($quadra))
				$nome_quadra= $quadra->nome;
		}
		
		
		
		
		$rel = "";
							
		$form ="";			
		
		$inspecoes = $bd->getPorQuery(new BeanInspecao(), null, $subquery, "###.fk_quadra ASC, ###.data DESC");

		if(count($inspecoes)>0){
			
			$form .=	"
					<table width='100%'  style='font-size:".$this->fonte_menor."'>
						";
		
			
			foreach($inspecoes as $inspecao){
				
				$form .= "
						<tr>
							<td align='center' style='width:15%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>CÓDIGO</td>
							<td align='center' style='width:25%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>FAZENDA</td>
							<td align='center' style='width:30%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>QUADRA</td>
							<td align='center' style='width:15%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>SITUAÇÃO</td>
							<td align='center' style='width:15%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>OCORRÊNCIAS</td>
						</tr>
						<tr>
							<td align='center'>".$inspecao->codigo."</td>
							<td align='left'>".$inspecao->nome_fazenda."</td>
							<td align='left'>".$inspecao->nome_quadra."</td>
							<td align='center'>".$inspecao->formataSituacao()."</td>
							<td align='center'>".$inspecao->formataNumeroDeOcorrencias()."</td>
						</tr>";
						
						
						
				$ocorrencias = $bd->getPorQuery(new BeanOcorrencia(), null, "###.fk_inspecao=".$inspecao->id." and ###.fk_praga>0", "###.data DESC, ###.hora DESC,###.min DESC");

				if(count($ocorrencias)>0){
			
					$form .=	"
						<tr>
							<td align='center'></td>
							<td align='center' colspan=4>
								<table width='100%'  style='font-size:".$this->fonte_menor."'>
									<tr>
										<td align='center' style='width:18%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>DATA</td>
										<td align='center' style='width:41%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>PRAGA</td>
										<td align='center' style='width:13%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>LOCAL</td>
										<td align='center' style='width:13%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>PORC.</td>
										<td align='center' style='width:15%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>DANO ECO.</td>
									</tr>";
		
			
					foreach($ocorrencias as $ocorrencao){
						
						$praga = $bd->getPorId(new BeanPraga(), $ocorrencao->fk_praga);

						$dano_eco = "";
		
						if(is_object($praga)){
							
							$porcentagens = json_decode($praga->params);
						
							if(is_object($porcentagens) && strcmp($porcentagens->nivel_4_i, "-")!=0)
								$dano_eco="<table width='100%'>
												<tr>
													<td width='50%' align='right'>".$porcentagens->nivel_4_i."%</td>
													<td width='50%' align='right'><img src='".AGRCL_PATH_IMGS."ocorrencia_alto.png' width='15px' height='15px'></td>
												</tr>
											</table>";
		
						}

						$form .= "
									<tr>
										<td align='center'>".$data->converteEUAParaBRComHorario($ocorrencao->data, $ocorrencao->hora,$ocorrencao->min)."</td>
										<td align='left'>".(is_object($praga)?$praga->nome:"")."</td>
										<td align='left'>".$ocorrencao->nome_local."</td>
										<td align='center'>".$ocorrencao->porcentagem."%</td>
										<td align='right'>".$dano_eco."</td>
									</tr>";
									
					}
					
					$form .= "	</table>
							</td>
						</tr>";
				}
			}
			
			$form .= "
					</table>";
		}
		else
			$form = "
					<div align='center'>
					<br><br><br><br>
					Sem dados para o período informado
					<br><br><br><br>
					</div>";
					
		$rel .= "	
					<div align='center' style='border:solid 1px #333;padding:10px;margin:10px;font-size:".$this->fonte_normal."'>
						<table width='100%' style='font-size:".$this->fonte_normal."'>
							<tr>
								<td align='left' colspan=2>
									<b>Período: </b>".$data_inicial.($tem_duas_datas?" à ":"").$data_final."
								</td>
							</tr>
							<tr>
								<td align='left' width='40%'>
									<b>Fazenda(s):  </b>".$nome_fazenda."
								</td>
								<td align='left' width='60%'>
									<b>Quadra(s):  </b>".$nome_quadra."
								</td>
							</tr>
						</table>
						".$form."
					</div>";	
		
		
		return $this->setTemplate($bd, $rel);	
	}
	
	
	
	
	
	
	private function getRelatorioDeAplicacoes(&$bd){
		
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Data.class.php';
		include_once AGRCL_PATH_ABS.'geral/fazendas/BeanFazenda.class.php';
		include_once AGRCL_PATH_ABS.'campo/aplicacao/BeanAplicacao.class.php';
		include_once AGRCL_PATH_ABS.'campo/pragas/BeanPraga.class.php';
		include_once AGRCL_CALC_PATH_ABS.'Calculo.class.php';
		include_once AGRCL_PATH_ABS.'campo/quadras/BeanQuadra.class.php';	
		include_once AGRCL_PATH_ABS.'campo/produtos/BeanProduto.class.php';
		include_once AGRCL_PATH_ABS.'patrimonio/equipamentos/BeanEquipamento.class.php';
		include_once AGRCL_PATH_ABS.'campo/aplicacao/BeanProdutoAplicacao.class.php';
		include_once AGRCL_PATH_ABS.'campo/aplicacao/BeanEquipamentoAplicacao.class.php';
		include_once AGRCL_PATH_ABS.'configuracoes/BeanEntidade.class.php';	
		
		$data = new Data();
		$comuns = new Comuns();
		$calculo = new Calculo();
		
		$subquery = "";
		
		$data_inicial= "";
		$data_final= "";
		$tem_duas_datas= false;
		
		$nome_fazenda= "TODAS";
		$nome_quadra= "TODAS";
		$nome_operador= "TODOS";
		$nome_produto= "TODOS";
		$nome_equipamento= "TODOS";
		$nome_tipo= "TODOS";
		
		if(array_key_exists("i", $_GET) && strlen($_GET['i'])==10){
			
			$data_inicial= $data->converteEUAParaBR($_GET['i']);
			
			if(array_key_exists("f", $_GET) && strlen($_GET['f'])==10){
			
				$data_final= $data->converteEUAParaBR($_GET['f']);
			
				$subquery = " (###.data between '".$_GET['i']."' and '".$_GET['f']."') ";
				$tem_duas_datas = true;
			}
			else
				$subquery = " (###.data>='".$_GET['i']."') ";
		}
		else{
			
			if(array_key_exists("f", $_GET) && strlen($_GET['f'])==10){
				
				$data_final= $data->converteEUAParaBR($_GET['f']);
				
				$subquery = " (###.data<='".$_GET['f']."') ";
			}
		}
		
		if(array_key_exists("fz", $_GET) && $_GET['fz']>0){
			
			$subquery .= (strlen($subquery)>0?" AND ":"")." (###.fk_fazenda=".$_GET['fz'].") ";
		
			$fazenda = $bd->getPorId(new BeanFazenda(), $_GET['fz']);

			if(is_object($fazenda))
				$nome_fazenda= $fazenda->nome;
		}
		
		
		if(array_key_exists("fq", $_GET) && $_GET['fq']>0){
			
			$subquery .= (strlen($subquery)>0?" AND ":"")." (###.fk_quadra=".$_GET['fq'].") ";
		
			$quadra = $bd->getPorId(new BeanQuadra(), $_GET['fq']);

			if(is_object($quadra))
				$nome_quadra= $quadra->nome;
		}
		
		
		if(array_key_exists("fo", $_GET) && $_GET['fo']>0){
			
			$subquery .= (strlen($subquery)>0?" AND ":"")." (###.fk_operador=".$_GET['fz'].") ";
		
			$operador = $bd->getPorId(new BeanUsuario(), $_GET['fo']);

			if(is_object($operador))
				$nome_operador= $operador->nome_completo ;
		}
		
		if(array_key_exists("fe", $_GET) && $_GET['fe']>0){
			
			$subquery .= (strlen($subquery)>0?" AND ":""). 
						" (###.id_aplicacao IN (select x.fk_aplicacao from equipamentos_aplicacoes as x where x.fk_equipamento=".
							$_GET['fe'].")) ";
							
			$equipamento = $bd->getPorId(new BeanEquipamento(), $_GET['fe']);

			if(is_object($equipamento))
				$nome_equipamento= $equipamento->nome;				
		}
		
		
		if((array_key_exists("fp", $_GET) && $_GET['fp']>0) || 
				(array_key_exists("ft", $_GET) && $_GET['ft']>0)){
			
			$subquery .= (strlen($subquery)>0?" AND ":""). 
						" (###.id_aplicacao IN (select x.fk_aplicacao from produtos_aplicacoes as x where "; 
			
			if(array_key_exists("ft", $_GET) && $_GET['ft']>0){
				
				$subquery .="x.fk_tipo=".$_GET['ft']." and ";
				
				$tipo = $bd->getPorId(new BeanEntidade(), $_GET['ft']);

				if(is_object($tipo))
					$nome_tipo= $tipo->nome;
			}
		
			if(array_key_exists("fp", $_GET) && $_GET['fp']>0){
				
				$subquery .="x.fk_produto=".$_GET['fp'];
				
				$produto = $bd->getPorId(new BeanProduto(), $_GET['fp']);

				if(is_object($produto))
					$nome_produto= $produto->nome;
			}
			else
				$subquery .="1";
			
			$subquery .="))";
		}
	
		//echo $subquery."<br><br>";
		
		$rel = "";
							
		$form ="";			
		
		$aplicacoes = $bd->getPorQuery(new BeanAplicacao(), null, $subquery, "###.fk_quadra ASC, ###.data DESC");

		if(count($aplicacoes)>0){
			
			$form .=	"
					<table width='100%'  cellspacing=0 cellpadding=0  style='font-size:".$this->fonte_menor."'>
						";
		
			
			foreach($aplicacoes as $aplicacao){
				
				$form .= "
						<tr>
							<td align='center' style='width:15%;".STYLE_TABCAB."'><b>CÓDIGO</b></td>
							<td align='center' style='width:15%;".STYLE_TABCAB."'><b>RECOMEN.</b></td>
							<td align='center' style='width:15%;".STYLE_TABCAB."'><b>FAZENDA</b></td>
							<td align='center' style='width:25%;".STYLE_TABCAB."'><b>QUADRA</b></td>
							<td align='center' style='width:15%;".STYLE_TABCAB."'><b>VOL.CAU.</b></td>
							<td align='center' style='width:15%;".STYLE_TABCAB."'><b>CAREN.TTL.</b></td>
						</tr>
						<tr>
							<td align='center'>".$aplicacao->codigo."</td>
							<td align='center'>".$aplicacao->recomendacao_codigo."</td>
							<td align='left'>".$aplicacao->nome_fazenda."</td>
							<td align='left'>".$aplicacao->nome_quadra."</td>
							<td align='center'>".$aplicacao->volume_calda." L</td>
							<td align='center'>".$aplicacao->formataLiberacao()."</td>
						</tr>
						<tr>
							<td></td>
							<td align='center' colspan='5'>
								<table width='100%'   cellspacing=0 cellpadding=0  style='font-size:".$this->fonte_menor."'>
									<tr>
										<td align='center' style='width:10%;".STYLE_TABCAB."'><b>PRODUTOS</b></td>
									</tr>
									<tr>
										<td align='center'>
											<table width='100%' cellspacing=0 cellpadding=0 style='font-size:".$this->fonte_menor."'>
												<tr>
													<td width='20%'   style='".STYLE_TABCAB."'><b>TIPO</b></td>
													<td width='20%'   style='".STYLE_TABCAB."'><b>PRODUTO</b></td>
													<td width='15%' style='".STYLE_TABCAB."'><b>DOSE</b></td>
													<td width='15%' style='".STYLE_TABCAB."'><b>P/CADA</b></td>
													<td width='15%' style='".STYLE_TABCAB."'><b>TTL PROD.</b></td>
													<td width='15%' style='".STYLE_TABCAB."'><b>CARÊNCIA</b></td>
												</tr>";
						
					
				$produtos = $bd->getPorQuery(new BeanProdutoAplicacao, 
											null, 
												"###.fk_aplicacao=".$aplicacao->id, 
													"###.id_prod_aplicacao ASC");
				if(count($produtos)>0){
			
					foreach($produtos as $produto){

						$medida = (strlen($produto->medida_produto)>0?" ".$produto->medida_produto:"");
						
						$form .=				"<tr>
													<td align='left'>".$produto->nome_tipo."</td>".
													"<td align='left'>".$produto->nome_produto."</td>".
													"<td align='center'>".$calculo->formataParaMostrarDecimal($produto->quant_produto).$medida."</td>".
													"<td align='center'>".$calculo->formataParaMostrarDecimal($produto->quant_diluente_base)." L</td>".
													"<td align='center'>".$calculo->formataParaMostrarDecimal($produto->quant_prod_total).$medida."</td>".
													"<td align='center'>".$produto->carencia." Dia(s)</td>".
												"</tr>";
					}
				}
				
				
				$form .= "					</table>
										</td>
									</tr>
									<tr>
										<td align='center' style='".STYLE_TABCAB."'><b>EQUIPAMENTOS</b></td>
									</tr>
									<tr>
										<td align='center'>
											<table width='100%' cellspacing=0 cellpadding=0 style='font-size:".$this->fonte_menor."'>
												<tr>
													<td width='10%' style='".STYLE_TABCAB."'><b>CODIGO</b></td>
													<td width='30%' style='".STYLE_TABCAB."'><b>OPERADOR</b></td>
													<td width='30%' style='".STYLE_TABCAB."'><b>EQUIPAMENTO</b></td>
													<td width='30%' style='".STYLE_TABCAB."'><b>IMPLEMENTO</b></td>
												</tr>";
				
				$equipamentos = $bd->getPorQuery(new BeanEquipamentoAplicacao, null, "###.fk_aplicacao=".$aplicacao->id, null);
		
				if(count($equipamentos)>0){
		
					foreach($equipamentos as $equipamento)
						$form .=				"<tr>
													<td align='center'>".$equipamento->codigo_equipamento."</td>
													<td align='left'>".$equipamento->nome_operador."</td>
													<td align='left'>".$equipamento->nome_equipamento."</td>
													<td align='left'>".$equipamento->nome_implemento."</td>
												</tr>";
				}	
			
				$form .= "					</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>";
			}	
						
		
			$form .= "
					</table>";

		}
		else
			$form .= "
					<div align='center'>
					<br><br><br><br>
					Sem dados para o período informado
					<br><br><br><br>
					</div>";
					
		$rel .= "	
					<div align='center' style='border:solid 1px #333;padding:10px;margin:10px;font-size:".$this->fonte_normal."'>
						<table width='100%' style='font-size:".$this->fonte_normal."'>
							<tr>
								<td align='left' colspan=2>
									<b>Período: </b>".$data_inicial.($tem_duas_datas?" à ":"").$data_final."
								</td>
							</tr>
							<tr>
								<td align='left' width='34%'>
									<b>Fazenda(s):  </b>".$nome_fazenda."
								</td>
								<td align='left' width='33%'>
									<b>Quadra(s):  </b>".$nome_quadra."
								</td>
								<td align='left' width='33%'>
									<b>Operador(es):  </b>".$nome_operador."
								</td>
							</tr>
							<tr>
								<td align='left'>
									<b>Produto(s):  </b>".$nome_produto."
								</td>
								<td align='left'>
									<b>Equipamento(s):  </b>".$nome_equipamento."
								</td>
								<td align='left'>
									<b>Tipo(s) Aplicação:  </b>".$nome_tipo."
								</td>
							</tr>
						</table>
						".$form."
					</div>";	
		
		
		return $this->setTemplate($bd, $rel);	
	}
	
	
	
	
		
	private function getRelatorioDeRecomendacoes(&$bd){
		
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Data.class.php';
		include_once AGRCL_PATH_ABS.'geral/fazendas/BeanFazenda.class.php';
		include_once AGRCL_PATH_ABS.'campo/recomendacao/BeanRecomendacao.class.php';
		include_once AGRCL_PATH_ABS.'campo/pragas/BeanPraga.class.php';
		include_once AGRCL_CALC_PATH_ABS.'Calculo.class.php';
		include_once AGRCL_PATH_ABS.'campo/quadras/BeanQuadra.class.php';	
		include_once AGRCL_PATH_ABS.'campo/produtos/BeanProduto.class.php';
		include_once AGRCL_PATH_ABS.'patrimonio/equipamentos/BeanEquipamento.class.php';
		include_once AGRCL_PATH_ABS.'campo/recomendacao/BeanProdutoRecomendacao.class.php';
		include_once AGRCL_PATH_ABS.'configuracoes/BeanEntidade.class.php';	
		
		$data = new Data();
		$comuns = new Comuns();
		$calculo = new Calculo();
		
		$subquery = "";
		
		$data_inicial= "";
		$data_final= "";
		$tem_duas_datas= false;
		
		$nome_fazenda= "TODAS";
		$nome_quadra= "TODAS";
		$nome_operador= "TODOS";
		$nome_produto= "TODOS";
		$nome_tipo= "TODOS";
		
		if(array_key_exists("i", $_GET) && strlen($_GET['i'])==10){
			
			$data_inicial= $data->converteEUAParaBR($_GET['i']);
			
			if(array_key_exists("f", $_GET) && strlen($_GET['f'])==10){
			
				$data_final= $data->converteEUAParaBR($_GET['f']);
			
				$subquery = " (###.data between '".$_GET['i']."' and '".$_GET['f']."') ";
				$tem_duas_datas = true;
			}
			else
				$subquery = " (###.data>='".$_GET['i']."') ";
		}
		else{
			
			if(array_key_exists("f", $_GET) && strlen($_GET['f'])==10){
				
				$data_final= $data->converteEUAParaBR($_GET['f']);
				
				$subquery = " (###.data<='".$_GET['f']."') ";
			}
		}
		
		if(array_key_exists("fz", $_GET) && $_GET['fz']>0){
			
			$subquery .= (strlen($subquery)>0?" AND ":"")." (###.fk_fazenda=".$_GET['fz'].") ";
		
			$fazenda = $bd->getPorId(new BeanFazenda(), $_GET['fz']);

			if(is_object($fazenda))
				$nome_fazenda= $fazenda->nome;
		}
		
		
		if(array_key_exists("fq", $_GET) && $_GET['fq']>0){
			
			$subquery .= (strlen($subquery)>0?" AND ":"")." (###.fk_quadra=".$_GET['fq'].") ";
		
			$quadra = $bd->getPorId(new BeanQuadra(), $_GET['fq']);

			if(is_object($quadra))
				$nome_quadra= $quadra->nome;
		}
		
		
		if(array_key_exists("fo", $_GET) && $_GET['fo']>0){
			
			$subquery .= (strlen($subquery)>0?" AND ":"")." (###.fk_operador=".$_GET['fz'].") ";
		
			$operador = $bd->getPorId(new BeanUsuario(), $_GET['fo']);

			if(is_object($operador))
				$nome_operador= $operador->nome_completo ;
		}
		
		if((array_key_exists("fp", $_GET) && $_GET['fp']>0) || 
				(array_key_exists("ft", $_GET) && $_GET['ft']>0)){
			
			$subquery .= (strlen($subquery)>0?" AND ":""). 
						" (###.id_recomendacao IN (select x.fk_recomendacao from produtos_recomendacoes as x where "; 
			
			if(array_key_exists("ft", $_GET) && $_GET['ft']>0){
				
				$subquery .="x.fk_tipo=".$_GET['ft']." and ";
				
				$tipo = $bd->getPorId(new BeanEntidade(), $_GET['ft']);

				if(is_object($tipo))
					$nome_tipo= $tipo->nome;
			}
		
			if(array_key_exists("fp", $_GET) && $_GET['fp']>0){
				
				$subquery .="x.fk_produto=".$_GET['fp'];
				
				$produto = $bd->getPorId(new BeanProduto(), $_GET['fp']);

				if(is_object($produto))
					$nome_produto= $produto->nome;
			}
			else
				$subquery .="1";
			
			$subquery .="))";
		}
		
		//echo $subquery."<br><br>";
		
		$rel = "";
							
		$form ="";			
		
		$recomendacoes = $bd->getPorQuery(new BeanRecomendacao(), null, $subquery, "###.fk_quadra ASC, ###.data DESC");

		if(count($recomendacoes)>0){
			
			$form .=	"
					<table width='100%'  cellspacing=0 cellpadding=0  style='font-size:".$this->fonte_menor."'>
						";
		
			
			foreach($recomendacoes as $recomendacao){
				
				$form .= "
						<tr>
							<td align='center' style='width:15%;".STYLE_TABCAB."'><b>CÓDIGO</b></td>
							<td align='center' style='width:15%;".STYLE_TABCAB."'><b>INSPEÇÃO.</b></td>
							<td align='center' style='width:15%;".STYLE_TABCAB."'><b>FAZENDA</b></td>
							<td align='center' style='width:25%;".STYLE_TABCAB."'><b>QUADRA</b></td>
							<td align='center' style='width:15%;".STYLE_TABCAB."'><b>DATA</b></td>
							<td align='center' style='width:15%;".STYLE_TABCAB."'><b>APLICAÇÃO</b></td>
						</tr>
						<tr>
							<td align='center'>".$recomendacao->codigo."</td>
							<td align='center'>".$recomendacao->codigo_inspecao."</td>
							<td align='left'>".$recomendacao->nome_fazenda."</td>
							<td align='left'>".$recomendacao->nome_quadra."</td>
							<td align='center'>".$recomendacao->data." L</td>
							<td align='center'>".$recomendacao->codigo_aplicacao."</td>
						</tr>
						<tr>
							<td></td>
							<td align='center' colspan='5'>
								<table width='100%'   cellspacing=0 cellpadding=0  style='font-size:".$this->fonte_menor."'>
									<tr>
										<td align='center' style='width:10%;".STYLE_TABCAB."'><b>PRODUTOS</b></td>
									</tr>
									<tr>
										<td align='center'>
											<table width='100%' cellspacing=0 cellpadding=0 style='font-size:".$this->fonte_menor."'>
												<tr>
													<td width='35%' style='".STYLE_TABCAB."'><b>TIPO</b></td>
													<td width='35%' style='".STYLE_TABCAB."'><b>PRODUTO</b></td>
													<td width='15%' style='".STYLE_TABCAB."'><b>DOSE</b></td>
													<td width='15%' style='".STYLE_TABCAB."'><b>VOLUME</b></td>
												</tr>";
						
					
				$produtos = $bd->getPorQuery(new BeanProdutoRecomendacao, 
											null, 
												"###.fk_recomendacao=".$recomendacao->id, 
													"###.id_prod_recomendacao ASC");
				if(count($produtos)>0){
			
					foreach($produtos as $produto){

						$medida = (strlen($produto->medida_produto)>0?" ".$produto->medida_produto:"");
						
						$form .=				"<tr>
													<td align='left'>".$produto->nome_tipo."</td>".
													"<td align='left'>".$produto->nome_produto."</td>".
													"<td align='center'>".$calculo->formataParaMostrarDecimal($produto->dose).$medida."</td>".
													"<td align='center'>".$calculo->formataParaMostrarDecimal($produto->volume)." L</td>".
												"</tr>";
					}
				}
				
				
				$form .= "					</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>";
			}	
						
		
			$form .= "
					</table>";

		}
		else
			$form .= "
					<div align='center'>
					<br><br><br><br>
					Sem dados para o período informado
					<br><br><br><br>
					</div>";
					
		$rel .= "	
					<div align='center' style='border:solid 1px #333;padding:10px;margin:10px;font-size:".$this->fonte_normal."'>
						<table width='100%' style='font-size:".$this->fonte_normal."'>
							<tr>
								<td align='left' colspan=2>
									<b>Período: </b>".$data_inicial.($tem_duas_datas?" à ":"").$data_final."
								</td>
							</tr>
							<tr>
								<td align='left' width='34%'>
									<b>Fazenda(s):  </b>".$nome_fazenda."
								</td>
								<td align='left' width='33%'>
									<b>Quadra(s):  </b>".$nome_quadra."
								</td>
								<td align='left' width='33%'>
									<b>Operador(es):  </b>".$nome_operador."
								</td>
							</tr>
							<tr>
								<td align='left'>
									<b>Produto(s):  </b>".$nome_produto."
								</td>
								<td align='left' colspan='2'>
									<b>Tipo(s) Aplicação:  </b>".$nome_tipo."
								</td>
							</tr>
						</table>
						".$form."
					</div>
					".$this->getAssinatura($bd, $_GET['i']);	
		
		
		return $this->setTemplate($bd, $rel);	
	}
	
	
	
	
	
	
	private function getRelatorioDeAbastecimento(&$bd){
	
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Data.class.php';
		include_once AGRCL_PATH_ABS.'frota/abastecimento/BeanAbastecimento.class.php';
		include_once AGRCL_PATH_ABS.'patrimonio/equipamentos/BeanEquipamento.class.php';
		include_once AGRCL_CALC_PATH_ABS.'Calculo.class.php';
		include_once AGRCL_PATH_ABS.'configuracoes/BeanEntidade.class.php';
		include_once AGRCL_PATH_ABS.'geral/fazendas/BeanFazenda.class.php';
		include_once AGRCL_PATH_ABS.'frota/tanques/BeanTanque.class.php';
		
		
		$data = new Data();
		$comuns = new Comuns();
		$calculo = new Calculo();
		
		$subquery_filtros = "###.status>0 ";
		$subquery_modelo = "";
		$modelo = "";
	
		$data_inicial= "";
		$data_final= "";
		$tem_duas_datas= false;
		
		$periodo= "";
		
		$nome_fazenda= "TODAS";
		$nome_tanque= "TODOS";
		$nome_modelo= "TODOS";
		$nome_veiculo= "TODOS";
		
		
		if(array_key_exists("i", $_GET) && strlen($_GET['i'])==10){
			
			$data_inicial= $data->converteEUAParaBR($_GET['i']);
			
			if(array_key_exists("f", $_GET) && strlen($_GET['f'])==10){
			
				$data_final= $data->converteEUAParaBR($_GET['f']);
			
				$subquery = " (###.data between '".$_GET['i']."' and '".$_GET['f']."') ";
				$tem_duas_datas = true;
			}
			else
				$subquery = " (###.data>='".$_GET['i']."') ";
		}
		else{
			
			if(array_key_exists("f", $_GET) && strlen($_GET['f'])==10){
				
				$data_final= $data->converteEUAParaBR($_GET['f']);
				
				$subquery = " (###.data<='".$_GET['f']."') ";
			}
		}
		
		if(array_key_exists("fz", $_GET) && $_GET['fz']>0){
			
			$subquery_filtros .= (strlen($subquery_filtros)>0?" AND ":"").
			"  (###.fk_tanque IN (select x.id_tanque from tanques as x where x.fk_fazenda=".$_GET['fz']."))  ";
		
			$fazenda = $bd->getPorId(new BeanFazenda(), $_GET['fz']);

			if(is_object($fazenda))
				$nome_fazenda= $fazenda->nome;
		}	
		
		if(array_key_exists("tq", $_GET) && $_GET['tq']>0){
			
			$subquery_filtros .= (strlen($subquery_filtros)>0?" AND ":"")." (###.fk_tanque=".$_GET['tq'].") ";
		
			$tanque = $bd->getPorId(new BeanTanque(), $_GET['tq']);

			if(is_object($tanque))
				$nome_tanque= $tanque->nome;
		}
		
		if(array_key_exists("vc", $_GET) && $_GET['vc']>0){
			
			$subquery_filtros .= (strlen($subquery_filtros)>0?" AND ":"")." (###.fk_veiculo=".$_GET['vc'].") ";
		
			$veiculo = $bd->getPorId(new BeanEquipamento(), $_GET['vc']);

			if(is_object($veiculo))
				$nome_veiculo= $veiculo->nome;
		}
		
		if(array_key_exists("md", $_GET) && $_GET['md']>0){
			
			$subquery_filtros .= (strlen($subquery_filtros)>0?" AND ":"").
			"  (###.fk_veiculo IN (select x.id_equipamento from equipamentos as x where x.fk_modelo=".$_GET['md']." and x.status>0))  ";
		
			$subquery_modelo .= " ###.status>0 and ###.controlar_combustivel>0 and ###.fk_modelo=".$_GET['md'];
		
			$modelo = $bd->getPorId(new BeanEntidade(), $_GET['md']);
			
			if(is_object($modelo))
				$nome_modelo= $modelo->nome;
		}
		
		
		$abastecimentos = $bd->getPorQuery(new BeanAbastecimento(), 
												null, 
													$subquery_filtros, 
														"###.data DESC");
		
		$form = "";
		
		if(count($abastecimentos)>0){
			
			$form .= "
					<table width='100%'  cellspacing=0 cellpadding=0  style='font-size:".$this->fonte_menor."'>
						<tr>
							<td align='center' style='width:10%;".STYLE_TABCAB."'><b>DATA</b></td>
							<td align='center' style='width:10%;".STYLE_TABCAB."'><b>TANQUE</b></td>
							<td align='center' style='width:20%;".STYLE_TABCAB."'><b>VEICULO</b></td>
							<td align='center' style='width:10%;".STYLE_TABCAB."'><b>LITROS</b></td>
							<td align='center' style='width:10%;".STYLE_TABCAB."'><b>H/O INI.</b></td>
							<td align='center' style='width:10%;".STYLE_TABCAB."'><b>H/O FIN.</b></td>
							<td align='center' style='width:10%;".STYLE_TABCAB."'><b>Km/H</b></td>
							<td align='center' style='width:10%;".STYLE_TABCAB."'><b>Hora ou Km/L</b></td>
							<td align='center' style='width:10%;".STYLE_TABCAB."'><b>STATUS</b></td>
						</tr>";
		
			foreach($abastecimentos as $abastecimento)
				$form .= "
						<tr>
							<td align='center'>".$data->converteEUAParaBR($abastecimento->data)."</td>
							<td align='left'>".  $abastecimento->nome_tanque."</td>
							<td align='left'>".  $abastecimento->nome_veiculo."</td>
							<td align='center'>".$abastecimento->litros." L</td>
							<td align='center'>".($abastecimento->usa_horimetro?($abastecimento->horimetro>0?$abastecimento->horimetro." H":""):($abastecimento->odometro>0?$abastecimento->odometro." Km":""))."</td>
							<td align='center'>".($abastecimento->usa_horimetro?($abastecimento->horimetro_final>0?$abastecimento->horimetro_final." H":""):($abastecimento->odometro_final>0?$abastecimento->odometro_final." Km":""))."</td>
							<td align='center'>".$abastecimento->formataHorimOdom()."</td>
							<td align='center'>".$abastecimento->formataMedida()."</td>
							<td align='center'>".$abastecimento->formataStatus()."</td>
						</tr>";
				
			$form .= "
					</table>";
		}
		else
			$form .= "
					<div align='center'>
					<br><br><br><br>
					Sem dados para o período informado
					<br><br><br><br>
					</div>";
					
		$rel = "	
					<div align='center' style='border:solid 1px #333;padding:10px;margin:10px;font-size:".$this->fonte_normal."'>
						<table width='100%' style='font-size:".$this->fonte_normal."'>
							<tr>
								<td align='left' colspan=4>
									<b>Período: </b>".$data_inicial.($tem_duas_datas?" à ":"").$data_final."
								</td>
							</tr>
							<tr>
								<td align='left' width='25%'>
									<b>Fazenda(s):  </b>".$nome_fazenda."
								</td>
								<td align='left' width='25%'>
									<b>Tanque(s):  </b>".$nome_tanque."
								</td>
								<td align='left' width='25%'>
									<b>Modelo(es):  </b>".$nome_modelo."
								</td>
								<td align='left' width='25%'>
									<b>Produto(s):  </b>".$nome_veiculo."
								</td>
							</tr>
						</table>
						".$form."
					</div>";	
		
		
		return $this->setTemplate($bd, $rel);																													
	}
	
	
	
	
	
		
	private function getRelatorioDeRecomendacaoIndividual(&$bd){
		
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Data.class.php';
		include_once AGRCL_PATH_ABS.'geral/fazendas/BeanFazenda.class.php';
		include_once AGRCL_PATH_ABS.'campo/recomendacao/BeanRecomendacao.class.php';
		include_once AGRCL_PATH_ABS.'campo/pragas/BeanPraga.class.php';
		include_once AGRCL_CALC_PATH_ABS.'Calculo.class.php';
		include_once AGRCL_PATH_ABS.'campo/produtos/BeanProduto.class.php';
		include_once AGRCL_PATH_ABS.'patrimonio/equipamentos/BeanEquipamento.class.php';
		include_once AGRCL_PATH_ABS.'campo/recomendacao/BeanProdutoRecomendacao.class.php';
		include_once AGRCL_PATH_ABS.'configuracoes/BeanEntidade.class.php';	
		
		$data = new Data();
		$comuns = new Comuns();
		$calculo = new Calculo();
		
		$rel = "";
		
		for($i=0; $i<($_GET['2v']>0?2:1); $i++){
		
			$recomendacao = $bd->getPorId(new BeanRecomendacao(), $_GET['id']);

			if(is_object($recomendacao)){
				
				if($i==1)
					$rel .= "<div align='center'><br><br>.........................................................................<br><br><br></div>";

				
				$rel .= $this->getCabecalho($bd)."	
						<div align='center' style='border:solid 1px #333;padding:10px;margin:10px;font-size:".$this->fonte_normal."'>
							<table width='100%' style='font-size:".$this->fonte_normal."'>
								<tr>
									<td align='left' width='33%'>
										<b>Código: </b>".$recomendacao->codigo."
									</td>
									<td align='left' width='34%'>
										<b>Data: </b>".$data->converteEUAParaBR($recomendacao->data)."
									</td>
									<td align='left' width='33%'>
										<b>Inspeção: </b>".$recomendacao->codigo_inspecao."
									</td>
								</tr>
								<tr>
									<td align='left'>
										<b>Fazenda:  </b>".$recomendacao->nome_fazenda."
									</td>
									<td align='left'>
										<b>Quadra:  </b>".$recomendacao->nome_quadra."
									</td>
									<td align='left'>
									</td>
								</tr>
							</table>
						</div>
						<table width='100%'  cellspacing=0 cellpadding=0  style='font-size:".$this->fonte_menor."'>
							<tr>
								<td width='35%' style='".STYLE_TABCAB."' align='center'><b>TIPO</b></td>
								<td width='35%' style='".STYLE_TABCAB."' align='center'><b>PRODUTO</b></td>
								<td width='15%' style='".STYLE_TABCAB."' align='center'><b>DOSE</b></td>
								<td width='15%' style='".STYLE_TABCAB."' align='center'><b>VOLUME</b></td>
							</tr>";

				$produtos = $bd->getPorQuery(new BeanProdutoRecomendacao, 
												null, 
													"###.fk_recomendacao=".$recomendacao->id, 
														"###.id_prod_recomendacao ASC");
				if(count($produtos)>0){
				
					foreach($produtos as $produto){

						$medida = (strlen($produto->medida_produto)>0?" ".$produto->medida_produto:"");
							
						$rel .=
							"<tr>
								<td align='left'>".$produto->nome_tipo."</td>".
								"<td align='left'>".$produto->nome_produto."</td>".
								"<td align='center'>".$calculo->formataParaMostrarDecimal($produto->dose).$medida."</td>".
								"<td align='center'>".$calculo->formataParaMostrarDecimal($produto->volume)." L</td>".
							"</tr>";
						}
					}
									
				$rel .= "</table>
						<br>
						".$this->getAssinatura($bd, $recomendacao->data).
						$this->getRodape();	
						
			}
		}
		
		return $this->setTemplate($bd, $rel);	
	}
	
	

	
	
		
	private function getRelatorioDeInspecao(&$bd){
		
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Data.class.php';
		include_once AGRCL_PATH_ABS.'geral/fazendas/BeanFazenda.class.php';
		include_once AGRCL_PATH_ABS.'campo/inspecao/BeanInspecao.class.php';
		include_once AGRCL_PATH_ABS.'campo/inspecao/BeanOcorrencia.class.php';
		include_once AGRCL_PATH_ABS.'campo/pragas/BeanPraga.class.php';
		include_once AGRCL_CALC_PATH_ABS.'Calculo.class.php';
		include_once AGRCL_PATH_ABS.'campo/recomendacao/BeanRecomendacao.class.php';
		include_once AGRCL_PATH_ABS.'campo/produtos/BeanProduto.class.php';
		include_once AGRCL_PATH_ABS.'patrimonio/equipamentos/BeanEquipamento.class.php';
		include_once AGRCL_PATH_ABS.'campo/recomendacao/BeanProdutoRecomendacao.class.php';
		include_once AGRCL_PATH_ABS.'configuracoes/BeanEntidade.class.php';	
		
		$data = new Data();
		$comuns = new Comuns();
		$calculo = new Calculo();
		
		$rel = "";
		
		
		$inspecao = $bd->getPorId(new BeanInspecao(), (array_key_exists("id", $_GET) && $_GET['id']>0?$_GET['id']:0));

		if(is_object($inspecao)){
				
			$rel .= "
				<table width='100%'  style='font-size:".$this->fonte_menor."'>
					<tr>
						<td align='center' style='width:15%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>CÓDIGO</td>
						<td align='center' style='width:25%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>FAZENDA</td>
						<td align='center' style='width:30%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>QUADRA</td>
						<td align='center' style='width:15%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>SITUAÇÃO</td>
						<td align='center' style='width:15%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>OCORRÊNCIAS</td>
					</tr>
					<tr>
						<td align='center'>".$inspecao->codigo."</td>
						<td align='left'>".$inspecao->nome_fazenda."</td>
						<td align='left'>".$inspecao->nome_quadra."</td>
						<td align='center'>".$inspecao->formataSituacao()."</td>
						<td align='center'>".$inspecao->formataNumeroDeOcorrencias()."</td>
					</tr>";
						
						
						
			$ocorrencias = $bd->getPorQuery(new BeanOcorrencia(), null, "###.fk_inspecao=".$inspecao->id." and ###.fk_praga>0", "###.data DESC, ###.hora DESC,###.min DESC");

			if(count($ocorrencias)>0){
			
				$rel .=	"
					<tr>
						<td align='center'></td>
						<td align='center' colspan=4>
							<table width='100%'  style='font-size:".$this->fonte_menor."'>
								<tr>
									<td align='center' style='width:18%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>DATA</td>
									<td align='center' style='width:41%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>PRAGA</td>
									<td align='center' style='width:13%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>LOCAL</td>
									<td align='center' style='width:13%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>PORC.</td>
									<td align='center' style='width:15%;background:".$this->bg_cabecalho.";color:".$this->cor_fonte.";border:solid 1px #333'>DANO ECO.</td>
								</tr>";
		
			
				foreach($ocorrencias as $ocorrencao){
						
					$praga = $bd->getPorId(new BeanPraga(), $ocorrencao->fk_praga);

					$dano_eco = "";
		
					if(is_object($praga)){
							
						$porcentagens = json_decode($praga->params);
						
						if(is_object($porcentagens) && strcmp($porcentagens->nivel_4_i, "-")!=0)
							$dano_eco="<table width='100%'  style='font-size:".$this->fonte_menor."'>
											<tr>
												<td width='50%' align='right'>".$porcentagens->nivel_4_i."%</td>
												<td width='50%' align='right'><img src='".AGRCL_PATH_IMGS."ocorrencia_alto.png' width='15px' height='15px'></td>
											</tr>
										</table>";
		
					}

					$rel .= "
								<tr>
									<td align='center'>".$data->converteEUAParaBRComHorario($ocorrencao->data, $ocorrencao->hora,$ocorrencao->min)."</td>
									<td align='left'>".(is_object($praga)?$praga->nome:"")."</td>
									<td align='left'>".$ocorrencao->nome_local."</td>
									<td align='center'>".$ocorrencao->porcentagem."%</td>
									<td align='right'>".$dano_eco."</td>
								</tr>";
									
				}
					
				$rel .= "	</table>
						</td>
					</tr>";
			}
			
			$rel .= "
				</table>";
				
			$recomendacao = $bd->getPrimeiroOuNada(new BeanRecomendacao(), null, "###.fk_inspecao=".$inspecao->id, null);

			if(is_object($recomendacao)){
				
				$rel .= "
				<div align='center' style='border:solid 1px #333;padding:10px;margin:10px;font-size:".$this->fonte_normal."'>
					<b><span style='font-size:".$this->fonte_maior."'>RECOMENDAÇÕES</span></b>
					<table width='100%' style='font-size:".$this->fonte_normal."'>
						<tr>
							<td align='left' width='33%'>
								<b>Código: </b>".$recomendacao->codigo."
							</td>
							<td align='left' width='34%'>
								<b>Data: </b>".$data->converteEUAParaBR($recomendacao->data)."
							</td>
							<td align='left' width='33%'>
								<b>Inspeção: </b>".$recomendacao->codigo_inspecao."
							</td>
						</tr>
						<tr>
							<td align='left'>
								<b>Fazenda:  </b>".$recomendacao->nome_fazenda."
							</td>
							<td align='left'>
								<b>Quadra:  </b>".$recomendacao->nome_quadra."
							</td>
							<td align='left'>
							</td>
						</tr>
					</table>
				</div>
				<table width='100%'  cellspacing=0 cellpadding=0  style='font-size:".$this->fonte_menor."'>
					<tr>
						<td width='35%' style='".STYLE_TABCAB."' align='center'><b>TIPO</b></td>
						<td width='35%' style='".STYLE_TABCAB."' align='center'><b>PRODUTO</b></td>
						<td width='15%' style='".STYLE_TABCAB."' align='center'><b>DOSE</b></td>
						<td width='15%' style='".STYLE_TABCAB."' align='center'><b>VOLUME</b></td>
					</tr>";

				$produtos = $bd->getPorQuery(new BeanProdutoRecomendacao, 
												null, 
													"###.fk_recomendacao=".$recomendacao->id, 
														"###.id_prod_recomendacao ASC");
				if(count($produtos)>0){
				
					foreach($produtos as $produto){

						$medida = (strlen($produto->medida_produto)>0?" ".$produto->medida_produto:"");
							
						$rel .=
							"<tr>
								<td align='left'>".$produto->nome_tipo."</td>".
								"<td align='left'>".$produto->nome_produto."</td>".
								"<td align='center'>".$calculo->formataParaMostrarDecimal($produto->dose).$medida."</td>".
								"<td align='center'>".$calculo->formataParaMostrarDecimal($produto->volume)." L</td>".
							"</tr>";
						}
					}
									
				
				$rel .= "</table>
						<br>
						<table width='100%' cellspacing=0 cellpadding=0  style='font-size:".$this->fonte_menor."'>
							<tr>
								<td align='center' width='50%'>
									".$this->getAssinatura($bd, $recomendacao->data).
								"</td>	
								<td align='center' width='50%'>
									".$this->getAssinatura($bd, $recomendacao->data, TIPO_ASSINA_TECAGRI).
								"</td>
							</tr>
						</table>";
						
			}
		}
		else
			$rel = "
					<div align='center'>
					<br><br><br><br>
						Inspeção Não Encontrada
					<br><br><br><br>
					</div>";
					
		
		return $this->setTemplate($bd, $rel);	
	}
	
	
	
	
	
	
	
	
/*******************************************************/		
	
	
	
	
	private function setGrafico($dados, $titulo, $tipo, $y_moeda, $x_nome, $y_nome, $prefixo, $sufixo){
		
		$nome = "_".$this->gandalf->usuarioAtual()->id."_".round(microtime(true) * 1000).rand(0, 9999999).".jpg";
		
		include_once AGRCL_GRFC_PATH_ABS.'phplot.php';
	
		$p = null;

		if($tipo==TP_GRAFC_LINHA){
			
			$p = new PHPlot((count($dados)*count($dados[0])*50)+170, 450);
			$p->SetDataType('text-data');
			$p->SetYDataLabelPos('plotin');
			//$p->SetImageBorderType('plain');
			$p->SetPointSizes(10);
			$p->SetPlotType('linepoints');
			$p->SetLineWidths(4);
		}
		else if($tipo==TP_GRAFC_BARHO){
			
			$p = new PHPlot((count($dados)*count($dados[0])*55)+170, 450);
			$p->SetDataType('text-data');
			$p->SetYDataLabelPos('plotin');
			$p->SetPlotType('bars');
			$p->SetXTickPos('none');
			$p->SetXTickLabelPos('none');
			$p->SetShading(0);
		}
		
		$p->SetTitle($titulo);
		
		if($y_moeda)
			$p->SetYDataLabelType('custom', array(new Relatorios, 'formataMoeda'), array($prefixo, $sufixo));
		else
			$p->SetYDataLabelType('custom', array(new Relatorios, 'formataInteiro'), array($prefixo, $sufixo));
		
		
		if(strlen($x_nome)>0)
			$p->SetXTitle($x_nome);
		
		if(strlen($y_nome)>0)
			$p->SetYTitle($y_nome);
		
		$p->SetMarginsPixels(120, 50, 30, 50);
		$p->SetDataValues($dados);
		$p->SetBackgroundColor('#ffffff');
		$p->SetDrawPlotAreaBackground(True);
		$p->SetPlotBgColor('#ffffff');
		$p->SetPlotBorderType('full');
		$p->SetDataColors($this->cor_grafico);
		
		$p->SetIsInline(true);
		$p->SetOutputFile(AGRCL_PATH_ABS."relatorios/temp/".$nome);
		$p->DrawGraph();
		
		return "<img src='".AGRCL_PATH_SMP."relatorios/temp/".$nome."'><br>";
	}
	
	

	
	
	
	private function constroiGraficoDeDataPorDia($dados, $titulo, $tipo, $y_moeda, $x_nome, $y_nome, $prefixo, $sufixo, $legendas=null, $pdf=false){
		
		$rel = "";
		
		if(count($dados)>0){	
	
			foreach($dados as $i=>$mes){
				$rel .=  
					"
					<div class='item_relatorio' id='mes_".$i."' ".(!$pdf && $i>0?"style='display:none'":"").">
						<div align='center' style='margin:20px 0px 0px 0px'>
							<b>".$titulo."</b>
						</div>
						<div align='center' style='margin:10px 0px 0px 0px'>
							<b>".$mes['descricao']."</b>
							<input type='hidden' value='".$mes['descricao']."' id='titulo_".$i."'>
						</div>
						<div class='area_relatorio'>".
							$this->setGrafico($mes['dias'], "", $tipo, $y_moeda, $x_nome, $y_nome, $prefixo, $sufixo).
						"</div>
					</div>";	
			}	

			if($legendas!=null && count($legendas)>0){		
					
				$rel .=  "
					<div class='area_legendas' align='center'>
						<table>
							<tr>";
					
				foreach($legendas as $i=>$legenda)
					$rel .=  "
								<td style='width:20px;height:10px;background:".($i>count($this->cor_grafico)-1?$this->cor_grafico[0]:$this->cor_grafico[$i])."'></td>
								<td>&nbsp;&nbsp;".$legenda."&nbsp;&nbsp;</td>";
						
					
					
					
					
				$rel .=  "	</tr>
						</table>
					</div>";	
			}	
		}
		else
			$rel .= "
					<div align='center'>
					<b>".$titulo."</b>
					<br><br><br><br>
					Não há dados para o período.
					<br><br><br><br>
					</div>";
					
		return $rel;
	}
	
	
	
	
	
	private function constroiGraficoDeDataPorMes($dados, $titulo, $tipo, $y_moeda, $x_nome, $y_nome, $prefixo, $sufixo, $legendas=null){
		
		$rel = "";
		
		if(count($dados)>0)	{
			$rel .=  
					"
					<div class='item_relatorio'>
						<div align='center' style='margin:20px 0px 0px 0px'>
						<b>".$titulo."</b>
						</div>
					</div>
					<div class='area_relatorio'>".
						$this->setGrafico($dados, "", $tipo, $y_moeda, $x_nome, $y_nome, $prefixo, $sufixo)."
					</div>";
					
			if($legendas!=null && count($legendas)>0){		
				$rel .=  "
					<div class='area_legendas' align='center'>
						<table>
							<tr>";
					
					foreach($legendas as $i=>$legenda)
						$rel .=  "
								<td style='width:20px;height:10px;background:".($i>count($this->cor_grafico)-1?$this->cor_grafico[0]:$this->cor_grafico[$i])."'></td>
								<td>&nbsp;&nbsp;".$legenda."&nbsp;&nbsp;</td>";
						
					
					
					
					
				$rel .=  "	</tr>
						</table>
					</div>";	
			}
		}			
		else
			$rel .= "
					<div align='center'>
					<b>".$titulo."</b>
					<br><br><br>
					Não há dados para o período.
					<br><br><br>
					</div>";
					
		return $rel;
	}
	

	
	
	
	
	public function formataMoeda($label, $arg){
		
		include_once AGRCL_CALC_PATH_ABS.'Calculo.class.php';
		
		$calculo = new Calculo();
		
		return $arg[0].$calculo->formataParaMostrar($label).$arg[1];

	}

	
	
	
	
	public function formataInteiro($label, $arg){
		
		include_once AGRCL_CALC_PATH_ABS.'Calculo.class.php';
		
		$calculo = new Calculo();
		
		return $arg[0].$calculo->formataParaMostrar($label, false).$arg[1];

	}

	
	
	
	
	private function getLegendas($legendas){
		
		$rel =  "";
		
		if($legendas!=null && count($legendas)>0){		
				
			$rel .=  "
					<div class='area_legendas' align='center'>
						<table>
							<tr>";
					
			foreach($legendas as $i=>$legenda)
				$rel .=  "
								<td style='width:20px;height:10px;background:".($i>count($this->cor_grafico)-1?$this->cor_grafico[0]:$this->cor_grafico[$i])."'></td>
								<td>&nbsp;&nbsp;".$legenda."&nbsp;&nbsp;</td>";
						
						
			$rel .=  "	</tr>
						</table>
					</div>";	
		}
		
		return $rel;
	}
	
	
	
	
	
	
	
	private function getAssinatura($bd, $data, $tipo=TIPO_ASSINA_ENGAGRO){
		
		include_once AGRCL_PATH_ABS.'geral/assinaturas/BeanAssinatura.class.php';
		
		$assinatura = null;
		
		$profissao = "";
		
		if($tipo==TIPO_ASSINA_ENGAGRO){
			
			$assinatura = $bd->getPrimeiroOuNada(new BeanAssinatura(), null, "###.tipo=".TIPO_ASSINA_ENGAGRO." and ###.data_inicio<='".$data."' and ###.data_fim>='".$data."'", null);
			$profissao = NOME_ASSINA_ENGAGRO;
		}
		else if($tipo==TIPO_ASSINA_TECAGRI){
			
			$assinatura = $bd->getPrimeiroOuNada(new BeanAssinatura(), null, "###.tipo=".TIPO_ASSINA_TECAGRI." and ###.data_inicio<='".$data."' and ###.data_fim>='".$data."'", null);
			$profissao = NOME_ASSINA_TECAGRI;
		}
		
		if(is_object($assinatura)){
		
			$form = 
			"<div align='center'  style='font-size:".$this->fonte_normal."'>
				<br><br>
				______________________________________________<br>
				<b><i>".$assinatura->nome."</i><br>
				".$profissao."<br>
				Nº do ".$assinatura->orgao.", ".$assinatura->num_orgao." ".$assinatura->uf."<br>
				Nº de habilitação, ".$assinatura->num_habilitacao."
			</div><br>";

			return $form;
		}
		
		return "";
	}
	
	
	
	
	
	
	
	
	
/*	
	private function constroiGraficoDeLista($dados, $titulo, $legendas){
		
		$rel = "";
		
		if(count($dados)>0){
			
			$quant_colunas= count($dados[0])-1;
			
			$dados= array_chunk($dados, round(QUANT_MAX_ITENS_REL_LISTA/$quant_colunas));
			
			foreach($dados as $item)
				$rel  .= $this->setGrafico($titulo, $item, 1, count($item)*ALT_MAX_ITENS_REL_LISTA*$quant_colunas, false, $legendas);	
		}
		else
			$rel .= $this->naoHaDados($titulo);
					
		return $rel;
	}
*/	
	
	
	
	
	
		
/*	
	private function temPermissao(){
		
		if(array_key_exists("op", $_GET) && strlen($_GET['op'])>0){
			
			include_once PSNL_VNVP_PATH_ABS.'base/VNVPBase.class.php';
	
			$vnvp= new VNVPBase;
			
			switch($_GET['op']){
				
				case 'RELCMD':
				case 'RELCMDI':
					return $vnvp->temPermissao("RELCMD");
				case 'RELCNC':
					return $vnvp->temPermissao("RELCNC");	
					
				case 'CMD':
				case 'CNC':
					return true;
			}
		}
		
		return false;
	}
*/	
	
	
	

	
	
}
	
?>