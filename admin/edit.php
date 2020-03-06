<?php require_once('../Connections/conn.php'); ?>
<?php require_once('../includes/functions.php'); ?>
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

$colname_edit = "-1";
if (isset($_GET['id'])) {
  $colname_edit = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_edit = sprintf("SELECT * FROM notice WHERE id = %s", GetSQLValueString($colname_edit, "int"));
$edit = mysql_query($query_edit, $conn) or die(mysql_error());
$row_edit = mysql_fetch_assoc($edit);
$totalRows_edit = mysql_num_rows($edit);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "notice")) {
  $updateSQL = sprintf("UPDATE notice SET m_to=%s,message=%s WHERE id=%s",
                       GetSQLValueString($_POST['to'], "text"),
                       GetSQLValueString($_POST['mess'], "text"),
					    GetSQLValueString($colname_edit, "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());

  $updateGoTo = "notices.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
 echo "<script> alert('Updated!');window.location.href='notices.php';</script>";
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
    <link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
    <link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryAccordion.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
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
                <form action="<?php echo $editFormAction; ?>" method="POST" name="notice" id="notice">
                  <table width="512" border="0" align="center" class="table-responsive">
                    <tr>
                      <th colspan="2" scope="col">NOTICES</th>
                    </tr>
                    <tr>
                      <th width="159" scope="row">TO</th>
                      <td width="343"><label for="to2"  class="form-group"></label>
                        <select name="to" id="to" required value="<?php echo $row_edit['m_to']; ?>" class="form-control">
                          <option value="Employee">Employee</option>
                          <option value="Staff">Staff</option>
                      </select></td>
                    </tr>
                    <tr>
                      <th scope="row">From</th>
                      <td><label for="from" class="form-group"></label>
                      <input type="text" name="from" id="from" required readonly class="form-control"></td>
                    </tr>
                    <tr>
                      <th scope="row">Message(odd)</th>
                      <td><?php echo $row_edit['message']; ?> </td>
                    </tr>
                    <tr>
                      <th scope="row">Message(type new)</th>
                      <td><label for="message"  class="form-group"></label>
                      <textarea name="mess" required id="mess" class="form-control"></textarea>
                   </td>
                    </tr>
                    <tr>
                      <th colspan="2" scope="row">
                      <input type="submit" name="sent" id="sent" value="Update" class="btn btn-sm btn-info">
                      </th>
                    </tr>
                  </table>
                  <input type="hidden" name="MM_update" value="notice">
                </form>
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
<?php
mysql_free_result($edit);

mysql_free_result($edit);
?>
