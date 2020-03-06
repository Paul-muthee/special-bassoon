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
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_assigned_task = 10;
$pageNum_assigned_task = 0;
if (isset($_GET['pageNum_assigned_task'])) {
  $pageNum_assigned_task = $_GET['pageNum_assigned_task'];
}
$startRow_assigned_task = $pageNum_assigned_task * $maxRows_assigned_task;

mysql_select_db($database_conn, $conn);
$query_assigned_task = "SELECT fname,task,due_date,complains,comments FROM assign_tasks WHERE complete = 1 ORDER BY id ASC";
$query_limit_assigned_task = sprintf("%s LIMIT %d, %d", $query_assigned_task, $startRow_assigned_task, $maxRows_assigned_task);
$assigned_task = mysql_query($query_limit_assigned_task, $conn) or die(mysql_error());
$row_assigned_task = mysql_fetch_assoc($assigned_task);

if (isset($_GET['totalRows_assigned_task'])) {
  $totalRows_assigned_task = $_GET['totalRows_assigned_task'];
} else {
  $all_assigned_task = mysql_query($query_assigned_task);
  $totalRows_assigned_task = mysql_num_rows($all_assigned_task);
}
$totalPages_assigned_task = ceil($totalRows_assigned_task/$maxRows_assigned_task)-1;

$queryString_assigned_task = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_assigned_task") == false && 
        stristr($param, "totalRows_assigned_task") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_assigned_task = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_assigned_task = sprintf("&totalRows_assigned_task=%d%s", $totalRows_assigned_task, $queryString_assigned_task);
?>
<?php
//require('../fpdf.php');
require('../mysql_table.php');
class PDF extends PDF_MySQL_Table{
	function Header(){
		//$this->Image("../images/logo.png",10,6,30);
	 	$this->SetFont('Arial','B',18);
		//$this->SetFillColor(230,230,0);
		$this->SetFillColor(200,220,255);
		$this->SetTextColor(221,50,50);
	    $this->Cell(0,6,"BIDCO OIL COMPANY",0,1,'C');
		$this->SetFont('Arial','B',14);
		$this->Cell(0,6,"P.O BOX 98898 THIKA",0,1,'C');
		$this->SetFont('Arial','I',9);
		$this->Cell(0,6,"Tel:0700145876",0,1,'C');
	    $this->Ln(6);
		$this->SetFont('Arial','B',12);
		$this->Cell(0,6,"Employee Feeback Report",0,1,'C');
    // Line break
 	parent::Header();
	
}

// footer
}


//PRINTING OUT DATA
	mysql_connect('localhost','root','');
	mysql_select_db('employee_mgn');
 
	$pdf=new PDF();
	$pdf->AddPage();
 	$pdf->SetFont('Times','B',9);
	$pdf->SetTextColor(128);
	$pdf->Table($query_assigned_task);
	$pdf->Output("../Reports/employeefeedback.pdf","F");

	//redirect("../classes/form11.php");

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
				 kkkk
                </div>
            </div>
      </div>
	<!--------------content------------------------>
        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                     <!---------------------------------------->
                     <form action="" method="post" name="assigned_tasks" id="assigned_tasks">
                       <table width="944" height="80" border="1" align="center"   class="table table-striped table-responsive" >
                         <tr>
                           <th colspan="6" scope="col"><strong><center>EMPLOYEES ASSIGNED TASKS</center></strong></th>
                         </tr>
                         <tr>
                           <td width="142" scope="row">Employee Firstname</td>
                           <td width="129">Task</td>

                           <td width="163">due date</td>
                           <td width="166">Complains</td>
                           <td width="220">Comments</td>
                           
                         </tr>
                         <?php if ($totalRows_assigned_task > 0) { // Show if recordset not empty ?>
                           <?php do { ?>
                             <tr>
                               <td scope="row"><?php echo $row_assigned_task['fname']; ?></td>
                               <td class="alert-info"><?php echo $row_assigned_task['task']; ?></td>
                               <td><?php echo $row_assigned_task['due_date']; ?></td>
                               <td><?php echo $row_assigned_task['complains']; ?></td>
                               <td><?php echo $row_assigned_task['comments']; ?></td>
                             </tr>
                             <?php } while ($row_assigned_task = mysql_fetch_assoc($assigned_task)); ?>
                           <?php } // Show if recordset not empty ?>
                       </table>
                       <ul class="pager">
                      <li><a href="<?php printf("%s?pageNum_assigned_task=%d%s", $currentPage, max(0, $pageNum_assigned_task - 1), $queryString_assigned_task); ?>">Previous</a></li>
                      <li><a href="../Reports/employeefeedback.pdf">PRINT</a></li>
                      <li><a href="<?php printf("%s?pageNum_assigned_task=%d%s", $currentPage, min($totalPages_assigned_task, $pageNum_assigned_task + 1), $queryString_assigned_task); ?>">Next</a></li>
                    </ul>
                     </form>
<!---------------------------------------->  
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
mysql_free_result($assigned_task);
?>
