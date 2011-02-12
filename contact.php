<?php
	require("forms/contact_functions.php");

	if (!requestedByAjax()) { // show them the full page
		include("header.php");
?>
  <script src="js/contact.js" type="text/javascript" charset="utf-8"></script>
	<h2>Contact Us</h2>
	
    <div id="status">
      <?php 
	  $contact_info = "<p>Radio X is located in the University Center on NMU's campus</p><p>Radio X<br>
1204 University Center<br>
Marquette, MI 49855</p>\n\n";
	  $contact_info.= "<p>Use the form below to contact a member of our staff, or call us on the phone:</p>\n\n";
	  $contact_info.= "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Music Director --</b> 227-1845\n\n
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Office --</b> 227-1844\n\n
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Request Line --</b> 227-2348</p>\n\n";
	  $contact_info.= "<p>Staff office hours are also available on the ".
	  	"<a href=\"http://www.wupx.com/estaff.php\">staff</a> page.</p>\n\n";
	  $contact_info.= "<p><i>Fields marked with an asterisk (<span class=\"alert\">*</span>) are required.</i></p>\n\n";
	  
	  // don't show the contact information unless the user is seeing page for first time
	  echo (!isset($_POST["submit"])) ? $contact_info : processForm();

	  ?>      	  
    </div>

	<?php
		$contact = htmlspecialchars($_POST['contact']);
		$name = htmlspecialchars($_POST['name']);
		$email = htmlspecialchars($_POST['email']);
		$subject = htmlspecialchars($_POST['subject']);
		$message = htmlspecialchars($_POST['message']);
		if (!isset($_POST["sent"])) displayForm($contact, $name, $email, $subject, $message); 
	?>
  
<?php 
		include("footer.php");
	} else {
		echo processForm();
	}
	
?>

	<br />
