<?php require_once('../Connections/conn.php'); ?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>ADMIN</title>

    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/animate.min.css" rel="stylesheet"> 
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>	
	
	<?php include("../include/admin_header.php");?>



          <!--content-->
         <center><table>
<center><h2>MANAGE  SLOTS</h2>
<?php
include("connection.php");
 $sql = mysql_query("select * from employee")
   or die('Error in query : $sql. ' .mysql_error());
   
if (mysql_num_rows($sql) > 0) 
{            
	echo "<table border='1' cellspacing='0' cellpadding='10' class='table' width='600'>";
	echo "<td>Firstname</td>";
	echo "<td>Lastname</td>";
	echo "<td>Id no</td>";
	echo "<td>Leave</td>";

	echo "<td colspan='4'>Action</td>";
	echo "<tr>";
		}
else
	echo "No User Record form the Database!";	 
						
while ($row = mysql_fetch_array($sql))
	{
	echo "<tr>";
	echo "<td>".$row['fname']."</td>";
	echo "<td>".$row['lname']."</td>";
	echo "<td>".$row['id_no']."</td>";
	echo "<td>".$row['apply']."</td>";
	echo "<td title ='manage slots'><a href=\"manageslots.php?id=$row[id]\"><center><img src='img/edit.png'></center></a></td>";

}	

mysql_free_result($sql);

?>
			</table>	
	</div>

</table>


	</div>