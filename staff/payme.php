<?php require_once('../includes/sess.php'); ?>
<?php require_once('../Connections/conn.php'); ?>
<?php require_once('../includes/functions.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
 GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "");
}
?>
<?php
$MM_authorizedUsers = "1";
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

$MM_restrictGoTo = "staff_login.php";
if (!((isset($_SESSION['staff'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['staff'], $_SESSION['MM_UserGroup'])))) {   
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
if (isset($_SESSION['staff'])) {
  $colname_staff = $_SESSION['staff'];
}
mysql_select_db($database_conn, $conn);
$query_staff = sprintf("SELECT * FROM staff WHERE username = %s", GetSQLValueString($colname_staff, "text"));
$staff = mysql_query($query_staff, $conn) or die(mysql_error());
$row_staff = mysql_fetch_assoc($staff);
$totalRows_staff = mysql_num_rows($staff);
?>
<?php
$colname_mymypayment = "-1";
if (isset($_GET['id'])) {
  $colname_mymypayment = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_mymypayment = sprintf("SELECT * FROM assign_tasks WHERE id = %s", GetSQLValueString($colname_mymypayment, "int"));
$mymypayment = mysql_query($query_mymypayment, $conn) or die(mysql_error());
$row_mymypayment = mysql_fetch_assoc($mymypayment);
$totalRows_mymypayment = mysql_num_rows($mymypayment);

mysql_select_db($database_conn, $conn);
$query_cost = "SELECT * FROM tasks ORDER BY id ASC";
$cost = mysql_query($query_cost, $conn) or die(mysql_error());
$row_cost = mysql_fetch_assoc($cost);
$totalRows_cost = mysql_num_rows($cost);
/********************************************************/
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "payme")) {
	$id=$row_mymypayment['id'];
	$paid_by=$row_staff['fname'];
	$amount=mysql_real_escape_string($_POST['amount']);
	$tax=(0.16*$amount);
	$nhif=mysql_real_escape_string($_POST['nhif']);
	$others=mysql_real_escape_string($_POST['others']);
	$compute=$tax+$nhif+$others;
	$total=$amount-$compute;
	$task_complete=$row_mymypayment['id_no'];
  $insertSQL = sprintf("INSERT INTO payment (fname, lname, id_no, amount,tax,nhif,others,total,payed_by) VALUES (%s, %s, %s, %s, %s, %s,%s, %s, %s)",
                       GetSQLValueString($_POST['fname'], "text"),
                       GetSQLValueString($_POST['lname'], "text"),
                       GetSQLValueString($_POST['id_no'], "int"),
                       GetSQLValueString($amount, "int"),
                       GetSQLValueString($tax, "int"),
					   GetSQLValueString($nhif, "int"),
                       GetSQLValueString($others, "int"),
					   GetSQLValueString($total, "int"),
					   GetSQLValueString($paid_by, "text"));
					  

 
  	$updateSQL = sprintf("UPDATE assign_tasks SET salary=%s,paid=%s,paid_by=%sWHERE id=%s",
                       GetSQLValueString($total, "int"),
					   GetSQLValueString(1, "int"),
					    GetSQLValueString($paid_by, "text"),
						GetSQLValueString($id, "int"));
	$updateSQL2 = sprintf("UPDATE employee SET task=%s WHERE id_no=%s",
                       GetSQLValueString(0, "int"),
						GetSQLValueString($task_complete, "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  $Result2 = mysql_query($updateSQL, $conn) or die(mysql_error());
  $Result3 = mysql_query($updateSQL2, $conn) or die(mysql_error());
	echo "<script> alert('Payed!');
window.location.href='payment.php';</script>";
/**********************************/
}

/************************************/

/***********************************************************/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>STAFF LOGIN</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/business-casual.css" rel="stylesheet">
    <link href="../css/mycss.css" rel="stylesheet" type="text/css">
    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
	<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>

<body>
    <!----------head---------->
    	<?php require_once("../includes/staff_head.php");?>
    <!----------head---------->
	<div class="container">
    <div class="row">
            <div class="box">
                    <div class="col-lg-3 text-center">
                      <table width="250" border="1">
                        <tr>
                          <th colspan="2" scope="col">Payment scheme</th>
                        </tr>
                        <tr>
                          <td>Task</td>
                          <td>Amount</td>
                        </tr>
                         <?php if ($totalRows_cost > 0) { // Show if recordset not empty ?>
                            <?php do { ?>
                        <tr>
                         
                          <td><?php echo $row_cost['taskname']; ?></td>
                          <td><?php echo $row_cost['amount']; ?></td>
                          </tr>
                          <?php } while ($row_cost = mysql_fetch_assoc($cost)); ?>
                            <?php } // Show if recordset not empty ?>
                      </table>
                    </div>
                <div class="col-lg-9 text-center">
                  <form action="<?php echo $editFormAction; ?>" method="POST" name="payme" id="payme">
                    <table width="589" border="1" align="center">
                      <tr>
                        <th width="102" scope="col">&nbsp;</th>
                        <th colspan="2" scope="col"><p>BIDCO OIL COMPANY </p>
                        PAYSLIP</th>
                        <th width="98" scope="col">&nbsp;</th>
                      </tr>
                      <tr>
                        <td>Firstname</td>
                        <td width="139"><label for="fname"></label>
                        <input type="text" name="fname" id="fname" value="<?php echo $row_mymypayment['fname'];?>" readonly></td>
                        <td width="149">Lastname</td>
                        <td><label for="lname"></label>
                        <input type="text" name="lname" id="lname" value="<?php echo $row_mymypayment['lname'];?>" readonly></td>
                      </tr>
                      <tr>
                        <td>Id number</td>
                        <td><label for="id_no"></label>
                        <input type="text" name="id_no" id="id_no" value="<?php echo $row_mymypayment['id_no'];?>" readonly></td>
                        <td>Job completed on</td>
                        <td><label for="due_date"></label>
                        <input type="text" name="due_date" id="due_date" value="<?php echo $row_mymypayment['due_date'];?>" readonly></td>
                      </tr>
                      <tr>
                        <td colspan="4">&nbsp;</td>
                      </tr>
                      <tr>
                        <td>Earnings</td>
                        <td><label for="amount"></label>
                        <input type="text" name="amount" id="amount" required maxlength="5"></td>
                        <td>Deductions</td>
                        <td>Amount</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Taxation</td>
                        <td><label for="tax">
                          <input type="checkbox" name="tax" id="tax" required>
                        </label></td>
                      </tr>
                      <tr>
                        <td>TASK</td>
                        <td style="color:#FFF; background-color:#00F"><?php echo $row_mymypayment['task'];?></td>
                        <td>NHIF</td>
                        <td><label for="nhif">
                          <input type="checkbox" name="nhif" id="nhif" value="500" required>
                        </label></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Others</td>
                        <td><label for="others"></label>
                        <input type="text" name="others" id="others" required maxlength="5"></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td colspan="2"><input type="submit" name="pay" id="pay" value="PAY" class="btn btn-primary"></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                    <input type="hidden" name="MM_insert" value="payme">
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
mysql_free_result($mymypayment);

mysql_free_result($cost);
?>
