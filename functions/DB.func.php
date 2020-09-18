<?php

//Insert data into any table
function add_data($query,$connect,$message){
	$result = $connect->query($query);
	if ($result === true) {
		$messagetype = "1";
	} else {
		$messagetype = "0";
	}
	$message1 = $connect->error;
	message($message,$messagetype);
}

//Check if data and return result
function select_data($query,$connect){
	$result = $connect->query($query);
	return $result;
}

//Update data into a table
function update_data($query,$connect,$message){
	$result = $connect->query($query);
	if ($result->num_rows > 0) {
		return $result;
	}
	if ($result === true) {
		$messagetype = "1";
	} else {
		$messagetype = "0";
	}
	$message1 = $connect->error;
	message($message,$message1,$messagetype);
}

//Delete data and return message
function delete_data($query,$connect,$message){
	$result = $connect->query($query);
	if ($result->num_rows > 0) {
		return $result;
	}
	if ($result === true) {
		$messagetype = "1";
	} else {
		$messagetype = "0";
	}
	$message1 = $connect->error;
	message($message,$message1,$messagetype);
}


function get_users($connect){
	$sql = "SELECT * FROM users ORDER BY id ASC";
	$result = $connect->query($sql);
	if ($result->num_rows > 0) {
		return $result;
	}
}

function get_user_data($connect,$id){
	$sql = "SELECT * FROM users WHERE id=$id";
	$result = $connect->query($sql);
	if ($result->num_rows > 0) {
		return $result->fetch_assoc();
	}
}

function update_user_data($connect,$name,$email,$phone,$address,$payment_method,$payment_account,$fb_url,$twitter_url,$instagram_url,$picture,$id){
	$sql = "UPDATE users SET name='$name',email='$email',phone='$phone',address='$address',payment_method='$payment_method',payment_account='$payment_account',fb_url='$fb_url',twitter_url='$twitter_url',instagram_url='$instagram_url',picture='$picture' WHERE id=$id";
	$result = $connect->query($sql);
	if ($result === true) {
		return 1;
	} else {
		return 0;
	}
}

function update_password($connect,$new,$id){
	$sql = "UPDATE users SET password = '$new' WHERE id = '$id'";
	$result = $connect->query($sql);
	if ($result === true) {
		return 1;
	} else {
		return 0;
	}
}

function update_user($connect,$role,$quality,$rate,$id){
	$sql = "UPDATE users SET role='$role',rate='$rate',quality='$quality' WHERE id = '$id'";
	$result = $connect->query($sql);
	if ($result === true) {
		return 1;
	} else {
		return 0;
	}
}

function delete_user($connect,$id){
	$sql = "DELETE FROM users WHERE id='$id'";
	$result = $connect->query($sql);
	if ($result === true) {
		return 1;
	} else {
		return 0;
	}
}

function get_roles($connect){
	$sql = "SELECT * FROM roles";
	$result = $connect->query($sql);
	if ($result->num_rows > 0) {
		return $result;
	}
}

function get_role_name($connect,$id){
	$sql = "SELECT * FROM roles WHERE id=$id";
	$rslt = $connect->query($sql);
	$result = $rslt->fetch_assoc();
	if ($rslt->num_rows > 0) {
		return $result['name'];
	} else {
		return "No role found";
	}
}

function get_projects($connect){
	//ORDER BY deadline ASC
	$sql = "SELECT * FROM projects WHERE status='' OR status=0 OR status=1";
	$result = $connect->query($sql);
		return $result;
}

function get_project($connect,$id){
	$sql = "SELECT * FROM projects WHERE id='$id'";
	$result = $connect->query($sql);
	if ($result->num_rows > 0) {
		return $result->fetch_assoc();
	} else {
		return "0";
	}
}

function create_project($connect,$name,$description,$creator,$deadline,$created){
	$description = base64_encode($description);
	$sql = "INSERT INTO projects VALUES ('','$name','$description','$creator','$deadline','$created',0)";
	$result = $connect->query($sql);
	if ($result === true) {
		return 1;
	} else {
		return 0;
	}
}

function update_project($connect,$name,$description,$deadline,$id){
	$description = base64_encode($description);
	$sql = "UPDATE projects SET name='$name',description='$description',deadline='$deadline' WHERE id=$id";
	$result = $connect->query($sql);
	if ($result === true) {
		return 1;
	} else {
		return 0;
	}
}

function delete_project($connect,$id){
	$sql = "DELETE FROM projects WHERE id=$id";
	$result = $connect->query($sql);
	if ($result === true) {
		return "1";
	} else {
		return "0";
	}
}

function get_project_by_id($connect,$id){
	$sql = "SELECT * FROM projects WHERE id=$id";
	$result = $connect->query($sql);
	if ($result->num_rows > 0) {
		return $result->fetch_assoc();
	} else {
		return "0";
	}
}


function create_task($connect,$name,$description,$assignee,$creator,$words,$rate,$type,$project,$deadline,$publishing,$priority){
	if ($name =="") {
		return 0;
	} else {
		$description = base64_encode($description);
		$sql = "INSERT INTO tasks (name,description,assignee,creator,words,rate,type,project,deadline,status,publishing,priority) VALUES ('$name','$description','$assignee','$creator','$words','$rate','$type','$project','$deadline',0,'$publishing','$priority')";
		$result = $connect->query($sql);
		if ($result === true) {
			return 1;
		} else {
			return $connect->error;
		}
	}
}

function update_task($connect,$name,$description,$assignee,$creator,$words,$rate,$type,$project,$deadline,$publishing,$priority,$id){
	$description = base64_encode($description);
	$sql = "UPDATE tasks SET name='$name', description='$description', assignee='$assignee', creator='$creator', words='$words', rate='$rate', type='$type', project='$project', deadline='$deadline', publishing='$publishing', priority='$priority' WHERE id=$id";
	$result = $connect->query($sql);
	if ($result === true) {
		return 1;
	} else {
		return 0;
	}
}

function update_task_payment($connect,$invoice,$status,$id){
	$sql = "UPDATE tasks SET status=$status, invoice='$invoice' WHERE id=$id";
	$result = $connect->query($sql);
	if ($result === true) {
		return 1;
	} else {
		return 0;
	}
}

function get_tasks($connect,$status=0){
	if ($status ==0) {
		$sql = "SELECT * FROM tasks ORDER BY deadline,priority DESC";
	} else {
		$sql = "SELECT * FROM tasks WHERE status='$status' ORDER BY deadline,priority DESC";
	}
	$result = $connect->query($sql);
	return $result;
}

function get_tasks_by_project($connect,$project){
	$sql = "SELECT * FROM tasks WHERE project=$project ORDER BY deadline ASC,priority DESC";
	$result = $connect->query($sql);
	return $result;
}

function get_tasks_by_assignee($connect,$assignee,$status=0){
	if ($status ==0) {
		$sql = "SELECT * FROM tasks WHERE (status='0' OR status='1') AND assignee=$assignee ";
	} else {
		$sql = "SELECT * FROM tasks WHERE status='$status' AND assignee=$assignee";
	}
	$result = $connect->query($sql);
	return $result;
}

function get_tasks_by_creator($connect,$creator,$status=0){
	if ($status ==0) {
		$sql = "SELECT * FROM tasks WHERE (status='0' OR status='1') AND creator=$creator";
	} else {
		$sql = "SELECT * FROM tasks WHERE status='$status' AND creator=$creator";
	}
	$result = $connect->query($sql);
	return $result;
}

function get_tasks_by_publisher($connect,$publisher,$status=0){
	if ($status ==0) {
		$sql = "SELECT * FROM tasks WHERE (publish_status=0 OR publish_status=1) AND publisher=$publisher";
	} else {
		$sql = "SELECT * FROM tasks WHERE publish_status='$status' AND publisher=$publisher";
	}
	$result = $connect->query($sql);
	return $result;
}

function get_paid_tasks_number($connect,$assignee){
	$sql = "SELECT * FROM tasks WHERE status=4 AND assignee=$assignee";
	$result = $connect->query($sql);
	return $result->num_rows;
}

function get_tasks_by_invoice($connect,$invoice){
	$sql = "SELECT * FROM tasks WHERE status=4 AND invoice='$invoice'";
	$result = $connect->query($sql);
		return $result;
}

function get_task_data($connect,$id){
	$sql = "SELECT * FROM tasks WHERE id=$id";
	$result = $connect->query($sql);
	if ($result->num_rows > 0) {
		return $result;
	}
}


function delete_task($connect,$id){
	$sql = "DELETE FROM tasks WHERE id=$id";
	$result = $connect->query($sql);
	if ($result === true) {
		return "1";
	} else {
		return "0";
	}
}

function delete_tasks($connect,$data){
	foreach($data as $key=>$value){
 			if("id_" == substr($key,0,3)){
 				$number = "id".substr($key,strrpos($key,'_'));
 				$sql = "DELETE FROM tasks WHERE id=$_POST[$number]";
 				$batch = $connect->query($sql);
 			}
 		}
}

function create_invoice($connect,$name,$paid,$words,$paid_to){
	$sql = "INSERT INTO invoice (name,paid,words,paid_to,creation_date) VALUES ('$name','$paid','$words','$paid_to',CURRENT_TIMESTAMP)";
	$result = $connect->query($sql);
	if ($result === true) {
		return 1;
	} else {
		return 0;
	}
}

function get_invoice_by_name($connect,$name){
	$sql = "SELECT * FROM invoice WHERE name='$name'";
	$result = $connect->query($sql);
	if ($result->num_rows > 0) {
		return $result->fetch_assoc();
	} else {
		return "0";
	}
}

function get_invoices($connect,$status=0){
	if ($status==0) {
	$sql = "SELECT * FROM invoice";
} else {
	$sql = "SELECT * FROM invoice WHERE status=$status";
}
	$result = $connect->query($sql);
		return $result;
}

function get_invoices_by_user($connect,$id){
	$sql = "SELECT * FROM invoice WHERE paid_to=$id";
	$result = $connect->query($sql);
		return $result;
}

function get_invoices_report($connect,$date){
	$sql = "SELECT * FROM invoice WHERE status=1 AND paid_date LIKE '%$date%'";
	$result = $connect->query($sql);
		return $result;
}


function get_invoice_data($connect,$id){
	$sql = "SELECT * FROM invoice WHERE id=$id";
	$result = $connect->query($sql);
		return $result->fetch_assoc();
}

function delete_invoice($connect,$id){
	$sql = "DELETE FROM invoice WHERE id=$id";
	$result = $connect->query($sql);
	if ($result===true) {
		return 1;
	} else {
		return 0;
	}
}


//Comments
function create_comment($connect,$comment_of,$creator,$comment,$file,$comment_type){
	$comment = base64_encode($comment);
	$sql = "INSERT INTO comments (comment_of,creator,comment,file,type,creation_date) VALUES ('$comment_of','$creator','$comment','$file','$comment_type',CURRENT_TIMESTAMP)";
	$result = $connect->query($sql);
	if ($result === true) {
		return 1;
	} else {
		return 0;
	}
}

function get_all_comments($connect,$limit=10){
	$sql = "SELECT * FROM comments ORDER BY id DESC LIMIT $limit";
	$result = $connect->query($sql);
		return $result;
}

function get_comments($connect,$comment_type,$comment_of){
	$sql = "SELECT * FROM comments WHERE comment_of='$comment_of' AND type='$comment_type' ORDER BY id DESC";
	$result = $connect->query($sql);
		return $result;
}

function delete_comment($connect,$id){
	$sql = "DELETE FROM comments WHERE id=$id";
	$result = $connect->query($sql);
	if ($result === true) {
		return 1;
	} else {
		return 0;
	}
}

?>