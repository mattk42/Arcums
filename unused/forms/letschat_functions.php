<?php
	function validateForm() {

		$form_items = array(
			"contact"  => array("regex" => "/^([a-zA-Z\' '-]+)$/",
							 "error" => "You did not select a valid contact."),

			"name"  => array("regex" => "/^([a-zA-Z\' '-]+)$/",
							 "error" => "Your name contains invalid characters, or was left blank."),

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

	function email($name, $contact, $message) {
		$name = htmlspecialchars_decode($name);
		$contact = htmlspecialchars_decode($contact);
		$message = htmlspecialchars_decode($message);
			
		$body = "$name has sent in the following question for $contact from the WUPX website:\n\n";
		$body.= $message;

		$subject = "WUPX Let's Chat Question for $contact";

		$headers = "From: $name <wupxweb@nmu.edu>\r\n";
		$headers.= "Reply-To: <wupxweb@nmu.edu>\r\n";
		$headers.= "Return-Path: <wupxweb@nmu.edu>\r\n";

		if (mail("generalmanager@wupx.com", $subject, $body, $headers)) {
			$_POST["sent"] = "true";
			
			echo "<p><b>Thanks!</b></p>";
			echo "<p style=\"color: #fff;\">Your Let's Chat question has been received.</p>";
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
			email(trim($_POST["name"]), $_POST["contact"], trim($_POST["message"]));
		}
		
		return $response;
	}

	function displayForm($contact = "", $name = "", $message = "") {
		$contacts = array(
			array ("name" => "Chris Greer (4.16.08)", "shortName" => "Chris Greer"),
			array ("name" => "Carl Holm (4.17.08)", "shortName" => "Carl Holm")			
		);
			
		$contact_list = "";
		
		foreach ($contacts as $contact_item) {
			$contact_list .= "	  <option value=\"". $contact_item["shortName"]. "\"";
			$contact_list .= ($contact_item["shortName"] == $contact) ? " selected=\"selected\">" : ">";
			$contact_list .= $contact_item["name"]. "</option>\n";
		}

   		echo <<<FORM
	<form id="contact_form" method="post" action="{$_SERVER['PHP_SELF']}">
	<label for="contact">Contact: <span class="alert">*</span></label>
	<select id="contact" name="contact" tabindex="1">{$contact_list}
	</select>
	<br />

    <label for="name">Your Name: <span class="alert">*</span></label>
    <input type="text" id="name" name="name" value="$name" size="35" maxlength="40" tabindex="2" />
	<br />

    <label for="message">Question: <span class="alert">*</span></label>
	<textarea name="message" id="message" cols="55" rows="6" tabindex="5">$message</textarea>
	<br />

	<label for="submit"></label>
	<button type="submit" id="submit" name="submit" tabindex="6">Send Question</button>
  </form>
FORM;
	}
	
	function requestedByAjax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
	}

?>