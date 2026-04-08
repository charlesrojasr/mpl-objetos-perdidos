<?php
	//include '../00_includes/session.php';
include '../00_includes/conn.php';

if(isset($_POST['id'])&&isset($_POST['tabla'])){
	$tabla1= $_POST['tabla'];
	$id = $_POST['id'];
	
		 //$sql = "SELECT *,".$tabla1.".id as empid FROM ".$tabla1."  WHERE ".$tabla1.".id = '$id'";
		 $sql = "SELECT * FROM $tabla1  WHERE ".$tabla1.".id = '$id'";
		 $query = $conn->query($sql);
		 $row = $query->fetch_assoc();


	echo json_encode($row);
}
?>