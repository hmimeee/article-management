<?php
include "inc/config.php";

//Task Management
if (isset($_GET['task'])) {
	if (isset($_GET['finish'])) {
		$sql = "UPDATE tasks SET status=2 WHERE id=".$_GET['finish'];
		$result = $connect->query($sql);
		if ($result === true) {
			echo 1;
		} else {
			echo 0;
		}
	}

	if (isset($_GET['resume'])) {
		$sql = "UPDATE tasks SET status=0 WHERE id=".$_GET['resume'];
		$result = $connect->query($sql);
		if ($result === true) {
			echo 1;
		} else {
			echo 0;
		}
	}

	if (isset($_GET['start'])) {
		$sql = "UPDATE tasks SET status=1 WHERE id=".$_GET['start'];
		$result = $connect->query($sql);
		if ($result === true) {
			echo 1;
		} else {
			echo 0;
		}
	}

	if (isset($_GET['accept'])) {
		$words = $_GET['words'];
		if (!empty($_GET['publisher'])) { $publisher = $_GET['publisher'];} else {$publisher = 0;}
		if (!empty($_GET['deadline'])) {$deadline = $_GET['deadline'];} else {$deadline = $_GET['deadline'];}
		$sql = "UPDATE tasks SET status=3, publisher=".$publisher.", words=".$words.", deadline='".$deadline."' WHERE id=".$_GET['accept'];
		$result = $connect->query($sql);
		if ($result === true) {
			echo 1;
		} else {
			echo 0;
		}
	}

	if (isset($_GET['start_pub'])) {
		$sql = "UPDATE tasks SET publish_status=1 WHERE id=".$_GET['start_pub'];
		$result = $connect->query($sql);
		if ($result === true) {
			echo 1;
		} else {
			echo 0;
		}
	}


	if (isset($_GET['restart_pub'])) {
		$sql = "UPDATE tasks SET publish_status=0 WHERE id=".$_GET['restart_pub'];
		$result = $connect->query($sql);
		if ($result === true) {
			echo 1;
		} else {
			echo 0;
		}
	}

	if (isset($_GET['finish_pub'])) {
		$sql = "UPDATE tasks SET publish_status=2, publish_link='".$_GET['publish_link']."' WHERE id=".$_GET['finish_pub'];
		$result = $connect->query($sql);
		if ($result === true) {
			echo 1;
		} else {
			echo 0;
		}
	}
}





//Project Management
if (isset($_GET['project'])) {
	if (isset($_GET['finish'])) {
		$sql = "UPDATE projects SET status=2 WHERE id=".$_GET['finish'];
		$result = $connect->query($sql);
		if ($result === true) {
			echo 1;
		} else {
			echo 0;
		}
	}

	if (isset($_GET['restart'])) {
		$sql = "UPDATE projects SET status=0 WHERE id=".$_GET['restart'];
		$result = $connect->query($sql);
		if ($result === true) {
			echo 1;
		} else {
			echo 0;
		}
	}

	if (isset($_GET['start'])) {
		$sql = "UPDATE projects SET status=1 WHERE id=".$_GET['start'];
		$result = $connect->query($sql);
		if ($result === true) {
			echo 1;
		} else {
			echo 0;
		}
	}

	if (isset($_GET['delete'])) {
		$sql = "DELETE FROM projects WHERE id=".$_GET['delete'];
		$result = $connect->query($sql);
		if ($result === true) {
			header("location: ./".$_GET['ref'].".php");
		} else {
			echo 0;
		}
	}
}

//Invoice Manager
if (isset($_GET['invoice'])) {
	if (isset($_GET['paid'])) {
	$sql = "UPDATE invoice SET status='1', paid_date='".$_GET['date']."' WHERE id=".$_GET['paid'];
	$result = $connect->query($sql);
	if ($result === true) {
		header("Location: ./invoice.php?id=".$_GET['paid']);
	} else {
		echo 0;
	}
}

	if (isset($_GET['unpaid'])) {
	$sql = "UPDATE invoice SET status='0', paid_date='' WHERE id=".$_GET['unpaid'];
	$result = $connect->query($sql);
	if ($result === true) {
		header("Location: ./invoice.php?id=".$_GET['unpaid']);
	} else {
		echo 0;
	}
}

}


//User Data
if (isset($_GET['user'])) {
	if (isset($_GET['rate'])) {
		$sql = "SELECT rate FROM users WHERE id=".$_GET['rate'];
		$result = $connect->query($sql);
		if ($result->num_rows>0) {
			echo $result->fetch_assoc()['rate'];
		} else {
			echo 0;
		}
	}
}
?>