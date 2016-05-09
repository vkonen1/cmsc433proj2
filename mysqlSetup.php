<?php 
/*******************************************************************************************************************
 *        File: mysqlSetup.php
 *     Created: 4/09/2016
 *     Project: CMSC 433 - Project 1
 * Description: This is database setup code for Project 1. This file contains the php to connect to the database
 * 				and initalizes the functions to be used to query the database.
 *******************************************************************************************************************/

/*  
 *	------------------------------------------------------------------------------------------------
 *	Modified by: Team 11 (Daniel, Victor, Ka)
 *	Modified on: 5/9/2016
 *	Description: Modified this file for Project 2
 *  Slightly modified to work with reverse engineered database
 *	------------------------------------------------------------------------------------------------
 */
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
	
	// modified this to select all classes and all fields for each one and return
	// an array with that information
	function fetchClasses()
	{
		$classes = array();	
		$result = $this->infoDb->query("SELECT * FROM classes");
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			array_push($classes, $row);
		}
		
		return $classes;
	}

	// added this function to select all class names only, which is what the
	// javascript uses to manipulate the tree and class list
	function fetchClassNames()
	{
		$classes = array();	
		$result = $this->infoDb->query("SELECT name FROM classes");
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			array_push($classes, $row["name"]);
		}
		
		return $classes;
	}

	// modified this to select from requirements by actual course id not name
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
				array_push($reqsInClass, $this->fetchClassName($allPrereqs[$ctr]['requirement']));
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
			array_push($simulateMap, $class['name']);
			array_push($simulateMap, $this->fetchReqs($class['CID']));
			array_push($allReqs, $simulateMap);
		}
		
		return $allReqs;
	}

	// added this to get a class name given the actual course id
	function fetchClassName($classId)
	{
		$result = $this->infoDb->query("SELECT name FROM classes WHERE CID = '$classId'");
		$class = $result->fetch(PDO::FETCH_ASSOC);
		return $class['name'];
	}

	// added this to get the actual course id given a class name
	function fetchClassId($className)
	{
		$result = $this->infoDb->query("SELECT CID FROM classes WHERE name = '$className'");
		$class = $result->fetch(PDO::FETCH_ASSOC);
		return $class['CID'];
	}

}

?>