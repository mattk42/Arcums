<html>
<head>
<title>ARCUMS 2.0</title>
<link href="../../themes/<?php echo $curtheme;?>/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php

$name = $_GET['name'];
$to = $_GET['email'];
$artist = $_GET['artist'];
$album = $_GET['album'];
$section = $_GET['section'];
$sectionnumber = $_GET['sectionnumber'];
$date = $_GET['date'];
$email = $_GET['email'];
$time = $_GET['time'];

$subject = "Radio X- Warning";
$body = "

$name,

Our records show that you were the last DJ to play the album below and it was not properly put away:

Artist: $artist
Album: $album
CD#: $section $sectionnumber
Date: $date
Time: $time

Please make sure that you put your CD's away so others don't have to do it for you.

Thank you,
E-Staff

This message was generated using the ARCUMS Hound Dog @$\$hole Tracker 1.0.
";

$dodgy_strings = array(
                "content-type:"
                ,"mime-version:"
                ,"multipart/mixed"
                ,"bcc:"
);

function is_valid_email($email) {
  return preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s]+\.+[a-z]{2,6}))$#si', $email);
}

function contains_bad_str($str_to_test) {
  $bad_strings = array(
                "content-type:"
                ,"mime-version:"
                ,"multipart/mixed"
		,"Content-Transfer-Encoding:"
                ,"bcc:"
		,"cc:"
		,"to:"
  );
  
  foreach($bad_strings as $bad_string) {
    if(eregi($bad_string, strtolower($str_to_test))) {
      echo "$bad_string found. Suspected injection attempt - mail not being sent.";
      exit;
    }
  }
}

function contains_newlines($str_to_test) {
   if(preg_match("/(%0A|%0D|\\n+|\\r+)/i", $str_to_test) != 0) {
     echo "newline found in $str_to_test. Suspected injection attempt - mail not being sent.";
     exit;
   }
} 


if (!is_valid_email($email)) {
  echo 'Invalid email submitted - mail not being sent.';
  exit;
}

contains_bad_str($email);
contains_bad_str($subject);
contains_bad_str(body);

contains_newlines($email);
contains_newlines($subject);

$headers = "From: musicdirector@wupx.com";
mail($email, $subject, $body, $headers);
echo "<div style='color:#ffffff' class='edit'>E-mail has been sent. <a href='javascript:closeWindow();'>Close Window</a></div>";

?>




<script language="javascript" type="text/javascript">

function closeWindow() {

window.open('','_parent','');

window.close();

}

</script>
</body>
</html>