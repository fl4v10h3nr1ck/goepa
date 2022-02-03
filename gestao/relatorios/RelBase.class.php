<?php

include_once AGRCL_PATH_ABS.'Base.class.php';
include_once AGRCL_BD_PATH_ABS."BdUtil.class.php";

define("STYLE_TABCAB", "border:solid 1px #000;background:#FFFFFF;color:#000000");


class RelBase extends Base{


public $bd;

public $fonte_maior;
public $fonte_normal;
public $fonte_menor;
public $fonte_micro;
public $fonte_pico;


	function __construct() {
		
		parent::__construct();
		
		$this->bd = new BdUtil();
		
		$this->fonte_maior = "14px";
		$this->fonte_normal = "12px";
		$this->fonte_menor = "10px";
		$this->fonte_micro = "8px";
		$this->fonte_pico = "7px";
	}

	
	
	
	
	final function dependencias(){
	
		echo "
		
		<script src='".AGRCL_PATH_SMP."relatorios/relatorios.js' type='text/javascript'></script>
	
		<link rel='stylesheet' href='".AGRCL_PATH_SMP."relatorios/relatorios.css' type='text/css' media='all'>";
	}

	
	

	
	final function getRodape(){
		
		return "<div align='center' style='font-family:Times;font-size:12px;background:#000;color:#FFF'>
					Copyright ".date("Y")." Agrocontrole. Todos os Direitos Reservados.
				</div>";
	}

	
	
	
	
	
	final function getCabecalho(){
		
		include_once AGRCL_PATH_ABS.'geral/empresa/BeanEmpresa.class.php';
		include_once AGRCL_PATH_ABS.'geral/endereco/BeanEndereco.class.php';
		include_once AGRCL_CMS_PATH_ABS.'Comuns.class.php';
		
		$empresa = $this->bd->getPorId(new BeanEmpresa(), $this->id_empresa);
		$comuns = new Comuns();
		
		$rel = "
				<table width='100%' style='font-family:Times;font-size:".$this->fonte_normal."'>
					<tr>
						<td width='40%' align='left'>
							<img src='".AGRCL_PATH_IMGS."banner.jpg' height='70px'>
						</td>
						<td width='60%' align='right' style='font-family:Times;font-size:".$this->fonte_menor."'>";
			
		if(is_object($empresa))	{							
				
			$endereco = $this->bd->getPorId(new BeanEndereco(), $empresa->fk_endereco);
				
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

	
	
	
	
	final function geraRelatorio($conteudo, $mostrar_rodape=true, $orientacao_paisagem=false){

		if(strlen($conteudo)<MAX_CARACTERES_POR_REL){
	
			if($orientacao_paisagem)
				$mpdf= new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
			else
				$mpdf= new \Mpdf\Mpdf();
			
			$nome_arquivo = "REL_".rand(1, 1000000).".pdf";
				
			$mpdf->SetTitle($this->getTitulo());
					
			$mpdf->WriteHTML($conteudo);
				
			if($mostrar_rodape)
				$mpdf->SetHTMLFooter($this->getRodape());
				
			$mpdf->Output($nome_arquivo, 'I');
		}
		else
			echo $conteudo;
	}
	
	
	
	
	
	final function setTemplate($conteudo, $mostrar_cabecalho=true){
		
		
		$rel = "<div align='left'>";
		
		if($mostrar_cabecalho)
			$rel .= $this->getCabecalho();
		
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
	
	
	
	
	
	final function getAssinatura($data, $tipo=TIPO_ASSINA_ENGAGRO){
		
		include_once AGRCL_PATH_ABS.'geral/assinaturas/BeanAssinatura.class.php';
		
		$assinatura = null;
		
		$profissao = "";
		
		if($tipo==TIPO_ASSINA_ENGAGRO){
			
			$assinatura = $this->bd->getPrimeiroOuNada(new BeanAssinatura(), null, "###.tipo=".TIPO_ASSINA_ENGAGRO." and ###.data_inicio<='".$data."' and ###.data_fim>='".$data."'", null);
			$profissao = NOME_ASSINA_ENGAGRO;
		}
		else if($tipo==TIPO_ASSINA_TECAGRI){
			
			$assinatura = $this->bd->getPrimeiroOuNada(new BeanAssinatura(), null, "###.tipo=".TIPO_ASSINA_TECAGRI." and ###.data_inicio<='".$data."' and ###.data_fim>='".$data."'", null);
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
	
	
	
	
	
	/**************************************************************************/
	
	
	public function getTitulo(){}
	
	
	
	public function conteudo(){}
	
	
	


/*******************************************************/		
	
	
	
	
}
	
?>