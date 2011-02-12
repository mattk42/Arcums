<? /* BEGIN - SECTION 1 */ ?>
<?php
	
	require($_SERVER["DOCUMENT_ROOT"] . "/FORMfields/FORMfields.php");
	
	//$GLOBALS["FF_VERBOSE"] = true; // For debugging

	define("FF_FORM_NAME", "contact");
	define("FF_FORM_TITLE", "contact");
	define("FF_STYLE_FILE", FF_ROOT_URL . "/styles/defaultWithStripes.css");
	define("FF_SHOW_W3C_VALIDATOR", false);
	define("FF_SELECT_CLAUSE", "Name AS 'Name', Email AS 'E-Mail', Location AS 'Where are you from?'");
	
	// What permissions are enabled?
	define("FF_CAN_SEARCH", true);
	define("FF_CAN_EDIT", true);
	define("FF_CAN_DELETE", true);
	define("FF_CAN_NEW", true);
	define("FF_CAN_IMPORT", true);
	define("FF_CAN_EXPORT", true);

	if ((FormField::getOrNull("action", $_REQUEST) === "export" && FF_CAN_EXPORT)
	    || FormField::getOrNull("action", $_REQUEST) === "template") {

		// OPERATIONS FOR EXPORTING RECORDS:

		// Define the headers for the outputted text file
		header('Content-Type: text/plain');

		// Create and populate a TableSet
		$tableSet = new TableSet();
		
		if (FormField::getOrNull("action", $_REQUEST) === "template") {
			$tableSet->loadResultSet(gmysql_query("SELECT * FROM " . FF_FORM_NAME . " WHERE 1=2")); 
			header("Content-Disposition: attachment; filename=\"" . FF_FORM_NAME . "_template.txt\"");
		} else {
			$tableSet->loadResultSet(gmysql_query("SELECT * FROM " . FF_FORM_NAME)); 
			header("Content-Disposition: attachment; filename=\"" . FF_FORM_NAME . ".txt\"");
		}

		// Print the TableSet's data
		echo $tableSet->getTabDeliminatedData(false);

		exit(1);
	} 

	$formBean = new FormBean();
	$formBean->addField(new FormField("__id", "ID", NOT_REQUIRED));
	$formBean->addField(new DateTimeField("__inserted_on", "Inserted On", NOT_REQUIRED, " "));
	$formBean->formFields["__inserted_on"]->setEditable(false);
	$formBean->addField(new DateTimeField("__timestamp", "Last Updated", NOT_REQUIRED, " "));
	$formBean->formFields["__timestamp"]->setEditable(false);
	// Field Specification - BEGIN
	$formBean->addField(new TextField("Name", "Name", REQUIRED, 30, 2, null));
	$formBean->formFields["Name"]->setHelp("Tell us the name that you would like to be contacted by.");
	$formBean->addField(new EmailAddrField("Email", "E-Mail", REQUIRED));
	$formBean->formFields["Email"]->setHelp("Please supply us with your e-mail address.");
	$formBean->addField(new TextField("Location", "Where are you from?", NOT_REQUIRED, 50, 1, null));
	$formBean->formFields["Location"]->setHelp("Please tell us where you are located.");
	$formBean->addField(new TextAreaField("Message", "Message", REQUIRED, 1000, 10, 5, 40));
	$formBean->formFields["Message"]->setHelp("What would you like to tell us?");
	// Field Specification - END
	$formBean->addField(new SubmitField("save", "Save"));
	$formBean->addField(new SubmitField("cancel", "Cancel"));
	
	//echo "1id = " . FormField::getOrNull("__id", $_REQUEST);
	if (FormField::getOrNull("action", $_REQUEST) === "new") {
	
		// OPERATIONS FOR CREATING A NEW RECORD:
	
		$formBean->formFields["__id"]->setIsHidden(true);
		$formBean->formFields["__timestamp"]->setIsHidden(true);
		$formBean->formFields["__inserted_on"]->setIsHidden(true);
	
		$formBean->getParameters();

		if ($formBean->getValue("save") && FF_CAN_NEW) {
			if ($formBean->checkValues()) {
				$formBean->addField(new DateTimeField("__inserted_on", "Inserted On", REQUIRED, null));
				$formBean->formFields["__inserted_on"]->getCurrentDateTime();
				$formBean->insertValuesIntoDb(FF_FORM_NAME);
				header("Location: " . $_SERVER["PHP_SELF"]);
			}
		} else if ($formBean->getValue("cancel")) { 
			header("Location: " . $_SERVER["PHP_SELF"]);
		}
	
	} else if (FormField::getOrNull("__id", $_REQUEST)) {
	
		if (FormField::getOrNull("action", $_REQUEST) === "delete") {
		
			// OPERATIONS FOR DELETING A RECORD:
		
			$confirmBean = new FormBean();
			$confirmBean->addField(new DescriptionField("sure", "Are you sure you want to delete this record?"));
			$confirmBean->addField(new SubmitField("yes", " Yes "));
			$confirmBean->addField(new SubmitField("no", " No "));
			$confirmBean->getParameters();
			if ($confirmBean->getValue("yes") && FF_CAN_DELETE) {
				gmysql_query("DELETE FROM " . FF_FORM_NAME . " WHERE __id=" . $_REQUEST["__id"]);
				header("Location: " . $_SERVER["PHP_SELF"]);
			} else if ($confirmBean->getValue("no")) {
				header("Location: " . $_SERVER["PHP_SELF"]);
			}
			
		} else {
		
			// OPERATIONS FOR UPDATING A RECORD:

			$formBean->getParameters();
			
			if ($formBean->getValue("save")) {
				if ($formBean->checkValues()) {
					$formBean->formFields["__timestamp"]->setIsData(false);
					$formBean->updateValuesInDb(FF_FORM_NAME, "WHERE __id=" . $_REQUEST["__id"]);
					header("Location: " . $_SERVER["PHP_SELF"]);
				}
			} else if ($formBean->getValue("cancel")) { 
				header("Location: " . $_SERVER["PHP_SELF"]);
			} else if (!$formBean->getButtonClicked()) {
				$formBean->loadValuesFromDb(FF_FORM_NAME, "WHERE __id=" . $_REQUEST["__id"]);
			}
		}
		
	} 
	
	if (!FormField::getOrNull("__id", $_REQUEST)) {
	
		$uploadBean = new FormBean();
		$uploadBean->addField(new UploadField("upload", "Upload File", REQUIRED_NO_LOOK, "2000000"));
		$uploadBean->addField(new SubmitField("upload_now", "Upload Now"));
		$uploadBean->getParameters();
		if ($uploadBean->getValue("upload_now") && FF_CAN_IMPORT) {
			if ($uploadBean->checkValues()) {
			
				// OPERATIONS FOR IMPORTING RECORDS:
			
				$entries = FfHelper::parseTabDeliminatedFile($uploadBean->formFields["upload"]->uploadedFile);
				for ($i = 1; $i < sizeof($entries); $i++ ) {
					if ($entries[$i][0] === "") // ignore blank lines
						continue;
					$assocArray = FfHelper::array_combine($entries[0], $entries[$i]);
					//print_r($assocArray);
					$formBean->loadDbValues($assocArray);
					if (getSimpleValue("SELECT __id FROM " . FF_FORM_NAME . " WHERE __id='" . $entries[$i][0] . "'")) {
						$formBean->updateValuesInDb(FF_FORM_NAME, "WHERE __id='" . $entries[$i][0] . "'");
						//gmysql_update($assocArray, FF_FORM_NAME, "WHERE __id='" . $entries[$i][0] . "'");
					} else {
						$formBean->insertValuesIntoDb(FF_FORM_NAME);
						//gmysql_insert($assocArray, FF_FORM_NAME);
					}
				}
			}
		}
		
		// OPERATIONS FOR SEARCHING RECORDS:
		
		$searchBean = new FormBean();
		$searchBean->addField(new TextField("search_str", "Search", NOT_REQUIRED, 40, 1, 20));
		$searchBean->addField(new SubmitField("search", "Search"));
		$fieldNames = array();
		foreach ($formBean->formFields as $field) {
			if ($field->getIsData()) {
				$fieldNames[$field->getName()] = $field->getLabel();
			}
		}
		$searchBean->addField(new DropDownField("field_name", "Field Name", NOT_REQUIRED, $fieldNames, array_keys($fieldNames), "[Select One]"));
		$searchBean->setEnterTabs(false);
		$searchBean->getParameters();
		
		$resultsBean = new FormBean();

		$sql = 
			"SELECT "
				. "CONCAT('<a href=\"?__id=',__id,'\">View</a>') AS ' ',"
				. (FF_CAN_EDIT ? "CONCAT('<a href=\"?__id=',__id,'&amp;action=edit\">Edit</a>') AS '  '," : "")
				. (FF_CAN_DELETE ? "CONCAT('<a href=\"?__id=',__id,'&amp;action=delete\">Delete</a>') AS '   '," : "")
				. "__id AS 'ID',"
				. FF_SELECT_CLAUSE . ","
				. "DATE_FORMAT(__inserted_on, '%Y-%m-%d %l:%i %p') AS 'Inserted On',"
				. "DATE_FORMAT(__timestamp, '%Y-%m-%d %l:%i %p') AS 'Last Updated' "
			. "FROM " . FF_FORM_NAME;
		
		if ($searchBean->getValue("search_str") && $searchBean->getValue("field_name"))
			$sql .= " WHERE " . $searchBean->getValue("field_name") . " LIKE '%" . $searchBean->getValue("search_str") . "%'";

		// Create the SearchResultsField and add it to our FormBean
		$resultsBean->addField(new SearchResultsField("list", "List Records:", $sql, 20));

		// Enable column sorting
		$resultsBean->formFields["list"]->tableSet->enableSort = true;

		// Capture and process any button presses
		$resultsBean->getParameters();
		
	}
	
?>
<? /* END - SECTION 1 */ ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><?= FF_FORM_TITLE ?></title>
<? /* BEGIN - SECTION 2 */ ?>
<link rel="stylesheet" type="text/css" href="<?= FF_ROOT_URL ?>/FORMfields.css" />
<script type="text/javascript" src="<?= FF_ROOT_URL ?>/FORMfields.js"></script>
<link rel="stylesheet" type="text/css" href="<?= FF_ROOT_URL ?>/tableHelpers.css" />
<link rel="stylesheet" type="text/css" href="<?= FF_STYLE_FILE ?>" />
<? /* END - SECTION 2 */ ?>
</head>

<body class="FORMfields">
<? /* BEGIN - SECTION 3 */ ?>
	<h1 class="FORMfields" style="margin:0px 0px 15px 0px;"><?= FF_FORM_TITLE ?> - Control Panel</h1>
	<table>
	<tr>
	<td>
	<?
		if (FormField::getOrNull("action", $_REQUEST) === "new" && FF_CAN_NEW) {
			?>
				<form name="newForm" action="" method="post" enctype="multipart/form-data">
					<div class="FORMfields">
						<h1 class="FORMfields">New Record</h1>
						<?= $formBean->getTableTag(); ?>
					</div>
				</form>
			<?
		} else if (FormField::getOrNull("__id", $_REQUEST)) {
			if (FormField::getOrNull("action", $_REQUEST) === "delete" && FF_CAN_DELETE) {
				?>
					<form name="deleteForm" action="" method="post" enctype="multipart/form-data">
						<div class="FORMfields">
							<h1 class="FORMfields">Delete Record</h1>
							<?= $confirmBean->getTableTag(); ?>
						</div>
					</form>
				<?
			} else if (FormField::getOrNull("action", $_REQUEST) === "edit" && FF_CAN_EDIT) {
				?>
					<form name="editForm" action="" method="post" enctype="multipart/form-data">
						<div style="margin:0px 0px 10px 5px;" class="ffField"><a href="?">&laquo; Go Back to List</a></div>
						<? if (FF_CAN_DELETE) { ?>
							<div style="margin:0px 0px 10px 5px;" class="ffField"><a href="?action=delete&amp;__id=<?= $_REQUEST["__id"] ?>">Delete Record</a></div>
						<? } ?>
						<div class="FORMfields">
							<h1 class="FORMfields">Edit Record</h1>
							<?= $formBean->getTableTag(); ?>
						</div>
					</form>
				<?
			} else {
				?>
					<form name="viewForm" action="" method="post" enctype="multipart/form-data">
						<div style="margin:0px 0px 10px 5px;" class="ffField"><a href="?">&laquo; Go Back to List</a></div>
						<? if (FF_CAN_EDIT) { ?>
							<div style="margin:0px 0px 10px 5px;" class="ffField"><a href="?__id=<?= $_REQUEST["__id"] ?>&amp;action=edit">Edit this record</a></div>
						<? } ?>
						<? if (FF_CAN_DELETE) { ?>
							<div style="margin:0px 0px 10px 5px;" class="ffField"><a href="?action=delete&amp;__id=<?= $_REQUEST["__id"] ?>">Delete Record</a></div>
						<? } ?>
						<div class="FORMfields">
							<h1 class="FORMfields">View Record</h1>
							<?= $formBean->getDisplayTableTag(); ?>
						</div>
					</form>
				<?
			}
		} else {
			?>
				<? if (FF_CAN_SEARCH) { ?>
					<form name="searchForm" action="" method="post" enctype="multipart/form-data">
						<div class="FORMfields">
							<h1 class="FORMfields">Search Records:</h1>
							<?= $searchBean->getIfError(); ?>
							<table class="tableHelpers">
								<tr class="tableHelpersFormFields">
									<td class="tableHelpersFormFields1" >
										<div class="cell">
											<?= $searchBean->getLabelTag("search_str") ?>
										</div>
									</td>
									<td class="tableHelpersFormFields1" >
										<div class="cell">
											<?= $searchBean->getFieldTag("field_name") ?>
										</div>
									</td>
									<td class="tableHelpersFormFields1" >
										<div class="cell">
											<?= $searchBean->getFieldTag("search_str") ?>
										</div>
									</td>
									<td class="tableHelpersFormFields1" >
										<div class="cell">
											<?= $searchBean->getFieldTag("search") ?>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</form>
				<? } ?>
				<? if (FF_CAN_NEW) { ?>
					<div style="margin:0px 0px 10px 5px;" class="ffField"><a href="?action=new">New Record</a></div>
				<? } ?>
				<form name="resultsForm" action="" method="post">
					<?= $resultsBean->getFieldTag("list"); ?>
				</form>
				<? if (FF_CAN_IMPORT) { ?>
					<form name="uploadForm" action="?action=upload#uploadStart" method="post" enctype="multipart/form-data">
						<a name="uploadStart"></a> 
						<div class="FORMfields">
							<h1 class="FORMfields">Import Records:</h1>
							<div class="ffField" style="margin:10px 0px 0px 10px;">
								Directions:
								<ul style="margin:0px 10px 5px 25px;">
									<li><a href="?action=template">Click here to download <?= FF_FORM_NAME ?>_template.txt</a>.</li>
									<li>Open MS Excel and select File->Open and open <?= FF_FORM_NAME ?>_template.txt.</li>
									<li>Some values may show up as "#####" or may appear to be incorrectly formatted. To fix this, just increase the width of the column accordingly.</li>
									<li>Fill in the spreadsheet and then click File->Save As. Enter any file name and then save this file as Text (Tab deliminated) (*.txt).</li>
									<li>Click the Browse button below, select your .txt file, and then click Upload Now.</li>
									<li>Note 1: Due to MS Excel's automatic conversion to scientific notation, you MUST do the following to all columns containing CreditCardNumberField,<br />PhoneNumberField, or IntlPhoneNumberField data. In MS Excel, select the entire target column, right click and click Format Cells, select Category: Number,<br />Decimal Places: 0 and click OK.</li>
									<li>Note 2: The 1st row of entries in your spreadsheet must correspond with the appropriate field names.</li>
									<li>Note 3: This import process is an add/replace function.</li>
								</ul>
							</div>
							<?= $uploadBean->getIfError(); ?>
							<table class="tableHelpers">
								<tr class="tableHelpersFormFields">
									<td class="tableHelpersFormFields1" >
										<div class="cell">
											<?= $uploadBean->getLabelTag("upload") ?>
										</div>
									</td>
									<td class="tableHelpersFormFields1" >
										<div class="cell">
											<?= $uploadBean->getFieldTag("upload") ?>
										</div>
									</td>
									<td class="tableHelpersFormFields1" >
										<div class="cell">
											<?= $uploadBean->getFieldTag("upload_now") ?>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</form>
				<? } ?>
				<? if (FF_CAN_EXPORT) { ?>
					<div class="FORMfields">
						<h1 class="FORMfields">Export Records:</h1>
						<div class="ffField" style="margin:10px 0px 5px 10px;">
							Directions:
							<ul style="margin:0px 10px 0px 25px;">
								<li><a href="?action=export">Click here to download <?= FF_FORM_NAME ?>.txt</a>.</li>
								<li>Open MS Excel and select File->Open and open <?= FF_FORM_NAME ?>.txt.</li>
								<li>Some values may show up as "#####" or may appear to be incorrectly formatted. To fix this, just increase the width of the column accordingly.</li>
							</ul>
						</div>
					</div>
				<? } ?>
			<?
		}
	?>
	<div style="text-align:left;margin:10px 3px 10px 3px;">
		<a style="font: normal normal normal 9px verdana,sans-serif;" href="http://www.formfields.com" target="_blank">Generated by FORMgen</a>
	</div>
	</td>
	</tr>
	</table>
	<? if (FF_SHOW_W3C_VALIDATOR) { ?>
		<div>
			<a href="http://validator.w3.org/check?uri=referer"><img border="0" src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0!" height="31" width="88" /></a>
			<a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!" /></a>
		</div>
	<? } ?>
</body>


</html>