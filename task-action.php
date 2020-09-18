<?php
include_once "inc/header.php";
if (empty($_SESSION['id'])) {
	header("Location: ./login.php");
}

if ($_SERVER['REQUEST_METHOD'] =="POST") {
	if (isset($_POST['update'])) {
		$id = $_POST['id'];
		$name = $_POST['name'];
		$description = $_POST['description'];
		$assignee = $_POST['assignee'];
		$creator = $_POST['creator'];
		$words = $_POST['words'];
		$rate = $_POST['rate'];
		$type = $_POST['type'];
		$project = $_POST['project'];
		$deadline = $_POST['deadline'];
		$result = update_task($connect,$name,$description,$assignee,$creator,$words,$rate,$type,$project,$deadline,$id);
		if ($result == "1") {
			echo "1";
		}
	}
}
?>