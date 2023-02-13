<?php
	require_once('../Models/PDF/watermark.php');
	// require_once('../Models/conector.class.php');
	class PDF_BINCARD extends Watermark{
		private $id_acta;
		public function __construct($orientacion = "P", $unit = "mm", $format = "Letter", $id){
			parent::__construct($orientacion, $unit, $format);
			$this->id_acta = $id;
			$this->AliasNbPages();
		}
		function SetWidths($w){
			//Set the array of column widths
			$this->widths=$w;
		}
		function SetAligns($a){
			//Set the array of column alignments
			$this->aligns=$a;
		}
		function Row($data,$rowTYPE,$borderTYPE){
			//Calculate the height of the row
			$nb=0;
			for($i=0;$i<count($data);$i++)		
				$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
			$h=8*$nb;
			//Issue a page break first if needed
			$this->CheckPageBreak($h);
			//Draw the cells of the row
			for($i=0;$i<count($data);$i++){
				$w=$this->widths[$i];
				$align = isset($this->aligns[$i]) ? $this->aligns[$i] : 'J';
				//Save the current position
				$x=$this->GetX();
				$y=$this->GetY();
				//Draw the border
				//Print the text
				switch($rowTYPE){
					case	"head"	:
						$this->SetTextColor(231,240,253);
						$this->SetFillColor(4,52,117);
						$this->MultiCell($w, 5, utf8_decode($data[$i]), $borderTYPE, $align, TRUE);
						break;
					case	"body"	:
						$this->MultiCell($w, 5, utf8_decode($data[$i]), 0, $align, FALSE);
						break;
					case	"footer"	:
						$this->MultiCell($w, 5, utf8_decode($data[$i]), $borderTYPE, $align, FALSE);
						break;
					default			:
						$this->MultiCell($w, 5, utf8_decode($data[$i]), 0, $align, FALSE);
						$this->Rect($x,$y,$w,$h);
						break;
				}
				//Put the position to the right of the cell
				$this->SetXY($x+$w,$y);
			}
			//Go to the next line
			$this->Ln($h);
		}
		function CheckPageBreak($h){
			//If the height h would cause an overflow, add a new page immediately
			if($this->GetY()+$h>$this->PageBreakTrigger){
				$this->AddPage($this->CurOrientation);
				$this->SetY(40);
			}
		}
		function Header(){
			//Logo	
			$x = $this->GetX();
			$y = $this->GetY();
			$this->SetTextColor(4,52,117);
			$this->SetFont('Arial','I',6);
			$this->Cell(0,5,'Pagina '.$this->PageNo().'/{nb}','B',0,'J');
			$this->SetY($y);
//				$this->Ln();
			$this->Cell(0,5,utf8_decode('Servicio de Salud Arica - SDAF  '),0,1,'C');				
			$this->SetFont('Arial','B',7);
			$this->SetY($y);
			//Título
			$this->Cell(151,5);
			$this->Cell(0,5,utf8_decode('Fecha impresión').": ".date("d-m-Y"),0,1,'L');
			$this->SetX(166);
			$this->Cell(0,5,utf8_decode('Hora impresión').": ".date("H:i:s"),0,1,'L');
			$this->SetY($y+6);
			$this->Image('../../img/logo gobierno/logo1.jpg',NULL,NULL,23);
			$y2 = $this->GetY();
			$x1 = $this->GetX()+23;
			$this->SetXY($x1,$y+5);
			$this->Cell(0,5,'SERVICIO DE SALUD ARICA',0,1,'L',false);
			$this->SetXY($x1,($y+8));
			$this->SetFont('','B',7);
			$this->Cell(0,5,'SUBDIRECCION RECURSOS HUMANOS',0,1,'L',false);
			$this->SetFont('','B',7);
			$this->SetXY($x1,($y+11));
			$this->Cell(0,5,'CALIDAD DE VIDA LABORAL',0,1,'L',false);
			$this->SetXY($x1,($y+14));
			$this->Cell(0,5,'SDAF - SSA',0,1,'L',false);
			$this->SetXY($x1,($y+14));
			$this->SetFont('Arial','B',15);
			//$pdf->SetX(160);
			$this->Cell(315,20,utf8_decode('N° '.$this->id_acta),0,0,"C");
			///////
			$this->Ln(20);
			$this->SetFont('Arial','BU',14);
			$this->Ln(-5);
			$this->SetTextColor(0,0,0);
			$this->Cell(0,10,utf8_decode('ACTA DE REGISTRO'),0,1,"C");
			$this->Ln(-5);
			$this->SetFont('Arial','',11);
			$this->Cell(0,10,utf8_decode("SISTEMA DENUNCIAS DE AGRECIONES A FUNCIONARIOS"),0,1,"C");			

			$this->Ln(5);
			
			$this->SetFont('Arial','B',55);
			$this->SetTextColor(228,237,250);
			$this->RotatedText(35,190,'Servicio de Salud Arica',45);	
			$this->SetFont('Arial','B',35);
			$this->RotatedText(110,155,utf8_decode('SDAF'),45);	
		}
			//Pie de página
		function Footer(){
				$this->SetY(-30);
				$this->SetFont('Arial','I',8);
				//Número de página
				$this->Cell(0,5,utf8_decode('______________________________________________________________________'),0,0,'C');
				$this->Ln();
				$this->SetTextColor(4,52,117);
				$this->Cell(0,5,utf8_decode('Servicio de Salud Arica  -  SDAF'),0,0,'C');

				// $this->Cell(0,5,utf8_decode('',0,0,'C')
			}
		function NbLines($w,$txt){
		//Computes the number of lines a MultiCell of width w will take
			$cw=&$this->CurrentFont['cw'];
			if($w==0)
				$w=$this->w-$this->rMargin-$this->x;
			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			$s=str_replace("\r",'',$txt);
			$nb=strlen($s);
			if($nb>0 and $s[$nb-1]=="\n")
				$nb--;
			$sep=-1;
			$i=0;
			$j=0;
			$l=0;
			$nl=1;
			while($i<$nb){
				$c=$s[$i];
				if($c=="\n"){
					$i++;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
					continue;
				}
				if($c==' ')
					$sep=$i;
				$l+=$cw[$c];
				if($l>$wmax){
					if($sep==-1){
						if($i==$j)
							$i++;
					}
					else
						$i=$sep+1;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
				}else
					$i++;
			}
			return $nl;
		}
		function CircularText($x, $y, $r, $text, $align='top', $kerning=120, $fontwidth=100){
			$kerning/=100;
			$fontwidth/=100;
			if($kerning==0) $this->Error('Please use values unequal to zero for kerning');
			if($fontwidth==0) $this->Error('Please use values unequal to zero for font width');
			//get width of every letter
			$t=0;
			for($i=0; $i<strlen($text); $i++){
				$w[$i]=$this->GetStringWidth($text[$i]);
				$w[$i]*=$kerning*$fontwidth;
				//total width of string
				$t+=$w[$i];
			}
			//circumference
			$u=($r*2)*M_PI;
			//total width of string in degrees
			$d=($t/$u)*360;
			$this->StartTransform();
			// rotate matrix for the first letter to center the text
			// (half of total degrees)
			if($align=='top'){
				$this->Rotate2($d/2, $x, $y);
			}else{
				$this->Rotate2(-$d/2, $x, $y);
			}
			//run through the string
			for($i=0; $i<strlen($text); $i++){
				if($align=='top'){
					//rotate matrix half of the width of current letter + half of the width of preceding letter
					if($i==0){
						$this->Rotate2(-(($w[$i]/2)/$u)*360, $x, $y);
					}
					else{
						$this->Rotate2(-(($w[$i]/2+$w[$i-1]/2)/$u)*360, $x, $y);
					}
					if($fontwidth!=1){
						$this->StartTransform();
						$this->ScaleX($fontwidth*100, $x, $y);
					}
					$this->SetXY($x-$w[$i]/2, $y-$r);
				}else{
					//rotate matrix half of the width of current letter + half of the width of preceding letter
					if($i==0){
						$this->Rotate2((($w[$i]/2)/$u)*360, $x, $y);
					}else{
						$this->Rotate2((($w[$i]/2+$w[$i-1]/2)/$u)*360, $x, $y);
					}
					if($fontwidth!=1){
						$this->StartTransform();
						$this->ScaleX($fontwidth*100, $x, $y);
					}
					$this->SetXY($x-$w[$i]/2, $y+$r-($this->FontSize));
				}
				$this->Cell($w[$i],$this->FontSize,$text[$i],0,0,'C');
				if($fontwidth!=1){
					 $this->StopTransform();
				}
			}
			$this->StopTransform();
		}
		function StopTransform(){
			//restore previous graphic state
			$this->_out('Q');
		}
		function StartTransform(){
			//save the current graphic state
			$this->_out('q');
		}
		function ScaleX($s_x, $x='', $y=''){
			$this->Scale($s_x, 100, $x, $y);
		}
		function ScaleY($s_y, $x='', $y=''){
			$this->Scale(100, $s_y, $x, $y);
		}
		function ScaleXY($s, $x='', $y=''){
			$this->Scale($s, $s, $x, $y);
		}
		function Scale($s_x, $s_y, $x='', $y=''){
			if($x === '')
				$x=$this->x;
			if($y === '')
				$y=$this->y;
			if($s_x == 0 || $s_y == 0)
				$this->Error('Please use values unequal to zero for Scaling');
			$y=($this->h-$y)*$this->k;
			$x*=$this->k;
			//calculate elements of transformation matrix
			$s_x/=100;
			$s_y/=100;
			$tm[0]=$s_x;
			$tm[1]=0;
			$tm[2]=0;
			$tm[3]=$s_y;
			$tm[4]=$x*(1-$s_x);
			$tm[5]=$y*(1-$s_y);
			//scale the coordinate system
			$this->Transform($tm);
		}
		function MirrorH($x=''){
			$this->Scale(-100, 100, $x);
		}
		function MirrorV($y=''){
			$this->Scale(100, -100, '', $y);
		}
		function MirrorP($x='',$y=''){
			$this->Scale(-100, -100, $x, $y);
		}
		
		function MirrorL($angle=0, $x='',$y=''){
			$this->Scale(-100, 100, $x, $y);
			$this->Rotate2(-2*($angle-90),$x,$y);
		}
		function TranslateX($t_x){
			$this->Translate($t_x, 0, $x, $y);
		}
		function TranslateY($t_y){
			$this->Translate(0, $t_y, $x, $y);
		}
		function Translate($t_x, $t_y){
			//calculate elements of transformation matrix
			$tm[0]=1;
			$tm[1]=0;
			$tm[2]=0;
			$tm[3]=1;
			$tm[4]=$t_x*$this->k;
			$tm[5]=-$t_y*$this->k;
			//translate the coordinate system
			$this->Transform($tm);
		}
		function SkewX($angle_x, $x='', $y=''){
			$this->Skew($angle_x, 0, $x, $y);
		}
		function SkewY($angle_y, $x='', $y=''){
			$this->Skew(0, $angle_y, $x, $y);
		}
		function Skew($angle_x, $angle_y, $x='', $y=''){
			if($x === '')
				$x=$this->x;
			if($y === '')
				$y=$this->y;
			if($angle_x <= -90 || $angle_x >= 90 || $angle_y <= -90 || $angle_y >= 90)
				$this->Error('Please use values between -90° and 90° for skewing');
			$x*=$this->k;
			$y=($this->h-$y)*$this->k;
			//calculate elements of transformation matrix
			$tm[0]=1;
			$tm[1]=tan(deg2rad($angle_y));
			$tm[2]=tan(deg2rad($angle_x));
			$tm[3]=1;
			$tm[4]=-$tm[2]*$y;
			$tm[5]=-$tm[1]*$x;
			//skew the coordinate system
			$this->Transform($tm);
		}
		function Rotate2($angle, $x='', $y=''){
			if($x === '')
				$x=$this->x;
			if($y === '')
				$y=$this->y;
			$y=($this->h-$y)*$this->k;
			$x*=$this->k;
			//calculate elements of transformation matrix
			$tm[0]=cos(deg2rad($angle));
			$tm[1]=sin(deg2rad($angle));
			$tm[2]=-$tm[1];
			$tm[3]=$tm[0];
			$tm[4]=$x+$tm[1]*$y-$tm[0]*$x;
			$tm[5]=$y-$tm[0]*$y-$tm[1]*$x;
			//rotate the coordinate system around ($x,$y)
			$this->Transform($tm);
		}
		function Transform($tm){
			$this->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F cm', $tm[0],$tm[1],$tm[2],$tm[3],$tm[4],$tm[5]));
		}
		function Circle($x, $y, $r, $style='D'){
			$this->Ellipse($x,$y,$r,$r,$style);
		}
		function Ellipse($x, $y, $rx, $ry, $style='D'){
			if($style=='F')
				$op='f';
			elseif($style=='FD' || $style=='DF')
				$op='B';
			else
				$op='S';
			$lx=4/3*(M_SQRT2-1)*$rx;
			$ly=4/3*(M_SQRT2-1)*$ry;
			$k=$this->k;
			$h=$this->h;
			$this->_out(sprintf('%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
				($x+$rx)*$k,($h-$y)*$k,
				($x+$rx)*$k,($h-($y-$ly))*$k,
				($x+$lx)*$k,($h-($y-$ry))*$k,
				$x*$k,($h-($y-$ry))*$k));
			$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
				($x-$lx)*$k,($h-($y-$ry))*$k,
				($x-$rx)*$k,($h-($y-$ly))*$k,
				($x-$rx)*$k,($h-$y)*$k));
			$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
				($x-$rx)*$k,($h-($y+$ly))*$k,
				($x-$lx)*$k,($h-($y+$ry))*$k,
				$x*$k,($h-($y+$ry))*$k));
			$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %s',
				($x+$lx)*$k,($h-($y+$ry))*$k,
				($x+$rx)*$k,($h-($y+$ly))*$k,
				($x+$rx)*$k,($h-$y)*$k,
				$op));
		}
	}
?>
