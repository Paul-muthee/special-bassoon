<?php require_once('../includes/functions.php'); ?>
<?php require_once('../Connections/conn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
 GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "");
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "2";
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

$MM_restrictGoTo = "admin_login.php";
if (!((isset($_SESSION['MM_admin'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_admin'], $_SESSION['MM_UserGroup'])))) {   
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
$colname_update = "-1";
if (isset($_GET['id'])) {
  $colname_update = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_update = sprintf("SELECT * FROM staff WHERE id = %s", GetSQLValueString($colname_update, "int"));
$update = mysql_query($query_update, $conn) or die(mysql_error());
$row_update = mysql_fetch_assoc($update);
$totalRows_update = mysql_num_rows($update);
/*************************/
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "reg_staff")) {
	$password=$_POST['pass'];
  $updateSQL = sprintf("UPDATE staff SET fname=%s, lname=%s, id_no=%s,residence=%s, tel=%s,username=%s, password=%s WHERE id=%s",
                       GetSQLValueString($_POST['fname'], "text"),
                       GetSQLValueString($_POST['lname'], "text"),
					   GetSQLValueString($_POST['id_no'], "int"),
                       GetSQLValueString($_POST['residence'], "text"),
                       GetSQLValueString($_POST['tel'], "int"),
                       GetSQLValueString($_POST['username'], "text"),
					   GetSQLValueString($password, "text"),
                       GetSQLValueString($colname_update, "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());

  $updateGoTo = "update.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  echo "<script> alert('Updated!');
window.location.href='admin.php';</script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ADMIN</title>

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
    	<?php require_once("admin_head.php");?>
    <!----------head---------->
	<div class="container">
    <div class="row">
            <div class="box">
              <div class="col-lg-12 text-center"> 
                 <!------------reg company staff------------>
                <form action="<?php echo $editFormAction; ?>" method="POST" name="reg_staff" id="reg_staff">
                      <table width="473" border="0" align="center" class="table-responsive">
                      <tr>
                      <th colspan="2"><center><marquee>Staff Registration Form</marquee></center></th>
                      </tr>
                     <tr>
                       <th width="88" scope="row">Firstname</th>
                       <td width="203"><span id="sprytextfield1" class="form-group has-warning">
                         <label for="fname"></label>
                         <input type="text" name="fname" id="fname" class="form-control"  value="<?php echo $row_update['fname']; ?>">
                       <span class="textfieldRequiredMsg">A value is required.</span></span>
                       <?php //echo $error;?>
                       </td>
                     </tr>
                     <tr>
                       <th scope="row">lastname</th>
                       <td><span id="sprytextfield2" class="form-group has-warning">
                         <label for="lname"></label>
                         <input type="text" name="lname" id="lname" class="form-control" value="<?php echo $row_update['lname']; ?>">
                       <span class="textfieldRequiredMsg">A value is required.</span></span>
                       <?php //echo $error;?>
                       </td>
                     </tr>
                     <tr>
                       <th scope="row">id no</th>
                       <td><span id="sprytextfield3" class="form-group has-warning">
                       <label for="id_no"></label>
                       <input type="text" name="id_no" id="id_no" class="form-control"  value="<?php echo $row_update['id_no']; ?>" maxlength="8">
                       <span class="textfieldRequiredMsg">A value is required.</span></span>
                       <?php //echo $error;?>
                       </td>
                     </tr>
                     <tr>
                     <tr>
                       <th scope="row">residence</th>
                       <td>
                         <span id="sprytextfield4" class="form-group has-warning">
                         <label for="residence"></label>
                         <input type="text" name="residence" id="residence" class="form-control"  value="<?php echo $row_update['residence']; ?>">
                       <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                     </tr>
                     <tr>
                       <th scope="row">telephone</th>
                       <td><span id="sprytextfield5" class="form-group has-warning">
                       <label for="tel"></label>
                       <input type="text" name="tel" id="tel" class="form-control" value="<?php echo $row_update['tel']; ?>" maxlength="10">
                       <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                     </tr>
                     <tr>
                       <th scope="row">username</th>
                       <td><span id="sprytextfield6" class="form-group has-warning">
                         <label for="username" ></label>
                         <input type="text" name="username" id="username" class="form-control" value="<?php echo $row_update['username']; ?>">
                       <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                     </tr>
                     <tr>
                       <th scope="row">password</th>
                       <td><span id="sprytextfield7" class="form-group has-warning">
                         <label for="pass"></label>
                         <input type="password" name="pass" id="pass" class="form-control" required>
                       <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                     </tr>
                     <tr>
                       <th colspan="2" scope="row">
                       <input type="submit" name="reg" id="reg" value="UPDATE" class="form-control btn btn-success btn-lg">
                       </th>
                     </tr>
                   </table>
                      <input type="hidden" name="MM_update" value="reg_staff">
                  </form>
				<!------------reg company staff------------>
                
              </div>
            </div>
      </div>
	<!--------------content------------------------>
        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                 ggg
                </div>
            </div>
        </div>
	<!--------------content------------------------>
    <!--------------content------------------------>
        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                    lll
                </div>
            </div>
        </div>
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
<script type="text/javascript">
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
</script>
</body>

</html>
<?php
mysql_free_result($update);
?>
