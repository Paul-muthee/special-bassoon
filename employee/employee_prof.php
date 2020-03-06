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

$colname_staff = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_staff = $_SESSION['MM_Username'];
}
mysql_select_db($database_conn, $conn);
$query_staff = sprintf("SELECT * FROM employee WHERE username = %s", GetSQLValueString($colname_staff, "text"));
$staff = mysql_query($query_staff, $conn) or die(mysql_error());
$row_staff = mysql_fetch_assoc($staff);
$totalRows_staff = mysql_num_rows($staff);
$id=$row_staff['id_no'];
/**********************************/
$maxRows_task = 10;
$pageNum_task = 0;
if (isset($_GET['pageNum_task'])) {
  $pageNum_task = $_GET['pageNum_task'];
}
$startRow_task = $pageNum_task * $maxRows_task;

$colname_task = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_task = $_SESSION['MM_Username'];
}
mysql_select_db($database_conn, $conn);
$query_task = sprintf("SELECT * FROM assign_tasks WHERE username = %s AND comments='' ", GetSQLValueString($colname_task, "text"));
$query_limit_task = sprintf("%s LIMIT %d, %d", $query_task, $startRow_task, $maxRows_task);
$task = mysql_query($query_limit_task, $conn) or die(mysql_error());
$row_task = mysql_fetch_assoc($task);

if (isset($_GET['totalRows_task'])) {
  $totalRows_task = $_GET['totalRows_task'];
} else {
  $all_task = mysql_query($query_task);
  $totalRows_task = mysql_num_rows($all_task);
}
$totalPages_task = ceil($totalRows_task/$maxRows_task)-1;


/**************updating comments***********************/
		$editFormAction = $_SERVER['PHP_SELF'];
		if (isset($_SERVER['QUERY_STRING'])) {
		  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
		}
		$username=$row_staff['username'];
		if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "comments")) {
		  $updateSQL = sprintf("UPDATE assign_tasks SET comments=%s, complains=%s,complete=%s WHERE username=%s AND salary=%s",
							   GetSQLValueString($_POST['comments'], "text"),
							   GetSQLValueString($_POST['complains'], "text"),
							   GetSQLValueString($_POST['complete'], "int"),
							   GetSQLValueString($username, "text"),
							   GetSQLValueString(0, "int"));
		
		  mysql_select_db($database_conn, $conn);
		  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
		echo "<script> alert('Thank you for your contributions');</script>";
		}
/**************end updating comments***********************/
?>
<?php
/*if(isset($_POST['submit'])){
	if((empty($_POST['comments'])|| empty($_POST['complains']) || empty($_POST['complete']))){
		$cant_empty="";
		}
}
if(isset($cant_empty)){$cant_empty="cant be empty";}else{$cant_empty="";}*/
?>
<?php
mysql_select_db($database_conn, $conn);
$query_notify = "SELECT * FROM notice WHERE status = 0 AND m_to='Employee' ORDER BY `date` ASC";
$notify = mysql_query($query_notify, $conn) or die(mysql_error());
$row_notify = mysql_fetch_assoc($notify);
$totalRows_notify = mysql_num_rows($notify);


mysql_select_db($database_conn, $conn);
$query_complete = sprintf("SELECT * FROM payment WHERE id_no = %s ORDER BY amount DESC", GetSQLValueString($id, "int"));
$complete = mysql_query($query_complete, $conn) or die(mysql_error());
$row_complete = mysql_fetch_assoc($complete);
$totalRows_complete = mysql_num_rows($complete);
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
    			 <!--------------content------------------------>
                        <div class="row">
                            <div class="box">
                                <div class="col-lg-4">
                                   <span id="noticeno"> 
                                   You have <h2><?php echo $totalRows_notify ?></h2> Unread Messages 
                                   </span>
                                </div>
                                <div class="col-lg-8">
                                  <!-------------------------->
                                  <form action="" method="post" name="notice" id="notice">
                                      <table width="445" height="81" border="0" align="center" class="table table-responsive"id="notice">
                                        <tr>
                                          <th colspan="3" scope="col"><center><marquee>NOTICES</marquee></center></th>
                                        </tr>
                                        <?php if ($totalRows_notify > 0) { // Show if recordset not empty ?>
                                        <?php do { ?>
                                          <tr>
                                            <th width="88" scope="row">From</th>
                                            <td width="441"><?php echo $row_notify['m_from']; ?></td>
                                          </tr>
                                          <tr>
                                            <th scope="row">Message</th>
                                            <td><?php echo $row_notify['message']; ?><br>
                                           <span id="date"> <?php echo $row_notify['date']; ?></span>
                                            </td>
                                          </tr>
                                          <?php } while ($row_notify = mysql_fetch_assoc($notify)); ?>
                                    <?php } // Show if recordset not empty ?>
                                            </table>

               					 </form>
                                  <!-------------------------->
                                </div>
                            </div>
                        </div>
           		 <!--------------content------------------------>
    <div class="row">
            <div class="box">
            	  <div class="col-lg-3 text-center">
                   <!------------profile------------>
                  <table width="251" height="177" border="0" align="left" class="table table-responsive table-hover">
                  <caption>WELCOME</caption>
                   
                    <tr>
                      <th colspan="2" scope="col">
                      <center>
                      <img src="<?php echo $row_staff['photo']; ?>" height="150" width="150" class="img-responsive img-circle"/>		  </center>
                      </th>
                    </tr>
                    <tr>
                      <th scope="row">Name</th>
                      <td><?php echo $row_staff['fname']; ?>	<?php echo $row_staff['lname']; ?></td>
                    </tr>
                    <tr>
                      <th scope="row">Id No</th>
                      <td><?php echo $row_staff['id_no']; ?></td>
                    </tr>
                    <tr>
                      <th scope="row">Resident</th>
                      <td><?php echo $row_staff['resident']; ?></td>
                    </tr>
                    <tr>
                      <th scope="row">Telephone No</th>
                      <td><?php echo $row_staff['tel_no']; ?></td>
                    </tr>
                  </table>
			<!------------profile------------>
                  
                  </div>
              <div class="col-lg-9 text-center"> 
                <form name="comments" method="POST" action="<?php echo $editFormAction; ?>">
                  <table width="802" height="94" border="0" class="table table-responsive table-striped">
                    <caption class="alert alert-danger">
					    Fill this form after your done
					  </caption>
                    <tr>
                      <td width="84" scope="row">task name</td>
                      <td width="110">Date task issued</td>
                      <td width="98">Due date</td>
                      <td width="137">Complains</td>
                      <td width="141">Comments</td>
                      <td width="69">Complete</td>
                      <td width="69">Action</td>
                    </tr>
                    <?php if ($totalRows_task > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                    <tr class="alert alert-info">
                      <th scope="row"><?php echo $row_task['task']; ?></th>
                      <td><?php echo $row_task['date']; ?></td>
                      <td><?php echo $row_task['due_date']; ?></td>
                      <td><label for="complains" class="form-group"></label>
                      <textarea name="complains" id="complains2" required maxlength="20" class="form-control"></textarea></td>
                      <td><label for="comment" class="form-group"></label>
                      <textarea name="comments" id="comments" required maxlength="20" class="form-control"></textarea></td>
                      <td><label for="complete" class="form-group"></label>
                      <input type="checkbox" name="complete" id="complete" class="form-control" value="1" required>
                      </td>
                      <td>
                      <input type="submit" name="submit" id="submit" value="Done" class="btn btn-primary">
                      </td>
                       <input type="hidden" name="id" value="<?php echo $row_task['id']; ?>">
                    </tr>
                    <?php } while ($row_task = mysql_fetch_assoc($task)); ?>
                      <?php } // Show if recordset not empty ?>
                     
                  </table>
                  <input type="hidden" name="MM_update" value="comments">
                </form>
                <p><?php // echo "<h2>".$cant_empty."</h2>";?></p>
              </div>
            </div>
      </div>
	<!--------------content------------------------>
        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                  <form action="" method="post" name="payment" id="payment">
					<table width="983" border="0" align="center" class="table table-responsive table-striped table-hover">
					  <caption class="alert alert-info">
					    Employee Payment Sammary
					  </caption>
					  <tr>
					    <th scope="col">ID numeber</th>
					    <th scope="col">Date paid</th>
					    <th scope="col">Amount</th>
					    <th scope="col">Tax</th>
					    <th scope="col">Nhif</th>
					    <th scope="col">Others</th>
					    <th scope="col">Total</th>
					    <th scope="col">Paid by</th>
				      </tr>
					
                        <?php if ($totalRows_complete > 0) { // Show if recordset not empty ?>
                          <?php do { ?>
                            <tr>
                            <td><?php echo $row_complete['id_no']; ?></td>
                            <td><?php echo $row_complete['pay_date']; ?></td>
                            <td><?php echo $row_complete['amount']; ?></td>
                            <td><?php echo $row_complete['tax']; ?></td>
                            <td><?php echo $row_complete['nhif']; ?></td>
                            <td><?php echo $row_complete['others']; ?></td>
                            <td><?php echo $row_complete['total']; ?></td>
                            <td><?php echo $row_complete['payed_by']; ?></td>
                             </tr>
                            <?php } while ($row_complete = mysql_fetch_assoc($complete)); ?>
                          <?php } // Show if recordset not empty ?>
                     
				    </table>
                  </form>
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
</body>

</html>
<?php
mysql_free_result($task);

mysql_free_result($complete);
?>
