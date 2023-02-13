<?php 
// $key = "sySC4p4c1tAci0N2022";
// function encrypt($string, $key) {
function encrypt($string) {
   $key = "sySC4p4c1tAci0N2022";
   $result = '';
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
   }
   return base64_encode($result);
}

// function decrypt($string, $key) {
function decrypt($string) {
   $key = "sySC4p4c1tAci0N2022";
   $result = '';
   $string = base64_decode($string);
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)-ord($keychar));
      $result.=$char;
   }
   return $result;
}

?>
