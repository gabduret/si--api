<?php

/* CONFIG */
// $to             = 'simon.bruno.77@gmail.com';
// $gmail_username = 'smtp.hetic.p2019@gmail.com';
// $gmail_password = 'azerty93';
$to             = 'gabrieldur12@gmail.com';
$gmail_username = 'narga.marvis@gmail.com';
$gmail_password = 'Toshiro1';
$subject        = 'Confirmation de l\'abonnement';
$email          = '../template/EarthImpact.html';

/* SCRIPT */
require_once 'phpmailer/class.phpmailer.php';
$html = file_get_contents($email);

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth   = true;
$mail->SMTPSecure = 'ssl';
$mail->Host       = 'smtp.gmail.com';
$mail->Port       = 465;
$mail->Username   = $gmail_username;
$mail->Password   = $gmail_password;
$mail->Subject    = $subject;
// $mail->SetFrom($to);
$mail->MsgHTML($html);
$mail->AddAddress($to);

if(!$mail->Send())
{
    echo 'Error: ' . $mail->ErrorInfo;
}
else
{
    echo 'Votre e-mail de confirmation a bien été envoyé !';
}
