<?php
$con = mysql_connect("localhost","root","");
if (!$con) die('Could not connect: ' . mysql_error());

if(!(mysql_select_db("emplyee_mgn"))) die("couldn't select".mysql_error());

  if(isset($_POST['submit'])){
  if(isset($_POST['fname'])){$fname=$_POST['fname'];}else{$fname="";}
  if(isset($_POST['lname'])){$lname=$_POST['lname'];}else{$lname="";}
  if(isset($_POST['id_no'])){$id_no=$_POST['id_no'];}else{$id_no="";}
  if(isset($_POST['tel_no'])){$tel_no=$_POST['tel_no'];}else{$tel_no="";}
  if(isset($_POST['reason'])){$reason=$_POST['reason'];}else{$reason="";}
  if(isset($_POST['return'])){$return=$_POST['return'];}else{$return="";}
 
}

$sql="INSERT INTO leave (fname,lname,id_no,tel_no,reason,return)".
"VALUES('$fname','$lname','$id_no','$tel_no','$reason','$return')";
if (!(mysql_query($sql))) {die('Error: ' . mysql_error());
 
} 
{
		?>
 <script>
  alert('Leave Applied Successfully');
  window.location='leave.php';
 </script>
 <?php
	}
	



mysql_close($con)
?>
 

		