<?php
include_once "inc/header.php";
if (empty($_SESSION['id'])) {
	header("Location: ./login.php");
}

if ($_SERVER['REQUEST_METHOD'] =="POST") {
	if (isset($_POST['update'])) {
		$id = $_GET['id'];
		$name = $_POST['name'];
		$description = $_POST['description'];
		$assignee = $_POST['assignee'];
		$creator = $_POST['creator'];
		$words = $_POST['words'];
		$rate = $_POST['rate'];
		$type = $_POST['type'];
		$project = $_POST['project'];
		$deadline = $_POST['deadline'];
		if (isset($_POST['publishing'])) {
			$publishing = $_POST['publishing'];
		} else {
			$publishing ="0";
		}

		if (isset($_POST['priority'])) {
			$priority = $_POST['priority'];
		} else {
			$priority ="0";
		}

		$result = update_task($connect,$name,$description,$assignee,$creator,$words,$rate,$type,$project,$deadline,$publishing,$priority,$id);
		if ($result == 1) {
			message("Task updated successfully!",1);
		}
	}
}

if (isset($_GET['delete'])) {
	$result = delete_task($connect,$_GET['delete']);
	if ($result ==1) {
		header("Location: ./tasks.php");
		exit;
	}
}

if (isset($_GET['id'])) {
	$result = get_task_data($connect,$_GET['id']);
	$data = $result->fetch_assoc();
	?>

	<!--Edit Project-->
	<?php if (isset($_GET['edit'])){ ?>
		<form method="post">
			<table class="table table-bordered">
				<thead class="thead-dark">
					<tr>
						<th scope="col" colspan="2" class="p-0">
							<a href="?ref=<?php echo $_GET['ref'];?>&id=<?php echo $data['id'];?>" class="btn btn-dark">← Back to Task</a> Edit Task
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row">Name</th>
						<td><input class="form-control" type="text" name="name" value="<?php echo $data['name'];?>"></td>
					</tr>

					<tr>
						<th scope="row">Deadline</th>
						<td><input type="date" class="form-control" id="deadline" name="deadline" value="<?php echo $data['deadline'];?>"></td>
					</tr>

					<tr>
						<th scope="row">Assignee</th>
						<td>
							<select class="form-control" name="assignee">
								<option value="<?php echo $data['assignee'];?>" selected><?php echo get_user_data($connect,$data['assignee'])['name'];?> (Selected)</option>
								<?php
								$users = get_users($connect);
								while ($user = mysqli_fetch_array($users)) {
									echo "<option value='".$user['id']."'>".$user['name']."</option>";
								}
								?>
							</select>
						</td>
					</tr>

					<tr>
						<th scope="row">Creator</th>
						<td>
							<?php
							if ($_SESSION['id'] == $data['creator'] || $_SESSION['role'] == 1) {?>
								<select class="form-control" name="creator">
									<option value="<?php echo $data['creator'];?>" selected><?php echo get_user_data($connect,$data['creator'])['name'];?> (Selected)</option>
									<?php
									$users = get_users($connect);
									while ($user = mysqli_fetch_array($users)) {
										echo "<option value='".$user['id']."'>".$user['name']."</option>";
									}
									?>
								</select>
								<?php
							} else {?>
								<input type="hidden" name="creator" value="<?php echo $data['creator'];?>">
								<?php $user = get_user_data($connect,$data['creator']); echo $user['name']; } ?>
							</td>
						</tr>

						<tr>
							<th scope="row">Word Count</th>
							<td><input type="number" class="form-control" id="words" name="words" value="<?php echo $data['words'];?>"></td>
						</tr>

						<tr>
							<th scope="row">Rate (Per 1K	)</th>
							<td><input type="number" class="form-control" id="rate" name="rate" value="<?php echo $data['rate'];?>"></td>
						</tr>

						<tr>
							<th scope="row">Type</th>
							<td><select id="type" class="form-control" name="type"><option value="<?php echo $data['type'];?>" selected><?php echo $data['type'];?> (Selected)</option><option value="Review Article">Review Article</option><option value="Info Article">Info Article</option><option value="Blog Article">Blog Article</option><option value="Web 2.0 Article">Web 2.0 Article</option><option value="Page Content">Page Content</option><option value="Guest Post">Guest Post</option></select></td>
						</tr>

						<tr>
							<th scope="row">Project</th>
							<td><select id="project" class="form-control" name="project">
								<option value="<?php echo $data['project'];?>" selected><?php echo get_project($connect,$data['project'])['name'];?> (Selected)</option>
								<?php
								$result = get_projects($connect);
								while ($project = mysqli_fetch_array($result)) {
									echo "<option value='".$project['id']."'>".$project['name']."</option>";
								}
								?>
							</select></td>
						</tr>

						<tr>
							<th scope="row">Created On</th>
							<td><?php echo date("Y-m-d h:i:sa",strtotime($data['created']));?></td>
						</tr>
						<tr>
							<th scope="row">Publishing</th>
							<td>
								<div class="form-check pub">
									<input class="form-check-input" type="checkbox" id="publishing" name="publishing" value="1" <?php if ($data['publishing']==1) {echo "checked";}?>>
									<label class="form-check-label" for="publishing">
										Publishing Requirement
									</label>
								</div>
							</td>
						</tr>
						<tr>
							<th scope="row">Priority</th>
							<td>
								<div class="form-check pub">
									<input class="form-check-input" type="checkbox" id="priority" name="priority" value="1" <?php if ($data['priority']==1) {echo "checked";}?>>
									<label class="form-check-label" for="priority">
										<i class="text-danger fa fa-fire" data-toggle="tooltip" data-placement="top" title="High"></i>
									</label>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<br>
				<textarea rows="15" class="form-control" id="description" name="description"><?php echo base64_decode($data['description']);?></textarea>
				<br/>
				<input type="hidden" name="id" value="<?php echo $data['id'];?>">
				<input type="hidden" name="update">
				<div class="modal-footer">
					<button class="btn btn-primary">Save Task</button>
					<a href="?ref=<?php echo $_GET['ref'];?>&id=<?php echo $data['id'];?>" class="btn btn-dark">Cancel</a>
				</div>
			</form>
		<?php } else{ ?>

			<!--View Project-->
			<table class="table table-bordered">
				<thead class="thead-dark">
					<tr>
						<th scope="col" colspan="2" class="p-0">
							<h5> <a href="tasks.php"><button class="btn btn-dark">← Back to Tasks </button></a> <?php echo $data['name']?> <?php if ($data['priority']==1) {?>
								<i class="text-danger fa fa-fire" data-toggle="tooltip" data-placement="top" title="High"></i><?php } ?></h5>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td width="40%">
							<div class="row">
							<div class="col-4 border-right border-bottom p-1">Deadline</div>
						<div class="col-8 border-bottom p-1"><?php echo $data['deadline'];?> </div>

					<?php if ($_SESSION['role'] !=4) {?>
							<div class="col-4 border-right border-bottom p-1">Assignee</div>
							<div class="col-8 border-bottom p-1"><b><?php $user = get_user_data($connect,$data['assignee']); echo $user['name']; ?></b></div>
					<?php } ?>

					<?php if ($_SESSION['role'] ==4 || $_SESSION['role'] ==1 || $_SESSION['role'] ==2) {?>
						<div class="col-4 border-right border-bottom p-1">Publisher</div>
							<div class="col-8 border-bottom p-1"><b><?php $user = get_user_data($connect,$data['publisher']); echo $user['name']; ?></b></div>
					<?php } ?>

					<div class="col-4 border-right border-bottom p-1">Creator</div>
						<div class="col-8 border-bottom p-1"><?php $user = get_user_data($connect,$data['creator']); echo $user['name']; ?></div>

					<?php if ($_SESSION['role'] !=4) {?>
						<div class="col-4 border-right border-bottom p-1">Word Count</div>
							<div class="col-8 border-bottom p-1"><?php echo $data['words']; ?></div>

						<div class="col-4 border-right border-bottom p-1">Rate</div>
							<div class="col-8 border-bottom p-1"><?php echo $data['rate']; ?></div>

							<?php if ($_SESSION['role'] ==1 || $_SESSION['role'] ==2) {?>
						<div class="col-4 border-right border-bottom p-1">Total Amount</div>
							<div class="col-8 border-bottom p-1"><?php echo ($data['rate']/1000)*$data['words']; ?> <?php if($data['status']==4){ echo "<span class='badge badge-success'>Paid";}else{echo "<span class='badge badge-danger'>Unpaid";} echo "</span>";?></div>
				<?php } } ?>

				<div class="col-4 border-right border-bottom p-1">Type</div>
					<div class="col-8 border-bottom p-1"><?php echo $data['type'];?></div>

				<div class="col-4 border-right border-bottom p-1">Project</div>
					<div class="col-8 border-bottom p-1"><?php if($_SESSION['role']==1 || $_SESSION['role']==2){?>
						<a href="project.php?id=<?php echo $data['project'];?>&ref=projects"><b><?php $project = get_project_by_id($connect,$data['project']);
						if ($project !=0) {
							echo $project['name'];
						} ?></b></a> 
					<?php } else { echo $project = get_project_by_id($connect,$data['project'])['name'];}?>
				</div>

			<div class="col-4 border-right border-bottom p-1">Created On</div>
				<div class="col-8 border-bottom p-1"><?php echo date("Y-m-d",strtotime($data['created']));?></div>
			</div>
			</td>
			<td width="60%">
				<?php echo base64_decode($data['description']); ?>
			</td>
		</tr>
		</tbody>
	</table>

	<div id="result"></div>
	<div class="modal-footer">
		<?php if($_SESSION['id'] == $data['creator']  || $_SESSION['role'] == 1) {?>
			<table>
				<tr>
					<?php if ($data['publishing']==1) {?>
						<td> <select id="publisher" class="form-control" name="publisher">
							<option value="0">Select Publisher</option>
							<?php 
							$publishers = get_users($connect);
							while ($publisher = mysqli_fetch_array($publishers)) {
								if ($publisher['role'] ==4) {?>
									<option value="<?php echo $publisher['id'];?>"><?php echo $publisher['name'];?></option>
								<?php } } ?>
							</select>
						</td>
						<td>
							<input type="date" class="form-control" id="deadline_new" name="deadline_new">
						</td>
					<?php } else { ?>
						<td><input type="hidden" class="form-control" id="deadline_new" name="deadline_new" value="<?php echo $data['deadline'];?>">
						<input type="hidden" class="form-control" id="publisher" name="publisher" value="">
					</td>
					<?php } ?>


					<td> <input type="number" class="form-control" id="words_count" name="words" placeholder="Words out of <?php echo $data['words'];?>"></td>
					<td><a id="accept" href="#accept-<?php echo $data['id'];?>"><button class="btn btn-success">Accept <?php if ($data['publishing']==1 && $data['publisher'] ==0) {echo "and Transfer";}?> Task</button></a></td>
					<td><a id="return" href="#return-<?php echo $data['id'];?>"><button class="btn btn-danger">Return for Revision</button></a></td>
				<?php }?>

				<!-- Writers Panel -->
				<?php if ($_SESSION['id'] == $data['assignee'] || $_SESSION['id'] == $data['creator']) {?>
					<td><a id="start" href="#start-<?php echo $data['id'];?>"><button class="btn btn-primary">Start</button></a></td>
					<td><a id="finish" href="#finish-<?php echo $data['id'];?>"><button class="btn btn-primary">Finish</button></a></td>
				<?php }?>

				<!-- Publisher Panel -->
				<?php if ($_SESSION['id'] == $data['publisher'] || $_SESSION['role'] == 1 && $data['publishing']==1) {?>
					<td> <input type="text" class="form-control w-25" id="publish_link" name="publish_link" placeholder="Put your publish link here"></td>
					<td><a id="start_pub" href="#start-<?php echo $data['id'];?>"><button class="btn btn-success">Start Publishing</button></a></td>
					<td><a id="finish_pub" href="#finish-<?php echo $data['id'];?>"><button class="btn btn-primary">Finish Publishing</button></a></td>
					<?php if ($_SESSION['role']==1) {?>
						<td><a id="restart_pub" href="#restart-<?php echo $data['id'];?>"><button class="btn btn-danger">Restart Publishing</button></a></td>
						<?php }?>
				<?php }?>

				<td><a href="<?php echo $_GET['ref'].".php";?>"><button class="btn btn-dark">Back</button></a></td>
				<?php if ($_SESSION['role'] ==1 || $data['creator']==$_SESSION['id']) {?>
					<td>
						<div class="dropdown">
					<button class="btn btn-outline-primary" type="button" id="option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></button>

					<div class="dropdown-menu" aria-labelledby="option">
							<a id="resume" class="dropdown-item" href="#resume">Resume Task</a>
							<a id="edit" class="dropdown-item" href="?edit&ref=<?php echo $_GET['ref'];?>&id=<?php echo $data['id'];?>">Edit Task</a>
							<a class="dropdown-item" href="#<?php echo $data['id'];?>" data-toggle="modal" data-target="#delete">Delete</a>
					</div>

				</div></td>
				<?php } ?>
			</tr>
		</table>
	</div>

	<!-- Delete Popup -->
	<div style="margin-top: 200px;width: 30%;margin-left: 35%;margin-right: 35%;" class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="deleteLabel">Delete Task</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					Are you sure you want to delete?
				</div>
				<div class="modal-footer">
					<a href="?delete=<?php echo $data['id'];?>&ref=<?php echo $_GET['ref'];?>"><button type="button" class="btn btn-danger">Yes</button></a>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>

	<?php 
	$comment_type=1;
	$comment_of=$data['id'];
	$query_string = $_SERVER['QUERY_STRING'];
	include 'comments.php';
	?>

<?php } ?>




<script type="text/javascript">
	var id = <?php echo $data['id'];?>;

	$("#accept").click(function(){
		var words = $("#words_count").val();
		var publisher = $("#publisher").val();
		var deadline = $("#deadline_new").val();
		$.ajax({url: "status.php?task&accept="+id+"&words="+words+"&publisher="+publisher+"&deadline="+deadline, success: function(result){
			if (result==1) {
				$("#accept").hide();
				$("#return").hide();
				$("#publisher").hide();
				$("#deadline_new").hide();
				$("#words_count").hide();
			}
		}});
	});

	$("#finish").click(function(){
		$("#finish").css("opacity:0.5");
		$.ajax({url: "status.php?task&finish="+id, success: function(result){
			if (result==1) {
				$("#finish").hide();
				$("#start").hide();
				$("#resume").show();
				$("#return").show();
				$("#accept").show();
				$("#words_count").show();
				$("#publisher").show();
				$("#deadline_new").show();
			}
		}});
	});

	$("#resume").click(function(){
		$("#resume").css("opacity:0.5");
		$.ajax({url: "status.php?task&resume="+id, success: function(result){
			if (result==1) {
				$("#resume").hide();
				$("#return").hide();
				$("#accept").hide();
				$("#finish").show();
				$("#start").show();
				$("#words_count").hide();
				$("#publisher").hide();
				$("#deadline_new").hide();
			}
		}});
	});

	$("#return").click(function(){
		$("#return").css("opacity:0.5");
		$.ajax({url: "status.php?task&resume="+id, success: function(result){
			if (result==1) {
				$("#return").hide();
				$("#resume").hide();
				$("#accept").hide();
				$("#finish").show();
				$("#start").show();
				$("#words_count").hide();
				$("#publisher").hide();
				$("#deadline_new").hide();
			}
		}});
	});

	$("#accept").click(function(){
		$("#accept").css("opacity:0.5");
		$.ajax({url: "status.php?task&accept="+id, success: function(result){
			if (result==1) {
				$("#accept").hide();
				$("#return").hide();
				$("#words_count").hide();
				$("#publisher").hide();
				$("#deadline_new").hide();
			}
		}});
	});

	$("#start").click(function(){
		$("#start").css("opacity:0.5");
		$.ajax({url: "status.php?task&start="+id, success: function(result){
			if (result==1) {
				$("#start").hide();
			}
		}});
	});

	$("#start_pub").click(function(){
		$("#start_pub").css("opacity:0.5");
		$.ajax({url: "status.php?task&start_pub="+id, success: function(result){
			if (result==1) {
				$("#start_pub").hide();
				$("#finish_pub").show();
				$("#publish_link").show();
			}
		}});
	});

	$("#finish_pub").click(function(){
		var publish_link = $("#publish_link").val();
		$("#finish_pub").css("opacity:0.5");
		$.ajax({url: "status.php?task&finish_pub="+id+"&publish_link="+publish_link, success: function(result){
			if (result==1) {
				$("#finish_pub").hide();
				$("#restart_pub").show();
				$("#publish_link").hide();
			}
		}});
	});

	$("#restart_pub").click(function(){
		$("#restart_pub").css("opacity:0.5");
		$.ajax({url: "status.php?task&restart_pub="+id, success: function(result){
			if (result==1) {
				$("#restart_pub").hide();
				$("#start_pub").show();
			}
		}});
	});
</script>


<script type="text/javascript">
	var status = <?php echo $data['status']?>;
	$(document).ready(function(){
		if (status==2) {
			$("#finish").hide();
			$("#start").hide();
			$("#resume").show();
			$("#return").show();
			$("#deadline_new").show();
			$("#words_count").show();
			$("#publish_link").hide();
		}
	});


	$(document).ready(function(){
		if (status==0) {
			$("#resume").hide();
			$("#return").hide();
			$("#accept").hide();
			$("#words_count").hide();
			$("#publisher").hide();
			$("#deadline_new").hide();
			$("#publish_link").hide();
		}
	});


	$(document).ready(function(){
		if (status==1) {
			$("#resume").hide();
			$("#return").hide();
			$("#accept").hide();
			$("#start").hide();
			$("#words_count").hide();
			$("#publisher").hide();
			$("#deadline_new").hide();
			$("#publish_link").hide();
		}
	});


	$(document).ready(function(){
		if (status==3) {
			$("#accept").hide();
			$("#start").hide();
			$("#finish").hide();
			$("#return").hide();
			$("#words_count").hide();
			$("#publisher").hide();
			$("#deadline_new").hide();
		}
	});

	$(document).ready(function(){
		if (status==4) {
			$("#accept").hide();
			$("#start").hide();
			$("#finish").hide();
			$("#return").hide();
			$("#words_count").hide();
			$("#publisher").hide();
			$("#deadline_new").hide();

		}
	});

	var publish_status = <?php echo $data['publish_status']?>;
	$(document).ready(function(){
		if (publish_status==2) {
			$("#finish_pub").hide();
			$("#start_pub").hide();
			$("#publish_link").hide();
			$("#restart_pub").show();
		}
	});
	$(document).ready(function(){
		if (publish_status==1) {
			$("#finish_pub").show();
			$("#start_pub").hide();
			$("#publish_link").show();
			$("#restart_pub").hide();
		}
	});

	$(document).ready(function(){
		if (publish_status==0) {
			$("#finish_pub").hide();
			$("#start_pub").show();
			$("#publish_link").hide();
			$("#restart_pub").hide();
		}
	});

</script>

<?php
}
include_once "inc/footer.php";
?>