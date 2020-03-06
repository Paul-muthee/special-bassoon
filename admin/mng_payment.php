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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "admin_payment")) {
  $insertSQL = sprintf("INSERT INTO tasks(taskname, amount) VALUES (%s, %s)",
                       GetSQLValueString($_POST['task'], "text"),
                       GetSQLValueString($_POST['salary'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());

  $insertGoTo = "mng_payment.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_conn, $conn);
$query_salary = "SELECT * FROM tasks ORDER BY amount DESC";
$salary = mysql_query($query_salary, $conn) or die(mysql_error());
$row_salary = mysql_fetch_assoc($salary);
$totalRows_salary = mysql_num_rows($salary);
?>
<?php
if ((isset($_POST['hiddenField'])) && ($_POST['hiddenField'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tasks WHERE id=%s",
                       GetSQLValueString($_POST['hiddenField'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());

  $deleteGoTo = "admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
   echo "<script> alert('Deleted!');</script>";
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ADMIN </title>

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
                  <!------------------------------------>
                  <form action="" method="post" name="edit" id="edit">
                    <table width="556" border="0" class="table table-bordered table-responsive">
                      <caption class="alert alert-info">
                        Manage Tasks and Salary
                      </caption>
                      <tr>
                        <td scope="row">Task</td>
                        <td>Salary</td>
                        <td colspan="2">ACTION</td>
                      </tr>
                        <?php if ($totalRows_salary > 0) { // Show if recordset not empty ?>
                          <?php do { ?>
                      <tr>
                        <td scope="row"><?php echo $row_salary['taskname']; ?></td>
                        <td><?php echo $row_salary['amount']; ?></td>
                        <td><a href="edit_payment.php?id=<?php  echo $row_salary['id'];?>" class="btn btn-xs btn-info">Edit</a></td>
                        <td><input name="delete" type="submit" value="Delete" class="btn btn-xs btn-danger">
                        <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_salary['id']; ?>" />
                        </td>
                      </tr>
                        <?php } while ($row_salary = mysql_fetch_assoc($salary)); ?>
                          <?php } // Show if recordset not empty ?>
                    </table>
                  </form>
<!------------------------------------>
             	 </div>
              <div class="col-lg-4 text-center">
               	<form action="<?php echo $editFormAction; ?>" method="POST" name="admin_payment" id="admin_payment">
               	  <table width="200" border="0" class="table table-striped table-responsive"/>
               	    <caption  class="alert alert-info">
               	      Add tasks and there salaries
           	        </caption>
               	    <tr>
               	      <th width="80" scope="row">Task</th>
               	      <td width="104"><span id="sprytextfield1" class="form-group">
               	        <label for="task"></label>
               	        <input type="text" name="task" id="task" class="form-control">
           	          <span class="textfieldRequiredMsg">A value is required.</span></span></td>
           	        </tr>
               	    <tr>
               	      <th scope="row">Salary</th>
               	      <td><span id="sprytextfield2" class="form-group">
               	        <label for="salary"></label>
               	        <input type="text" name="salary" id="salary" class="form-control">
           	          <span class="textfieldRequiredMsg">A value is required.</span></span></td>
           	        </tr>
               	    <tr>
               	      <th colspan="2" scope="row">
                      <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary"></th>
           	        </tr>
               	    <input type="hidden" name="MM_insert" value="admin_payment">
</table>
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
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
</script>
</body>

</html>
<?php
mysql_free_result($salary);
?>
