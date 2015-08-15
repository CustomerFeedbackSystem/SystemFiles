<?php
session_start();

if (!isset($_SESSION['installation']))
	{
	$_SESSION['installation']=1;
	} 
	
if (isset($_GET['installation']))
	{
	$step=mysql_real_escape_string(trim($_GET['installation']));
		if ( ($step>0) && ($step<5) )
			{
			$_SESSION['installation']=$step;
			}
	}	
	
//screen size resolution
if ( (isset($_GET['width'])) && (isset($_GET['height'])) )
	{ 
	//calculate adjustments
	$adj_width=number_format((0.08*$_GET['width']),0);
	$adj_height=number_format((0.3*$_GET['height']),0);
		
	$_SESSION['tb_width']=($_GET['width']-$adj_width);
	$_SESSION['tb_height']=($_GET['height']-$adj_height);
	$_SESSION['set_width']=1;
			
	} else {     
	$_SESSION['tb_width']=1000;
	$_SESSION['tb_height']=520;
	$_SESSION['set_width']=1;
	}     

if (!isset($_POST['nocsrf'])) //
	{
	include ('../nocsrf.php');
	}

//if user alreay logged in, then do not load this page. Take them back to the protected area
if ( (isset($_SESSION['MVGitHub_logstatus'])) && ($_SESSION['MVGitHub_logstatus']="IS_LOGGED_IN") && (isset($_SESSION['MVGitHub_idacname'])) && (isset($_SESSION['MVGitHub_iduserrole'])) && (isset($_SESSION['MVGitHub_idacteam'])) && (isset($_SESSION['MVGitHub_iduserprofile'])) )
	{
	header('location:../../myac/');
	exit;
	}

$token = NoCSRF::generate( 'nocsrf' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Installation</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<link href="../user_login/a/slider.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery_1.4.2.js"></script>
<script type="text/javascript" src="../scripts/jquery.simpleSlide.js"></script>
<script type="text/javascript" src="../uilock/jquery.uilock.js"></script>
<script language="Javascript">
        $(document).ready(function(){
            $('input').keypress(function(e) { 
                var s = String.fromCharCode( e.which );

                if((s.toUpperCase() === s && s.toLowerCase() !== s && !e.shiftKey) ||
                   (s.toUpperCase() !== s && s.toLowerCase() === s && e.shiftKey)){
                    if($('#capsalert').length < 1) $(this).after('<b style="color:#ff0000;font-family:arial" id="capsalert">CapsLock is on!</b>');
                } else {
                    if($('#capsalert').length > 0 ) $('#capsalert').remove();
                }
            });
        });
    </script>
<script language="javascript">
//Logging in preloader
			$(document).ready(function() {
				//$('#lock').click(function(){
				$('#button_login').click(function(){
				
					// To lock user interface interactions
					// Optinal: put html on top of the lock section,
					// like animated loading gif
					
					//$.uiLock('some html and <a href="#" onclick="$.uiUnlock();">unlock</a>');
				$.uiLock('<center class=msg_ok_overlay>Logging In...One Moment Please...</center>');
					
				});
				
				
				// To unlock user interface interactions
				//$.uiUnlock();

			});
			

</script>	
</head>
<body onLoad="document.login.account_usr.focus();">
<?php
//if (!isset($_SESSION['set_width']))
if (!isset($_GET['height']))
	{
	echo "<script language=\"JavaScript\">     
	<!--      
		document.location=\"".$_SERVER['PHP_SELF']."?resolution=1&width=\"+screen.width+\"&height=\"+screen.height;     
	//-->     
	</script>"; 
	}
?>
<div>
	<div class="title_header" ><?php require_once('header.php');?></div>
    <div style="padding:0px 0px 190px 0px;" >
    	<table border="0" cellpadding="3" cellspacing="0" width="100%">
        	<tr>
            	<td width="23%" valign="top" style="padding:100px 20px 10px 20px" class="text_body">&nbsp;</td>
            	<td width="77%" valign="top" style="padding:30px 20px 0px 30px" align="left">
                <div>
                    <div style="padding:10px 10px 20px 10px">
                    <a href="<?php echo $_SERVER['PHP_SELF'];?>?installation=1" <?php if ($_SESSION['installation']==1) { echo "style=\"font-weight:bold;color:#cc0000;text-decoration:none\" "; } ?>>Step 1</a> <span style="padding:0px 40px 0px 20px">|</span>
                    <a href="<?php echo $_SERVER['PHP_SELF'];?>?installation=2" <?php if ($_SESSION['installation']==2) { echo "style=\"font-weight:bold;color:#cc0000;text-decoration:none\" "; } ?>>Step 2</a> <span style="padding:0px 20px">|</span>
                    <a href="<?php echo $_SERVER['PHP_SELF'];?>?installation=3" <?php if ($_SESSION['installation']==3) { echo "style=\"font-weight:bold;color:#cc0000;text-decoration:none\" "; } ?>>Step 3</a> <span style="padding:0px 20px">|</span>
                    <a href="<?php echo $_SERVER['PHP_SELF'];?>?installation=4" <?php if ($_SESSION['installation']==4) { echo "style=\"font-weight:bold;color:#cc0000;text-decoration:none\" "; } ?>>Step 4</a> 
                    </div>
                    <div style="padding:15px 10px 10px 0px;">
                    <form method="post" action="">
                    <table border="0" cellpadding="0" cellspacing="0">
       	  			<tr>
                    	<td colspan="2" class="table_header_alt">
                        Initial Information                        </td>
                    </tr>
                    <tr>
                        	<td height="35">
                            <span style="color:#ff0000;font-size:20px;font-weight:bold">*</span>
                            Company Name</td>
                            <td><input type="text" name="company" size="60" maxlength="50" /></td>
                        </tr>
                        <tr>
                        	<td height="35">
                            <span style="color:#ff0000;font-size:20px;font-weight:bold">*</span>
                            Country</td>
                            <td>
                            <select id="countries" name="countries">
<option value="Afghanistan">Afghanistan</option>
<option value="Aland Islands">Aland Islands</option>
<option value="Albania">Albania</option>
<option value="Algeria">Algeria</option>
<option value="American Samoa">American Samoa</option>
<option value="Andorra">Andorra</option>
<option value="Angola">Angola</option>
<option value="Anguilla">Anguilla</option>
<option value="Antarctica">Antarctica</option>
<option value="Antigua and Barbuda">Antigua and Barbuda</option>
<option value="Argentina">Argentina</option>
<option value="Armenia">Armenia</option>
<option value="Aruba">Aruba</option>
<option value="Australia">Australia</option>
<option value="Austria">Austria</option>
<option value="Azerbaijan">Azerbaijan</option>
<option value="Bahamas">Bahamas</option>
<option value="Bahrain">Bahrain</option>
<option value="Bangladesh">Bangladesh</option>
<option value="Barbados">Barbados</option>
<option value="Belarus">Belarus</option>
<option value="Belgium">Belgium</option>
<option value="Belize">Belize</option>
<option value="Benin">Benin</option>
<option value="Bermuda">Bermuda</option>
<option value="Bhutan">Bhutan</option>
<option value="Bolivia">Bolivia</option>
<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
<option value="Botswana">Botswana</option>
<option value="Bouvet Island">Bouvet Island</option>
<option value="Brazil">Brazil</option>
<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
<option value="Brunei Darussalam">Brunei Darussalam</option>
<option value="Bulgaria">Bulgaria</option>
<option value="Burkina Faso">Burkina Faso</option>
<option value="Burundi">Burundi</option>
<option value="Cambodia">Cambodia</option>
<option value="Cameroon">Cameroon</option>
<option value="Canada">Canada</option>
<option value="Cape Verde">Cape Verde</option>
<option value="Cayman Islands">Cayman Islands</option>
<option value="Central African Republic">Central African Republic</option>
<option value="Chad">Chad</option>
<option value="Chile">Chile</option>
<option value="China">China</option>
<option value="Christmas Island">Christmas Island</option>
<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
<option value="Colombia">Colombia</option>
<option value="Comoros">Comoros</option>
<option value="Congo">Congo</option>
<option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
<option value="Cook Islands">Cook Islands</option>
<option value="Costa Rica">Costa Rica</option>
<option value="Cote D'ivoire">Cote D'ivoire</option>
<option value="Croatia">Croatia</option>
<option value="Cuba">Cuba</option>
<option value="Cyprus">Cyprus</option>
<option value="Czech Republic">Czech Republic</option>
<option value="Denmark">Denmark</option>
<option value="Djibouti">Djibouti</option>
<option value="Dominica">Dominica</option>
<option value="Dominican Republic">Dominican Republic</option>
<option value="Ecuador">Ecuador</option>
<option value="Egypt">Egypt</option>
<option value="El Salvador">El Salvador</option>
<option value="Equatorial Guinea">Equatorial Guinea</option>
<option value="Eritrea">Eritrea</option>
<option value="Estonia">Estonia</option>
<option value="Ethiopia">Ethiopia</option>
<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
<option value="Faroe Islands">Faroe Islands</option>
<option value="Fiji">Fiji</option>
<option value="Finland">Finland</option>
<option value="France">France</option>
<option value="French Guiana">French Guiana</option>
<option value="French Polynesia">French Polynesia</option>
<option value="French Southern Territories">French Southern Territories</option>
<option value="Gabon">Gabon</option>
<option value="Gambia">Gambia</option>
<option value="Georgia">Georgia</option>
<option value="Germany">Germany</option>
<option value="Ghana">Ghana</option>
<option value="Gibraltar">Gibraltar</option>
<option value="Greece">Greece</option>
<option value="Greenland">Greenland</option>
<option value="Grenada">Grenada</option>
<option value="Guadeloupe">Guadeloupe</option>
<option value="Guam">Guam</option>
<option value="Guatemala">Guatemala</option>
<option value="Guernsey">Guernsey</option>
<option value="Guinea">Guinea</option>
<option value="Guinea-bissau">Guinea-bissau</option>
<option value="Guyana">Guyana</option>
<option value="Haiti">Haiti</option>
<option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
<option value="Honduras">Honduras</option>
<option value="Hong Kong">Hong Kong</option>
<option value="Hungary">Hungary</option>
<option value="Iceland">Iceland</option>
<option value="India">India</option>
<option value="Indonesia">Indonesia</option>
<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
<option value="Iraq">Iraq</option>
<option value="Ireland">Ireland</option>
<option value="Isle of Man">Isle of Man</option>
<option value="Israel">Israel</option>
<option value="Italy">Italy</option>
<option value="Jamaica">Jamaica</option>
<option value="Japan">Japan</option>
<option value="Jersey">Jersey</option>
<option value="Jordan">Jordan</option>
<option value="Kazakhstan">Kazakhstan</option>
<option value="Kenya">Kenya</option>
<option value="Kiribati">Kiribati</option>
<option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
<option value="Korea, Republic of">Korea, Republic of</option>
<option value="Kuwait">Kuwait</option>
<option value="Kyrgyzstan">Kyrgyzstan</option>
<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
<option value="Latvia">Latvia</option>
<option value="Lebanon">Lebanon</option>
<option value="Lesotho">Lesotho</option>
<option value="Liberia">Liberia</option>
<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
<option value="Liechtenstein">Liechtenstein</option>
<option value="Lithuania">Lithuania</option>
<option value="Luxembourg">Luxembourg</option>
<option value="Macao">Macao</option>
<option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
<option value="Madagascar">Madagascar</option>
<option value="Malawi">Malawi</option>
<option value="Malaysia">Malaysia</option>
<option value="Maldives">Maldives</option>
<option value="Mali">Mali</option>
<option value="Malta">Malta</option>
<option value="Marshall Islands">Marshall Islands</option>
<option value="Martinique">Martinique</option>
<option value="Mauritania">Mauritania</option>
<option value="Mauritius">Mauritius</option>
<option value="Mayotte">Mayotte</option>
<option value="Mexico">Mexico</option>
<option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
<option value="Moldova, Republic of">Moldova, Republic of</option>
<option value="Monaco">Monaco</option>
<option value="Mongolia">Mongolia</option>
<option value="Montenegro">Montenegro</option>
<option value="Montserrat">Montserrat</option>
<option value="Morocco">Morocco</option>
<option value="Mozambique">Mozambique</option>
<option value="Myanmar">Myanmar</option>
<option value="Namibia">Namibia</option>
<option value="Nauru">Nauru</option>
<option value="Nepal">Nepal</option>
<option value="Netherlands">Netherlands</option>
<option value="Netherlands Antilles">Netherlands Antilles</option>
<option value="New Caledonia">New Caledonia</option>
<option value="New Zealand">New Zealand</option>
<option value="Nicaragua">Nicaragua</option>
<option value="Niger">Niger</option>
<option value="Nigeria">Nigeria</option>
<option value="Niue">Niue</option>
<option value="Norfolk Island">Norfolk Island</option>
<option value="Northern Mariana Islands">Northern Mariana Islands</option>
<option value="Norway">Norway</option>
<option value="Oman">Oman</option>
<option value="Pakistan">Pakistan</option>
<option value="Palau">Palau</option>
<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
<option value="Panama">Panama</option>
<option value="Papua New Guinea">Papua New Guinea</option>
<option value="Paraguay">Paraguay</option>
<option value="Peru">Peru</option>
<option value="Philippines">Philippines</option>
<option value="Pitcairn">Pitcairn</option>
<option value="Poland">Poland</option>
<option value="Portugal">Portugal</option>
<option value="Puerto Rico">Puerto Rico</option>
<option value="Qatar">Qatar</option>
<option value="Reunion">Reunion</option>
<option value="Romania">Romania</option>
<option value="Russian Federation">Russian Federation</option>
<option value="Rwanda">Rwanda</option>
<option value="Saint Helena">Saint Helena</option>
<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
<option value="Saint Lucia">Saint Lucia</option>
<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
<option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
<option value="Samoa">Samoa</option>
<option value="San Marino">San Marino</option>
<option value="Sao Tome and Principe">Sao Tome and Principe</option>
<option value="Saudi Arabia">Saudi Arabia</option>
<option value="Senegal">Senegal</option>
<option value="Serbia">Serbia</option>
<option value="Seychelles">Seychelles</option>
<option value="Sierra Leone">Sierra Leone</option>
<option value="Singapore">Singapore</option>
<option value="Slovakia">Slovakia</option>
<option value="Slovenia">Slovenia</option>
<option value="Solomon Islands">Solomon Islands</option>
<option value="Somalia">Somalia</option>
<option value="South Africa">South Africa</option>
<option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
<option value="Spain">Spain</option>
<option value="Sri Lanka">Sri Lanka</option>
<option value="Sudan">Sudan</option>
<option value="Suriname">Suriname</option>
<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
<option value="Swaziland">Swaziland</option>
<option value="Sweden">Sweden</option>
<option value="Switzerland">Switzerland</option>
<option value="Syrian Arab Republic">Syrian Arab Republic</option>
<option value="Taiwan, Province of China">Taiwan, Province of China</option>
<option value="Tajikistan">Tajikistan</option>
<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
<option value="Thailand">Thailand</option>
<option value="Timor-leste">Timor-leste</option>
<option value="Togo">Togo</option>
<option value="Tokelau">Tokelau</option>
<option value="Tonga">Tonga</option>
<option value="Trinidad and Tobago">Trinidad and Tobago</option>
<option value="Tunisia">Tunisia</option>
<option value="Turkey">Turkey</option>
<option value="Turkmenistan">Turkmenistan</option>
<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
<option value="Tuvalu">Tuvalu</option>
<option value="Uganda">Uganda</option>
<option value="Ukraine">Ukraine</option>
<option value="United Arab Emirates">United Arab Emirates</option>
<option value="United Kingdom">United Kingdom</option>
<option value="United States">United States</option>
<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
<option value="Uruguay">Uruguay</option>
<option value="Uzbekistan">Uzbekistan</option>
<option value="Vanuatu">Vanuatu</option>
<option value="Venezuela">Venezuela</option>
<option value="Viet Nam">Viet Nam</option>
<option value="Virgin Islands, British">Virgin Islands, British</option>
<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
<option value="Wallis and Futuna">Wallis and Futuna</option>
<option value="Western Sahara">Western Sahara</option>
<option value="Yemen">Yemen</option>
<option value="Zambia">Zambia</option>
<option value="Zimbabwe">Zimbabwe</option>
</select>                            </td>
                        </tr>
                        <tr>
                        	<td height="35">County [ Optional ]</td>
                            <td><input type="text" name="company" size="30" maxlength="20" /></td>
                        </tr>
                        <tr>
                        	<td height="35">City or Town</td>
                            <td><input type="text" name="company" size="40" maxlength="20" /></td>
                        </tr>
                        <tr>
                        	<td height="35" bgcolor="#F2F2F2">
                            <span style="color:#ff0000;font-size:20px;font-weight:bold">*</span>
                            Branches / Regional Offices</td>
                          <td bgcolor="#F2F2F2"><input name="no_offices" type="text" value="1" size="5" maxlength="2" /></td>
                        </tr>
                        <tr>
                        	<td></td>
                            <td>
                            <input type="submit" value="Next Step &raquo;"  style="font-size:16px; padding:5px; margin:10px 0px"/>
                            </td>
                        </tr>
                    </table>
                    </form>
                    </div>
                    
                    
                    
                    <div>
                    <form method="post" action="">
                    <table border="0">
                    <tr>
                    	<td colspan="2" class="table_header_alt">Initial Users Information</td>
                    </tr>
                    	<tr>
                        	<td colspan="2">
                            [ to set up the workflow, system needs at least two user accounts below ]
                            </td>
                        </tr>
                    	<tr>
                        	<td width="172">sd</td>
                            <td width="234">d</td>
                        </tr>
                    </table>
                    </form>
                    </div>
                </div>
                </td>
          </tr>
        </table>
    </div>
    
<div class="text_small" style="background-color:#F6F6F6; padding:10px; position:fixed; bottom:1px; width:100%; font-size:10px ">

</div>
</div>
</body>
</html>
