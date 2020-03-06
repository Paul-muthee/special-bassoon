<?php require_once('../includes/sess.php'); ?>
<?php require_once('../Connections/conn.php'); ?>
<?php require_once('../includes/functions.php'); ?>

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
    <link href="../SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css">
</head>

<body>

    <div class="brand" id="companyname">EMPLOYEE PORTAL</div>
    <div class="address-bar">The Plaza | 5483 | Beverly Hills, Thika 26892 | 555.519.2020</div>
    <!----------head---------->
    	<?php require_once("../includes/emp_head.php");?>
    <!----------head---------->
	<div class="container">
    			 <!--------------content------------------------>
                        
           		 <!--------------content------------------------>
    <div class="row">
            <div class="box">
            	  <div class="col-lg-3 text-center">
                   <!------------profile------------>
                  <table width="251" height="177" border="0" align="left" class="table table-responsive table-hover">
                  <caption>WELCOME</caption>
                    <tr>
                      <th scope="row">Name</th>
                      <td><?php echo $row_staff['fname']; ?>	<?php echo $row_staff['lname']; ?></td>
                    </tr>
					<tr>
                      <th scope="row">Leave Status</th>
                      <td><?php echo $row_staff['apply']; ?></td>
                    </tr>
					<tr>
                      <th scope="row">Start date</th>
                      <td><?php echo $row_staff['dfrom']; ?></td>
                    </tr>
					<tr>
                      <th scope="row">Return date</th>
                      <td><?php echo $row_staff['back']; ?></td>
                    </tr>
                   <tr>
</tr>
                  
                    </tr>
                  </table>
			<!------------profile------------>
                  
                  </div>