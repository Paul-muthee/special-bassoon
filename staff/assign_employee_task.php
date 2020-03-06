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
$colname_get_task = "-1";
if (isset($_GET['id'])) {
  $colname_get_task = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_get_task = sprintf("SELECT * FROM employee WHERE id = %s", GetSQLValueString($colname_get_task, "int"));
$get_task = mysql_query($query_get_task, $conn) or die(mysql_error());
$row_get_task = mysql_fetch_assoc($get_task);
$totalRows_get_task = mysql_num_rows($get_task);
/*************************/
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "assign")) {
  $updateSQL = sprintf("UPDATE assign_tasks SET fname=%s, lname=%s, id_no=%s, task=%s WHERE id=%s",
                       GetSQLValueString($_POST['fname'], "text"),
                       GetSQLValueString($_POST['lname'], "text"),
                       GetSQLValueString($_POST['id_no'], "int"),
                       GetSQLValueString($_POST['task'], "text"),
                       GetSQLValueString($_POST['fname'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());

 
}

/********calculating dates******/
$username=$row_get_task['username'];
$date = date('Y-m-j');
$newdate = strtotime ( '+30 day' , strtotime ( $date ) ) ;
$newdate = date ( 'Y-m-j' , $newdate );
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "assign")) {
  $insertSQL = sprintf("INSERT INTO assign_tasks (fname, lname,username,id_no,due_date,task) VALUES (%s, %s, %s, %s, %s,%s)",
                       GetSQLValueString($_POST['fname'], "text"),
                       GetSQLValueString($_POST['lname'], "text"),
					    GetSQLValueString($username, "text"),
                       GetSQLValueString($_POST['id_no'], "int"),
					   GetSQLValueString($newdate, "date"),
                       GetSQLValueString($_POST['task'], "text"));
   $updateSQL = sprintf("UPDATE employee SET task=%s WHERE fname=%s",
                       GetSQLValueString(1, "text"),
                       GetSQLValueString($_POST['fname'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result2 = mysql_query($updateSQL, $conn) or die(mysql_error());
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
  if($Result1){$sucess="";}
}

mysql_select_db($database_conn, $conn);
$query_tasks = "SELECT * FROM tasks ORDER BY taskname DESC";
$tasks = mysql_query($query_tasks, $conn) or die(mysql_error());
$row_tasks = mysql_fetch_assoc($tasks);
$totalRows_tasks = mysql_num_rows($tasks);

$maxRows_assiged = 10;
$pageNum_assiged = 0;
if (isset($_GET['pageNum_assiged'])) {
  $pageNum_assiged = $_GET['pageNum_assiged'];
}
$startRow_assiged = $pageNum_assiged * $maxRows_assiged;

mysql_select_db($database_conn, $conn);
$query_assiged = "SELECT * FROM assign_tasks WHERE complete = 0 ORDER BY due_date DESC";
$query_limit_assiged = sprintf("%s LIMIT %d, %d", $query_assiged, $startRow_assiged, $maxRows_assiged);
$assiged = mysql_query($query_limit_assiged, $conn) or die(mysql_error());
$row_assiged = mysql_fetch_assoc($assiged);

if (isset($_GET['totalRows_assiged'])) {
  $totalRows_assiged = $_GET['totalRows_assiged'];
} else {
  $all_assiged = mysql_query($query_assiged);
  $totalRows_assiged = mysql_num_rows($all_assiged);
}
$totalPages_assiged = ceil($totalRows_assiged/$maxRows_assiged)-1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>STAFFLOGIN</title>

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
					<div id="message">
                    <?php
					if(isset($sucess)){$sucess="Sucessfuly assigned task";}else{$sucess="";}
					echo "<h2>".$sucess."</h2>";
                    ?>
                    </div>
                </div>
              <div class="col-lg-8 text-center">
                   <!-------------assign task------------->
                   <form action="<?php echo $editFormAction; ?>" method="POST" name="assign" id="assign" class="form-horizontal" role="form">
                     <div class="form-group">
                       <label for="fname" class="control-label col-sm-2">Firstname</label>
                       		<div class="col-sm-6">
                       <input type="text" name="fname" id="fname" class="form-control" value="<?php echo $row_get_task['fname']; ?>">
                       		</div>
                     </div>
                    
                    <div class="form-group">
                       <label for="lname"  class="control-label col-sm-2">Lastname</label>
                       		<div class="col-sm-6">
                       <input type="text" name="lname" id="lname" class="form-control" value="<?php echo $row_get_task['lname']; ?>" required maxlength="10">
                       		</div>
                     </div>
                     <div class="form-group">
                       <label for="id_no" class="control-label col-sm-2">Id no</label>
                       		<div class="col-sm-6">
                       <input type="text" name="id_no" id="id_no" class="form-control" value="<?php echo $row_get_task['id_no']; ?>" required maxlength="10">
                       		</div>
                     </div>
                     <div class="form-group">
                       <label for="tel_no" class="control-label col-sm-2">Telphone No</label>
                       		<div class="col-sm-6">
                       <input type="text" name="tel_no" id="tel_no" class="form-control" value="<?php echo $row_get_task['tel_no']; ?>" required maxlength="10">
                       		</div>
                     </div>
                     <div class="form-group">
                       <label for="acc_no" class="control-label col-sm-2">Account No</label>
                       		<div class="col-sm-6">
                       <input type="text" name="acc_no" id="acc_no" class="form-control" value="<?php echo $row_get_task['account_no']; ?>"  readonly maxlength="10">
                       		</div>
                     </div>
                       <div class="form-group">
                         <label for="task" class="control-label col-sm-2">Tasks</label>
                         	<div class="col-sm-6">
                         <select name="task" id="task" class="form-control" required maxlength="10">
                           <?php do {  ?>
                           <option value="<?php echo $row_tasks['taskname']?>"<?php if (!(strcmp($row_tasks['taskname'], $row_tasks['taskname']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tasks['taskname']?></option>
                           <?php
						} while ($row_tasks = mysql_fetch_assoc($tasks));
						  $rows = mysql_num_rows($tasks);
						  if($rows > 0) {
							  mysql_data_seek($tasks, 0);
							  $row_tasks = mysql_fetch_assoc($tasks);
						  }
						?>
                         </select>
                         </div>
                      </div>
                         <input type="submit" name="submit" id="submit" value="ASSIGN" class="btn btn-info">
                       <p>&nbsp;</p>
                       <input type="hidden" name="MM_insert" value="assign">
                       <input type="hidden" name="MM_update" value="assign">
                   </form>
		<!-------------assign task------------->
                </div>
            </div>
      </div>
	<!--------------content------------------------>
        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                  <form action="" method="post" name="assigned" id="assigned">
                    <table width="933" border="1" align="center">
                      <tr>
                        <th colspan="7" scope="col"><center>ASSIGNED TASKS</center></th>
                      </tr>
                      <tr>
                        <th width="160" scope="row">Firstname</th>
                        <td width="144">Lastname</td>
                        <td width="140">id_no</td>
                        <td width="127">Tasks</td>
                        <td width="158">Start date</td>
                        <td width="148">Due date</td>
                        <td width="10">&nbsp;</td>
                      </tr>
                      
                        <?php if ($totalRows_assiged > 0) { // Show if recordset not empty ?>
                          <?php do { ?>
                          <tr>
                        <th scope="row"><?php echo $row_assiged['fname']; ?></th>
                        <td><?php echo $row_assiged['lname']; ?></td>
                        <td><?php echo $row_assiged['id_no']; ?></td>
                        <td><?php echo $row_assiged['task']; ?></td>
                        <td><?php echo $row_assiged['date']; ?></td>
                        <td><?php echo $row_assiged['due_date']; ?></td>
                        <td>&nbsp;</td>
                        </tr>
                        <?php } while ($row_assiged = mysql_fetch_assoc($assiged)); ?>
                          <?php } // Show if recordset not empty ?>
                    </table>
                  </form>
                </div>
            </div>
        </div>

        <div  class="row">
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
mysql_free_result($tasks);

mysql_free_result($get_task);

mysql_free_result($assiged);
?>
