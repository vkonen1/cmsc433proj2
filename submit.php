<?php
/*******************************************************************************************************************
 *        File: submit.php
 *     Created: 4/09/2016
 *     Project: CMSC 433 - Project 1
 * Description: This is post-submit page for Project 1. This file contains the php to store the classes taken into
 * 				the database. Should an error occur, an error message will be displayed and the user will be
 * 				redirected to the main page after 5 seconds (with a link to expedite the process).
 *******************************************************************************************************************/
ob_start();
echo "<link rel='stylesheet' type='text/css' href='style.css'/>";
echo "<link rel=\"icon\" href=\"icon.ico\" />";

// Connect to the database
require_once("mysqlSetup.php");
$db = new Database(); // Has members $infoDb and $reqDb

/* Making sure all info is present */
if(!allAreSet()) {
	header('refresh: 10; index.php');
	echo "<span class=\"message\">Error: Some information not provided. Information not stored.<br/></span>";
	echo "You will be redirected back to the class entry form within 10 seconds.<br/>";
	echo "<a class=\"submitLink\" href='index.php'>Click here to redirect now.</a>";
	ob_flush();
	exit;
}

/* Initialize variables from POST and SESSION */
$fname = $_POST['fname'];
$minitial = $_POST['minitial'];
$lname = $_POST['lname'];
$SID = $_POST['SID'];
$email = $_POST['email'];
$phone1 = $_POST['phone1'];
$phone2 = $_POST['phone2'];
$phone3 = $_POST['phone3'];
$phone = "$phone1-$phone2-$phone3";
$classes = $_POST['classes'];
$classes = explode(",", $classes);

/* Validating $classes */
$invalid = array();
$notClasses = array();
$allClasses = $db->fetchClassNames();

foreach($classes as $class) {
	
	// If a class ID is misset, add it to the invalid array
	if(!preg_match('/^[A-Z]{3,4}[1-4][0-9]{2}(H|Y|L)?/', $class)) {
		array_push($invalid, $class);
		
	// If a class is not a valid umbc class, add it to the notClasses array
	} else if(in_array($class, $allClasses) == false) {
		array_push($notClasses, $class);
	}
	
}
// Remove all elements of $classes that are also in $invalid
$classes = array_diff($classes, $invalid);
$classes = array_diff($classes, $notClasses);

/* Storing contact info in `advisingInfo` */
// NOTE: Validation took place on the previous page, so all values besides $classes (validated above)
//		 can be added 'as is' here with no validation.
try {
	$db->infoDb->beginTransaction();
	
	// Delete old entry if one exists
	$db->infoDb->exec("DELETE FROM `users` WHERE `SID`='$SID'");
	
	// Add new entry
	$query = $db->infoDb->prepare("INSERT INTO `users`(`SID`, `fname`, `minitial`, `lname`, `email`, `phone`) VALUES (?, ?, ?, ?, ?, ?)");
	$query->bindParam(1, $SID);
	$query->bindParam(2, $fname);
	$query->bindParam(3, $minitial);
	$query->bindParam(4, $lname);
	$query->bindParam(5, $email);
	$query->bindParam(6, $phone);
	$query->execute();
	
	$db->infoDb->commit();
	
// If an error occurs, don't store anything and redirect
} catch (PDOException $e) {	
	$db->infoDb->rollBack();
	
	header('refresh: 10; index.php');
	echo "Error: Contact information corrupted. Information not stored.<br/>";
	echo "You will be redirected back to the class entry form within 10 seconds.<br/>";
	echo "<a class=\"submitLink\" href='index.php'>Click here to redirect now.</a>";
	
	ob_flush();
	exit;
}

/* Storing contact info in `userClassLink` */
$CID = null;
try {
	$db->infoDb->beginTransaction();
	
	$query = $db->infoDb->prepare("INSERT INTO `userClassLink`(`SID`, `CID`) VALUES (?, ?)");
	$query->bindParam(1, $SID);
	$query->bindParam(2, $CID);
	
	foreach($classes as $class) {
		$CID = $db->fetchClassId($class);
		$query->execute();
	}
	
	$db->infoDb->commit();

// If an error occurs, don't store anything and redirect
} catch (PDOException $e) {	
	$db->infoDb->rollBack();
	
	header('refresh: 10; index.php');
	echo "Error: Class information corrupted. Information not stored.<br/>";
	echo "You will be redirected back to the class entry form within 10 seconds.<br/>";
	echo "<a class=\"submitLink\" href='index.php'>Click here to redirect now.</a>";
	
	ob_flush();
	exit;
}

/* Outputing success or error messages */
// Everything is fine
if(sizeof($invalid) == 0 && sizeof($notClasses) == 0) {
	echo "<span class=\"message\">Advising information successfully saved!</span><br/>";
	echo "<a class=\"submitLink\" href='index.php'>Click here to return to the class entry form.</a>";
	
// Both invalid format and non-classes used
} else if(sizeof($invalid) != 0 && sizeof($notClasses) != 0) {
	echo "<span class=\"message\">One or more classes entered were invalid.</span><br/>";
	
	echo "The following classes were added: <i>";
	foreach($classes as $class) {
		$class = strtoupper($class);
		echo "$class  ";
	}
	echo "</i><br/>";
	
	echo "The following classes were of an invalid format and <b>not</b> added: <i>";
	foreach($invalid as $class) {
		$class = strtoupper($class);
		echo "$class  ";
	}
	echo "</i><br/>";
	
	echo "The following classes were not found in the course list and <b>not</b> added: <i>";
	foreach($notClasses as $class) {
		$class = strtoupper($class);
		echo "$class  ";
	}
	echo "</i><br/>";
	
	echo "<a class=\"submitLink\" href='index.php'>Click here to return to the class entry form.</a>";
	
// Some invalid classes but no non-classes
} else if (sizeof($invalid) != 0) {
	echo "<span class=\"message\">One or more classes entered were invalid.</span><br/>";
	
	echo "The following classes were added: <i>";
	foreach($classes as $class) {
		$class = strtoupper($class);
		echo "$class  ";
	}
	echo "</i><br/>";
	
	echo "The following classes were of an invalid format and <b>not</b> added: <i>";
	foreach($invalid as $class) {
		$class = strtoupper($class);
		echo "$class  ";
	}
	echo "</i><br/>";
	
	echo "<a class=\"submitLink\" href='index.php'>Click here to return to the class entry form.</a>";
	
// No invalid classes but some non-classes	
} else {
	echo "<span class=\"message\">One or more classes entered were invalid.</span><br/>";
	
	echo "The following classes were added: <i>";
	foreach($classes as $class) {
		$class = strtoupper($class);
		echo "$class  ";
	}
	echo "</i><br/>";
	
	echo "The following classes were not found in the course list and <b>not</b> added: <i>";
	foreach($notClasses as $class) {
		$class = strtoupper($class);
		echo "$class  ";
	}
	echo "</i><br/>";
	
	echo "<a class=\"submitLink\" href='index.php'>Click here to return to the class entry form.</a>";
}
ob_flush();
session_destroy();
ob_end_clean();

/* Checks to see if all needed variables are set */
function allAreSet() {
	if(!isset($_POST['fname'])) {
		return false;
	}
	
	if(!isset($_POST['minitial'])) {
		return false;
	}
	
	if(!isset($_POST['lname'])) {
		return false;
	}
	
	if(!isset($_POST['SID'])) {
		return false;
	}
	
	if(!isset($_POST['email'])) {
		return false;
	}
	
	if(!isset($_POST['phone1'])) {
		return false;
	}
	
	if(!isset($_POST['phone2'])) {
		return false;
	}
	
	if(!isset($_POST['phone3'])) {
		return false;
	}
	
	if(!isset($_POST['classes']) || $_POST['classes'] == "") {
		return false;
	}
	
	return true;
}
?>