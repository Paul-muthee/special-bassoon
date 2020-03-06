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

//cheking if id_no exists in the database
$colname_validate = "-1";
if (isset($_POST['id_no'])) {
  $colname_validate = $_POST['id_no'];
}
mysql_select_db($database_conn, $conn);
$query_validate = sprintf("SELECT id_no FROM employee WHERE id_no = %s", GetSQLValueString($colname_validate, "int"));
$validate = mysql_query($query_validate, $conn) or die(mysql_error());
$row_validate = mysql_fetch_assoc($validate);
$totalRows_validate = mysql_num_rows($validate);

$maxRows_employee = 10;
$pageNum_employee = 0;
if (isset($_GET['pageNum_employee'])) {
  $pageNum_employee = $_GET['pageNum_employee'];
}
$startRow_employee = $pageNum_employee * $maxRows_employee;

mysql_select_db($database_conn, $conn);
$query_employee = "SELECT * FROM employee";
$query_limit_employee = sprintf("%s LIMIT %d, %d", $query_employee, $startRow_employee, $maxRows_employee);
$employee = mysql_query($query_limit_employee, $conn) or die(mysql_error());
$row_employee = mysql_fetch_assoc($employee);

if (isset($_GET['totalRows_employee'])) {
  $totalRows_employee = $_GET['totalRows_employee'];
} else {
  $all_employee = mysql_query($query_employee);
  $totalRows_employee = mysql_num_rows($all_employee);
}
$totalPages_employee = ceil($totalRows_employee/$maxRows_employee)-1;

$queryString_employee = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_employee") == false && 
        stristr($param, "totalRows_employee") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_employee = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_employee = sprintf("&totalRows_employee=%d%s", $totalRows_employee, $queryString_employee);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

/***********************************************/
if(isset($_POST['pass'])){$pass=($_POST['pass']);}else{$pass="";}
if(isset($_POST['pass_2'])){$pass_2=($_POST['pass_2']);}else{$pass_2="";}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "employee_reg")) {
	
		if(is_numeric($_POST['fname']) || is_numeric($_POST['lname']))
		{
				$error="Numbers not allowed";
	
		}else
		{
			if($pass !== $pass_2){
				//
				$passwordmissmatch="password miss match";
				}elseif($totalRows_validate==0){
					//the insetion
					//images hundling
					 $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
					 $image_name = addslashes($_FILES['image']['name']);
					 $image_size = getimagesize($_FILES['image']['tmp_name']);
					 move_uploaded_file($_FILES["image"]["tmp_name"], "../upload/" . $_FILES["image"]["name"]);
					 $myimage = "../upload/" . $_FILES["image"]["name"];
					//end of image	
					 $insertSQL = sprintf("INSERT INTO employee (fname, lname,gender,id_no,account_no,photo,resident,tel_no, username,password) 
					 VALUES (%s, %s, %s, %s, %s, %s,%s, %s, %s, %s)",
								 GetSQLValueString($_POST['fname'], "text"),
								 GetSQLValueString($_POST['lname'], "text"),
								 GetSQLValueString($_POST['gender'], "int"),
								 GetSQLValueString($_POST['id_no'], "int"),
							 	  GetSQLValueString($_POST['account_no'], "int"),
							   GetSQLValueString($myimage, "text"),
            					 GetSQLValueString($_POST['residence'], "text"),
								   GetSQLValueString($_POST['tel'], "int"),
								   GetSQLValueString($_POST['username'], "text"),
								   GetSQLValueString($pass, "text"));

						  mysql_select_db($database_conn, $conn);
						  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
							if(isset($Result1)){ echo "<script> alert('Sucess!');</script>";}
					}else{
					 echo "<script> alert('Id no exists!');</script>";
					}
		}
}
/************************************************/
if(isset($error)){$error="Invalid input";}else{$error="";}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Staff reg</title>

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
	<!--------------content------------------------>
        <div class="row">
            <div class="box">
                <div class="col-lg-6">
               <center>EMPLOYEE REGISTATION FORM</center>
                 <form action="<?php echo $editFormAction; ?>" method="POST" name="employee_reg" id="employee_reg" enctype="multipart/form-data">
                   <table width="473" border="0" align="center" class="table-responsive">
                     <tr>
                       <th width="88" scope="row">Firstname</th>
                       <td width="203"><span id="sprytextfield1" class="form-group has-success">
                         <label for="fname"></label>
                         <input type="text" name="fname" id="fname" class="form-control" required>
                       <span class="textfieldRequiredMsg">A value is required.</span></span>
                       <?php echo $error;?>
                       </td>
                     </tr>
                     <tr>
                       <th scope="row">lastname</th>
                       <td><span id="sprytextfield2" class="form-group has-success">
                         <label for="lname"></label>
                         <input type="text" name="lname" id="lname" class="form-control" required>
                       <span class="textfieldRequiredMsg">A value is required.</span></span>
                       <?php echo $error;?>
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
                       <td><span id="sprytextfield3" class="form-group has-success">
                       <label for="id_no"></label>
                       <input type="text" name="id_no" id="id_no" class="form-control" required maxlength="8">
                       <span class="textfieldRequiredMsg">A value is required.</span></span>
                       <?php echo $error;?>
                       </td>
                     </tr>
                      <tr>
                       <th scope="row">Photo</th>
                       <td><span id="sprytextfield8" class="form-group has-success">
                       <label for="photo"></label>
                       <input type="file" name="image" id="image" class="form-control" required>
                       <span class="textfieldRequiredMsg">A value is required.</span></span>
                       <?php echo $error;?>
                       </td>
                     </tr>
                     <tr>
                       <th scope="row">account no</th>
                       <td><span id="sprytextfield4" class="form-group has-success">
                         <label for="account_no"></label>
                         <input type="text" name="account_no" id="account_no" class="form-control" required maxlength="12">
                       <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                     </tr>
                     <tr>
                       <th scope="row">residence</th>
                       <td><label for="residence" class="form-group has-success"></label>
                         <select name="residence" id="residence" class="form-control">
                           <option value="kiandutu">kiandutu</option>
                           <option value="mako">makongeni</option>
                           <option value="town">thika</option>
					       <option value="Biaf">Biafra</option>

                       </select></td>
                     </tr>
                     <tr>
                       <th scope="row">telephone</th>
                       <td><span id="sprytextfield5" class="form-group has-success">
                       <label for="tel"></label>
                       <input type="text" name="tel" id="tel" class="form-control" required maxlength="10">
                       <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                     </tr>
                     <tr>
                       <th scope="row">username</th>
                       <td><span id="sprytextfield6" class="form-group has-success">
                         <label for="username" ></label>
                         <input type="text" name="username" id="username" class="form-control" required>
                       <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                     </tr>
                     <tr>
                       <th scope="row">password</th>

                       <?php if(isset($passwordmissmatch)){$passwordmissmatch="password missmatch";}else{$passwordmissmatch="";} echo "<h2>".$passwordmissmatch."</h2>";
					   ?>
                       <td><span id="sprytextfield7" class="form-group has-warning">
                         <label for="pass"></label>
                         <input type="password" name="pass" id="pass" class="form-control" required>
                       <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                     </tr>
                      <tr>
                       <th scope="row">Confirm password</th>
                       <td><span id="sprytextfield9" class="form-group has-warning">
                         <label for="pass"></label>
                         <input type="password" name="pass_2" id="pass_2" class="form-control" required>
                       <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                     </tr>
                     <tr>
                       <th colspan="2" scope="row">
                       <input type="submit" name="reg" id="reg" value="register" class="form-control btn btn-primary btn-lg" required>
                       </th>
                     </tr>
                   </table>
                   <input type="hidden" name="MM_insert" value="employee_reg">
                 
  	  			 </form>   
              </div>
              <div class="col-lg-6">
              <!------------sucess--------------->
              <span id="sucess">
              <?php
              if(isset($id_exist)){$id_exist="Failed!! Cant share ID No's";}else{$id_exist="";}
			  echo "<h4>".$id_exist."</h4>";
			  if(isset( $password_missmatch)){ $password_missmatch="Failed!! Cant share ID No's";}else{ $password_missmatch="";}
			  echo "<h4>". $password_missmatch."</h4>";
			 
			  ?></span>
              <!------------sucess--------------->
              <!-----------my employees----------->
              <form action="" method="post" name="myemployee" id="myemployee">
                <table width="500" border="0" align="center" class="table-condensed table-bordered table-responsive" id=	                   "employeeTable">
                  <tr>
                    <th colspan="6" scope="col"><center>BIDCO COMPANY EMPLOYEE LISTS</center></th>
                  </tr>
                  <tr>
                    <th colspan="6" scope="col"><center>Total Employees		<?php echo $totalRows_employee ?></center></th>
                  </tr>
                  <tr>
                    <td width="112">firstname</td>
                    <td width="111">lastname</td>
                    <td width="108">id No</td>
                    <td width="124">Residence</td>
                    <td width="112">tel no</td>
                    <td width="164">account No</td>
                  </tr>
                   <?php if ($totalRows_employee > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                  <tr>
                    <td><?php echo $row_employee['fname']; ?></td>
                    <td><?php echo $row_employee['lname']; ?></td>
                    <td><?php echo $row_employee['id_no']; ?></td>
                    <td><?php echo $row_employee['resident']; ?></td>
                    <td><?php echo $row_employee['tel_no']; ?></td>
                    <td><?php echo $row_employee['account_no']; ?></td>
                   </tr>
                    <?php } while ($row_employee = mysql_fetch_assoc($employee)); ?>
                      <?php } // Show if recordset not empty ?>
                  </table>
                 <ul class="pager">
                  <li class="previous"><a href="<?php printf("%s?pageNum_employee=%d%s", $currentPage, max(0, $pageNum_employee - 1), $queryString_employee); ?>">Previous</a></li>
                  <li class="next"><a href="<?php printf("%s?pageNum_employee=%d%s", $currentPage, min($totalPages_employee, $pageNum_employee + 1), $queryString_employee); ?>">Next</a></li>
                </ul>
              </form>
<!-----------my employees----------->
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
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6");
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7");
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8");
var sprytextfield9 = new Spry.Widget.ValidationTextField("sprytextfield9");
</script>
</body>

</html>
<?php
mysql_free_result($validate);

mysql_free_result($employee);
?>
