<?php

// Messages
$error_messages = array();
$success_messages = array();



// Form sent
if(isset($_POST['submit_add']))
{

  if(!empty($_POST))
  {
    // Default value for label_task
    


    $first_name       = $_POST['first-name'];
    $last_name        = $_POST['last-name'];
    $email_sub        = $_POST['email-sub'];


    // first name task errors
    if(empty($first_name))
    $error_messages['first-name'] = 'should not be empty';

    // Last name task errors
    if(empty($last_name))
    $error_messages['last-name'] = 'should not be empty';

    // email sub task errors
    if(empty($email_sub))
    $error_messages['email-sub'] = 'should not be empty';

    // No errors


    if(empty($error_messages))
    {

      // Prepare the INSERT
      $prepare = $pdo->prepare('INSERT INTO subscribers (first_name, last_name, email_sub) VALUES (:first_name, :last_name, :email_sub)');

      // Set values
      $prepare->bindValue('first_name', $first_name);
      $prepare->bindValue('last_name', $last_name);
      $prepare->bindValue('email_sub', $email_sub);

      // Execute INSERT
      $prepare->execute();

      // Add success message
      $success_messages[] = 'registered';

      // Reset values
      $_POST['first-name']           = '';
      $_POST['last-name']            = '';
      $_POST['email-sub']            = '';


      /* CONFIG */
      /* MAILING */
      $to             = $email_sub;
      $gmail_username = 'narga.marvis@gmail.com';
      $gmail_password = 'Toshiro1';
      $subject        = 'Confirmation de l\'abonnement';
      $email          = 'views/includes/template/EarthImpact.html';

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



    }
  }
}

// No data sent
else
{
  // Default values add
  $_POST['first-name']           = '';
  $_POST['last-name']            = '';
  $_POST['email-sub']            = '';
}


