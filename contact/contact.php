<? /* BEGIN - SECTION 1 */ ?>
<?php
	
	require($_SERVER["DOCUMENT_ROOT"] . "/FORMfields/FORMfields.php"); 

	define("SLEEP", 4);
	define("FF_FORM_NAME", "contact");
	define("FF_FORM_TITLE", "contact");
	define("FF_STYLE_FILE", FF_ROOT_URL . "/styles/defaultWithStripes.css");
	define("FF_SHOW_W3C_VALIDATOR", false);
	define("FF_DISPLAY_CONFIRMATION", true);
	define("FF_INSERT_INTO_DB", true);
	define("FF_SEND_EMAIL", true);
	define("FF_EMAIL", "generalmanager@wupx.com");
	define("FF_CANCEL_URL", "http://www.wupx.com");
	define("FF_DONE_URL", "http://www.wupx.com");
	
	define("FF_ENTER", 1);
	define("FF_CONFIRM", 2);
	define("FF_DONE", 3);
	
	$action = FF_ENTER;

	$formBean = new FormBean();
	$formBean->addField(new TextField("Name", "Name", REQUIRED, 30, 2, null));
	$formBean->formFields["Name"]->setHelp("Tell us the name that you would like to be contacted by.");
	$formBean->addField(new EmailAddrField("Email", "E-Mail", REQUIRED));
	$formBean->formFields["Email"]->setHelp("Please supply us with your e-mail address.");
	$formBean->addField(new TextField("Location", "Where are you from?", NOT_REQUIRED, 50, 1, null));
	$formBean->formFields["Location"]->setHelp("Please tell us where you are located.");
	$formBean->addField(new TextAreaField("Message", "Message", REQUIRED, 1000, 10, 5, 40));
	$formBean->formFields["Message"]->setHelp("What would you like to tell us?");
	$formBean->addField(new SubmitField("_submit", "Submit"));
	
	if (FormField::getOrNull("_confirm", $_REQUEST) || FormField::getOrNull("_back", $_REQUEST)) {
		$formBean->setEditable(false);
	}

	$formBean->getParameters();
	
	if (FormField::getOrNull("_back", $_REQUEST)) {
		$formBean->setEditable(true);
	}
	
	if (FormField::getOrNull("_submit", $_REQUEST) || FormField::getOrNull("_confirm", $_REQUEST)) {
		if ($formBean->checkValues()) {
			if (FF_DISPLAY_CONFIRMATION && !FormField::getOrNull("_confirm", $_REQUEST)) {
				// CONFIRMATION OPERATION:
				$formBean->setEditable(false);
				$formBean->addField(new SubmitField("_back", "< Back"));
				$formBean->addField(new SubmitField("_confirm", "Confirm >"));
				$action = FF_CONFIRM;
			} else {
				// SUBMIT OPERATION:
				$action = FF_DONE;
				if (FF_SEND_EMAIL)
					$formBean->email(FF_EMAIL, FF_FORM_TITLE . " Submission");
				if (FF_INSERT_INTO_DB) {
					$formBean->addField(new DateTimeField("__inserted_on", "Inserted On", REQUIRED, null));
					$formBean->formFields["__inserted_on"]->getCurrentDateTime();
					$formBean->insertValuesIntoDb(FF_FORM_NAME);
				}
			}
		}
	} else if (FormField::getOrNull("clear", $_REQUEST)) {
		// CLEAR OPERATION:
		$formBean->clearAllData();
	} else if (FormField::getOrNull("cancel", $_REQUEST)) {
		// CANCEL OPERATION:
		header("Location: " . FF_CANCEL_URL);
	} else if (!$formBean->getButtonClicked()) {
		// DEFAULT OPERATION:
		// Enter default values here. Example: 
		// $formBean->setValue("name", "John");
	}
	
?>
<? /* END - SECTION 1 */ ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><?= FF_FORM_TITLE ?></title>
<? /* BEGIN - SECTION 2 */ ?>
<? if ($action == FF_DONE) { ?>
	<meta http-equiv="Refresh" content="<?= SLEEP ?>; url=<?= FF_DONE_URL ?>">
<? } ?>
<link rel="stylesheet" type="text/css" href="<?= FF_ROOT_URL ?>/FORMfields.css" />
<script type="text/javascript" src="<?= FF_ROOT_URL ?>/FORMfields.js"></script>
<link rel="stylesheet" type="text/css" href="<?= FF_ROOT_URL ?>/tableHelpers.css" />
<link rel="stylesheet" type="text/css" href="<?= FF_STYLE_FILE ?>" />
<style type="text/css">
	/* Sometimes it is useful to specify a label width */
	/*label.notValid, label.isValid, label.disabled, label.display {
		width: 150px;
	}*/
</style>
<? /* END - SECTION 2 */ ?>
</head>

<body onload="setFocus();" class="FORMfields">
<? /* BEGIN - SECTION 3 */ ?>
	<table>
	<tr>
	<td>
	<a name="ffStart"></a>
	<form action="#ffStart" method="post" enctype="multipart/form-data">
		<div class="FORMfields">
			<h1 class="FORMfields"><?= FF_FORM_TITLE ?></h1>
			<? if ($action == FF_DONE) { ?>
				<br />
				<div style="font: normal normal bold 18px verdana;">
					Thank you for your submission.
				</div>
				<div style="margin-top:20px;font: normal normal bold 12px verdana;">
					Note: Please click <a href="/">here</a> if your browser does not redirect in <?= SLEEP ?> seconds.
				</div>
				<br />
			<? } else {
				if ($action == FF_CONFIRM) {
					?>
						<h3 class="FORMfields">
							Please confirm that the following data is correct:
						</h3>
					<?
				} else {
					?>
						<div style="text-align:right;">
							<span class="required">*</span><span class="help" style="padding-left:0px;font-size:9px;"> - required</span>
						</div>
					<?
				}
				
				echo $formBean->getTableTag();
			} ?>
			
		</div>
		<div style="text-align:left;margin:10px 3px 10px 3px;">
			<a style="font: normal normal normal 9px verdana,sans-serif;" href="http://www.formfields.com" target="_blank">Form Generated by FORMgen</a>
		</div>
		<? if (FF_SHOW_W3C_VALIDATOR) { ?>
			<div>
				<a href="http://validator.w3.org/check?uri=referer"><img border="0" src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0!" height="31" width="88" /></a>
				<a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!" /></a>
			</div>
		<? } ?>
	</form>
	</td>
	</tr>
	</table>
<? /* END - SECTION 3 */ ?>
</body>

</html>
