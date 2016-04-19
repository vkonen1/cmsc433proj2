<?php 
/*******************************************************************************************************************
 *        File: mysqlSetup.php
 *     Created: 4/09/2016
 *     Project: CMSC 433 - Project 1
 * Description: This is database setup code for Project 1. This file contains the php to connect to the database
 * 				and initalizes the functions to be used to query the database.
 *******************************************************************************************************************/

class Database
{
	// Two different databases are used to store the information
	var $infoDb;
	var $reqDb;
		
	// Initializes database
	function Database()
	{
		$rs = $this->connect("advisingInfo", "classPrereqs");
		return $rs;
	}

	// Connects to each database with PDO
	function connect($info, $req)
	{
		
		$infoDb = new PDO("mysql:host=localhost;dbname=$info;charset=utf8mb4", 'accessaccount', 'accesspass', array(PDO::ATTR_TIMEOUT => "120"));
		$infoDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$infoDb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$infoDb->setAttribute(PDO::ATTR_TIMEOUT, 240);
		
		$reqDb = new PDO("mysql:host=localhost;dbname=$req;charset=utf8mb4", 'accessaccount', 'accesspass', array(PDO::ATTR_TIMEOUT => "120"));
		$reqDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$reqDb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$reqDb->setAttribute(PDO::ATTR_TIMEOUT, 240);
		
		$this->infoDb = $infoDb;
		$this->reqDb = $reqDb;
	}
	
	// Fetches an array of the CIDs of all classes stored in the database.
	// Does not fetch any other information
	function fetchClasses()
	{
		$classes = array();	
		$result = $this->infoDb->query("SELECT * FROM classes");
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			array_push($classes, $row);
		}
		
		return $classes;
	}

	// Given a CID, this fetches the requirements as an array of preqreq classes
	// each class containing an array of requirements for that class
	function fetchReqs($classId)
	{
		$prereqs = array();
		$result = $this->reqDb->query("SELECT * FROM prereqs WHERE CID = '$classId' ORDER BY requirementClass ASC;");		
		
		$ctr = 0;
		$curClass = 0;
		$allPrereqs = $result->fetchAll();
		
		// While the class list has not be exhausted
		while($ctr < sizeof($allPrereqs)) {
			
			// Find all classes in the current prereq class (indicated by curClass)
			$reqsInClass = array();
			while($ctr < sizeof($allPrereqs) && $allPrereqs[$ctr]['requirementClass'] == $curClass) {
				array_push($reqsInClass, $allPrereqs[$ctr]['requirement']);
				$ctr++;
			}
			
			// Add the prereq class array to the overall array and increment class
			array_push($prereqs, $reqsInClass);
			$curClass++;
		}
		
		return $prereqs;
	}
	
	// Fetches the prereqs for each class in the database and returns them as a
	// 4d array that simulates a HashMap of 3d arrays (see the README)
	function fetchAllReqs()
	{
		$allReqs = array();
		$classes = $this->fetchClasses();
		
		foreach($classes as $class) {
			$simulateMap = array();
			array_push($simulateMap, $class);
			array_push($simulateMap, $this->fetchReqs($class['CID']));
			array_push($allReqs, $simulateMap);
		}
		
		return $allReqs;
	}

}

?>