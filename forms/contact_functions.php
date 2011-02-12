<?php
	function validateForm() {

		$form_items = array(
			"contact"  => array("regex" => "/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/",
								"error" => "You did not select a valid contact."),

			"name"  => array("regex" => "/^([a-zA-Z\' '-]+)$/",
							 "error" => "Your name contains invalid characters, or was left blank."),

			"email" => array("regex" => "/^[A-Za-z0-9](([_\.\-\+]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/",
							 "error" => "You have entered an invalid email address."),

			"subject"  => array("regex" => "/^.{0,60}$/",
								"error" => "Your subject must not exceed 60 characters."),

			"message" => array("regex" => "/^.{10,2000}$/s",
							   "error" => "Your message is either too short or exceeds 2000 characters.")
		);

	   $errors = array();

	   foreach ($form_items as $item => $keys) {
		   if (!preg_match($keys["regex"], trim($_POST[$item]))) {
				   $errors[] = $keys["error"];
		   }
	   }

	   return $errors;
	}

	function email($name, $email, $contact, $subject, $message) {
		$name = htmlspecialchars_decode($name);
		$email = htmlspecialchars_decode($email);
		$contact = htmlspecialchars_decode($contact);
		$subject = htmlspecialchars_decode($subject);
		$message = htmlspecialchars_decode($message);
			
		$body = "$name ($email) has sent you a message from the WUPX website:\n\n";
		$body.= $message;

		$subject = empty($subject) ? "WUPX Staff Mail" : $subject;

		$headers = "From: $name <$email>\r\n";
		$headers.= "Reply-To: $email\r\n";
		$headers.= "Return-Path: $email\r\n";

		if (mail($contact, $subject, $body, $headers)) {
			$_POST["sent"] = "true";
			
			echo "<p><b>Thanks!</b></p>";
			echo "<p style=\"color: #fff;\">Your message has been received. &nbsp;
				  We will get back to you as soon as possible.</p>";
		} else {
		    echo "<p><b>Doh!</b></p>";
			echo "<p style=\"color: #ff0000;\">There was an error sending your message.  Please try again.";
		}
	}

	function processForm() {
		$errors = validateForm();
		
		$response = "";

		if (sizeof($errors) > 0) {
			$response = "<p class=\"alert\"><b>Oops!</b></p>\n";
			$response.= "<p>The following errors occurred while processing your message:</p>\n";

			$response.= "<ul>\n";

			foreach ($errors as $error) {
				$response.= "<li class=\"alert\">$error</li>\n";
			}

			$response.= "</ul>\n";

			$response.= "<p><i>Fields marked with an asterisk (<span class=\"alert\">*</span>) are required.</i></p>\n";

		} else {
			email(trim($_POST["name"]), trim($_POST["email"]), $_POST["contact"],
				  trim($_POST["subject"]), trim($_POST["message"]));
		}
		
		return $response;
	}

	function displayForm($contact = "", $name = "", $email = "", $subject = "", $message = "") {
		$contacts = array(
			array ("title" => "Music Director", "email" => "musicdirector@wupx.com"), 
			array ("title" => "Station Manager", "email" => "stationmanager@wupx.com"), 
			array ("title" => "General Manager", "email" => "generalmanager@wupx.com"), 
			array ("title" => "Station Engineer", "email" => "engineer@wupx.com"),
			array ("title" => "IT Director", "email" => "itdirector@wupx.com"), 
			array ("title" => "Production Director", "email" => "production@wupx.com"), 
			array ("title" => "Promotions and Events Director", "email" => "promotions@wupx.com"), 
			array ("title" => "Training Director", "email" => "training@wupx.com"), 
			array ("title" => "Public Affairs Coordinator", "email" => "program@wupx.com"),
			array ("title" => "Art Director", "email" => "artdirector@wupx.com")
		);
			
		$contact_list = "";
		
		foreach ($contacts as $contact_item) {
			$contact_list .= "	  <option value=\"". $contact_item["email"]. "\"";
			$contact_list .= ($contact_item["email"] == $contact) ? " selected=\"selected\">" : ">";
			$contact_list .= $contact_item["title"]. "</option>\n";
		}

   		echo <<<FORM
	<form id="contact_form" method="post" action="{$_SERVER['PHP_SELF']}">
	<label for="contact">Contact: <span class="alert">*</span></label>
	<select id="contact" name="contact" tabindex="1">{$contact_list}
	</select>
	<br />

    <label for="name">Name: <span class="alert">*</span></label>
    <input type="text" id="name" name="name" value="$name" size="35" maxlength="40" tabindex="2" />
	<br />

    <label for="email">Email: <span class="alert">*</span></label>
    <input type="text" id="email" name="email" value="$email" size="35" maxlength="35" tabindex="3" />
	<br />

	<label for="subject">Subject: </label>
	<input type="text" id="subject" name="subject" value="$subject" size="50" maxlength="60" tabindex="4" />
	<br />

  <label for="message">Message: <span class="alert">*</span></label>
	<textarea name="message" id="message" cols="55" rows="6" tabindex="5">$message</textarea>
	<br />

    <label for="notice"></label>
		<input type="text" id="notice" name="notice" class="alert" readonly="readonly" value="Please do not use this form to request songs!" size="50" />
	<br />

		<label for="submit"></label>
		<button type="submit" id="submit" name="submit" tabindex="6">Send Message</button>
	<br />
  </form>
FORM;
	}
	
	function requestedByAjax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
	}

?>