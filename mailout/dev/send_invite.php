<?php 
// EDIT THE 2 LINES BELOW AS REQUIRED
$send_email_to = "mark.guthrie@17ways.com.au";

function send_email($name,$email,$phone)
{
  global $send_email_to;

  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
  $headers .= "From: ".$email. "\r\n";
  $message = "<p style='font-family:Verdana;font-size:13px;'>";
  $message .= "<table noborder><tr><td><strong>Email Address: </strong><td>".$email."</tr>";
  $message .= "<tr><td><strong>Sender: </strong><td>".$name."</tr>";
  $message .= "<tr><td><strong>Phone: </strong><td>".$phone."</tr></table><br>";
  @mail($send_email_to, "Accepted Invite", $message,$headers);
  return true;
}

function validate($name,$email,$phone)
{
  $return_array = array();
  $return_array['success'] = '1';
  $return_array['name_msg'] = '';
  $return_array['email_msg'] = '';
  $return_array['phone_msg'] = '';
  if($email == '')
  {
    $return_array['success'] = '0';
    $return_array['email_msg'] = 'email is required';
  }
  else
  {
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    if(!preg_match($email_exp,$email)) {
      $return_array['success'] = '0';
      $return_array['email_msg'] = 'enter valid email.';  
    }
  }
  if($name == '')
  {
    $return_array['success'] = '0';
    $return_array['name_msg'] = 'name is required';
  }
  else
  {
    $string_exp = "/^[A-Za-z .'-]+$/";
    if (!preg_match($string_exp, $name)) {
      $return_array['success'] = '0';
      $return_array['name_msg'] = 'enter valid name.';
    }
  }

  return $return_array;
}

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

$return_array = validate($name,$email,$phone);

if($return_array['success'] == '1')
{
	send_email($name,$email,$phone);
    header( 'Location: success.html' ) ;

} else {
    header( 'Location: failure.html' ) ;
}
?>
