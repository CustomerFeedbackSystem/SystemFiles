<?php
require_once('../../assets_backend/be_includes/config.php');

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
	include ('../../nocsrf.php');
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
<meta http-equiv="refresh" content="590" />
<title><?php echo $title_mega;?></title>
<link href="../../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<link href="slider.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../scripts/jquery_1.4.2.js"></script>
<script type="text/javascript" src="../../scripts/jquery.simpleSlide.js"></script>
<script type="text/javascript" src="../../uilock/jquery.uilock.js"></script>

<script language="javascript">
 $(document).ready( function($) {
		
	simpleSlide({'swipe':'true'});
	addNotranslate();
		
	$('.slideshow').live('mouseover mouseout', 
		function(event) {
			if(event.type == 'mouseover'){
				$(this).children('.left-button, .right-button').stop(true, true).fadeIn();
			}
			else {
				$(this).children('.left-button, .right-button').stop(true, true).fadeOut();
			}
		}
	);
	
	$('.auto-slider').each( function() {							 
		var related_group = $(this).attr('rel');
		clearInterval($.autoslide);
		$.autoslide = setInterval("simpleSlideAction('.right-button', " + related_group + ");", 4000);
	});	

	// Custom GA tracking event: AJAX Page Loads


});

function navColors(color_name){	
	$('ul#nav_links li').removeAttr('class');

	$('ul#nav_links li span').each( function() {
		if( $(this).attr('class') == color_name) {
			$(this).parent().addClass(color_name);
		}
	});
}

function addNotranslate() {
	$('.codeblock').addClass('notranslate');
	$('code').addClass('notranslate');
}
</script>

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
	<div ><?php require_once('../a/header.php');?></div>
    <div style="padding:0px 0px 190px 0px;" >
    	<table border="0" cellpadding="3" cellspacing="0" width="100%">
        	<tr>
            	<td width="50%" valign="top" style="padding:100px 20px 10px 20px" class="text_body">&nbsp;</td>
            	<td width="50%" valign="top" style="padding:80px 20px 0px 30px" align="left"><?php
						
						if (isset($error)) {
						?>
				  <div style="width:400px; text-align:left"><?php echo $error;?></div>
                        <?php
						}
						
						if (isset($result)) { echo "<div class=\"msg_warning\">".$result."</div>"; }
						?>
				<div style="padding:20px 0px">
                <form method="post" action="" class="inline" name="login" autocomplete="off">
                    <table align="center" width="400px" height="204" border="0" cellpadding="2" cellspacing="0" class="border_thick" >
               			<tr>
                            <td height="40" colspan="2" class="table_header" style="padding:5px 10px"><?php echo $lbl_login_formtitle;?>                            </td>
                      </tr>
                        <tr>
                            <td width="118" height="55" style="padding:20px 5px 10px 8px">
                            <strong><?php echo $lbl_username ;?></strong>
                            </td>
                          <td width="170" height="55"  style="padding:20px 5px 10px 8px">
                            <input type="text" name="account_usr" maxlength="50" value="<?php if (isset($_POST['account_usr'])) { echo $_POST['account_usr']; } ?>" autocomplete="off" size="30" />
                          </td>
                        </tr>
                         <tr>
                            <td height="40">
                            <strong><?php echo $lbl_password;?></strong>
                            </td>
                            <td height="40"><input type="password" name="account_pwd" autocomplete="off" maxlength="20" size="30" /></td>
                        </tr>
                       
                        <tr>
                            <td height="21"></td>
                          <td height="35"><a href="../password-reminder/">
                            <?php echo $link_forgotpwd;?>
                            </a></td>
                      </tr>
                      <tr>
                            <td height="55"></td>
                          <td valign="top">
                          <input type="hidden" value="authenticate" name="form_action" />
                          <input type="hidden" value="<?php echo $token;?>" name="nocsrf" />
                          
                            <a href="#" onClick="document.forms['login'].submit()" id="button_login"><input type="submit" value="Log In" /></a>
                        </td>
                      </tr>
                         
                    </table>
                </form>
                </div>
              </td>
          </tr>
        </table>
    </div>
<div class="text_small" style="background-color:#F6F6F6; padding:0px; position:fixed; bottom:1px; width:100%; font-size:10px ">
This application is Distributed under the GNU Lesser General Public License v3.0
</div>
</div>
</body>
</html>
