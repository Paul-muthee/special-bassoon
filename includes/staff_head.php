<?php require_once('../includes/sess.php'); ?>
<?php require_once('../Connections/conn.php'); ?>
<?php require_once('../includes/functions.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
 GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "");
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
//initialize the session
// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../staff/staff_login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<link href="../css/mycss.css" rel="stylesheet" type="text/css">
<nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Business Casual</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="../staff/staff_portal.php"><span class="btn btn-primary">Home</span></a>
                    </li>
                    <li><a href="../staff/reg_staff.php"> <span class="btn btn-primary">Reg Empl</span></a>
                    </li>
                    <li><a href="../staff/assign_tasks.php"><span class="btn btn-primary">TASKS</span></a>
                    </li>
                    <li><a href="../staff/payment.php"><span class="btn btn-primary">Payment</span></a>
				   <li><a href="../staff/leave.php"><span class="btn btn-primary">Leave</span></a>
                    </li>
                    <li><a href="../staff/empfeedback.php"><span class="btn btn-primary">Feedback</span></a>
                    </li>
                    <li><a href="<?php echo $logoutAction ?>"><span class="btn btn-xs btn-danger">Log Out</span></a></li>
                </ul>
            <ul class="nav navbar-nav">
                <li>
          <span id="profile">
          Welcome Staff:	<?php echo $row_staff['fname']; ?>	<?php echo $row_staff['lname']; ?> of IDno <?php echo $row_staff['id_no']; ?>
                </span
                ></li>
                </ul>
          </div>
            <!-- /.navbar-collapse -->
  </div>
        <!-- /.container -->
    </nav>
<?php
mysql_free_result($staff);
?>
