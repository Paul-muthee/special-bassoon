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
$maxRows_payment = 10;
$pageNum_payment = 0;
if (isset($_GET['pageNum_payment'])) {
  $pageNum_payment = $_GET['pageNum_payment'];
}
$startRow_payment = $pageNum_payment * $maxRows_payment;

mysql_select_db($database_conn, $conn);
$query_payment = "SELECT * FROM assign_tasks WHERE complete = 1 AND salary='0' ORDER BY due_date ASC";
$query_limit_payment = sprintf("%s LIMIT %d, %d", $query_payment, $startRow_payment, $maxRows_payment);
$payment = mysql_query($query_limit_payment, $conn) or die(mysql_error());
$row_payment = mysql_fetch_assoc($payment);

if (isset($_GET['totalRows_payment'])) {
  $totalRows_payment = $_GET['totalRows_payment'];
} else {
  $all_payment = mysql_query($query_payment);
  $totalRows_payment = mysql_num_rows($all_payment);
}
$totalPages_payment = ceil($totalRows_payment/$maxRows_payment)-1;

mysql_select_db($database_conn, $conn);
$query_payed = "SELECT fname,id_no,amount,tax,nhif,others,total,pay_date,payed_by FROM payment ORDER BY pay_date ASC";
$payed = mysql_query($query_payed, $conn) or die(mysql_error());
$row_payed = mysql_fetch_assoc($payed);
$totalRows_payed = mysql_num_rows($payed);
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
	    $this->Cell(0,6,"XX COMPANY",0,1,'C');
		$this->SetFont('Arial','B',14);
		$this->Cell(0,6,"P.O BOX test THIKA",0,1,'C');
		$this->SetFont('Arial','I',9);
		$this->Cell(0,6,"Tel:0...............",0,1,'C');
	    $this->Ln(6);
		$this->SetFont('Arial','B',12);
		$this->Cell(0,6,"Employee Payment Report",0,1,'C');
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
	$pdf->Table("SELECT fname,id_no,amount,tax,nhif,others,total,pay_date FROM payment ORDER BY id ASC");
	$pdf->Output("../Reports/employeepayment.pdf","F");

	//redirect("../classes/form11.php");

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
					Instructions<br />
                    Payments are made to only people who have commented and there due dates of jobs are complete.
                    
                </div>
              <div class="col-lg-8 text-center">
                   //
                </div>
            </div>
      </div>
	<!--------------content------------------------>
        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                  <!-----------payments--------------------->
                  <form action="" method="post" name="payment" id="payment">
                    <table width="607" border="0" align="center" class="table table-striped table-hover">
                      <tr>
                        <th colspan="6" scope="col"><center>ELIDGEBLE EMPLOYEES FOR PAYMENT </center></th>
                      </tr>
                      <tr class="alert alert-info">
                        <td width="91">Firstname</td>
                        <td width="90">Lastname</td>
                        <td width="144">Tasks allocated</td>
                        <td width="132">Date allocated</td>
                        <td width="69">Due date</td>
                        <td width="23">Pay</td>
                      </tr>
                        <?php if ($totalRows_payment > 0) { // Show if recordset not empty ?>
                          <?php do { ?>
                          <tr>
                        <td><?php echo $row_payment['fname']; ?></td>
                        <td><?php echo $row_payment['lname']; ?></td>
                        <td><?php echo $row_payment['task']; ?></td>
                        <td><?php echo $row_payment['date']; ?></td>
                        <td><?php echo $row_payment['due_date']; ?></td>
                        <td><a href="payme.php?id=<?php echo urlencode($row_payment['id']); ?>"><span class="btn btn-xs btn-danger">Pay Me</span></a></td>
                        </tr>
                        <?php } while ($row_payment = mysql_fetch_assoc($payment)); ?>
                          <?php } // Show if recordset not empty ?>
                      
                    </table>
                  </form>
			<!-----------payments---------------------> 
                </div>
            </div>
        </div>

        <div class="row">
            <div class="box">
                <div class="col-lg-12">
               	 <a href="../Reports/employeepayment.pdf" class="btn btn-info">Print Payment Report</a>
                   <!------------payed employeees-------------->
                   <form action="" method="post" name="payed" id="payed">
                     <table width="1042" border="0" align="center" class="table table-striped table-hover">
                       <tr>
                         <th colspan="9" scope="col"><center>PAYED EMPLOYEES</center></th>
                       </tr>
                       <tr class="alert alert-info">
                         <td width="51">Firstname</td>
                         <td width="84">Id No</td>
                         <td width="84">Basic salary</td>
                         <td width="84">Tax</td>
                         <td width="84">Nhif</td>
                         <td width="84">Others</td>
                         <td width="84">Amount Paid</td>
                         <td width="84">Date</td>
                         <td width="86">Paid by</td>
                       </tr>
                       <?php if ($totalRows_payed > 0) { // Show if recordset not empty ?>
                        <?php do { ?>
                         <tr class="alert alert-success">
                           <td><?php echo $row_payed['fname']; ?></td>
                           <td><?php echo $row_payed['id_no']; ?></td>
                           <td><?php echo $row_payed['amount']; ?></td>
                           <td><?php echo $row_payed['tax']; ?></td>
                           <td><?php echo $row_payed['nhif']; ?></td>
                           <td><?php echo $row_payed['others']; ?></td>
                           <td><?php echo $row_payed['total']; ?></td>
                           <td><?php echo $row_payed['pay_date']; ?></td>
                           <td><?php echo $row_payed['payed_by']; ?></td>
                         </tr>
                          <?php } while ($row_payed = mysql_fetch_assoc($payed)); ?>
                         <?php } // Show if recordset not empty ?>
                     </table>
                  </form>
				<!------------payed employeees-------------->
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
mysql_free_result($payment);

mysql_free_result($payed);
?>
