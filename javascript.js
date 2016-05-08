<!-- Javascript functions -->
//console.log(classReqs);
var classes = new Array(); // global array to manage classes entered
// var classReqs = <?php echo $JSONreqs; ?>; // Declared in index.php
// NOTE: The classReqs array is a four dimensional array and very confusing at first
// 		 however, the entire array is document in the README. Please read that before
//		 tampering with classReqs.
	
	window.onload = function() {
		updateViews();
	}
	<!--
	  // boxJump()
	  // Preconditions: element is the element to jump from
	  // Postcondtions: Moves cursor to next text box when entering phone number
	-->
	function boxJump(element) {
		if(element.value.length >= 3) {
			var i;
			var elements = element.form.elements;
			for(i = 0; i < elements.length; i++) {
				
				// Found the right element
				if(element == elements[i]) {
					elements[i+1].focus();
					elements[i+1].select();
					break;
				}
			}
		}
	}

	function toggleClass(className) {
		var type;
		var num;
		//console.log(className);
		if (className.indexOf("CMSC") == 0 || className.indexOf("MATH") == 0) {
			type = className.substring(0, 4);
			num = className.substring(4);
			//console.log(type);
			//console.log(num);
		} else {
			type = className.substring(0, 3);
			num = className.substring(3);
			//console.log(type);
			//console.log(num);
		}

		var toggle_ar = true;
		for (var i = 0; i < classes.length; i++) {
			if (classes[i] == className) {
				toggle_ar = false;
				break;
			}
		}

		document.getElementsByName("department")[0].value = type;
		document.getElementsByName("coursenumber")[0].value = num;



		if (toggle_ar) {
			updateClasses("addClass");
		} else {
			updateClasses("removeClass");
		}
	}
	
	<!--
	  // updateClasses()
	  // Preconditions: submitClicked is the submit value of the form
	  // Postcondtions: Updates class information string (stored in courses.value) 
	  // based on submitted data
	-->
	function updateClasses(submitClicked) {
		
		// Check for clear first since the class number text box can
		// be empty for this option.
		if(submitClicked == "courseClear") {
			if(confirm("Are you sure you want to clear the current class list?") == true) {
				classes.splice(0, classes.length);
				clearStyling();
			}
			
		// Add or remove
		} else {
			
			// Getting relevent data
			var dept = document.getElementsByName("department")[0].value;
			var number = document.getElementsByName("coursenumber")[0].value;
			var classString = (dept.concat(number)).toUpperCase();
			
			//console.log(classString);

			// Verify the proper form was used
			if(!/[1-4][0-9]{2}/.test(number)) {
				alert("Please enter class number between 200 and 499 in the form: ###");
				return false;
			}
			
			// If user wanted to add the class
			if(submitClicked == "addClass") {

				// If not present, add the class
				if(classes.indexOf(classString) == -1) {
					classes.push(classString);
				}

			// If user wanted to remove the class
			} else if(submitClicked == "removeClass") {

				// Check if the class is in the array and remove if so
				var index = classes.indexOf(classString);
				if(index != -1) {
					classes.splice(index, 1);
				}
			}
			
		}
		//console.log(classes);
		// Clearing textbox
		document.getElementsByName("coursenumber")[0].value = "";

		// Update the tree and class list
		updateViews();
		return false;
	}
	
	<!--
	  // clearStyling()
	  // Preconditions: none
	  // Postcondtions: Clears the tree of all styling
	-->
	function clearStyling() {
		
		// For every single class
		for(var i = 0; i < classReqs.length; i++) {
			var currClass = classReqs[0][0];
			
			// Reset the elements
			var element = document.getElementById(curClass);
			var depElement = document.getElementById(curClass + "dep");
			if(element) {
				element.style.border = "2px solid #6A49A7";
				element.style.backgroundColor = "white";
			}
			if(depElement){
				depElement.style.border = "2px solid #6A49A7";
				depElement.style.backgroundColor = "white";
			}
		}
	}

	<!--
	  // updateViews()
	  // Preconditions: none
	  // Postcondtions: Updates the courses taken list and the courses
	  // 				that can be taken tree.
	-->
	
	function updateViews() {
		
		// Clear current class list
		var div = document.getElementById("classDiv");
	 	div.innerHTML = "";

		// Sort the class list
		classes.sort();

	 	// For each class display the class in the proper div.
	 	var divHTML = "";
	 	for(var i = 0; i < classes.length; i++) {
		 	string = "<span class='course' onclick=\"toggleClass('" + classes[i] + "')\">" + classes[i] + "</span> ";
		 	//console.log(classes[i]);
		 	divHTML = divHTML + string;
		}
		div.innerHTML = divHTML;
		
		/* 
		 * This part is a mess, this is the javascript for determining which classes
		 * can be taken with the current courses entered. Comments are provided, but
		 * check the README for a better description of classReqs.
		 */
		
		// For each class in the database
		for(var i = 0; i < classReqs.length; i++) {
			
			var addClass = true;
			
			// And for each "prereq class" defined for that class
			for(var j = 0; j < classReqs[i][1].length; j++) {
				
				// If it's the '0' prereq class, all are required
				if(j == 0) {
					
					// If the first element of the prereq class '0' is null
					// Then there are no mandatory prereqs
					if(classReqs[i][1][j][0] == "" ) {
						continue;
					}
					
					// Set flag to false if any are not present
					for(var k = 0; k < classReqs[i][1][j].length; k++) {				
						if(classes.indexOf(classReqs[i][1][j][k]) == -1) {
							addClass = false;
							break;
						}
					}
					
				// If it's not the '0' prereq class, only one is required
				} else {
					
					var found = false;
					for(var k = 0; k < classReqs[i][1][j].length; k++) {
						if(classes.indexOf(classReqs[i][1][j][k]) != -1) {
							found = true;
							break;
						}
					}
					if(!found) {
						addClass = false;
					}
				}
			}
			
			/********
			refactored this to style elements in a more concise way and select
			multiple elements instead of just one
			*******/
			// If the class is valid, addClass will still be true
			// so if it is, change the styling of the class link on the tree
			// and make sure the dependency tree is not colored
			var elements = document.getElementsByClassName(classReqs[i][0]);
			var depElements = document.getElementsByClassName(classReqs[i][0] + "dep");
			if (elements) {
				for (var j = 0; j < elements.length; j++) {
					if (addClass) {
						elements[j].style.border = "2px solid #ffff00";
						elements[j].style.backgroundColor = "#ffff44";
					} else {
						elements[j].style.border = "2px solid #6A49A7";
						elements[j].style.backgroundColor = "white";
					}			
				}				
			}
			if (depElements) {
				for (var j = 0; j < depElements.length; j++) {
					if (addClass) {
						depElements[j].style.border = "2px solid #6A49A7";
						depElements[j].style.backgroundColor = "white";						
					} else {
						depElements[j].style.border = "2px solid #6A49A7";
						depElements[j].style.backgroundColor = "white";
					}
				}				
			}
		}
		
		// Changing tree properties
		//refactored this as well
		for(var i = 0; i < classes.length; i++) {
			var elements = document.getElementsByClassName(classes[i]);
			var depElements = document.getElementsByClassName(classes[i] + "dep");
			if (elements) {
				for (j = 0; j < elements.length; j++) {
					elements[j].style.border = "2px solid #33cc33";
					elements[j].style.backgroundColor = "#adebad";				
				}				
			}
			if (depElements) {
				for (j = 0; j < depElements.length; j++) {
					depElements[j].style.border = "2px solid #33cc33";
					depElements[j].style.backgroundColor = "#adebad";				
				}				
			}
		}
		

		/*
		-------------------------------------------
		Commented this hard coded multi Classes 
		Replaced with span so we don't have
		to code all the mutli classes one by one
		-------------------------------------------
		// Special ids for joint class nodes
		// id="313_341"
		if(classes.indexOf('CMSC313') != -1 && classes.indexOf('CMSC341') != -1) {
			//var element = document.getElementById("313_341");
			element.style.border = "2px solid #33cc33";
			element.style.backgroundColor = "#adebad";
		}
		
		// id="435_471"
		if(classes.indexOf('CMSC435') != -1 && classes.indexOf('CMSC471') != -1) {
			//var element = document.getElementById("435_471");
			element.style.border = "2px solid #33cc33";
			element.style.backgroundColor = "#adebad";
		}
		
		// id="421_481"
		if(classes.indexOf('CMSC421') != -1 && classes.indexOf('CMSC481') != -1) {
			//var element = document.getElementById("421_481");
			element.style.border = "2px solid #33cc33";
			element.style.backgroundColor = "#adebad";
		}
		
		// id="461_481"
		if(classes.indexOf('CMSC461') != -1 && classes.indexOf('CMSC481') != -1) {
			//var element = document.getElementById("461_481");
			element.style.border = "2px solid #33cc33";
			element.style.backgroundColor = "#adebad";
		}
		*/
		
		// id="any400"
		for(var i = 0; i < classes.length; i++) {
			if(classes[i].match(/4[0-9][0-9]/) != null && classes.indexOf('CMSC341') != -1) {
				var element = document.getElementById("any400");
				element.style.border = "2px solid #33cc33";
				element.style.backgroundColor = "#adebad";
				break;
			}
		}
	}
	
	<!--
	  // validate()
	  // Preconditions: none
	  // Postcondtions: Validates information upon submitting,
	  //                since almost all validation is done through html,
	  //				this will be nearly empty.
	-->
	
	function validate() {
		submitClasses();
	 	return true;
	}

	<!--
	  // submitClasses()
	  // Preconditions: none
	  // Postcondtions: Populates the hidden element with a string containing all
	  // 				classes for posting.
	-->
	
	function submitClasses() {
		var classElement = document.getElementsByName("classes")[0];
		classElement.setAttribute("value", classes.toString());
	}

	function toggleSubmit() {
		var studentForm = document.getElementById("saveStudentForm");
		var trees = document.getElementById("trees");
		if (studentForm.style.display == "inline") {
			studentForm.style.display = "none";
			trees.style.display = "inline";
		} else {
			studentForm.style.display = "inline";
			trees.style.display = "none";
		}
	}

	function changeTab(type){
		//get and hide all trees
		var tabs = document.getElementsByClassName("tree");
		for(var i = 0; i < tabs.length; i++){
			tabs[i].style.display = "none";	
		}

		//display active tab 'type'
		document.getElementById(type).style.display = "inline";

		/*display groupings of trees:
			cmsc 3 electives part 1 & part 2
			math for cmsc & math for science
			science & science labs
		*/
		if(type =="cmsc3"){
			document.getElementById(type + "1").style.display = "inline";
		}
		else if(type =="math"){
			document.getElementById(type + "1").style.display = "inline";
		}
		else if(type =="science"){
			document.getElementById(type + "1").style.display = "inline";
		}
	}
