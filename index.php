<?php
/* *****************************************************************************************************************
 *        File: index.php
 *     Created: 2/22/2016
 *     Project: CMSC 433 - Project 1
 * Description: This is the main page for Project 1. This file contains strictly HTML and JavaScript.
 *              All CSS should be written in style.css and all PHP should be written in the submission
 *              handling page (which currently does not exist).
 * ***************************************************************************************************************** */
require_once("mysqlSetup.php");
$db = new Database();
$classReqs = $db->fetchAllReqs();
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
                    <option value="math">MATH</option>
                    <option value="stat">SCI</option>
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
            <div class="tree">
                <ul>
						<li>
							<span class="required" id="CMSCGateway">CMSC  <br /> Intro <br />
								 <a class="CMSC201" onclick="toggleClass('CMSC201')">CMSC 201</a> 
										 <a class="MATH151" onclick="toggleClass('MATH151')">MATH 151</a>
						   </span>
								<ul>
									<li>
										<span class="required" id="CMSCGateway">CMSC  <br /> Gateway <br />
											<a class="CMSC202" onclick="toggleClass('CMSC202')">CMSC 202</a> 
											<a class="CMSC203" onclick="toggleClass('CMSC203')">CMSC 203</a> 
										</span>
										<ul>
											<li>
												<span class="CMSC202dep" >CMSC 202 <br /> Dependants <br />
													<a class="CMSC304" onclick="toggleClass('CMSC304')">CMSC 304</a> <br />
													<a class="CMSC484" onclick="toggleClass('CMSC484')">CMSC 484</a> <br />
												</span>
											</li>
											<li>
												<span class="CMSC203dep" >CMSC 203 <br /> Dependants <br />
													<a class="CMSC452" onclick="toggleClass('CMSC452')">CMSC 452</a> <br />
												</span>
											</li>
											<!-- these all have children -->

											<li>
												<a class="required CMSC313" onclick="toggleClass('CMSC313')">CMSC 313</a>
												<ul>
													<li>
														<span class="CMSC313dep">CMSC 313 <br /> Dependant <br />
															<a class="required CMSC411" onclick="toggleClass('CMSC411')">CMSC 411</a>
														</span>
													</li>
												</ul>
											</li>
											<li>
												<a class="required CMSC331" onclick="toggleClass('CMSC331')">CMSC 331</a>
												<ul>
													<li>
														<span class="CMSC331dep" >CMSC 331 <br /> Dependants <br />
															<a class="CMSC433" onclick="toggleClass('CMSC433')">CMSC 433</a> <br />
															<a class="CMSC473" onclick="toggleClass('CMSC473')">CMSC 473</a>
														</span>
													</li>                                
												</ul>
											</li>
											<li>
												<a class="required CMSC341" onclick="toggleClass('CMSC341')">CMSC 341</a>
												<ul>
													<li>
														<span class="CMSC341dep">CMSC 341 <br /> Dependants <br />
															<a class="CMSC427" onclick="toggleClass('CMSC427')">CMSC 427</a> <br />
															<a class="CMSC436" onclick="toggleClass('CMSC436')">CMSC 436</a> <br />
															<a class="CMSC475" onclick="toggleClass('CMSC475')">CMSC 475</a> <br />
															<a class="CMSC476" onclick="toggleClass('CMSC476')">CMSC 476</a>  
														</span>
													</li>

													<li>
														<a class="CMSC461" onclick="toggleClass('CMSC461')">CMSC 461</a>
													</li>
													<li>
														<a class="CMSC471" onclick="toggleClass('CMSC471')">CMSC 471</a>
														<ul>
															<li>
																<span class="CMSC471dep">CMSC 471 <br /> Dependants<br />
																	<a class="CMSC477" onclick="toggleClass('CMSC477')">CMSC 477</a> <br />
																	<a class="CMSC478" onclick="toggleClass('CMSC478')">CMSC 478</a> <br />
																	<a class="CMSC479" onclick="toggleClass('CMSC479')">CMSC 479</a>
																</span>
															</li>             

														</ul>                                    
													</li>                            
													<li>
														<a class="CMSC481" onclick="toggleClass('CMSC481')">CMSC 481</a>
													</li>
												</ul>
											</li>
										</ul>
									</li>
								</ul>
						</li>
                </ul>
            </div>

            <!-- Core Math tree -->	
		 	<div class="tree">
                <ul>
                    <li>
                        <a>Core Math Requirement</a>
                        <ul>
                        	<li>
								<a class="MATH151" onclick="toggleClass('MATH151')">MATH 151</a>
                                <ul>
                                    <li>
                                        <a class="MATH152" onclick="toggleClass('MATH152')">MATH 152</a>
                                         <ul>
                                    	<li>
                                            <a class="STAT355" onclick="toggleClass('MATH355')">MATH 355</a> <br />                                        
                                    </li>
                                </ul>                         
                                    </li>
                                    <li>
                                    	<a class="MATH221" onclick="toggleClass('MATH221')">MATH 221</a>
                                    </li>
                                </ul>
                            </li>
    					 </ul>
                    </li>
                </ul>
            </div>	


            <!-- Multi Req Tree for CMSC -->
            <div class="tree">
                <ul>
                    <li>
                        <a> CMSC Multiple dependancies <br /> and special cases #1</a>
                        <ul>
                        	
                            <li>
                                <span> Pre-Reqs <br />
									<a class="required CMSC313" onclick="toggleClass('CMSC313')">CMSC 313</a> <br/> 
									<a class="required CMSC341" onclick="toggleClass('CMSC341')">CMSC 341</a> 
								</span>
                                <ul>
                                	<li>                                                                           
                                        <a class="required CMSC421" onclick="toggleClass('CMSC421')">CMSC 421</a>
                                        <ul>
                                            <li>
                                                <span class="CMSC313CMSC341CMSC421dep"> Dependants <br />
                                                    <a class="CMSC426" onclick="toggleClass('CMSC426')">CMSC 426</a> <br />
                                                    <a class="CMSC483" onclick="toggleClass('CMSC483')">CMSC 483</a> 
                                                </span>
                                            </li>
                                        </ul>                                                                         
                                    </li>
                                	<li>
                                		<a class="CMSC432" onclick="toggleClass('CMSC432')">CMSC 432</a>
                                	</li>
                                </ul>
                            </li>
							
							<li>
								<span> Pre-Reqs <br />
									<a class="required CMSC313" onclick="toggleClass('CMSC313')">CMSC 313</a> <br /> 
									<a class="required CMSC341" onclick="toggleClass('CMSC341')">CMSC 341</a> <br />
									<a class="MATH221" onclick="toggleClass('MATH221')">MATH 221</a> 
								</span>
									<ul>
										<li>
											<a class="CMSC435" onclick="toggleClass('CMSC435')">CMSC 435</a> 
										</li>
									</ul>
							</li>

							<li>
								<span> Pre-Reqs <br />
									<a class="required CMSC341" onclick="toggleClass('CMSC341')">CMSC 341</a> <br />
									<a class="MATH221" onclick="toggleClass('MATH221')">MATH 221</a>
								</span>
									<ul>
										<li>
											<a class="CMSC437" onclick="toggleClass('CMSC437')">CMSC 437</a>
										</li>
									</ul>
							</li>
							
							<li>
								<span> Pre-Reqs <br />
									<a class="required CMSC341" onclick="toggleClass('CMSC341')">CMSC 341</a> <br />
									<a class="MATH152" onclick="toggleClass('MATH152')">MATH 152</a> <br />
									<a class="STAT355" onclick="toggleClass('STAT355')">STAT 355</a>
								</span>
									<ul>
										<li>
											<a class="required CMSC441" onclick="toggleClass('CMSC441')">CMSC 441</a>
										</li>
									</ul>
							</li>

							<li>
								<span> Pre-Reqs <br />
									<a class="required CMSC203" onclick="toggleClass('CMSC203')">CMSC 203</a> <br />
									<a class="MATH221" onclick="toggleClass('MATH221')">MATH 221</a>
								</span>
									<ul>
										<li>
											<a class="CMSC442" onclick="toggleClass('CMSC442')">CMSC 442</a>
										</li>
									</ul>
							</li>

							<li>
								<span> Pre-Reqs <br />
									<a class="required CMSC341" onclick="toggleClass('CMSC341')">CMSC 341</a> <br />
									<a class="MATH221" onclick="toggleClass('MATH221')">MATH 221</a> <br />
									<a class="STAT355" onclick="toggleClass('STAT355')">STAT 355</a>
								</span>
									<ul>
										<li>
											<a class="CMSC443" onclick="toggleClass('CMSC443')">CMSC 443</a> 
										</li>
									</ul>
							</li>

							<li>
                                <a id="any400">Any 4xx<br /> & 341</a>
                                <ul>
                                    <li>
                                        <a class="required CMSC447" onclick="toggleClass('CMSC447')">CMSC 447</a>
                                        <ul>
                                            <li>
                                                <a class="CMSC448" onclick="toggleClass('CMSC448')">CMSC 448</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </li>
                </ul>
            </div>	

            <!-- Multi Req Tree for CMSC -->
            <div class="tree">
                <ul>
                    <li>
                        <a>CMSC Multiple dependancies <br /> and special cases #2</a>
                        <ul>
                        	
							<li>
                                <span> Pre-Reqs <br />
									<a class="required CMSC202" onclick="toggleClass('CMSC202')">CMSC 202</a> <br/> 
									<a class="required CMSC203" onclick="toggleClass('CMSC203')">CMSC 203</a> 
								</span>
                                <ul>
                                    <li>                                                                           
                                        <a class="CMSC451" onclick="toggleClass('CMSC451')">CMSC 451</a>                      
                                    </li>
                                </ul>
                            </li>

							<li>
								<span> Pre-Reqs <br />
									<a class="required CMSC341" onclick="toggleClass('CMSC341')">CMSC 341</a> <br />
									<a class="MATH152" onclick="toggleClass('MATH152')">MATH 152</a> <br />
									<a class="MATH221" onclick="toggleClass('MATH221')">MATH 221</a>
								</span>
									<ul>
										<li>
											<span class="CMSC341MATH152MATH221dep"> Dependants <br />
											<a class="CMSC453" onclick="toggleClass('CMSC453')">CMSC 453</a><br />
											<a class="CMSC455" onclick="toggleClass('CMSC455')">CMSC 455</a><br />
											<a class="CMSC456" onclick="toggleClass('CMSC456')">CMSC 456</a>
											</span>
										</li>
									</ul>
							</li>
							 
							<li>
                                <span> Pre-Reqs <br />
									<a class="required CMSC203" onclick="toggleClass('CMSC203')">CMSC 203</a> <br/> 
									<a class="required MATH221" onclick="toggleClass('MATH221')">MATH 221</a> 
								</span>
                                <ul>
                                    <li>                                                                           
                                        <a class="CMSC457" onclick="toggleClass('CMSC457')">CMSC 457</a>                  
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <span> Pre-Reqs <br />
									<a class="CMSC461" onclick="toggleClass('CMSC461')">CMSC 461</a> <br />
									<a class="CMSC481" onclick="toggleClass('CMSC481')">CMSC 481</a>
								</span>
                                <ul>
                                    <li>
                                        <span class="CMSC461CMSC481dep"> Dependants <br />
                                            <a class="CMSC465" onclick="toggleClass('CMSC465')">CMSC 465</a> <br />
                                            <a class="CMSC466" onclick="toggleClass('CMSC466')">CMSC 466</a>
                                        </span>
                                    </li>
                                </ul>
                            </li>

                            <li>
								<span> Pre-Reqs <br />
									<a class="required CMSC202" onclick="toggleClass('CMSC202')">CMSC 202</a> <br />
									<a class="MATH152" onclick="toggleClass('MATH152')">MATH 152</a>
								</span>
									<ul>
										<li>
											<a class="CMSC486" onclick="toggleClass('CMSC486')">CMSC 486</a>
										</li>
									</ul>
							</li>

                            <li>
                                <span> Pre-Reqs <br />
									<a class="required CMSC421" onclick="toggleClass('CMSC421')">CMSC 421</a> <br />
									<a class="CMSC481" onclick="toggleClass('CMSC481')">CMSC 481</a>
								</span>
                                <ul>
                                    <li>
                                        <a class="CMSC487" onclick="toggleClass('CMSC487')">CMSC 487</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <span> Pre-Reqs <br />
									<a class="CMSC435" onclick="toggleClass('CMSC435')">CMSC 435</a> <br />
									<a class="CMSC471" onclick="toggleClass('CMSC471')">CMSC 471</a>
								</span>
                                <ul>
                                    <li>
                                        <a class="CMSC493" onclick="toggleClass('CMSC493')">CMSC 493</a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </li>
                </ul>
            </div>	
			
			<!-- Multi Req Tree for Math -->	
		 	<div class="tree">
                <ul>
                    <li>
                        <a>Two Math Classes to fulifull CMSC Elective</a>
                        <ul>
                        	
                            <li>
                            	<span> Pre-Reqs <br />
									<a class="MATH251" onclick="toggleClass('MATH251')">MATH 251</a> <br />
									<a class="MATH301" onclick="toggleClass('MATH301')">MATH 301</a>  
                                </span>
                                <ul>
                                    <li>  
                                        <a class="MATH430" onclick="toggleClass('MATH430')">MATH 430</a>                  
                                    </li>
                                </ul>
                            </li>

    						<li>
                            	<span> Pre-Reqs <br />
                            		<a class="required CMSC201" onclick="toggleClass('CMSC201')">CMSC 201</a> <br /> 
                            		<a class="MATH225" onclick="toggleClass('MATH225')">MATH 225</a> <br /> 
									<a class="MATH251" onclick="toggleClass('MATH251')">MATH 251</a> <br />
									<a class="MATH301" onclick="toggleClass('MATH301')">MATH 301</a>  
                                </span>
                                <ul>
                                    <li>  
                                        <a class="MATH441" onclick="toggleClass('MATH441')">MATH 441</a>                  
                                    </li>
                                </ul>
                            </li>

                            <li>
								<a class="STAT355" onclick="toggleClass('STAT355')">STAT 355</a>
                                <ul>
                                    <li>  
                                        <a class="MATH452" onclick="toggleClass('MATH452')">MATH 452</a>                  
                                    </li>
                                </ul>
                            </li>

                            <li>
								<a class="MATH301" onclick="toggleClass('MATH301')">MATH 301</a> 
                                <ul>
                                    <li>  
                                        <a class="MATH475" onclick="toggleClass('MATH475')">MATH 475</a>               
                                    </li>
                                </ul>
                            </li>

    						<li>
                            	<span> Pre-Reqs <br />
                            		<a class="MATH221" onclick="toggleClass('MATH221')">MATH 221</a> <br /> 
									<a class="MATH225" onclick="toggleClass('MATH225')">MATH 225</a> <br />
									<a class="MATH251" onclick="toggleClass('MATH251')">MATH 251</a>  
                                </span>
                                <ul>
                                    <li>  
                                        <a class="MATH481" onclick="toggleClass('MATH481')">MATH 481</a>                  
                                    </li>
                                </ul>
                            </li>

                            <li>
								<a class="MATH221" onclick="toggleClass('MATH221')">MATH 221</a>
                                <ul>
                                    <li>  
                                        <a class="MATH381" onclick="toggleClass('MATH381')">MATH 381</a>
                                        <ul>
                                    		<li>  
                                     		   <a class="MATH483" onclick="toggleClass('MATH483')">MATH 483</a>               
                                   			 </li>
                              		    </ul>                  
                                    </li>
                                </ul>
                            </li>

    					 </ul>
                    </li>
                </ul>
            </div>	

            <!-- Multi Req Tree for Math -->	
		 	<div class="tree">
                <ul>
                    <li>
                        <a>Additional Math to add up 12 credit Sci</a>
                        <ul>
                        	<li>
								<a class="MATH152" onclick="toggleClass('MATH152')">MATH 152</a>
                                <ul>
                                    <li>                                                                           
                                    	<span class="MATH152dep"> Dependants <br />
                                            <a class="MATH225" onclick="toggleClass('MATH225')">MATH 225</a> <br />
                                            <a class="MATH251" onclick="toggleClass('MATH251')">MATH 251</a>
                                        </span>                                          
                                    </li>
                                </ul>
                            </li>

                            <li>
                            	<span> Pre-Reqs <br />
									<a class="MATH152" onclick="toggleClass('MATH152')">MATH 152</a> <br />
									<a class="MATH221" onclick="toggleClass('MATH221')">MATH 221</a>
                                </span>
                                <ul>
                                    <li>  
                                        <a class="MATH301" onclick="toggleClass('MATH301')">MATH 301</a>                  
                                    </li>
                                </ul>
                            </li>
    					 </ul>
                    </li>
                </ul>
            </div>	

            <div class="tree">
                <ul>
                    <li>
                        <a href="#">Sciences</a>
                        <ul>
                            <li>
                                <span id="no-prereq" href="#">No Prereqs <br />
                                    <a class="GES110" href="#">GES 110</a> <br />
                                    <a class="GES111" href="#">GES 111</a> <br />
                                    <a class="GES120" href="#">GES 120</a> <br />
                                </span>
                            </li>
                            <li>
                                <a class="Math151">MATH 151</a>
                                <ul>
                                    <li>
                                        <a class="CHEM101">CHEM 101</a>
                                         <ul>
                                            <li>
                                                <a class="CHEM102">CHEM 102</a>
                                                <ul>
                                                    <li>
                                                        <a class="CHEM102L">CHEM 102L</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a class="BIOL141">BIOL 141</a>
                                         <ul>
                                            <li>
                                                <span id="142_275">
                                                    <a class="BIOL142">BIOL 142</a><br />
                                                    <a class="BIOL275">BIOL 275</a>
                                                </span>
                                                <ul>
                                                    <li>
                                                        <a class="BIOL275L">BIOL 275L</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <span id="152_121">
                                            <a class="MATH152">MATH 152</a><br />
                                            <a class="PHYS121">PHYS 121</a>
                                        </span>
                                         <ul>
                                            <li>
                                                <a class="PHYS122">PHYS 122</a>
                                                <ul>
                                                    <li>
                                                        <span id="122L_224_304">
                                                            <a class="PHYS122L">PHYS 122L</a><br />
                                                            <a class="PHYS224">PHYS 224</a><br />
                                                            <a class="PHYS304">PHYS 304</a><br />
                                                        </span>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="102_141">Chem 102 & Biol 141</a>
                                <ul>
                                    <li> 
                                        <a class="BIOL251">BIOL 251</a>
                                        <ul>
                                            <li> 
                                                <span>
                                                    <a class="BIOL251L">BIOL 251L</a><br />
                                                    <a class="BIOL252">BIOL 252</a>
                                                </span>
                                                <ul>
                                                    <li> 
                                                        <a class="BIOL252L">BIOL 252L</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="151_102_141_142">MATH 151 & CHEM 102<br />BIOL 141 & BIOL 142</a>
                                <ul>
                                    <li> 
                                        <a class="BIOL302">BIOL 302</a>
                                        <ul>
                                            <li> 
                                                <a class="BIOL303">BIOL 303</a>
                                                <ul>
                                                    <li> 
                                                        <a class="BIOL304">BIOL 304</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a clas="303_122">BIOL 303 & PHYS 122</a>
                                <ul>
                                    <li>
                                        <a class="BIOL305">BIOL 305</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="tree">
                <ul>
                    <li>
                        <a href="Biology_Labs">Biology Labs</a>
                        <ul>
                            <li>
                                <a class="CHEM102L_BIOL302">CHEM 102L & BIOL 302</a>
                                <ul>
                                    <li>
                                        <a class="BIOL300L">BIOL 300L</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="302_300L">BIOL 302 & 300L</a>
                                <ul>
                                    <li>
                                        <a class="BIOL302L">BIOL 302L</a>
                                    </li>   
                                </ul>
                            </li>
                            <li>
                                <a class="303_300L">BIOL 303 & 300L</a>
                                <ul>
                                    <li>
                                        <a class="BIOL303L">BIOL 303L</a>
                                    </li>   
                                </ul>
                            </li>
                            <li>
                                <a class="304_300L">BIOL 304 & 300L</a>
                                <ul>
                                    <li>
                                        <a class="BIOL304L">BIOL 304L</a>
                                    </li>   
                                </ul>
                            </li>
                            <li>
                                <a class="305_300L">BIOL 305 & 300L</a>
                                <ul>
                                    <li>
                                        <a class="BIOL305L">BIOL 305L</a>
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

