						<?php
$connection = mysql_connect("localhost", "root", ""); // Establishing Connection with Server
$db = mysql_select_db("emplyee_mgn", $connection); // Selecting Database from Server
if(isset($_POST['submit'])){ // Fetching variables of the form which travels in URL
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$id_no = $_POST['id_no'];
$tel_no = $_POST['tel_no'];
$reason = $_POST['reason'];
$return = $_POST['return'];
if($fname !=''||$reason !=''){
//Insert Query of SQL
$query = mysql_query("insert into lave(fname,lname, id_no, tel_no ,reason,return ) values ('$fname', '$lname', '$id_no', '$tel_no','$reason', '$return')");
echo "<br/><br/><span>Data Inserted successfully...!!</span>";
}
else{
echo "<p>Insertion Failed <br/> Some Fields are Blank....!!</p>";
}
}
mysql_close($connection); // Closing Connection with Server
?>