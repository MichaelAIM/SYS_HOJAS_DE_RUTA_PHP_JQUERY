<?php
require('rotation.php');

class Watermark extends PDF_Rotate{

	public function __construct($orientacion = "P", $unit = "mm", $format = "Letter"){
			
			parent::__construct($orientacion, $unit, $format);
			
	}

function RotatedText($x, $y, $txt, $angle)
{
	//Text rotated around its origin
	$this->Rotate($angle,$x,$y);
	$this->Text($x,$y,$txt);
	$this->Rotate(0);
}
}
?>
