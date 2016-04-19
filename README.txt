Project 1 - Documentation
============================
         
Raw File Descriptions
---------------------

The project submission contains the following files:

  > README.txt     - Documentation for the submission.
  > index.php      - The main page containing XHTML the user will see. This file contains
                     slight ammounts of PHP and JavaScript, but only what is absolutely
                     necessary.
  > submit.php       - The submit page containing the PHP for saving the entered classes to
                     the database. This file also echos the html to display submit messsages.
  > style.css       - The style sheet for the project, all pages use the same sheet.
  > mysqlSetup.php - The initialization and connection code for accessing the database
                     through PHP. It also contains helper functions for fetching information
                     from the databases.
  > javascript.js  - The JavaScript functions for dynamically altering the index.php page.
  > icon.ico       - The favicon for the project.


Server Setup
------------

The server is hosted through a Raspberry Pi using a LAMP server setup. The server's
DocumentRoot is a github repository containing the files listed above, plus an additional
bash script file 'gitupdate.sh'. The Raspberry Pi is constantly running the 'gitupdate.sh'
script in the background, which pulls changes from the git repository every few seconds.
This allows the changes being pushed to the git repository to be reflected 'live' on the
website itself.


Database Access
---------------

The database can be accessed through http://XXXX/phpmyadmin with the login:
  > Username: accessaccount
  > Password: accesspass
This is also the account used in 'mysqlSetup.php' to connect to the databases.
  
NOTE: This account has access to the following commands (and only the following commands):
  > SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, and DROP
  

Database Setup
--------------

For the purposes of this project, two databases are used. The `advisingInfo` database is used
for storage of contact information, storage of class information, storage of which classes a 
user has taken. The `classPrereqs` database is used for the storage of the prereq requirements
per class. More detail on each is given below:

  > advisingInfo - Contains 'classes', 'users', and 'userClassLink'
        - classes       - Contains the `CID` (Class ID), department, number, name, and credit count
                          for each cmsc class. Look at the comments and structure in phpmyadmin for 
                          more deatils.
        - users         - Contains the `SID` (Student ID), fname, minitial, lname, email, and phone
                          for each user. Look at the comments and structure in phpmyadmin for 
                          more deatils.
        - userClassLink - For each class a user has taken, it contains the SID linked to the CID. For
                          example, if a student has taken 10 classes, there will be 10 entries with their
                          SID, one for each class taken.
                    
  > classPrereqs - Contains a table for each class. Within each table, there are two columns `requirement`
                   and `requirementClass`. `requirement` take the CID of the classes required while
                   `requirementClass` is a way of grouping requirements.
                   
                   Every requirement with a class of '0' is required. However, for any class '1', '2', etc.
                   only one of the classes from that class is required. For example, cmsc447 requires 
                   cmsc341 and any 400 level class. Therefore cmsc341 is a class '0' prereq, and every class
                   of 400 level is a class '1' prereq.
                   

Special Notes
-------------

1. Initial caching of $_SESSION['classReqs']:
    - Upon loading index.php, the entire list of classes and their prereqs need to be stored in $classReqs.
      However, the server occasionally has a slow connection speed when accessing the database, and since
      populating $classReqs requires many database queries, it is far more efficient to cache the values
      in the SESSION variable $_SESSION['classReqs']. If the SESSION variable is not set, it will query the
      database, otherwise it will use the user's cached SESSION. Should there be issues with the tree not showing
      the proper classes that can be taken, please restart your browser to clear the SESSION.
      
2. The array classReqs:
    - classReqs, parsed using json_encode($classReqs), is a four dimensional array which is designed to simulate
      a HashMap of three dimensional arrays. The contents are as follows:
      
      classReqs[i] => ('<CID 'key' of the ith-class as a string>', <3d array of the ith-class' prereqs>)
      Therefore classReqs[i][0] contains the key of the class, and classReqs[i][1] is the array of its prereqs.
      
      classReqs[i][1] => An array of integers corresponding to the different requirementClasses of the class at
                         classReqs[i][0].
      Therefore classReqs[i][0][j] contains an array of classes in the jth requirementClass for the ith class
      
      classReqs[i][1][j] => An array of strings containing the CID's of classes in that requirement class.'
      
      For example, if i corresponds to the class cmsc435:
      classReqs[i] = array('cmsc435', array(array('cmsc313', 'cmsc341')))
      Since cmsc435 has two class '0' requirements and no other class of requirements.
                         
3. Dynamic tree styling
    - All of the tree styling besides the initial state of the tree takes place in the javascript.js file,
      specifically the updateViews() function
	  
4. Out of range classes: 
    - If the user enters a class within the range but isn't a real class, it will appear in the 'taken class'
      list. However, no part of the tree will be altered, and a message will be displayed upon submitting the
	  courses stating that the class was not found, and therefore was not added. All valid classes will still
	  be saved in the database.
	  
5. Server speed:
    - The server's connection has been relatively slow recently. This is strictly due to the connection on
	  which the server resides. All pages should load within a reasonable amount of time however.
      
      
Tree Styling
------------

The following is the format of how the tree is styled:

<!--
Formatted and styled after:
http://thecodeplayer.com/experiments/css3-family-tree-multiple-parents.html
and http://thecodeplayer.com/walkthrough/css3-family-tree

This structure is really just a heavily styled nested list, to understand
PLEASE render the tree with css and without css: it will greatly clarify the
underlying html structure in a way that comments can't

please note: This structure will wrap if it spans more than the entire page,
and if it wraps then the tree breaks and it looks ugly
-->
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>


    <!-- ul's describe a level down, li's describe elements that belong on that level
    Multiple li's per ul make sense, you should have a li between each ul for it to make sense
    -->
    <div class="tree" >
        <ul >
            <li>
                <a href="#">Parent</a>
                <ul>
                    <li>
                        <a href="#">Child</a>
                        <ul>
                            <li>
                                <!-- this is how you will write categories for collections
                                of dead end classes and collections of classes that are linked together
                                such as gateways. This can go anywhere
                                
                                when writing these categories, don't place any
                                list tags inside of spans
                                
                                -->
                                <span>Grand category
                                    <a>Grandchild 1</a>
                                    <a>Grandchild 2</a>
                                    <a>Grandchild 3</a>
                                </span>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Child</a>
                        <ul>
                            <li><a href="#">Grand Child</a></li>
                            <li>
                                <a href="#">Grand Child</a>
                                <ul>
                                    <li>
                                        <a href="#">Great Grand Child</a>
                                    </li>
                                    <li>
                                        <a href="#">Great Grand Child</a>
                                    </li>
                                    <li>
                                        <a href="#">Great Grand Child</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="#">Grand Child</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</html>
      
