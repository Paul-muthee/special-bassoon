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
mysql_select_db($database_conn, $conn);
$query_notify = "SELECT * FROM notice WHERE status = 0 AND m_to='Manager'ORDER BY `date` ASC";
$notify = mysql_query($query_notify, $conn) or die(mysql_error());
$row_notify = mysql_fetch_assoc($notify);
$totalRows_notify = mysql_num_rows($notify);
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
                <div class="col-lg-4 text-center"> 
               <span id="noticeno"> You have <h2><?php echo $totalRows_notify ?></h2> Unread Messages </span>
                </div>
              <div class="col-lg-8 text-center">
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
              </div>
            </div>
      </div>
	<!--------------content------------------------>
        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                   nn
                    
                </div>
            </div>
        </div>

        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                   ll
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
mysql_free_result($notify);
?>
