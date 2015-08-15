<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');

//pull relevant data from the DB
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css_report_print.css" rel="stylesheet" type="text/css" media="print" />
<link href="css_report.css" rel="stylesheet" type="text/css" />
<title>Form 1</title>
</head>
<body>
<div class="hidden"><a href="#" onclick="window.print()"><img border="0" align="absmiddle" src="../assets_backend/btns/print_small.gif" /> Print this Page</a></div>
<div style=" width:650px;  padding:5px" class="border">
	<table border="0" cellpadding="4" cellspacing="0" width="650"> 
    	<tr>
        	<td height="30" colspan="2" align="center" class="text_body_large">
            BILLING ADJUSTMENT VOUCHER NCWSC/FD/ADJ/01/FORM 1            </td>
      </tr>
        <tr>
        	<td class="border_top_thick" >
            <div class="text_body_mod">1. CUSTOMER DETAILS</div>
            </td>
            <td class="border_top_thick">
            <div class="text_body_mod">VOUCHER NUMBER : </div>
            </td>
        </tr>
        <tr>
        	<td height="131" colspan="2" valign="top">
       	  <table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
                    	<td valign="top" class="border_top_right">
                        <div class="text_body_mod">Region</div>
                        <div class="text_data">Western Region</div>
                        </td>
                        <td valign="top" class="border_top_right">
                        <div class="text_body_mod">Account No.</div>
                        <div></div>
                      </td>
                      <td valign="top" class="border_top_right">
                        <div class="text_body_mod">Meter Reading</div>
                        <div></div>
            </td>
                      <td valign="top" class="border_top_right">
                        <div class="text_body_mod">Complaint No.</div>
                        <div></div>
            </td>
            </tr>
                    <tr>
                    	<td colspan="2" valign="top" class="border_top_right">
                       <div class="text_body_mod">Name of acount holder</div>
                        <div></div>
                      </td>
                      <td valign="top" class="border_top_right">
                       	<div class="text_body_mod">Tel. no.</div>
                        <div></div>
                      </td>
                      <td valign="top" class="border_top_right">
                        <div class="text_body_mod">Source of complaint:</div>
                        <div></div>
                      </td>
            </tr>
                </table>
          </td>
      </tr>
        
        <tr>
        	<td colspan="2" class="border_top_thick">
            <div class="text_body_large">2. PARTICULARS OF THE ADJUSTMENT</div>
            </td>
        </tr>
        <tr>
        	<td height="80" colspan="2" class="border_top" valign="top">
            <div class="text_body_mod">Reasons for adjustments:</div>
            <div></div>
          </td>
      	</tr>
        <tr>
        	<td class="border_top" colspan="2" valign="top">
           			<table border="0" cellpadding="2" cellspacing="0" width="100%"> 
                    	<tr>
                        	<td rowspan="4" valign="top">
                            <div class="text_body_mod">
                            Calculation of adjustment
                            </div>
                            <div>
                            sdf
                            </div>
                            </td>
                            <td height="31" class="border_btm_right">
                            <div class="text_body_mod">Period</div>                            </td>
                          <td  class="border_btm_right">
                            <div class="text_body_mod">Debit (Ksh)</div>
                            </td>
                            <td  class="border_btm_right">
                            <div class="text_body_mod">Credit (Ksh)</div>
                            </td>
                        </tr>
                        <tr>
                            <td  class="border_btm_right"><div class="text_body_mod">Prior</div></td>
                          <td  class="border_btm_right">&nbsp;</td>
                          <td  class="border_btm_right">&nbsp;</td>
                      </tr>
                        <tr>
                            <td  class="border_btm_right">
                            <div class="text_body_mod">Current</div></td>
                            <td  class="border_btm_right">&nbsp;</td>
                          <td  class="border_btm_right">&nbsp;</td>
                      </tr>
                        <tr>
                            <td  class="border_btm_right">
                            <div class="text_body_mod">Total</div>
                            </td>
                            <td  class="border_btm_right">&nbsp;</td>
                          <td  class="border_btm_right">&nbsp;</td>
                      </tr>
                    </table>
            </td>
         </tr>
         <tr>
        	<td class="border_top" colspan="2" valign="top">
            <div class="text_body_mod">Reference of Annexed documents</div>
            <div>--</div>
            </td>
         </tr>
         <tr>
        	<td height="122" colspan="2" valign="top">
           		<table border="0" width="100%" cellpadding="2" cellspacing="0">
   	  		  <tr>
                    	<td colspan="2" valign="top" class="border_top_right">
                        <div class="text_body_verysmall">
                        PREPARED BY (CREDIT CONTROL SUPERVISOR)
                        </div>
                        <div class="text_body_small">
                        NWC :
                        </div>
                        </td>
                      <td colspan="2" valign="top" class="border_top_right">
                        <div class="text_body_verysmall">
                        PREPARED BY (CREDIT CONTROL OFFICER)
                        </div>
                        <div class="text_body_small">
                        NWC :
                        </div>
                        </td>
                      <td colspan="2" valign="top" class="border_top_right">
                        <div class="text_body_verysmall">
                        CONFIRMED BY (REGIONAL FINANCE COORDINATOR)
                        </div>
                        <div class="text_body_small">
                        NWC :
                        </div>
                        </td>
                  </tr>
                    <tr>
                    	<td height="47" valign="top" class="border_top_right">
                        <div class="text_body_small">Date</div>
                        <div></div>
                      </td>
                      <td valign="top" class="border_top_right">
                        <div class="text_body_small">Sign</div>
                        <div></div>
                        </td>
                      <td valign="top" class="border_top_right">
                        <div class="text_body_small">Date</div>
                        <div></div>
                        </td>
                      <td valign="top" class="border_top_right">
                        <div class="text_body_small">Sign</div>
                        <div></div>
                        </td>
                      <td valign="top" class="border_top_right">
                        <div class="text_body_small">Date</div>
                        <div></div>
                        </td>
                      <td valign="top" class="border_top_right">
                        <div class="text_body_small">Sign</div>
                        <div></div>
                        </td>
                  </tr>
                </table>
          </td>
      </tr>
        <tr>
        	<td  colspan="2" class="border_top_thick">
            <div class="text_body_large">3. RECOMMENDATION AND APPROVAL</div>
            </td>
        </tr>
        <tr>
        	<td height="108" colspan="2" valign="top">
       		  <table border="0" width="100%" cellpadding="2" cellspacing="0">
           	  		<tr>
                    	<td colspan="2" class="border_top_right" valign="top">
                        <div class="text_body_small">
                        RECOMMENDED BY ( Regional Manager )
                        </div>
                        <div class="text_body_small"></div>
                      </td>
                        <td colspan="2" class="border_top_right" valign="top">
                        <div class="text_body_small">
                        Approved by ( Finance Director )
                        </div>
                        <div class="text_body_small"></div>
                      </td>
                       
                    </tr>
                    <tr>
                    	<td height="47" valign="top" class="border_top_right">
                        <div class="text_body_small">Date</div>
                        <div></div>
                      </td>
                      <td valign="top" class="border_top_right">
                        <div class="text_body_small">Sign</div>
                        <div></div>
                        </td>
                      <td valign="top" class="border_top_right">
                        <div class="text_body_small">Date</div>
                        <div></div>
                        </td>
                      <td valign="top" class="border_top_right">
                        <div class="text_body_small">Sign</div>
                        <div></div>
                        </td>
                      
                  </tr>
                </table>
          </td>
      </tr>
        
        <tr>
        	<td class="border_top_thick" colspan="2">
           <div class="text_body_large"> 4. DATA ENTRY INTO THE DATABASE</div>
            </td>
        </tr>
        <tr>
        	<td colspan="2" valign="top">
            <table border="0" width="100%" cellpadding="2" cellspacing="0">
            <tr>
                    	<td colspan="2" class="border_top_right" valign="top">
                        <div class="text_body_small">
                        Batch no. </div>
                        <div class="text_body_small"></div>
                      </td>
                        <td colspan="2" class="border_top_right" valign="top">
                        <div class="text_body_small">Total adjustment amount (Ksh) </div>
                        <div class="text_body_small"></div>
                      </td>
                       
                    </tr>
           	  		<tr>
                    	<td colspan="2" class="border_top_right" valign="top">
                        <div class="text_body_small">
                        Posted By ( RBO)
                        </div>
                        <div class="text_body_small"></div>
                      </td>
                        <td colspan="2" class="border_top_right" valign="top">
                        <div class="text_body_small">
                        Checked by (RFC )
                        </div>
                        <div class="text_body_small"></div>
                      </td>
                       
                    </tr>
                    <tr>
                    	<td width="18%" height="47" valign="top" class="border_top_right">
                        <div class="text_body_small">Date</div>
                        <div></div>
                      </td>
                      <td width="32%" valign="top" class="border_top_right">
                        <div class="text_body_small">Sign</div>
                        <div></div>
                      </td>
                      <td width="18%" valign="top" class="border_top_right">
                        <div class="text_body_small">Date</div>
                        <div></div>
                      </td>
                      <td width="32%" valign="top" class="border_top_right">
                        <div class="text_body_small">Sign</div>
                        <div></div>
                      </td>
                      
                  </tr>
                </table>
            </td>
        </tr>
        
    </table>
</div>
</body>
</html>
