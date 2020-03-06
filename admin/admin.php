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
if ((isset($_POST['hiddenField'])) && ($_POST['hiddenField'] != "")) {
  $deleteSQL = sprintf("DELETE FROM staff WHERE id=%s",
                       GetSQLValueString($_POST['hiddenField'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());

  $deleteGoTo = "admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
   echo "<script> alert('Deleted!');</script>";
}

$maxRows_mgn_staff = 10;
$pageNum_mgn_staff = 0;
if (isset($_GET['pageNum_mgn_staff'])) {
  $pageNum_mgn_staff = $_GET['pageNum_mgn_staff'];
}
$startRow_mgn_staff = $pageNum_mgn_staff * $maxRows_mgn_staff;

mysql_select_db($database_conn, $conn);
$query_mgn_staff = "SELECT * FROM staff WHERE userlevel = 1 ORDER BY id ASC";
$query_limit_mgn_staff = sprintf("%s LIMIT %d, %d", $query_mgn_staff, $startRow_mgn_staff, $maxRows_mgn_staff);
$mgn_staff = mysql_query($query_limit_mgn_staff, $conn) or die(mysql_error());
$row_mgn_staff = mysql_fetch_assoc($mgn_staff);

if (isset($_GET['totalRows_mgn_staff'])) {
  $totalRows_mgn_staff = $_GET['totalRows_mgn_staff'];
} else {
  $all_mgn_staff = mysql_query($query_mgn_staff);
  $totalRows_mgn_staff = mysql_num_rows($all_mgn_staff);
}
$totalPages_mgn_staff = ceil($totalRows_mgn_staff/$maxRows_mgn_staff)-1;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "reg_staff")) {
					$password=sha1($_POST['pass']);	
  $insertSQL = sprintf("INSERT INTO staff (fname, lname, id_no,residence, tel,username, password,gender) VALUES (%s, %s, %s, %s, %s, %s,%s, %s)",
                       GetSQLValueString($_POST['fname'], "text"),
                       GetSQLValueString($_POST['lname'], "text"),
					   GetSQLValueString($_POST['id_no'], "int"),
                       GetSQLValueString($_POST['residence'], "text"),
                       GetSQLValueString($_POST['tel'], "int"),
                       GetSQLValueString($_POST['username'], "text"),
					   GetSQLValueString($password, "text"),
                       GetSQLValueString($_POST['gender'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());

  $insertGoTo = "admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
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
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryAccordion.js" type="text/javascript"></script>
</head>

<body>

    <div class="brand" id="companyname">ADMINS PORTAL</div>
    <div class="address-bar">The Plaza | 5483 | Beverly Hills, Thika 26892 | 555.519.2013</div>
    <!----------head---------->
    	<?php require_once("admin_head.php");?>
    <!----------head---------->
	<div class="container">
    <div class="row">
            <div class="box">
            	  <div class="col-lg-8 text-center">
                  <br>
                  <!-----------manage staff----------------->
                  <form action="" method="post" name="manage_staff" id="manage_staff">
                    <table width="700" border="0" class="table table-responsive table-striped table-hover">
                      <tr>
                        <th colspan="9" scope="col">Manage Managers Infomation</th>
                      </tr>
                      <tr class="alert alert-info">
                        <td>fisrtname</td>
                        <td>lastname</td>
                        <td>Id no</td>
                        <td>Telephone</td>
                        <td>Residence</td>
                        <td>Gender</td>
                        <td>Username</td>
                        <td colspan="2">ACTION</td>
                      </tr>
                       <?php if ($totalRows_mgn_staff > 0) { // Show if recordset not empty ?>
                          <?php do { ?>
                      <tr class="alert alert-success">
                        <td><?php echo $row_mgn_staff['fname']; ?></td>
                        <td><?php echo $row_mgn_staff['lname']; ?></td>
                        <td><?php echo $row_mgn_staff['id_no']; ?></td>
                        <td><?php echo $row_mgn_staff['tel']; ?></td>
                        <td><?php echo $row_mgn_staff['residence']; ?></td>
                        <td><?php echo $row_mgn_staff['gender']; ?></td>
                        <td><?php echo $row_mgn_staff['username']; ?></td>
                        <td><a href="update.php?id=<?php echo urlencode($row_mgn_staff['id']); ?>" class="btn btn-xs btn-primary">Edit</a></td>
                        <td><input type="submit" name="del" id="del" value="Delete" class="btn btn-xs btn-danger"></td>
                        <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_mgn_staff['id']; ?>" /></td>
                      
                      </tr>
                       <?php } while ($row_mgn_staff = mysql_fetch_assoc($mgn_staff)); ?>
                          <?php } // Show if recordset not empty ?>
                    </table>
                  </form>
                    <!-------------manage staff--------------->
                    <hr>
                    <!-------------reports--------------------->
                    <table width="306" border="0" class="table table-responsive table-striped table-hover">
                      <tr>
                        <td colspan="2">ALL REPORTS</td>
                      </tr>
                      <tr>
                        <td>Employees at Work</td>
                        <td><a href="../Reports/employeatwork.pdf">Employees at Work Report</a></td>
                      </tr>
                      <tr>
                        <td>Payed employees</td>
                        <td><a href="../Reports/employeepayment.pdf">Paid employees</a></td>
                      </tr>
                      <tr>
                        <td>Employee Feedback</td>
                        <td><a href="../Reports/employeefeedback.pdf">Employee Feedback</a></td>
                      </tr>
					  <tr>
                        <td>Employee on leave</td>
                        <td><a href="../Reports/employeefeedback.pdf">Employees on leave</a></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
<!-------------reports--------------------->
                  </div>
              <div class="col-lg-4 text-center"> 
                 <!------------reg company staff------------>
                <form action="<?php echo $editFormAction; ?>" method="POST" name="reg_staff" id="reg_staff">
                      <table width="473" border="0" align="center" class="table-responsive">
                      <tr>
                      <th colspan="2"><center><marquee>Managers Registration Form</marquee></center></th>
                      </tr>
                     <tr>
                       <th width="88" scope="row">Firstname</th>
                       <td width="203"><span id="sprytextfield1" class="form-group has-warning">
                         <label for="fname"></label>
                         <input type="text" name="fname" id="fname" class="form-control" required>
                       <span class="textfieldRequiredMsg">A value is required.</span></span>
                       <?php //echo $error;?>
                       </td>
                     </tr>
                     <tr>
                       <th scope="row">lastname</th>
                       <td><span id="sprytextfield2" class="form-group has-warning">
                         <label for="lname"></label>
                         <input type="text" name="lname" id="lname" class="form-control" required>
                       <span class="textfieldRequiredMsg">A value is required.</span></span>
                       <?php //echo $error;?>
                       </td>
                     </tr>
                     <tr>
                       <th scope="row">gender</th>
                       <td><p>
                         <label>
                           <input type="radio" name="gender" value="1" id="RadioGroup1_0">
                           male</label>
                         <label>
                           <input type="radio" name="gender" value="0" id="RadioGroup1_1">
                           female</label>
                         <br>
                       </p></td>
                     </tr>
                     <tr>
                       <th scope="row">id no</th>
                       <td><span id="sprytextfield3" class="form-group has-warning">
                       <label for="id_no"></label>
                       <input type="text" name="id_no" id="id_no" class="form-control" required maxlength="8">
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
                         <input type="text" name="residence" id="residence" class="form-control" required>
                       <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                     </tr>
                     <tr>
                       <th scope="row">telephone</th>
                       <td><span id="sprytextfield5" class="form-group has-warning">
                       <label for="tel"></label>
                       <input type="text" name="tel" id="tel" class="form-control" required maxlength="10">
                       <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                     </tr>
                     <tr>
                       <th scope="row">username</th>
                       <td><span id="sprytextfield6" class="form-group has-warning">
                         <label for="username" ></label>
                         <input type="text" name="username" id="username" class="form-control" required>
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
                       <input type="submit" name="reg" id="reg" value="register" class="form-control btn btn-primary btn-lg" required>
                       </th>
                     </tr>
                   </table>
                      <input type="hidden" name="MM_insert" value="reg_staff">
                  </form>
				<!------------reg company staff------------>
                
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
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
</script>
</body>

</html>
<?php
mysql_free_result($mgn_staff);
?>
