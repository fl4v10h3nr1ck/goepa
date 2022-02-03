<?php

include_once AGRCL_PATH_ABS.'Base.class.php';
include_once AGRCL_BD_PATH_ABS."BdUtil.class.php";


class RelBaseGrafico extends Base{


public $bd;

public $cor_grafico;



	function __construct() {
		
		parent::__construct();
		
		$this->bd = new BdUtil();
		
		$this->cor_grafico = array('#F3BB2A', '#87CEEB', '#00FF00', '#f25004');
	}

	
	
	
	
	final function dependencias(){
	
		echo "
		
		<script src='".AGRCL_PATH_SMP."relatorios/relatorios.js' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".AGRCL_PATH_SMP."relatorios/relatorios.css' type='text/css' media='all'>";
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
	

	
	public function conteudo(){}
	
	
	


/*******************************************************/		
	
	
	
	
	final function setGrafico($dados, $titulo, $tipo, $y_moeda, $x_nome, $y_nome, $prefixo, $sufixo){
		
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
			$p->SetYDataLabelType('custom', array(new RelBaseGrafico, 'formataMoeda'), array($prefixo, $sufixo));
		else
			$p->SetYDataLabelType('custom', array(new RelBaseGrafico, 'formataInteiro'), array($prefixo, $sufixo));
		
		
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
	
	

	
	
	
	final function constroiGraficoDeDataPorDia($dados, $titulo, $tipo, $y_moeda, $x_nome, $y_nome, $prefixo, $sufixo, $legendas=null, $pdf=false){
		
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
	
	
	
	
	
	final function constroiGraficoDeDataPorMes($dados, $titulo, $tipo, $y_moeda, $x_nome, $y_nome, $prefixo, $sufixo, $legendas=null){
		
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
	

	
	
	
	
	final function formataMoeda($label, $arg){
		
		include_once AGRCL_CALC_PATH_ABS.'Calculo.class.php';
		
		$calculo = new Calculo();
		
		return $arg[0].$calculo->formataParaMostrar($label).$arg[1];

	}

	
	
	
	
	final function formataInteiro($label, $arg){
		
		include_once AGRCL_CALC_PATH_ABS.'Calculo.class.php';
		
		$calculo = new Calculo();
		
		return $arg[0].$calculo->formataParaMostrar($label, false).$arg[1];

	}

	
	
	
	
	final function getLegendas($legendas){
		
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
	

	
}
	
?>