<?php
if(isset($_POST['name'])){
	$font=realpath('arial.ttf');
	$image=imagecreatefromjpeg("certificate.jpg");
	$color=imagecolorallocate($image,19,21,22);
	
	$date="6th may 2020";
	imagettftext($image,20,0,450,595,$color,$font,$date);
	$file=time();
	$file_path="certificate/".$file.".jpg";
	$file_path_pdf="certificate/".$file.".pdf";
	imagejpeg($image,$file_path);
	imagedestroy($image);

	require('fpdf.php');
	$pdf=new FPDF();
	$pdf->AddPage();
	$pdf->Image($file_path,0,0,210,150);
	$pdf->Output($file_path_pdf,"F");

	include('smtp/PHPMailerAutoload.php');
	$mail=new PHPMailer();
	$mail->isSMTP();
	$mail->Host='smtp.gmail.com';
	$mail->Port=587;
	$mail->SMTPSecure="tls";
	$mail->SMTPAuth=true;
	$mail->Username="shruti1299@gmail.com";
	$mail->Password="shruti123";
	$mail->setFrom("shruti1299@gmail.com");
	$mail->addAddress($_POST['email']);
	$mail->isHTML(true);
	$mail->Subjet="Certificate Generated";
	$mail->Body="Certificate Generated";
	$mail->addAttachment($file_path_pdf);
	$mail->SMTPOptions=array("ssl"=>array(
		"verify_peer"=>false,
		"verify_peer_name"=>false,
		"allow_self_signed"=>false,
	));
	if($mail->send()){
		echo "Send";
	}else{
		echo $mail->ErrorInfo;
	}
}
?>
<form method="post">
	<input type="textbox" name="name"/>
	<input type="email" name="email" placeholder="Enter email"/>
	<input type="submit"/>
</form>