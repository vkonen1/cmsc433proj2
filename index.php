<?php
/* * *****************************************************************************************************************
 *        File: index.php
 *     Created: 2/22/2016
 *     Project: CMSC 433 - Project 1
 * Description: This is the main page for Project 1. This file contains strictly HTML and JavaScript.
 *              All CSS should be written in style.css and all PHP should be written in the submission
 *              handling page (which currently does not exist).
 * ***************************************************************************************************************** */

// The server has been having slow connection issues recently, because of this
// the massive array of class requirements is better cached in a SESSION variable
// rather than being re-generated on page load.
session_start();
if (!isset($_SESSION['classReqs'])) {
    ini_set('max_execution_time', 300); // I know this is ad hoc and bad practice but it works for now
    require_once("mysqlSetup.php");
    $db = new Database();
    $_SESSION['classReqs'] = $db->fetchAllReqs(); // Declared in mysqlSetup.php
}
$classReqs = $_SESSION['classReqs'];
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link rel="icon" href="icon.ico" />
        <link rel="stylesheet" type="text/css" href="style.css"/>
        <title>Project 1</title>	
    </head>
    <body>
        <div class="header">CMSC Course Advising Form</div>
        <div style="float: left;">
            <!--Two forms for submission handling-->
            <!--All field names match their corresponding title in the database-->
            <!--Course Info section-->
            <form class="courseinfo" method="post" onsubmit="return updateClasses(this.submitted);">
                Enter Class:
                <select name="department">
                    <!--Most of the classes chosen will be in one of these departments-->
                    <!--CMSC is chosen as default because the majority of the classes will be CMSC-->
                    <option value="cmsc" selected="selected">CMSC</option>
                    <option value="math" disabled="disabled">MATH</option>
                    <option value="stat" disabled="disabled">STAT</option>
                    <option value="cmpe" disabled="disabled">CMPE</option>
                </select>*
                <input type="text" name="coursenumber" maxlength="3" size="3" title="Enter class number in the form: ###"></input>*
                <br/>
                <input name="addClass" type="submit" value="Add Class" onclick="this.form.submitted = this.name;"></input>
                <input name="removeClass" type="submit" value="Remove Class" onclick="this.form.submitted = this.name"></input><br/>
                <input name="courseClear" type="submit" value="Clear Classes" onclick="this.form.submitted = this.name"></input>
            </form>

            <!-- Contains the legend for the tree, and a dynamically generated list
            of which classes the user has entered. -->
            <div class="currentClasses">
                <i>Tree Legend:</i> 
                <span class="NA">Can't Take</span>
                <span class="curr">Can Take</span>
                <span class="done">Taken</span><br/>
                Classes Entered - Numerical Order:
                <div  id="classDiv">

                </div>
            </div>

            <!--User info section-->
            <form method="post" class="userinfo" action="submit.php" onsubmit="validate();">
                Name:<br/>
                <!--Name is split up into seperate fields because that is how it is set up in the database-->
                <input type="text" name="fname" required="required" placeholder="First Name" size="16"></input>*
                <input type="text" name="minitial" required="required" placeholder="MI" size="1"></input>*
                <input type="text" name="lname" required="required" placeholder="Last Name" size="16"></input>*<br/>
                
                Campus ID:<br/>
                <input type="text" name="SID" required="required" pattern="[A-Za-z]{2}[0-9]{5}" title="Enter in the form XX#####"></input>*<br/>
                
                Email (Best to contact):<br/>
                <!--Reminder: Using the email type does most of the error checking for us-->
                <!--The email type checks to make sure the input is of the pattern something@something-->
                <input type="email" name="email" required="required" title="Please enter a valid email address"></input>*<br/>
                Phone Number (Best to contact):<br/>
                <input type="text" name="phone1" required="required" size="3" maxlength="3" pattern="[0-9]{3}" onkeyup="boxJump(this)" title="Please enter in the form: ###"></input> -
                <input type="text" name="phone2" required="required" size="3" maxlength="3" pattern="[0-9]{3}" onkeyup="boxJump(this)" title="Please enter in the form: ###"></input> -
                <input type="text" name="phone3" required="required" size="4" maxlength="4" pattern="[0-9]{4}" title="Please enter in the form: ####"></input>*<br/>
                <input type="submit" name="submit" value="Save Information" class="finalsubmit"></input>
                <input type="hidden" name="classes" value=""></input>
            </form>
        </div>

        <!--
formatted after http://thecodeplayer.com/experiments/css3-family-tree-multiple-parents.html
and http://thecodeplayer.com/walkthrough/css3-family-tree
View without css to better understand how the structure is built:
Basically a list with sublists with some styling thrown over it to make it render differently.

if you have something that doesn't fit with the topdown perspective then
go ahead and use the special cases graph
Doesn't react well to wrapping: breaks the graph
        -->

        <div>
            <div class="tree">
                <ul>
                    <li>
                        <span class="required" id="cmscGateway">CMSC  <br /> Gateway <br />
                            <a id="cmsc201" href="#">CMSC 201</a> 
                            <a id="cmsc202" href="#">CMSC 202</a> 
                            <a id="cmsc203" href="#">CMSC 203</a> 
                        </span>
                        <ul>
                            <li>
                                <span id="cmsc203dep" >CMSC 203 <br /> Dependants <br />
                                    <a id="cmsc451" href="#">CMSC 451</a> <br />
                                    <a id="cmsc452" href="#">CMSC 452</a> <br />
                                    <a id="cmsc457" href="#">CMSC 457</a>   <br />
                                </span>
                            </li>
                            <li>
                                <span id="cmsc202dep" >CMSC 202 <br /> Dependants <br />
                                    <a id="cmsc304" href="#">CMSC 304</a> <br />
                                    <a id="cmsc484" href="#">CMSC 484</a> <br />
                                </span>
                            </li>
                            <!-- these all have children -->

                            <li>
                                <a class="required" id="cmsc313" href="#">CMSC 313</a>
                                <ul>
                                    <li>
                                        <span id="cmsc313dep">CMSC 313 <br /> Dependant <br />
                                            <a class="required" id="cmsc411" href="#">CMSC 411</a>
                                        </span>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="required" id="cmsc331" href="#">CMSC 331</a>
                                <ul>
                                    <li>
                                        <span id="cmsc331dep" >CMSC 331 <br /> Dependants <br />
                                            <a id="cmsc432" href="#">CMSC 432</a> <br />
                                            <a id="cmsc433" href="#">CMSC 433</a> <br />
                                            <a id="cmsc473" href="#">CMSC 473</a>
                                        </span>
                                    </li>                                
                                </ul>
                            </li>
                            <li>
                                <a class="required" id="cmsc341" href="#">CMSC 341</a>
                                <ul>
                                    <li>
                                        <span id="cmsc341dep">CMSC 341 <br /> Dependants <br />

                                            <a id="cmsc436" href="#">CMSC 436</a> <br />
                                            <a id="cmsc437" href="#">CMSC 437</a> 
                                            <a class="required" id="cmsc441" href="#">CMSC 441</a> <br />
                                            <a id="cmsc443" href="#">CMSC 443</a> 
                                            <a id="cmsc453" href="#">CMSC 453</a> <br />
                                            <a id="cmsc455" href="#">CMSC 455</a> 
                                            <a id="cmsc456" href="#">CMSC 456</a> <br />
                                            <a id="cmsc475" href="#">CMSC 475</a> 
                                            <a id="cmsc476" href="#">CMSC 476</a> 
                                        </span>
                                    </li>

                                    <li>
                                        <a id="cmsc461" href="#">CMSC 461</a>
                                    </li>
                                    <li>
                                        <a id="cmsc471" href="#">CMSC 471</a>
                                        <ul>
                                            <li>
                                                <span id="cmsc471dep">CMSC 471 <br /> Dependants<br />
                                                    <a id="cmsc477" href="#">CMSC 477</a> <br />
                                                    <a id="cmsc478" href="#">CMSC 478</a> <br />
                                                    <a id="cmsc479" href="#">CMSC 479</a>
                                                </span>
                                            </li>             

                                        </ul>                                    
                                    </li>                            
                                    <li>
                                        <a id="cmsc481" href="#">CMSC 481</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="tree"  >
                <ul>
                    <li>
                        <a href="#">Multiple dependancies <br /> and special cases</a>
                        <ul>
                            <li>
                                <span id="no-prereq" href="#">No Prereqs <br />
                                    <a id="cmsc427" href="#">CMSC 427</a> <br />
                                    <a id="cmsc486" href="#">CMSC 486</a>
                                </span>                                
                            </li>
                            <li>
                                <a id="313_341" href="#">CMSC 313 & 341</a>
                                <ul>
                                    <li>                                                                           
                                        <a class="required" id="cmsc421" href="#">CMSC 421</a>
                                        <ul>
                                            <li>
                                                <span>
                                                    <a id="cmsc426" href="#">CMSC 426</a> <br />
                                                    <a id="cmsc483" href="#">CMSC 483</a> 
                                                </span>
                                            </li>
                                        </ul>                                                                         
                                    </li>


                                    <li>
                                        <a id="cmsc435" href="#">CMSC 435</a> 
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a id="435_471" href="#">CMSC 435 & 471</a>
                                <ul>
                                    <li>
                                        <a id="cmsc493" href="#">CMSC 493</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a id="any400" href="#">Any 4xx<br /> & 341</a>
                                <ul>
                                    <li>
                                        <a class="required" id="cmsc447" href="#">CMSC 447</a>
                                        <ul>
                                            <li>
                                                <a id="cmsc448" href="#">CMSC 448</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a id="461_481" href="#">CMSC 461 & 481</a>
                                <ul>
                                    <li>
                                        <span>
                                            <a id="cmsc465" href="#">CMSC 465</a> <br />
                                            <a id="cmsc466" href="#">CMSC 466</a>
                                        </span>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a id="421_481" href="#">CMSC 421 & 481</a>
                                <ul>
                                    <li>
                                        <a id="cmsc487" href="#">CMSC 487</a>
                                    </li>
                                </ul>
                            </li>                       
                        </ul>
                    </li>
                </ul>
            </div>	
        </div>

        <!-- Include the javascript -->
        <script>var classReqs = <?php echo json_encode($classReqs); ?>;</script>
        <script src="javascript.js"></script>
    </body>
    <footer>
    </footer>
</html>

