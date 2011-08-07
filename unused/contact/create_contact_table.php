<?php

	require($_SERVER["DOCUMENT_ROOT"] . "/FORMfields/FORMfields.php"); 
	
	if (ffExecuteScript("contact_table.sql")) {
		echo "Table contact created successfully.";
	}

?>