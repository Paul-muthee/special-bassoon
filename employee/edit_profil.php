<?php require_once('../includes/sess.php'); ?>
<?php require_once('../Connections/conn.php'); ?>
<?php require_once('../includes/functions.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
 GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "");
}
?>
<?php
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "employee_login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
$colname_staff = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_staff = $_SESSION['MM_Username'];
}
mysql_select_db($database_conn, $conn);
$query_staff = sprintf("SELECT * FROM employee WHERE username = %s", GetSQLValueString($colname_staff, "text"));
$staff = mysql_query($query_staff, $conn) or die(mysql_error());
$row_staff = mysql_fetch_assoc($staff);
$totalRows_staff = mysql_num_rows($staff);

$username=$row_staff['id_no'];
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "edit")) {
	$pass1=($_POST['pass1']);
	$pass2=($_POST['pass2']);
	if($pass1==$pass2){
  $updateSQL = sprintf("UPDATE employee SET username=%s,password=%s,reason=%s,dfrom=%s,back=%s,apply=%s WHERE username=%s",
                       GetSQLValueString($_POST['username'], "text"),
					         GetSQLValueString($pass1, "text"),
					   GetSQLValueString($_POST['reason'], "text"),
					   GetSQLValueString($_POST['dfrom'], "text"),
                       GetSQLValueString($_POST['back'], "text"),
                       GetSQLValueString($_POST['apply'], "text"),

					   GetSQLValueString($colname_staff, "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
  echo "<script> alert('Sucess');</script>";
	}else{
		$passmissmatch="";
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

    <title>EMPLOYEELOGIN</title>

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

    <div class="brand" id="companyname">EMPLOYEE PORTAL</div>
    <div class="address-bar">The Plaza | 5483 | Beverly Hills, Thika 26892 | 555.519.2020</div>
    <!----------head---------->
    	<?php require_once("../includes/emp_head.php");?>
    <!----------head---------->
	<div class="container">
    <div class="row">
            <div class="box">
            	  <div class="col-lg-12 text-center">
                		<!--------------edit profile---------->
                        <form action="<?php echo $editFormAction; ?>" method="POST" name="edit" id="edit">
                          <table width="353" border="0" align="center" class="table-striped table-responsive">
                            <caption class="alert alert-danger">Edit Personal Information/leave application</caption>
                            <tr>
                              <th width="92" scope="row">New Username</th>
                              <td width="92"><label for="username" class="form-group"></label>
                              <input type="text" name="username" id="username" class="form-control" required autocomplete="off"></td>
                            </tr>
                            <tr>
                              <th scope="row">New Password</th>
                              <td><label for="password" class="form-group"></label>
                              <input type="password" name="pass1" id="pass1"  class="form-control" required autocomplete="off"></td>
                            </tr>
                            <tr>
                              <th scope="row">New Pass(confirm)</th>
                              <td><label for="password" class="form-group"></label>
                              <input type="password" name="pass2" id="pass2"  class="form-control" required autocomplete="off"></td>
                            </tr>
							 <tr>
                              <th width="92" scope="row">Reason</th>
                           <td><textarea name="reason" placeholder="Reason for Leave Application"></textarea></td>
                            </tr>
							<tr>
                              <th scope="row">Start Date </th>
                              <td><label for="dfrom" class="form-group"></label>
                              <input type="text" name="dfrom" id="dfrom"  class="form-control" required autocomplete="off"></td>
                            </tr>
							<tr>
                              <th scope="row">Return Date </th>
                              <td><label for="back" class="form-group"></label>
                              <input type="text" name="back" id="back"  class="form-control" required autocomplete="off"></td>
                            </tr>
							<tr>
					  <th scope="row">Apply leave </th>
					                                <td><label for="apply leave" class="form-group"></label>

	<select name="apply" class="form-control" required="required">
<option value=""></option>
<option value="applied">Apply</option>


</select>
</tr>
                            <tr>
                            <tr>
                              <th colspan="2" scope="row">
                              <input type="submit" name="update" id="update" value="Update"autocomplete="off" class="btn btn-primary">
                              </th>
                            </tr>
                             <tr>
                              <th colspan="2" scope="row">
                              <span id="message"><?php if(isset($passmissmatch)){$passmissmatch="Password Missmatch";}else{$passmissmatch="";}
							  echo $passmissmatch;
							  ?></span>
                              </th>
                            </tr>
                          </table>
                          <input type="hidden" name="MM_update" value="edit">
                        </form>
<!--------------edit profile---------->
             	 </div>
            </div>
      </div>
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

