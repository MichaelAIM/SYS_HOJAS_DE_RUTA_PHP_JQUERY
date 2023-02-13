<?PHP
	USE PHPMailer\PHPMailer\PHPMailer;
	USE PHPMailer\PHPMailer\Exception;

	require_once ('../Models/Mail/6.1.4/src/Exception.php');
	require_once ('../Models/Mail/6.1.4/src/PHPMailer.php');
	require_once ('../Models/Mail/6.1.4/src/SMTP.php');
	$mail = new PHPMailer(TRUE);

?>
