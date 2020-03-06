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
mysql_select_db($database_conn, $conn);
$query_notify = "SELECT * FROM notice ORDER BY m_to DESC";
$notify = mysql_query($query_notify, $conn) or die(mysql_error());
$row_notify = mysql_fetch_assoc($notify);
$totalRows_notify = mysql_num_rows($notify);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "notice")) {
	$from="Admin";
  $insertSQL = sprintf("INSERT INTO notice (m_to,m_from,message) VALUES (%s,%s,%s)",
                       GetSQLValueString($_POST['to'], "text"),
					   GetSQLValueString($from, "text"),
					   GetSQLValueString($_POST['mess'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());

  $insertGoTo = "notices.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
//////////////////////////
if ((isset($_POST['hiddenField'])) && ($_POST['hiddenField'] != "")) {
  $deleteSQL = sprintf("DELETE FROM notice WHERE id=%s",
                       GetSQLValueString($_POST['hiddenField'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());

  $deleteGoTo = "notices.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
   echo "<script> alert('Deleted!');</script>";
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
            	  <div class="col-lg-8 text-center">
					<!------notices-------------->
                    <form name="form1" method="post" action="">
                      <table width="680" border="1">
                        <tr>
                          <th colspan="6" scope="col">NOTICES FEEDBACK</th>
                        </tr>
                        <tr>
                          <th width="81" scope="row">To</th>
                          <td width="159">Message</td>
                          <td width="102">Seen by</td>
                          <td colspan="3">Action</td>
                        </tr>
                      
                          <?php do { ?>
                             <?php if ($totalRows_notify > 0) { // Show if recordset not empty ?>
                            <tr>
  							<th scope="row"><?php echo $row_notify['m_to']; ?></th>
                              <td>
                                <?php echo $row_notify['message']; ?>
                               <span id="date"> <?php echo $row_notify['date']; ?></span>
                              </td>
                              <td>&nbsp;</td>
                              <td width="59"><a href="edit.php?id=<?php echo urlencode($row_notify['id']);?>" class="btn btn-xs btn-primary">Edit</a></td>
                              <td width="59">
                              <input type="submit" name="delete" id="delete" value="Delete" class="btn btn-xs btn-danger">
                              <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_notify['id']; ?>" />
                              </td>
                            </tr>
                                <?php } // Show if recordset not empty ?>
                            <?php } while ($row_notify = mysql_fetch_assoc($notify)); ?>
                       
                      </table>
                    </form>
<!------notices-------------->
              </div>
              <div class="col-lg-4 text-center">
                <form action="<?php echo $editFormAction; ?>" method="POST" name="notice" id="notice">
                  <table width="200" border="0" class="table table-responsive">
                    <tr>
                      <th colspan="2" scope="col">NOTICES</th>
                    </tr>
                    <tr>
                      <th scope="row">TO</th>
                      <td><label for="to2" class="form-group"></label>
                        <select name="to" id="to" required class="form-control">
                          <option value="Employee">Employee</option>
                          <option value="Manager">Manager</option>
                      </select></td>
                    </tr>
                    <tr>
                      <th scope="row">From</th>
                      <td><label for="from" class="form-group"></label>
                      <input type="text" name="from" id="from" required readonly class="form-control" placeholder="Dont type here"></td>
                    </tr>
                    <tr>
                      <th scope="row">Message</th>
                      <td><label for="message" class="form-group"></label>
                      <textarea name="mess" required id="mess" class="form-control"></textarea></td>
                    </tr>
                    <tr>
                      <th colspan="2" scope="row"><input type="submit" name="sent" id="sent" value="Sent"></th>
                    </tr>
                  </table>
                  <input type="hidden" name="MM_insert" value="notice">
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
mysql_free_result($notify);
?>
