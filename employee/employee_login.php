<?php require_once('../includes/sess.php'); ?>
<?php require_once('../Connections/conn.php'); ?>
<?php require_once('../includes/functions.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
 GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "");
}
?>
<?php
// *** Validate request to login to this site.
$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=($_POST['password']);
  $MM_fldUserAuthorization = "userlevel";
  $MM_redirectLoginSuccess = "employee_prof.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_conn, $conn);
  	
  $LoginRS__query=sprintf("SELECT username, password, userlevel FROM employee WHERE username=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $conn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'userlevel');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
   $failed_login="";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>emplProf</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/business-casual.css" rel="stylesheet">
    <link href="../css/mycss.css" rel="stylesheet" type="text/css">
    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
    <link href="../SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css">
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryAccordion.js" type="text/javascript"></script>
</head>

<body>

    <div class="brand" id="companyname">## COMPANY</div>
    <div class="address-bar">The Plaza | 5483 | Beverly Hills, Thika 26892 | 555.519.2020</div>
    <!----------head---------->
    	<?php require_once("../includes/start_head.php");?>
    <!----------head---------->
	<div class="container">
    <div class="row">
            <div class="box">
            	  <div class="col-lg-4 text-center">
                   <img src="../img/ems.jpg" width="980" height="380" alt="employee">
                   
                    </div>
                <div class="col-lg-4 text-center">
                   <!-------login------------>
                     
                  <form action="<?php echo $loginFormAction; ?>" method="post" name="employeelogin" id="employeelogin" role="form">
                    <table width="333" border="0" class="table table-responsive">
                      <tr>
                        <th colspan="4" scope="col"><center>EMPLOYEE LOGIN WINDOW</center></th>
                      </tr>
                      <tr>
                        
                        <td width="71">
                         <span id="sprytextfield1" class="form-group col-lg-6">
                         <label for="username">Username</label>
                         <input type="text" name="username" id="username" required placeholder="Enter Username">
                         <span class="textfieldRequiredMsg">A username is required.</span></span>
                       
                        </td>
                        <td width="79">
                         <span id="sprytextfield2" class="form-group col-lg-6">
                         <label for="password">Password</label>
                         <input type="password" name="password" id="password" required placeholder="Enter Password">
                         <span class="textfieldRequiredMsg">Password is required.</span></span>
                        </td>
                      </tr>
                      <tr>
                        
                        <td colspan="2">
                        <input type="submit" name="login" id="login" value="LOGIN" class="btn btn-primary">
                        </td>
                       
                      </tr>
                      <tr>
                        <td colspan="3">
                        <span id="message">
						<?php if(isset($failed_login)){$failed_login="Username/password Missmatch";}else{$failed_login="";}
                        echo $failed_login;
                         ?>
                         </span>
                        </td>
                      </tr>
                    </table>
                  </form>
                  <!-------login------------>
</div>
              <div class="col-lg-4 text-center"> </div>
            </div>
      </div>
	<!--------------content------------------------>
     <?php require_once("../includes/content.php");?>
	<!--------------content------------------------>
    </div>
    <!-- /.container -->

    <footer>
       <?php require_once("../includes/footer.php");?>
    </footer>

    <!-- JavaScript -->
<script src="../js/jquery-1.10.2.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/myjs.js"></script>
</body>

</html>

