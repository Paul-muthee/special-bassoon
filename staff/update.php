<?php
		include("connection.php");
		if(isset($_POST['submit'])){
		         $id=$_GET['id'];
				
			    $apply=$_POST['apply'];
				
				
				//updating database from your table
				$sql="UPDATE  employee set apply='$apply' where id='".$id."'";
				mysql_query($sql) or die('Error');
				header("Location: staff_portal.php");
			}
			?>