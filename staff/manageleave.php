



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
    <!----------head---------->
	    	<?php require_once("../includes/staff_head.php");?>

	<div class="container">
    <div class="row">
            <div class="box">
                <div class="col-lg-12 text-center">
<?php
	include("connection.php");
	//selecting data from records based on id
	$query = "SELECT * FROM employee where id=$_GET[id]";
	//initializing result as a query
	$result = mysql_query($query);
	//display records from records table 
	while($rows = mysql_fetch_array($result))
	{	
		$id = $rows['id'];
		$fname = $rows['fname'];
		$lname= $rows['lname'];
	 
	
	}	

?>
<center><hr width="600">
<center><strong><h1>Manage Leave</h1></strong>
<hr width="600">
<center><table cellspacing="6">
<form class="form" action="update.php?id=<?php echo $id?>" method="POST">
    
	

	<tr>
	<select name="apply" class="form-control" required="required">
<option value=""></option>
<option value="pending">pending</option>
<option value="approved">approved</option>
<option value="rejected">rejected</option>

</select>
</tr>
	<tr>
		<td>
	<td><input type= "submit" name="submit" value="update" class="btn-primary" ></span></a></button></td>
	</tr>
	<td>
	</td>
	</form>
	</table>	
	</div>
	</div>
	  </div>
	  
</table>
</td>
</tr>

</table>
     
                </div>
            </body>
      </html>
	<!--------------content------------------------>
	