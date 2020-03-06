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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>EMPLOYEE LOGIN</title>

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
                <div class="col-lg-12 text-center">
				 <?php
include("connection.php");
 $sql = mysql_query("select * from employee")
   or die('Error in query : $sql. ' .mysql_error());
   
if (mysql_num_rows($sql) > 0) 
{            
	echo "<table border='1' cellspacing='0' cellpadding='10' class='table' width='600'>";
	echo "<td> id</td>";
	echo "<td>Firstname</td>";
	echo "<td>Lastname</td>";
	echo "<td>Id no</td>";
	echo "<td>resident</td>";
	echo "<td>tel no</td>";
    echo "<td>Employment</td>";
	echo "<td>Reason</td>";
	echo "<td>Start date</td>";
	echo "<td>Return date</td>";
	echo "<td>Leave Status</td>";
	echo "<td>Work Status</td>";
	echo "<td colspan='4'>Action</td>";
	echo "<tr>";
		}
else
	echo "No User Record form the Database!";	 
						
while ($row = mysql_fetch_array($sql))
	{
	echo "<tr>";
	echo "<td>".$row['id']."</td>";
	echo "<td>".$row['fname']."</td>";
	echo "<td>".$row['lname']."</td>";
	echo "<td>".$row['id_no']."</td>";
	echo "<td>".$row['resident']."</td>";
	echo "<td>".$row['tel_no']."</td>";
	echo "<td>".$row['reg_date']."</td>";
	echo "<td>".$row['reason']."</td>";
	echo "<td>".$row['dfrom']."</td>";
	echo "<td>".$row['back']."</td>";
	echo "<td>".$row['apply']."</td>";
	echo "<td>".$row['task']."</td>";
	echo "<td title ='manage Leave'><a href=\"manageleave.php?id=$row[id]\"><center><img src='img/edit.png'></center></a></td>";

}	

mysql_free_result($sql);

?>
     
                </div>
            </div>
      </div>
	<!--------------content------------------------>
	   